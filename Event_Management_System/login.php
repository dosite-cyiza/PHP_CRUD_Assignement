<?php
require_once "config.php";

$error ='';
// If already logged in, go to dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
}

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and clean the inputs
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

       // Check if fields are empty
    if (empty($username) || empty($password)) {
        $error = "All fields are required";
    } else {
        // Search for user in database
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
    }

     // Check if user exists and password is correct
        if ($user && password_verify($password, $user['password'])) {
            // Prevent session fixation - create new session ID
            session_regenerate_id(true);
            
            // Save user info in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['login_time'] = time();
            
            // Handle "Remember Me" checkbox
            if ($remember) {
                // Create a secure random token
                $token = bin2hex(random_bytes(32));
                
                // Save token in cookies for 30 days
                setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true);
                setcookie('user_id', $user['id'], time() + (86400 * 30), '/', '', false, true);
            }
            
            // Go to dashboard
            redirect('dashboard.php');
        } else {
            $error = "Invalid username or password";
        }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Event Management</title>
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
        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            font-size: 14px; 
        }
        input:focus { outline: none; border-color: #667eea; }
        .remember { 
            display: flex; 
            align-items: center; 
            margin-bottom: 20px; 
        }
        .remember input { 
            width: auto; 
            margin-right: 8px; 
        }
        .btn { 
            width: 100%; 
            padding: 12px; 
            background: #667eea; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            font-size: 16px; 
            cursor: pointer; 
        }
        .btn:hover { background: #5568d3; }
        .error { 
            background: #fee; 
            color: #c33; 
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
        <h2>Login</h2>
        
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="remember">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember" style="margin: 0;">Remember Me (30 days)</label>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <div class="link">
            Don't have an account? <a href="signup.php">Sign up here</a>
        </div>
    </div>
</body>
</html>