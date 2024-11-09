<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASAP - Evaluate, Educate, Develop</title>
    <link rel="stylesheet" href="homepage.css">

</head>
<body> 
    <div class="bg"></div>
    <div class="bg bg2"></div>
    <div class="bg bg3"></div>
    
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo">
            <h1>ASAP</h1>
        </div>
        <nav class="nav-links">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
            <!-- <a href="signup.php" class="signup-btn">Sign Up</a> Link to signup PHP file -->
            <!-- <a href="login.php" class="login-btn">Login</a> Link to login PHP file -->
            <a href="../sign in/signup.php" class="signup-btn">Sign Up</a>
            <a href="../login/login.php" class="login-btn">Login</a>

        </nav>
        <div class="mobile-menu" id="mobile-menu">&#9776;</div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <p style="font-weight: bold; font-size:70px;color: #8d493a; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
                ASAP
            </p>
            
            <p style="font-style: italic; font-size: 0.7em; color:white;">EMPOWERING STUDENTS TO ACHIEVE THEIR GOALS, AS SOON AS POSSIBLE</p>

            <a href="#signup" class="cta-btn">Discover Features</a>
        </div>
        
        <div class="hero-illustration">
            <img src="logonew.jpg" alt="Illustration of a person at a desk" />
        </div>
    </section>

    <!-- Key Modules Section -->
    <section id="modules" class="features">
        <h2>Our Key Modules</h2>
        <div class="module-cards">
            <div class="card">
                <h3>Personalized Interfaces</h3>
                <p>Personalized interfaces for users based on roles, such as students, teachers, and admins.</p>
                <a href="#personalized" class="start-btn">Start Now!</a>
            </div>
            <div class="card">
                <h3>Performance & Evaluation</h3>
                <p>Track student progress with detailed reports and graphs.</p>
                <a href="#performance" class="start-btn">Start Now!</a>
            </div>
            <div class="card">
                <h3>Criteria & Session Management</h3>
                <p>Sort students based on marks, manage sessions, and send notifications.</p>
                <a href="#criteria" class="start-btn">Start Now!</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <h2>About ASAP</h2>
        <p>ASAP is designed to bridge the gap between potential and achievement through personalized learning, continuous assessments, and teacher feedback.</p>
    </section>

    <!-- Signup Section -->
    <section id="signup" class="signup-section">
        <h2>Join ASAP Today</h2>
        <p>Sign up to start exploring all the features and enhance your learning journey.</p>
        <a href="signup.php" class="signup-btn">Sign Up Now</a> <!-- Link to signup PHP file -->
    </section>
    
    <!-- Footer Section -->
    <footer id="contact" class="footer">
        <p>&copy; <?php echo date("Y"); ?> ASAP Platform. All rights reserved.</p>
        <div class="footer-links">
            <a href="#">Privacy Policy</a> | 
            <a href="#">Terms of Service</a> | 
            <a href="#">Support</a>
        </div>
    </footer>

    <script>
        const mobileMenu = document.getElementById('mobile-menu');
        const navLinks = document.querySelector('.nav-links');
        mobileMenu.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>
</body>
</html>
