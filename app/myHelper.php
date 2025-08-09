<?php


global $argc, $argv ;

use Illuminate\Support\Str;
use Illuminate\Support\Env;

if (!function_exists('extractSort')) {
    function extractSort(string $param): array
    {
        return [
            'column' => Str::beforeLast($param, '_'),
            'direction' => Str::afterLast($param, '_'),
        ];
    }
}


if (!function_exists('to_standard_letter')) {
    function to_standard_letter(string|null $string): string
    {
        $string = strip_tags($string ?? '');
        $arabic = ['ي', 'ك', 'ؤ', 'ۀ'];
        $farsi = ['ی', 'ک', 'و', 'ه'];
        $fa_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $en_num = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

        return str_replace($fa_num, $en_num, str_replace($arabic, $farsi, $string));
    }
}

if (!function_exists('eachOneIsFalse')) {
    function eachOneIsFalse(...$args): bool
    {
        return in_array(false, $args);
    }

}

