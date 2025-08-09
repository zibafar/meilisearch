<?php

namespace App\Macros;

use App\Models\Moodle\_BaseLMSModel;
use Closure;
use Illuminate\Support\Str;

class StrMixin
{

    /**
     * @return Closure
     */
    public function unmark(): Closure
    {
        return function ($str) {
            return str_replace(_BaseLMSModel::MARKER_OPEN, '', str_replace(_BaseLMSModel::MARKER_CLOSE, '', $str));
        };
    }
    /**
     * @return Closure
     */
    public function hasMarker(): Closure
    {
        return function ($str) {
            return Str::Contains($str, _BaseLMSModel::MARKER_OPEN);
        };
    }

}
