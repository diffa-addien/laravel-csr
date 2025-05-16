<?php

if (! function_exists('format_uang')) {
    function format_uang($angka)
    {
        $hasil_rupiah = 'Rp ' . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }
}