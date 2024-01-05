<?php

namespace App\Helpers;

/**
 * Session helper class
 */
class Session
{
    /**
     * auth returns the current logged in user information or set the current logged in user information
     *
     * @param mixed $user
     * @return ?array
     */
    public static function auth(mixed $auth = null): ?array
    {
        if (($auth) != null) {
            $_SESSION['auth'] = $auth;
        }

        $auth = $_SESSION['auth'] ?? null;
        return ($auth != null) ? json_decode(json_encode($auth, true), true) : null;
    }

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
