<?php
class CookieHandler
{
    protected $link;
    public function __construct(mysqli $link)
    {
        $this->link = $link;
    }
    public function cookieIsValid()
    {
        if (isset($_COOKIE['userToken'])) {
            $token = base64_decode($_COOKIE['userToken']);
            list($userId, $timestamp, $signature) = explode('|', $token);

            $data = $userId . '|' . $timestamp;
            $expectedSignature = hash_hmac('sha256', $data, 'very_secret_key');

            if (hash_equals($expectedSignature, $signature)) {
                return true;
            } else {
                return false;
            }
        }
    }
    /**
     * Получение информации о юзере из куки
     */    public function getUserInfoFromCookie()
    {
        $userId = $this->getUserIdFromValidatedCookie();
        if ($userId) {
            $query = "SELECT * FROM applicants WHERE id = $userId";
            $result = $this->link->query($query);
            return $result->fetch_assoc();
        }

        return null;
    }

    /**
     * Получение айди юзера из куки
     */
    public function getUserIdFromCookie()
    {
        return $this->getUserIdFromValidatedCookie();
    }

    /**
     * Валидация куки 
     */
    private function getUserIdFromValidatedCookie()
    {
        if (isset($_COOKIE['userToken'])) {
            $token = base64_decode($_COOKIE['userToken']);
            list($userId, $timestamp, $signature) = explode('|', $token);

            $data = $userId . '|' . $timestamp;
            $expectedSignature = hash_hmac('sha256', $data, 'very_secret_key');

            if (hash_equals($expectedSignature, $signature)) {
                return $userId;
            }
        }

        return null;
    }
}
