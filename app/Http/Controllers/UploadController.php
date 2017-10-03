<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Model;
use App\Client;
use App\Upload;
use Intervention\Image\ImageManagerStatic as Image;

class UploadController extends Controller
{
    public function create(Request $request)
    {
        $client_id = $request->header('x-client');
        $client    = Client::where('client_id', $client_id)->first();
        $rawJson   = $request->getContent();
        if (empty($client) || !$client->validate($request->header('x-signature'), $rawJson)) {
            return ['error' => 'No authentiation'];
        }

        $image = new App\Image;
        $image->client_id = $client->id;
        $image->secret    = Model::random(9);
        $image->public    = Model::random(60);
        $image->status    = 'new';
        $image->save();

        $upload = new Upload;
        $upload->client_id = $client->id;
        $upload->image_id  = $image->id;
        $upload->ip = ip2long('127.0.0.1');
        $upload->max_size = 1024 * 1024;
        $upload->code = Upload::random(60);
        $upload->save();

        return ['error' => false, 'url' => route('upload', ['code' => $upload->code]), 'image' => $image->code()];
    }

    public function upload(Request $request, $code)
    {
        $upload = Upload::where('code', $code)->first();
        $files  = $request->allFiles();
        $file   = current($files);
        if (!$upload || $upload->uploaded || count($files) !== 1 || !$file->isValid()) {
            throw new \RuntimeException;
        }
        
        $img  = Image::make($file);
        $prefix   = implode("/", str_split(substr($upload->image->public, 0, 3)));
        $filename = substr($upload->image->public, 3);



        $target = $upload->image->path();

        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }

        $img->save($target);

        $image = $upload->image;
        $image->status    = 'new';
        $image->size   = filesize($target);
        $image->width  = $img->width();
        $image->height = $img->height();
        $image->save();

        $upload->uploaded = 1;
        $upload->save();

        var_dump($img);exit;
    }
}
