<?php 
$conn = new PDO("mysql:host=localhost;dbname=applicant-records", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'];
$sql = "SELECT applicant_form FROM applicantrecord WHERE applicantID = :id";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/pdf');
echo $result['applicant_form'];
?>