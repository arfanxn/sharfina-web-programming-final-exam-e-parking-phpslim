<?php

namespace App\Helpers;

/**
 * Array helper class
 */
class Arr
{
    /**
     * dotToAssoc converts dotted array into an associative array
     *
     * @param array $array
     * @return array
     */
    public static function dotToAssoc($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $keys = explode('.', $key);
            $currentArray = &$result;

            foreach ($keys as $nestedKey) {
                if (!isset($currentArray[$nestedKey])) {
                    $currentArray[$nestedKey] = [];
                }

                $currentArray = &$currentArray[$nestedKey];
            }

            $currentArray = $value;
        }
        return $result;
    }



}
