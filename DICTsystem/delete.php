<?php
try {
    // Create a new PDO instance with your provided credentials
    $conn = new PDO("mysql:host=localhost;dbname=applicant-records", "root", "");
    
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form has been submitted
    if(isset($_POST['delete_applicant'])){
        // Retrieve applicantID from POST data
        $applicantID = $_POST['applicantID'];
        $sql = "SELECT firstname, middlename, lastname FROM applicantrecord WHERE applicantID = :applicantID";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':applicantID', $applicantID);
    $stmt->execute();

    // Fetch the data
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result){
        $firstname = $result['firstname'];
        $middlename = $result['middlename'];
        $lastname = $result['lastname'];
        }else{
            $deletedapplicant= "Deleted applicant record di ko nakuha yung names";
        }

        // Delete related scores in applicantscore first
        $deleteScoresSql = "DELETE FROM applicantscore WHERE applicantID = :applicantID";
        $deleteScoresStmt = $conn->prepare($deleteScoresSql);
        $deleteScoresStmt->bindParam(':applicantID', $applicantID, PDO::PARAM_INT);
        $deleteScoresStmt->execute();

        // Then delete the applicant record
        $deleteApplicantSql = "DELETE FROM applicantrecord WHERE applicantID = :applicantID";
        $deleteApplicantStmt = $conn->prepare($deleteApplicantSql);
        $deleteApplicantStmt->bindParam(':applicantID', $applicantID, PDO::PARAM_INT);
        $deleteApplicantStmt->execute();

        



        // Check if any rows were affected
        if ($deleteApplicantStmt->rowCount() > 0) {
            $deletedapplicant =$firstname.' '. $middlename.' '. $lastname;
            include('addrecord.php');
            header("Location: table-records.php");
            exit(); // Ensure that script execution stops after redirection
        } else {
            echo '<script>alert("No applicant found with that ID.")</script>';
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
