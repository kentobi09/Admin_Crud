<?php
session_start();

if (isset($_SESSION['user_id'])) {
    try {
        $conn = new PDO("mysql:host=localhost;dbname=applicant-records", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Clear the session token in the database
        $stmt = $conn->prepare("UPDATE adminaccount SET session_token = NULL WHERE adminID = :adminID");
        $stmt->bindParam(':adminID', $_SESSION['user_id']);
        $stmt->execute();
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

// Destroy the session
session_destroy();

header("Location: login.php");
exit();
?>
