<?php
// Database Configuration
define('DB_HOST','localhost');
define('DB_user','root');
define('DB_PASS','');
define('DB_NAME','event_system');

// session security setting

ini_set('session.cookie_httponly',1);//Prevents JavaScript from accessing cookies(Hackers can use JavaScript to steal your session cookies)
ini_set('session.use_only_cookies',1);//Forces sessions to ONLY use cookies (not URL parameters) ,Why? Sessions in URLs can be seen/stolen easily, example(Example of BAD: website.com?PHPSESSID=abc123)
ini-set('session.cookie_secure',0);//Determines if cookies only work on HTTPS
ini_set('session.cookie_samesite','strict');//  Cookies only sent from YOUR website,Prevents Cross-Site Request Forgery (CSRF) attacks

// Connect to database

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    // Prevent session fixation
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

// Clean user input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

//Redirect to another page
function redirect($url) {
    header("Location: $url");
    exit();
}

?>