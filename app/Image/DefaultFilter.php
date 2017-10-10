<?php

namespace App\Image;

use Intervention\Image\Image;

class DefaultFilter
{
    /**
     * "resize" command
     *
     * It overrides the 'resize' command to set the contraints.
     */
    public function resize(Image $image, array $args)
    {
        if (empty($args[1])) {
            $args[1] = null;
        }
        $image->resize($args[0], $args[1], function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    }

    /**
     * "fit" command
     *
     * It overrides the 'fit' command to set the contraints.
     */
    public function fit(Image $image, Array $args)
    {
        $args[1] = empty($args[1]) ? $args[0] : $args[1];
        $args[2] = empty($args[2]) ? 'center' : $args[2];

        $image->fit($args[0], $args[1], function($constraint) {
            $constraint->upsize();
        }, $args[2]);
    }
}
