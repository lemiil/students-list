<?php

function sanitizeInput($data)
{
    return htmlspecialchars(trim($data));
}

function validateName($name)
{
    if (empty($name)) {
        return "Имя не должно быть пустым";
    }
    if (strlen($name) > 50) {
        return "Имя не должно превышать 50 символов";
    }
    return null;
}

function validateLastname($lastname)
{
    if (empty($lastname)) {
        return "Фамилия не должна быть пустой";
    }
    if (strlen($lastname) > 50) {
        return "Фамилия не должна превышать 50 символов";
    }
    return null;
}

function validateGroupNum($groupNum)
{
    if (!preg_match("/^\w{2,5}$/", $groupNum)) {
        return "Номер группы должен содержать от 2 до 5 символов";
    }
    return null;
}

function validatePoints($points)
{
    if ($points < 0 || $points > 400) {
        return "Суммарное число баллов на НМТ должно быть в диапазоне от 0 до 400";
    }
    return null;
}

function validateDateOfB($dateOfB)
{
    if ($dateOfB < 1900 || $dateOfB > date('Y')) {
        return "Некорректный год рождения";
    }
    return null;
}

function validateEmail($email, $validation)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Некорректный email";
    }
    if ($validation->checkEmail($email)) {
        return 'Email уже занят';
    }
    return null;
}

function handleErrors($errors)
{
    $errors = array_filter($errors);
    if (empty($errors)) {
        return null;
    }
    return implode('<br>', $errors);
}

function setProtCookie($userId)
{
    $data = $userId . '|' . time();
    $signature = hash_hmac('sha256', $data, 'very_secret_key');
    $token = base64_encode($data . '|' . $signature);
    setcookie("userToken", $token, time() + (86400 * 365 * 10), "/");
}
