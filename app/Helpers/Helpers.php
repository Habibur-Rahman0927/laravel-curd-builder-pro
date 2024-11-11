<?php

namespace App\Helpers;

class Helpers
{
    public static function getCustomNameValueFromEnum($class): array
    {
        $permissions = array_column($class::cases(), 'name', 'value');
        $formattedPermissions = array_map(function ($value) {
            $value = str_replace('_', ' ', $value);
            $value = preg_replace('/([a-z])([A-Z])/', '$1 $2', $value);
            return ucwords(strtolower($value));
        }, $permissions);

        return array_combine(array_keys($permissions), $formattedPermissions);
    }

    /**
     * Convert a string with hyphens and camel case to normal text format.
     *
     * @param string $input
     * @return string
     */
    public static function convertToNormalText(string $input): string
    {
        $output = str_replace(['-', '.'], ' ', $input);
        $output = preg_replace('/([a-z])([A-Z])/', '$1 $2', $output);
        return ucwords(strtolower($output));
    }
}


