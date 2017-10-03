<?php

namespace App;

class Client extends Model {
    protected $fillable = [
        'client_id',
        'secret',
    ];
    protected $hidden = [ 'secret' ];

    public function validate($signature, $content)
    {
        return hash_equals($signature ?: '', hash_hmac('sha256', $content, $this->secret));
    }
}
