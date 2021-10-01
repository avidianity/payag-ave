<?php

if (!function_exists('frontend')) {
    /**
     * Create a frontend url
     *
     * @param string $url
     * @return string
     */
    function frontend($url)
    {
        return config('urls.frontend') . $url;
    }
}
