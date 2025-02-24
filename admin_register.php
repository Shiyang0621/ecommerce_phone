<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Email is already registered.";
    } else {
        // Insert new admin if email is unique
        $stmt = $conn->prepare("INSERT INTO admins (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $name, $email, $password);
        
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #a8edea, #fed6e3);
            font-family: 'Poppins', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .register-container {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 200px; /* 保持原始尺寸 */
            height: auto;
        }
        .register-container h1 {
            font-size: 26px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #555;
            text-align: left;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
            position: relative;
        }
        .password-toggle {
            position: absolute;
            top: 15px;
            right: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            height: 100%;
        }
        input[type="password"]:focus {
            border-color: #a8edea;
            outline: none;
            box-shadow: 0 0 6px rgba(168, 237, 234, 0.5);
        }
        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #a8edea, #fed6e3);
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #fed6e3, #a8edea);
            box-shadow: 0 4px 10px rgba(168, 237, 234, 0.3);
            transform: scale(1.03);
        }
        .alert {
            font-size: 14px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: rgba(255, 59, 59, 0.1);
            color: #ff3b3b;
            border: 1px solid #ff3b3b;
            text-align: left;
        }
        @media (max-width: 576px) {
            .register-container {
                max-width: 90%;
                padding: 30px 20px;
            }
            .register-container h1 {
                font-size: 22px;
            }
            .btn-primary {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <img src="uploads/logo.webp" alt="Admin Logo" class="logo">
        <h1>Admin Registration</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <i class="fas fa-eye password-toggle"></i>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <div class="footer mt-3">© 2024 Your eCommerce Platform</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const passwordToggle = document.querySelector('.password-toggle');
        const passwordInput = document.querySelector('#password');
        passwordToggle.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
