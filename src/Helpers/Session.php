<?php

namespace App\Helpers;

/**
 * Session helper class
 */
class Session
{
    /**
     * pullRedirectData returns the redirect data associated with the session then deletes it
     *
     * @return array
     */
    public static function pullRedirectData(): array
    {
        $data = $_SESSION['redirect_data'] ?? [];
        self::putRedirectData([]); // clear all redirect data
        return $data;
    }
    /**
     * putRedirectData sets the redirect data to the session
     *
     * @param array $data
     * @return void
     */
    public static function putRedirectData(array $data): void
    {
        $_SESSION['redirect_data'] = $data;
    }
}
