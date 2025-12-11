<?php
session_start();
require_once "database.php";
require_once "Auth.php";

// Create database connection and Auth object
$database = new Database();
$conn = $database->connect();
$auth = new Auth($conn);

// Check if user is logged in, if not go to login page
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Get user data to show on page
$user = $auth->getUserData();

// Handle logout request
if (isset($_GET['logout'])) {
    $auth->logout();
    header("Location: login.php");
    exit;
}

// Success message popout
$welcomeMessage = "Welcome, " . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . "!";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaCar - Home</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Arial', sans-serif; }
        body { background-color:#f8f9fa; color:#333; line-height:1.6; }
        .header { background: linear-gradient(135deg,#ff6b9d,#89CFF0); color:white; padding:25px 20px; text-align:center; position:relative; }
        .logo { font-size:42px; font-weight:bold; margin-bottom:10px; }
        .tagline { font-size:18px; opacity:0.9; }
        .logout-btn { position:absolute; top:20px; right:20px; background:rgba(255,255,255,0.2); color:white; border:2px solid white; padding:8px 20px; border-radius:20px; cursor:pointer; font-weight:bold; transition:all 0.3s ease; }
        .logout-btn:hover { background:white; color:#ff6b9d; }
        .welcome-section { text-align:center; padding:50px 20px; background:white; margin:25px; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1); }
        .welcome-section h2 { font-size:32px; margin-bottom:15px; color:#ff6b9d; }
        .welcome-princess { font-size:26px; font-weight:bold; color:#89CFF0; margin:20px 0; }
        .features { display:flex; justify-content:center; gap:20px; margin:30px 25px; flex-wrap:wrap; }
        .feature-card { background:white; padding:25px; border-radius:8px; text-align:center; width:220px; box-shadow:0 3px 8px rgba(0,0,0,0.08); }
        .feature-icon { font-size:36px; margin-bottom:15px; }
        .pink-icon { color:#ff6b9d; }
        .blue-icon { color:#89CFF0; }
        .feature-card h3 { margin-bottom:10px; color:#444; }
        .feature-card p { color:#666; font-size:14px; }
        .cars-section { padding:30px 25px; background:white; margin:25px; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1); }
        .section-title { text-align:center; margin-bottom:25px; color:#ff6b9d; font-size:26px; }
        .cars-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(250px,1fr)); gap:20px; }
        .car-card { border-radius:8px; overflow:hidden; box-shadow:0 3px 8px rgba(0,0,0,0.1); background:#f8f9fa; }
        .car-image { height:140px; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:16px; }
        .pink-bg { background:#ff6b9d; }
        .blue-bg { background:#89CFF0; }
        .gradient-bg { background:linear-gradient(135deg,#ff6b9d,#89CFF0); }
        .car-details { padding:15px; }
        .car-details h3 { margin-bottom:8px; color:#444; }
        .car-details p { color:#666; font-size:14px; margin-bottom:8px; }
        .car-price { font-weight:bold; color:#ff6b9d; font-size:18px; }
        .footer { background:linear-gradient(135deg,#ff6b9d,#89CFF0); color:white; padding:25px 20px; text-align:center; margin-top:30px; }
        .footer p { margin:5px 0; }
        #welcomePopout { max-width:450px; margin:20px auto; padding:15px; background:#eaffea; color:#27ae60; border-left:4px solid #27ae60; border-radius:5px; text-align:center; font-weight:bold; font-size:16px; position:fixed; top:20px; left:50%; transform:translateX(-50%); z-index:9999; box-shadow:0 5px 15px rgba(0,0,0,0.2); opacity:0; transition: opacity 0.5s ease-in-out; }
    </style>
</head>
<body>
    <!-- Success popout -->
    <div id="welcomePopout"><?php echo $welcomeMessage; ?></div>

    <!-- Header -->
    <header class="header">
        <button class="logout-btn" onclick="confirmLogout()">Logout</button>
        <div class="logo">LaCar</div>
        <p class="tagline">Find your perfect car with ease</p>
    </header>

    <!-- Welcome section -->
    <section class="welcome-section">
        <h2>Welcome to LaCar</h2>
        <p>Your journey to the perfect car starts here</p>
    </section>

    <!-- Features section -->
    <section class="features">
        <div class="feature-card">
            <div class="feature-icon pink-icon">🚗</div>
            <h3>New Cars</h3>
            <p>Latest models from top brands</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon blue-icon">💰</div>
            <h3>Best Prices</h3>
            <p>Great deals and financing options</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon pink-icon">🔧</div>
            <h3>Full Service</h3>
            <p>Complete after-sales support</p>
        </div>
    </section>

    <!-- Featured cars -->
    <section class="cars-section">
        <h2 class="section-title">Featured Cars</h2>
        <div class="cars-grid">
            <div class="car-card">
                <div class="car-image pink-bg">Pink Sedan</div>
                <div class="car-details">
                    <h3>Elegant Sedan</h3>
                    <p>2026 Model • Automatic • Luxury</p>
                    <div class="car-price">$32,000</div>
                </div>
            </div>
            <div class="car-card">
                <div class="car-image blue-bg">Blue SUV</div>
                <div class="car-details">
                    <h3>Family SUV</h3>
                    <p>2026 Model • 4WD • 7 Seats</p>
                    <div class="car-price">$45,000</div>
                </div>
            </div>
            <div class="car-card">
                <div class="car-image gradient-bg">Sport Car</div>
                <div class="car-details">
                    <h3>Sports Model</h3>
                    <p>2026 Model • Fast • Premium</p>
                    <div class="car-price">$58,000</div>
                </div>
            </div>
            <div class="car-card">
                <div class="car-image pink-bg">City Car</div>
                <div class="car-details">
                    <h3>Compact Car</h3>
                    <p>2026 Model • Eco • Urban</p>
                    <div class="car-price">$22,000</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>LaCar &copy; 2026 - All rights reserved</p>
        <p>Email: contact@lacar.com | Phone: (123) 456-7890</p>
    </footer>

    <script>
        // Logout confirmation
        function confirmLogout() {
            if(confirm('Are you sure you want to logout?')) {
                window.location.href = 'index.php?logout=1';
            }
        }

        // Show welcome popout
        const popout = document.getElementById('welcomePopout');
        if(popout) {
            setTimeout(()=>{popout.style.opacity=1;},100);      // fade in
            setTimeout(()=>{popout.style.opacity=0;},3100);     // fade out
            setTimeout(()=>{popout.remove();},3600);            // remove from DOM
        }
    </script>
</body>
</html>
