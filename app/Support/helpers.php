<?php

use App\Support\Terbilang;

if (!function_exists('terbilang')) {
    function terbilang($nilai): string
    {
        return Terbilang::rupiah($nilai);
    }
}
