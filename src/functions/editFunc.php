<?php
require_once 'functions/commonFunctions.php';

$validation = new CookieHandler($link);
if (!($validation->cookieIsValid())) {
    header('Location: /src/register');
    exit();
}
$edit = new ApplicantsTable($link);
$cookie = new CookieHandler($link);
$userInfo = $cookie->getUserInfoFromCookie();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    $userInfo = [
        'name' => sanitizeInput($_POST['name']),
        'lastname' => sanitizeInput($_POST['lastname']),
        'gender' => sanitizeInput($_POST['gender']),
        'groupNum' => sanitizeInput($_POST['groupNum']),
        'points' => sanitizeInput($_POST['points']),
        'dateOfB' => sanitizeInput($_POST['dateOfB']),
    ];
    $userInfo['id'] = $cookie->getUserIdFromCookie();

    $errors = [
        'name' => validateName($userInfo['name']),
        'lastname' => validateLastname($userInfo['lastname']),
        'groupNum' => validateGroupNum($userInfo['groupNum']),
        'points' => validatePoints($userInfo['points']),
        'dateOfB' => validateDateOfB($userInfo['dateOfB'])
    ];

    $errorMessage = handleErrors($errors);
    if ($errorMessage === null) {
        $edit->editApplicant($userInfo);
        header('Location: applicants');
        exit();
    } else {
        $errors = $errorMessage;
    }

    $link->close();
}
