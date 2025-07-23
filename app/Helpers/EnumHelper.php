<?php


if (!function_exists('enum_to_string')) {
    function enum_to_string($enum)
    {
        if (is_object($enum) && property_exists($enum, 'value')) {
            return $enum->value;
        }
        return (string) $enum;
    }
}