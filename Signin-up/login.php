<?php
// Start the session for the user
session_start();

// Include the database file
require_once "database.php";

// Include the authentication file
require_once "Auth.php";

// Create a new database object
$database = new Database();

// Connect to the database
$conn = $database->connect();

// Create a new auth object
$auth = new Auth($conn);

// Check if the user is already logged in
if ($auth->isLoggedIn()) {
    // Redirect the user to the homepage
    header("Location: index.php");
    exit;
}

// Create empty messages for errors and success
$error = '';
$success = '';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    
    // Clean the username input
    $username = trim($_POST['username']);
    
    // Clean the password input
    $password = trim($_POST['password']);
    
    // Try to log in with the given data
    $result = $auth->login($username, $password);
    
    // If login is successful
    if ($result === true) {
        // Show success message
        $success = "Login Successful! Redirecting to Home...";
    } else {
        // Show error message
        $error = $result;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">

<!-- Title of the page -->
<title>Login - LaCar</title>

<style>
        /* Reset default browser styles */
        
        /* Set default margin, padding and font */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        /* Style for the page background */
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #ff6b9d, #89CFF0);
        }
        
        /* Container for the login box */
        .auth-container {
            width: 100%;
            max-width: 450px;
            margin: 20px;
            padding: 40px 30px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
        }
        
        /* Add a top line design */
        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #ff6b9d, #89CFF0);
        }
        
        /* Header section */
        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        /* Logo text design */
        .auth-logo {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #ff6b9d, #89CFF0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Login title */
        .auth-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 24px;
        }
        
        /* Style for input fields */
        .auth-form input {
            width: 100%;
            margin: 12px 0;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        /* Style when the input is selected */
        .auth-form input:focus {
            border-color: #89CFF0;
            box-shadow: 0 0 0 2px rgba(137, 207, 240, 0.2);
            outline: none;
        }
        
        /* Login button style */
        .auth-form button {
            width: 100%;
            padding: 14px;
            margin: 20px 0 10px;
            background: linear-gradient(135deg, #ff6b9d, #89CFF0);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        /* Button hover effect */
        .auth-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Container for links */
        .auth-links {
            text-align: center;
            margin-top: 20px;
        }
        
        /* Style for clickable links */
        .auth-links a {
            color: #ff6b9d;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        /* Hover effect for links */
        .auth-links a:hover {
            color: #89CFF0;
            text-decoration: underline;
        }
        
        /* Error message box style */
        .error { 
            color: #e74c3c; 
            text-align: center; 
            background: #ffeaea;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #e74c3c;
        }
        
        /* Success message box style */
        .success { 
            color: #27ae60; 
            text-align: center; 
            background: #eaffea;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #27ae60;
        }
</style>
</head>
<body>

<!-- Main login box -->
<div class="auth-container">
    
    <!-- Title of the form -->
    <h2>Login to Your Account</h2>

    <!-- Show error message if it exists -->
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Show success message if it exists -->
    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>

        <!-- Redirect after 3 seconds -->
        <script>
            setTimeout(function(){
                window.location.href = 'index.php';
            }, 3000);
        </script>
    <?php endif; ?>

    <!-- Login form section -->
    <form class="auth-form" action="login.php" method="POST">

        <!-- Field for username or email -->
        <input type="text" name="username" placeholder="Username or Email" required>

        <!-- Field for password -->
        <input type="password" name="password" placeholder="Password" required>

        <!-- Button to submit the form -->
        <button type="submit" name="login" class="btn-login">Login</button>
    </form>
</div>
</body>
</html>
