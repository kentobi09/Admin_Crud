<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "applicant-records";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_applicantIDs = "SELECT DISTINCT applicantID FROM applicantscore";
$result_applicantIDs = $conn->query($sql_applicantIDs);

if (!$result_applicantIDs) {
    die('Error executing SQL query: ' . $conn->error);
}

$scores = array();

while ($row_applicantID = $result_applicantIDs->fetch_assoc()) {
    $applicantID = $row_applicantID['applicantID'];

    $sql_scores = "SELECT applicant_score_part1, applicant_score_part2, applicant_score_part3, total_score 
                   FROM applicantscore 
                   WHERE applicantID = ?";
    
    $stmt_scores = $conn->prepare($sql_scores);

    if ($stmt_scores === false) {
        die('Error in SQL query: ' . $conn->error);
    }

    $stmt_scores->bind_param("i", $applicantID);

    if (!$stmt_scores->execute()) {
        die('Error executing SQL query: ' . $stmt_scores->error);
    }
    
    $stmt_scores->bind_result($score1, $score2, $score3, $total);

    while ($stmt_scores->fetch()) {
        $scores[$applicantID] = array(
            'score1' => $score1,
            'score2' => $score2,
            'score3' => $score3,
            'total' => $total
        );
    }
    $stmt_scores->close();
}
$conn->close();


