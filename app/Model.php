<?php

namespace App;

use Illuminate\Database\Eloquent;

abstract class Model extends Eloquent\Model
{
    /**
     * Function borrowed from: https://laracasts.com/discuss/channels/general-discussion/generate-unique-random-string/replies/28245
     */
    public static function random($length)
    {
        if ( ! function_exists('openssl_random_pseudo_bytes')) {
            throw new RuntimeException('OpenSSL extension is required.');
        }

        $bytes = openssl_random_pseudo_bytes($length * 2);

        if ($bytes === false) {
            throw new RuntimeException('Unable to generate random string.');
        }

        return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
    }

}
