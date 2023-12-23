<?php

namespace App\Helpers;

/**
 * Session helper class
 */
class Session
{
    /**
     * getRedirectData returns the redirect data associated with the session
     *
     * @return array
     */
    public static function getRedirectData(): array
    {
        return $_SESSION['redirect_data'] ?? [];
    }

    /**
     * getRedirectData sets the redirect data to the session
     *
     * @param array $data
     * @return array
     */
    public static function setRedirectData($data): void
    {
        $_SESSION['redirect_data'] = $data;
    }
}
