<?php

namespace App\Http\Controllers;

use App;
use App\Image\DefaultFilter;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as ImageManager;
use Intervention\Image\AbstractDriver as Driver;
use Intervention\Image\Image;

class ImageController extends Controller
{
    protected $filters = [];
    public function __construct()
    {
        $this->filters = [
            new DefaultFilter,
        ];
    }
    protected function isCommand($driver, $name)
    {
        foreach ($this->filters as $filter) {
            if (is_callable([$filter, $name])) {
                return true;
            }
        }

        $drivername      = $driver->getDriverName(); 
        $classnameLocal  = sprintf('\Intervention\Image\%s\Commands\%sCommand', $drivername, ucfirst($name));
        $classnameGlobal = sprintf('\Intervention\Image\Commands\%sCommand', ucfirst($name));

        return class_exists($classnameLocal) || class_exists($classnameGlobal);
    }

    protected function execute(Image $image, Driver $driver, $name, Array $args)
    {
        foreach ($this->filters as $filter) {
            if (is_callable([$filter, $name])) {
                return $filter->$name($image, $args);
            }
        }

        return $driver->executeCommand($image, $name, $args);
    }

    public function handler(Request $request, $image, $command = '')
    {
        $commands = array_values(array_filter(explode("/", $command)));
        $image = App\Image::where('public', $image)->where('status', 'uploaded')->first();

        if (!$image) {
            abort(404);
        }

        $signature = array_pop($commands);
        if (substr(hash_hmac('sha256', serialize($commands), $image->secret), 0, 8) !== $signature) {
            abort(403);
        }

        $image  = ImageManager::make($image->path());
        $driver = $image->getDriver();
        $functions = array();


        foreach ($commands as $pos => $command) {
            if ($this->isCommand($driver, $command)) {
                $functions[] = [$command, $pos];
            }
        }

        foreach ($functions as $pos => $command) {
            $end = !empty($functions[$pos+1]) ? $functions[$pos+1][1]-1 : null;
            $args = array_slice($commands, $command[1]+1, $end);
            $this->execute($image, $driver, $command[0], $args);
        }

        return response($image->response(env('IMAGE_FORMAT', 'png')))
            ->withHeaders([
                'Expires' => 'Thu, 31 Dec 2037 23:55:55 GMT',
                'Cache-Control' => 'public, max-age=315360000',
            ]);
    }
}
