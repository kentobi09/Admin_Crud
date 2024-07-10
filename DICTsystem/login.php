<?php
session_start();
// Check if session_conflict alert is set
if (isset($_GET['alert']) && $_GET['alert'] === 'session_conflict') {
    echo "<script>alert('Another admin is using this account. You have been logged out.');</script>";
    // Clear the alert after displaying
    unset($_GET['alert']);
}
try {
    $conn = new PDO("mysql:host=localhost;dbname=applicant-records", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

$error = "";
$name = "";
$password = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $name = $_POST['name'];

    $stmt = $conn->prepare("SELECT adminID, admin_password, session_token FROM adminaccount WHERE username = :name");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && password_verify($password, $result['admin_password'])) {
        // Generate a new session token
        $session_token = bin2hex(random_bytes(32));

        // Store the session token in the session and update database
        $_SESSION['user_id'] = $result['adminID'];
        $_SESSION['session_token'] = $session_token;

        // Update session token in the database
        $stmt = $conn->prepare("UPDATE adminaccount SET session_token = :session_token WHERE adminID = :adminID");
        $stmt->bindParam(':session_token', $session_token);
        $stmt->bindParam(':adminID', $result['adminID']);
        $stmt->execute();

        // Clear the session conflict flag
        unset($_SESSION['session_conflict']);

        header("Location: table-records.php");
        exit();
    } else {
        $error = "Invalid name or password.";
        $name = "";
        $password = "";
    }
}

if (isset($_SESSION['user_id'])) {
    // Verify session token
    $stmt = $conn->prepare("SELECT session_token FROM adminaccount WHERE adminID = :adminID");
    $stmt->bindParam(':adminID', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['session_token'] === $_SESSION['session_token']) {
        // Check if session_conflict flag is set
        if (isset($_SESSION['session_conflict'])) {
            // Redirect to login page with alert message
            header("Location: login.php?alert=session_conflict");
            exit();
        }
        header("Location: table-records.php");
        exit();
    } else {
        // Invalid session token
        session_destroy();
        header("Location: login.php");
        exit();
    }
}


?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3&display=swap" rel="stylesheet">
    <title>Login</title>
    <style>
        body {
            background-color: rgba(250, 250, 250, 255);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container-fluid {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* text-align: center; */
            border: 0px solid rgba(16, 68, 144, 255);
            padding: 20px;
            border-radius: 10px;
            background-color: #FAF9F6;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.1);
        }
        .login-container img {
            max-width: 100%;
            height: auto;
        }
        .login-container form {
            width: 100%;
        }
    </style>
</head>
<body>
<section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="login-container">
            <img src="image/dictregion2.png" alt="Sample image">
            <form method="POST" action="">
                <h1 style="color: rgba(16,68,144,255); text-align: center;">Login</h1>
                <br>
                <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                <div class="form-outline mb-4">
                    <label class="form-label" for="form3Example3" style="color: black;">Name</label>
                    <input style="background-color: white;" name="name" type="text" id="form3Example3" class="form-control form-control-lg" placeholder="Enter your name" value="<?php echo htmlspecialchars($name); ?>" />
                </div>
                <div class="form-outline mb-3">
                    <label class="form-label" for="form3Example4" style="color: black;">Password</label>
                    <div class="input-group">
                        <input style="background-color: white;" name="password" type="password" id="form3Example4" class="form-control form-control-lg" placeholder="Enter password" value="<?php echo htmlspecialchars($password); ?>" />
                        <span class="input-group-text">
                            <i id="togglePassword" class="fa-solid fa-eye-slash"></i>
                        </span>
                    </div>
                </div>
                <div class="text-center text-lg-start mt-4 pt-2">
                    <button type="submit" id="loginButton" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem; background-color: rgba(16,68,144,255); width: 100%;" disabled>Login</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Font Awesome Icons -->
<script src="https://kit.fontawesome.com/dc21b4aa01.js" crossorigin="anonymous"></script>

<script>
    // Function to toggle password visibility
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('form3Example4');
        const togglePassword = document.getElementById('togglePassword');
        const loginButton = document.getElementById('loginButton');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle eye icon
            togglePassword.classList.toggle('fa-eye');
            togglePassword.classList.toggle('fa-eye-slash');
        });

        // Function to check if both fields are filled and enable/disable the login button
        function checkInputs() {
            const nameValue = document.getElementById('form3Example3').value.trim();
            const passwordValue = passwordInput.value.trim();

            if (nameValue !== '' && passwordValue !== '') {
                loginButton.removeAttribute('disabled');
            } else {
                loginButton.setAttribute('disabled', 'true');
            }
        }

        // Event listeners for input fields
        document.getElementById('form3Example3').addEventListener('input', checkInputs);
        passwordInput.addEventListener('input', checkInputs);

        // Clear input fields if error message is displayed
        <?php if ($error): ?>
        document.getElementById('form3Example3').value = '';
        document.getElementById('form3Example4').value = '';
        <?php endif; ?>
    });
</script>

</body>

</html>
