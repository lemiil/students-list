<?php
require_once 'functions/commonFunctions.php';

$cookie = new CookieHandler($link);
$validation = new Validation($link);
if ($cookie->cookieIsValid()) {
    header('Location: /src/applicants');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
    $registration = new ApplicantsTable($link);
    $applicantData = [
        'name' => sanitizeInput($_POST['name']),
        'lastname' => sanitizeInput($_POST['lastname']),
        'gender' => sanitizeInput($_POST['gender']),
        'groupNum' => sanitizeInput($_POST['groupNum']),
        'email' => sanitizeInput($_POST['email']),
        'points' => sanitizeInput($_POST['points']),
        'dateOfB' => sanitizeInput($_POST['dateOfB'])
    ];

    $errors = [
        'name' => validateName($applicantData['name']),
        'lastname' => validateLastname($applicantData['lastname']),
        'email' => validateEmail($applicantData['email'], $validation),
        'groupNum' => validateGroupNum($applicantData['groupNum']),
        'points' => validatePoints($applicantData['points']),
        'dateOfB' => validateDateOfB($applicantData['dateOfB'])
    ];

    $errorMessage = handleErrors($errors);
    if ($errorMessage === null) {
        $registration->addApplicant($applicantData);
        $userId = mysqli_insert_id($link);
        unset($applicantData);
        setProtCookie($userId);
        header('Location: /src/applicants');
        exit();
    } else {
        $errors = $errorMessage;
    }

    $link->close();
}
