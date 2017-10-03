<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\Image as IImage;

class ImageController extends Controller
{
    protected $commands = [
        'resize',
    ];

    protected function resize_command(IImage $image, Array $args)
    {
        if (empty($args[1])) {
            $args[1] = $args[0];
        }
        $image->resize($args[0], $args[1]);
    }

    protected function crop_command(IImage $image, Array $args)
    {
        $args[1] = empty($args[1]) ? $args[0] : $args[1];
        $args[2] = empty($args[2]) ? 0 : $args[2];
        $args[3] = empty($args[3]) ? 0 : $args[3];
        $image->crop($args[0], $args[1], $args[2], $args[3]);
    }

    public function handler(Request $request, $image, $command = '')
    {
        $commands = array_values(array_filter(explode("/", $command)));
        $image = App\Image::where('public', $image)->where('status', 'uploaded')->first();
        $image = Image::make($image->path());

        $functions = array();

        foreach ($commands as $pos => $command) {
            if (is_callable(array($this, $command . '_command'))) {
                $functions[] = [$command . '_command', $pos];
            }
        }

        foreach ($functions as $pos => $command) {
            $end = !empty($functions[$pos+1]) ? $functions[$pos+1][1]-1 : null;
            $args = array_slice($commands, $command[1]+1, $end);
            $this->{$command[0]}($image, $args);
        }

        return $image->response('png');
    }
}
