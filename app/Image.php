<?php

namespace App;

use RuntimeException;

class Image extends Model {
    protected $fillable = [
        'client_id',
        'public',
        'secret',
        'status',
        'height',
        'weight',
        'size',
        'mime',
    ];

    protected $hidden = ['secret'];

    public function path()
    {
        $prefix   = implode("/", str_split(substr($this->public, 0, 3)));
        $filename = substr($this->public, 3);
        return storage_path() . '/images/' . $prefix . '/' . $filename . '.png';
    }

    public function code()
    {
        return $this->public . ':' . $this->secret;
    }
}
