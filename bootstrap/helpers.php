<?php

if (!function_exists('logo')) {

    /**
     * Mengambil path logo aplikasi.
     *
     * @return string
     */
    function logo(): string
    {
        return asset('images/logo.png');
    }
}
