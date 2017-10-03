<?php

namespace App;

class Upload extends Model {
    protected $fillable = [ 
        'ip',
        'code',
        'max_size',
    ];

    function image() {
        return $this->belongsTo(Image::class);
    }
}
