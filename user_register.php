<?php
session_start();
require 'db_connection.php';

if (isset($_POST['name'], $_POST['email'], $_POST['password'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Prepare and execute the insert query
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();

    // Set success message in session
    $_SESSION['success_message'] = "Registration successful! Please login.";

    // Redirect to login page
    header("Location: user_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Phone Store</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(45deg, #0B0B45, #4169E1);
            padding: 30px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 120%;
            height: 120%;
            background: url('https://images.unsplash.com/photo-1616348436168-de43ad0db179?ixlib=rb-4.0.3') center/cover;
            opacity: 0.1;
            filter: blur(5px);
            z-index: -1;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }

        .register-container:hover::before {
            left: 100%;
        }

        .register-title {
            color: #fff;
            text-align: center;
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-floating {
            margin-bottom: 20px;
            position: relative;
        }

        .form-floating > .form-control {
            background: rgb(252, 245, 245);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            height: 60px;
            padding: 20px;
            font-size: 1rem;
            color: #333;
            transition: all 0.3s ease;
        }

        .form-floating > label {
            padding: 20px;
            color: #666;
        }

        .form-floating > .form-control:focus {
            background: #fff;
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .input-group-text {
            background: transparent;
            border: none;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 4;
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }

        .input-group-text:hover {
            color: #4169E1;
        }

        .password-strength {
            position: absolute;
            bottom: -20px;
            left: 0;
            width: 100%;
            height: 4px;
            background: #ddd;
            border-radius: 2px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .password-strength::before {
            content: '';
            position: absolute;
            left: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(45deg, #FF6B6B, #4169E1);
            transition: all 0.3s ease;
        }

        .password-strength.weak::before {
            width: 33%;
            background: #FF6B6B;
        }

        .password-strength.medium::before {
            width: 66%;
            background: #FFB86C;
        }

        .password-strength.strong::before {
            width: 100%;
            background: #4BB543;
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #4169E1, #0B0B45);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(65, 105, 225, 0.4);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .login-text {
            text-align: center;
            margin-top: 25px;
            color: #fff;
        }

        .login-link {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
        }

        .login-link::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -2px;
            left: 0;
            background: #fff;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .login-link:hover::after {
            transform: scaleX(1);
        }

        .floating-shapes div {
            position: absolute;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.1);
            animation: float 15s linear infinite;
            bottom: -75px;
        }

        .floating-shapes div:nth-child(1) {
            left: 10%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
            animation-duration: 18s;
        }

        .floating-shapes div:nth-child(2) {
            left: 30%;
            width: 60px;
            height: 60px;
            animation-delay: 5s;
            animation-duration: 12s;
        }

        .floating-shapes div:nth-child(3) {
            left: 70%;
            width: 100px;
            height: 100px;
            animation-delay: 7s;
            animation-duration: 15s;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
            }
        }

        .alert {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 12px;
            color: #fff;
            padding: 15px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-success {
            border-left: 4px solid #4BB543;
        }

        .alert-danger {
            border-left: 4px solid #FF3333;
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div></div>
        <div></div>
        <div></div>
    </div>
    
    <div class="register-container">
        <h1 class="register-title">Create Account</h1>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo htmlspecialchars($_SESSION['error_message']); ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form method="post" action="user_register.php" id="registerForm">
            <div class="form-floating mb-4">
                <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
                <label for="name">Full Name</label>
                <span class="input-group-text">
                    <i class="fas fa-user"></i>
                </span>
            </div>
            
            <div class="form-floating mb-4">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                <label for="email">Email Address</label>
                <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </span>
            </div>
            
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
                <span class="input-group-text" id="togglePassword">
                    <i class="fas fa-eye"></i>
                </span>
                <div class="password-strength"></div>
            </div>
            
            <button type="submit" class="btn btn-register">
                <i class="fas fa-user-plus me-2"></i> Create Account
            </button>
        </form>
        
        <div class="login-text">
            <a href="user_login.php" class="login-link">
                <i class="fas fa-sign-in-alt me-1"></i> Already have an account? Login here
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Password strength indicator
        const passwordStrength = document.querySelector('.password-strength');
        password.addEventListener('input', function() {
            const value = this.value;
            const strength = checkPasswordStrength(value);
            
            passwordStrength.className = 'password-strength';
            if (value.length > 0) {
                passwordStrength.classList.add(strength);
            }
        });

        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Check length
            if (password.length >= 8) strength++;
            
            // Check for mixed case
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            
            // Check for numbers
            if (password.match(/\d/)) strength++;
            
            // Check for special characters
            if (password.match(/[^a-zA-Z\d]/)) strength++;
            
            if (strength < 2) return 'weak';
            if (strength < 3) return 'medium';
            return 'strong';
        }
    </script>
</body>
</html>
