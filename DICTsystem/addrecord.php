<?php
ob_start();
require_once 'insert.php'; 
require_once 'update.php';
require_once 'delete.php';
?>
<?php
try {
    session_start(); // Start or resume session
    // Database connection
    $conn = new PDO("mysql:host=localhost;dbname=applicant-records", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the session token is set
    if (isset($_SESSION['session_token'])) {
        // Retrieve session token from session
        $session_token = $_SESSION['session_token'];

        // Fetch admin details from the database using the session token
        $sql = "SELECT adminID, username FROM adminaccount WHERE session_token = :session_token";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':session_token', $session_token);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify if admin details are found
        if ($admin) {
            $admin_id = $admin['adminID'];
            $admin_name = $admin['username'];
        } else {
            // Handle case where admin details are not found
            header("Location: login.php");
            exit();
        }
    } else {
        // Handle case where session token is not set
        header("Location: login.php");
        exit();
    }

    // Start transaction
    $conn->beginTransaction();

    if(isset($addapplicant)&& preg_match('/[a-zA-Z]/', $addapplicant)){
    $action_done = 'Added applicant record for '.$addapplicant;
    }else if (isset($editapplicant)&& preg_match('/[a-zA-Z]/', $editapplicant)){
    $action_done = 'Updated applicant record for '.$editapplicant;
    }else if (isset($deletedapplicant)&& preg_match('/[a-zA-Z]/', $deletedapplicant)){
    $action_done = 'Deleted applicant record for '.$deletedapplicant;   
    }

    // Prepare SQL statement
    $sql = "INSERT INTO history (admin_id, admin_name, action_done) VALUES (:admin_id, :admin_name, :action_done)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':admin_id', $admin_id);
    $stmt->bindParam(':admin_name', $admin_name);
    $stmt->bindParam(':action_done', $action_done);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    echo "Dummy row has been added successfully by the logged-in user.";

} catch (Exception $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo "Error: " . $e->getMessage();
}
?>
