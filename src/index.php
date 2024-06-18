<?php
// подключение библиотек, базы данных и классов
require_once '../vendor/autoload.php';
require_once 'db.php';

spl_autoload_register(function ($class) {
    $path = 'classes' . '/' . $class . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        echo "Класс $class не найден";
    }
});

$loader = new \Twig\Loader\FilesystemLoader('../pages');
$twig = new \Twig\Environment($loader);
/// переменные
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$errors = null;
$applicants = null;
$total_pages = null;
$current_page = null;
$total_applicants = null;
$auth = null;
$userInfo = null;
$search = null;
$array = [];
/// отобажение контента с подсайтов с помощью twig
$routes = [
    '/src/register' => 'register.html',
    '/src/edit' => 'edit.html',
    '/src/applicants' => 'applicants.html',

];

$functions = [
    '/src/register' => 'functions/registerFunc.php',
    '/src/edit' => 'functions/editFunc.php',
    '/src/applicants' => 'functions/applicantsFunc.php'
];

if (array_key_exists($currentPath, $routes)) {
    $template = $routes[$currentPath];

    if (array_key_exists($currentPath, $functions) && file_exists($functions[$currentPath])) {
        include $functions[$currentPath];
    }
} else {
    $template = '404.html';
}
$variables = [
    '/src/edit' => ['errors' => $errors, 'userInfo' => $userInfo],
    '/src/register' =>  ['errors' => $errors, 'auth' => $auth],
    '/src/applicants' => ['applicants' => $applicants, 'total_pages' => $total_pages, 'current_page' => $current_page, 'total_applicants' => $total_applicants, 'auth' => $auth, 'search' => $search,],
];
// рендер
if (isset($variables[$currentPath])) $varArray = $variables[$currentPath];
else $varArray = [];
echo $twig->render($template, $varArray);
