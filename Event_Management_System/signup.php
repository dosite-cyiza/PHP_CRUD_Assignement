<?php
require_once 'config.php';

$error = '';
$success = '';


// If already logged in, go to dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
}

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and clean the inputs
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if fields are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required";
    }

    // Check if email is valid
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } 

    // Check password length
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } 

    // Check if passwords match
    elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    }

    // Check if passwords match
    elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } 

     else {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            $error = "Username or email already exists";
        }
        else {
            // Hash the password (encrypt it)
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashedPassword]);
            
             $success = "Registration successful! You can now login.";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Event Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }
        .container { 
            background: white; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.2); 
            width: 100%; 
            max-width: 400px; 
        }
        h2 { text-align: center; color: #333; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; }
        input { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            font-size: 14px; 
        }
        input:focus { outline: none; border-color: #667eea; }
        .btn { 
            width: 100%; 
            padding: 12px; 
            background: #667eea; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            font-size: 16px; 
            cursor: pointer; 
            margin-top: 10px; 
        }
        .btn:hover { background: #5568d3; }
        .error { 
            background: #fee; 
            color: #c33; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 20px; 
        }

        .success { 
            background: #efe; 
            color: #3c3; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 20px; 
        }
        .link { text-align: center; margin-top: 20px; color: #666; }
        .link a { color: #667eea; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Account</h2>
        
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">Sign Up</button>
        </form>
        
        <div class="link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>