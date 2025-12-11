<?php
// Start the user session
session_start();

// Load the database file
require_once "database.php";

// Load the Auth file
require_once "Auth.php";

// Create a new database object
$database = new Database();

// Connect to the database
$conn = $database->connect();

// Create a new Auth object
$auth = new Auth($conn);

// If the user is logged in, go to the home page
if ($auth->isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// Create empty error and success messages
$error = '';
$success = '';

// Check if the signup form was sent
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {

    // Get the username from the form
    $username = trim($_POST['username']);

    // Get the email from the form
    $email = trim($_POST['email']);

    // Get the password from the form
    $password = trim($_POST['password']);

    // Get the user's first name
    $firstName = trim($_POST['first_name']);

    // Get the user's last name
    $lastName = trim($_POST['last_name']);
    
    // Try to create a new account
    $result = $auth->register($username, $email, $password, $firstName, $lastName);
    
    // If signup is successful
    if ($result === true) {
        // Log the user in after signup
        $auth->login($username, $password);

        // Go to the home page
        header("Location: index.php");
        exit;
    } else {
        // If signup has an error, show it
        $error = $result;
    }
}
?>
<!-- This is the HTML structure of the signup page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Title of the signup page -->
    <title>Sign Up - LaCar</title>

    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="css.css">

    <style>
        /* Reset default margins and padding */
        
        /* Main font and box settings */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        /* Background design for the whole page */
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
        
        /* Box for the signup form */
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
        
        /* Top colored line decoration */
        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(135deg, #ff6b9d, #89CFF0);
        }
        
        /* Header section for logo and text */
        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        /* Logo style */
        .auth-logo {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #ff6b9d, #89CFF0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Page title style */
        .auth-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 24px;
        }
        
        /* Style for all input fields */
        .auth-form input {
            width: 100%;
            margin: 8px 0;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        /* Style when user clicks inside the input */
        .auth-form input:focus {
            border-color: #89CFF0;
            box-shadow: 0 0 0 2px rgba(137, 207, 240, 0.2);
            outline: none;
        }
        
        /* Style for the sign-up button */
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
        
        /* Hover effect for the button */
        .auth-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Box for the login link */
        .auth-links {
            text-align: center;
            margin-top: 20px;
        }
        
        /* Style for all links */
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
        
        /* Style for error message box */
        .error { 
            color: #e74c3c; 
            text-align: center; 
            background: #ffeaea;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #e74c3c;
        }
        
        /* Style for success message box */
        .success { 
            color: #27ae60; 
            text-align: center; 
            background: #eaffea;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #27ae60;
        }
        
        /* Footer small text style */
        .form-footer {
            text-align: center;
            margin-top: 25px;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- Main box for the signup content -->
    <div class="auth-container">

        <!-- Header with logo and text -->
        <div class="auth-header">
            <!-- Brand logo text -->
            <div class="auth-logo">LaCar</div>

            <!-- Small description text -->
            <p>Find your perfect car with ease</p>
        </div>
        
        <!-- Page title -->
        <h2>Create New Account</h2>
        
        <!-- Show error message if there is one -->
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Show success message if signup is successful -->
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Signup form -->
        <form class="auth-form" action="signup.php" method="POST">

            <!-- Field for username -->
            <input type="text" name="username" placeholder="Username" required>

            <!-- Field for email -->
            <input type="email" name="email" placeholder="Email" required>

            <!-- Field for password -->
            <input type="password" name="password" placeholder="Password" required>

            <!-- Field for first name -->
            <input type="text" name="first_name" placeholder="First Name" required>

            <!-- Field for last name -->
            <input type="text" name="last_name" placeholder="Last Name" required>

            <!-- Button to submit the form -->
            <button type="submit" name="signup" class="btn-signup">Sign Up</button>
        </form>

        <!-- Link to login page -->
        <div class="auth-links">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
        
        <!-- Footer small text -->
        <div class="form-footer">
            <p>&copy; 2026 LaCar. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
