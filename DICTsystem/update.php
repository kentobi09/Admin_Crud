<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate and sanitize inputs (you should add more validation as needed)
    $applicantID = $_POST['edit-applicantID'];
    $fname = htmlspecialchars($_POST['edit-fname']);
    $mname = htmlspecialchars($_POST['edit-mname']);
    $lname = htmlspecialchars($_POST['edit-lname']);
    $sex = $_POST['edit-sex'];
    $province = $_POST['edit-province'];
    $contactNumber = htmlspecialchars($_POST['edit-contactNumber']);
    $emailAddress = htmlspecialchars($_POST['edit-emailAddress']);
    $notificationDate = $_POST['edit-notificationDate'];
    $examDate = $_POST['edit-examDate'];
    $examVenue = htmlspecialchars($_POST['edit-examVenue']);
    $proctor = htmlspecialchars($_POST['edit-proctor']);
    $status = $_POST['edit-status'];
    $exam1Score = $_POST['edit-exam1Score'];
    $exam2Score = $_POST['edit-exam2Score'];
    $exam3Score = $_POST['edit-exam3Score'];
    $totalScore = $_POST['edit-totalScore'];

    // Database connection
    $dsn = 'mysql:host=localhost;dbname=applicant-records';
    $username = 'root';
    $password = '';


    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update applicantrecord table (personal details and application form)
        $sqlApplicantRecord = "UPDATE applicantrecord SET 
                               firstname = :fname,
                               middlename = :mname,
                               lastname = :lname,
                               sex = :sex,
                               province = :province,
                               contact_number = :contactNumber,
                               email_address = :emailAddress,
                               date_of_notification = :notificationDate,
                               date_of_examination = :examDate,
                               exam_venue = :examVenue,
                               proctor = :proctor,
                               status = :status";

        // Check if a new file is uploaded for application form
        if ($_FILES['edit-applicantForm']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['edit-applicantForm']['tmp_name'];
            $fileContent = file_get_contents($fileTmpPath);

            $sqlApplicantRecord .= ", applicant_form = :applicantForm";
        }

        $sqlApplicantRecord .= " WHERE applicantID = :applicantID";

        $stmtApplicantRecord = $pdo->prepare($sqlApplicantRecord);
        $stmtApplicantRecord->bindParam(':fname', $fname);
        $stmtApplicantRecord->bindParam(':mname', $mname);
        $stmtApplicantRecord->bindParam(':lname', $lname);
        $stmtApplicantRecord->bindParam(':sex', $sex);
        $stmtApplicantRecord->bindParam(':province', $province);
        $stmtApplicantRecord->bindParam(':contactNumber', $contactNumber);
        $stmtApplicantRecord->bindParam(':emailAddress', $emailAddress);
        $stmtApplicantRecord->bindParam(':notificationDate', $notificationDate);
        $stmtApplicantRecord->bindParam(':examDate', $examDate);
        $stmtApplicantRecord->bindParam(':examVenue', $examVenue);
        $stmtApplicantRecord->bindParam(':proctor', $proctor);
        $stmtApplicantRecord->bindParam(':status', $status);
        $stmtApplicantRecord->bindParam(':applicantID', $applicantID);

        // Bind application form content if it exists
        if ($_FILES['edit-applicantForm']['error'] === UPLOAD_ERR_OK) {
            $stmtApplicantRecord->bindParam(':applicantForm', $fileContent, PDO::PARAM_LOB);
        }

        // Execute update for applicantrecord table
        $stmtApplicantRecord->execute();

        // Update applicantscore table (exam scores)
        $sqlApplicantScore = "UPDATE applicantscore SET 
                              applicant_score_part1 = :exam1Score,
                              applicant_score_part2 = :exam2Score,
                              applicant_score_part3 = :exam3Score,
                              total_score = :totalScore
                              WHERE applicantID = :applicantID";

        $stmtApplicantScore = $pdo->prepare($sqlApplicantScore);
        $stmtApplicantScore->bindParam(':exam1Score', $exam1Score);
        $stmtApplicantScore->bindParam(':exam2Score', $exam2Score);
        $stmtApplicantScore->bindParam(':exam3Score', $exam3Score);
        $stmtApplicantScore->bindParam(':totalScore', $totalScore);
        $stmtApplicantScore->bindParam(':applicantID', $applicantID);

        // Execute update for applicantscore table
        $stmtApplicantScore->execute();
        $editapplicant = $fname.' '.$mname.' '. $lname;
        include('addrecord.php');
        // Redirect back to the previous page with success message
        header("Location: table-records.php");
        exit();
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Redirect if not submitted via POST method
    header("Location: {$_SERVER['HTTP_REFERER']}?update=error");
    exit();
}
?>
