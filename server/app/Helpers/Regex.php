<?php

namespace App\Helpers;

final class Regex
{
    /**
     * @param bool $strict
     * @param string $prefix
     * @return string
     */
    public static function phMobileNumber(bool $strict = false, string $prefix = '\+639'): string
    {
        if ($strict) {
            return '/^' . $prefix . '\d{9}$/';
        }

        return '/^(\+639|639|09)\d{9}$/';
    }

    /**
     * letters, digits and space
     * @return string
     */
    public static function alphaNumeric()
    {
        return '/^[a-zA-Z0-9Ññ\s]+$/';
    }

    /**
     * letters, digits and underscore
     * @return string
     */
    public static function snakeCaseAlphaNumeric()
    {
        return '/^[a-zA-Z0-9Ññ_]+$/';
    }

    /**
     * except <, >, /, ", ', (, )
     * @param bool $strict
     * @return string
     */
    public static function exceptDefinedCharacters($strict = true): string
    {
        if ($strict) {
            return '/^((?![<>\/\"\'\(\)%]).)*$/';
        }
        return '/^((?![<>\/\%]).)*$/';
    }

    /**
     * must include atleast 1 uppercase letter, 1 lowercase letter, 1 number, 1 special character
     * @return string
     */
    public static function strongPassword()
    {
        return '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])/';
    }

    public static function commaSeparatedString()
    {
        return '/^[a-z,-]+$/';
    }

    public static function email(): string
    {
        return '/^[a-zA-Z0-9Ññ\-\_\.+-]+@([a-zA-Z]+\.)+[a-zA-Z]{2,4}$/';
    }

    /**
     * accepts Alphanumeric, space, dash, comma, apostrophe, and period, enye
     * accepts Alphanumeric, enye, hyphen, underscore and period if strict
     */
    public static function alphaNumericWithDefinedCharacters($strict = false): string
    {
        if ($strict) {
            return '/^[a-zA-Z0-9Ññ\s\-\_\.\!]+$/';
        }
        return '/^[a-zA-Z0-9Ññ\s\-\.\,\/\(\)\']+$/';
    }

    /**
     * accepts Alphanumeric, space, dash, comma, apostrophe period, enye, (,), !, #, & , (/), (:), and line breaks(\n\r)
     */
    public static function extendedAlphaNumericWithUrls(): string
    {
        return '/^[a-zA-Z0-9Ññ\n\r\s\-\_\.\:\!\#\&\,\/\(\)\']+$/';
    }
}
