<?php
$applicantsTable = new ApplicantsTable($link);
$validation = new CookieHandler($link);

$table = $applicantsTable->getApplicants();

$result = $table['data'];
$total_pages = $table['total_pages'];
$current_page = $table['current_page'];
$total_applicants = $table['total_applicants'];

$applicants = [];

if ($total_applicants > 0) {
    foreach ($result as $row) {
        $applicants[] = [
            'name' => htmlspecialchars($row["name"]),
            'lastname' => htmlspecialchars($row["lastname"]),
            'groupNum' => htmlspecialchars($row["groupNum"]),
            'points' => htmlspecialchars($row["points"]),
        ];
    }
} else {
    $applicants[] = [
        'name' => '',
        'lastname' => '',
        'groupNum' => '',
        'points' => '',
        'message' => 'Абитуриентов не найдено'
    ];
}
if ($validation->cookieIsValid()) {
    $auth = true;
}
if (isset($_GET['search'])) $search = $_GET['search'];
