<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "applicant-records";
$connection = mysqli_connect($servername, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$sortIDOrder = isset($_GET['sortID']) && $_GET['sortID'] === 'oldest' ? 'ASC' : 'DESC';
$query = "SELECT * FROM applicantrecord";
$conditions = [];

if (isset($_GET['start_date_examination']) && isset($_GET['end_date_examination'])) {
    $start_date_examination = mysqli_real_escape_string($connection, $_GET['start_date_examination']);
    $end_date_examination = mysqli_real_escape_string($connection, $_GET['end_date_examination']);
    $conditions[] = "date_of_examination BETWEEN '$start_date_examination' AND '$end_date_examination'";
}

if (isset($_GET['start_date_notification']) && isset($_GET['end_date_notification'])) {
    $start_date_notification = mysqli_real_escape_string($connection, $_GET['start_date_notification']);
    $end_date_notification = mysqli_real_escape_string($connection, $_GET['end_date_notification']);
    $conditions[] = "date_of_notification BETWEEN '$start_date_notification' AND '$end_date_notification'";
}

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $statuses = array_map(function($status) use ($connection) {
        return mysqli_real_escape_string($connection, $status);
    }, $_GET['status']);
    $statusCondition = "status IN ('" . implode("','", $statuses) . "')";
    $conditions[] = $statusCondition;
}


if (isset($_GET['exam_venue']) && !empty($_GET['exam_venue'])) {
    $exam_venue = mysqli_real_escape_string($connection, $_GET['exam_venue']);
    $conditions[] = "exam_venue = '$exam_venue'";
}

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($connection, trim($_GET['search']));
    $conditions[] = "(status LIKE '%$search%' OR applicantID LIKE '%$search%' OR firstname LIKE '%$search%' OR lastname LIKE '%$search%' OR middlename LIKE '%$search%' OR province LIKE '%$search%')";
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$orderByClause = [];
if (isset($_GET['sortName'])) {
    $sortNameOrder = $_GET['sortName'] === 'desc' ? 'DESC' : 'ASC';
    $orderByClause[] = "firstname $sortNameOrder";
}

if (isset($_GET['sortID']) && $_GET['sortID'] === 'oldest') {
    $orderByClause[] = "applicantID ASC";
} else {
    $orderByClause[] = "applicantID $sortIDOrder";
}

$limit = 2; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_records_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM applicantrecord");
$total_records_row = mysqli_fetch_assoc($total_records_result);
$total_records = $total_records_row['total'];

$total_pages = ceil($total_records / $limit);


if ($page < 1) {
    $page = 1;
} elseif ($page > $total_pages) {
    $page = $total_pages;
}

$query .= " ORDER BY " . implode(', ', $orderByClause);
$query .= " LIMIT $limit OFFSET $offset";

$result = mysqli_query($connection, $query);

if ($result) {
    $applicant_record = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($connection);
}

mysqli_close($connection);
