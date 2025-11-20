<?php
require_once 'config.php';

// PROTECT THIS PAGE - Only logged in users can access
if (!isLoggedIn()) {
    redirect('login.php');
}

$error = '';

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and clean inputs
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $event_date = sanitizeInput($_POST['event_date']);
    $location = sanitizeInput($_POST['location']);
    
    // Validate required fields
    if (empty($title) || empty($event_date) || empty($location)) {
        $error = "Title, date, and location are required";
    } else {
        // Insert event into database
        $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, location, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $event_date, $location, $_SESSION['user_id']]);
        
        // Go back to dashboard
        redirect('dashboard.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event - Event Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        
        /* Top Navigation Bar */
        .navbar { 
            background: #667eea; 
            color: white; 
            padding: 15px 30px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .navbar h1 { font-size: 24px; }
        .navbar .user-info { 
            display: flex; 
            align-items: center; 
            gap: 20px; 
        }
        .navbar a { 
            color: white; 
            text-decoration: none; 
            padding: 8px 16px; 
            background: rgba(255,255,255,0.2); 
            border-radius: 5px; 
        }
        .navbar a:hover { background: rgba(255,255,255,0.3); }
        
        /* Form Container */
        .container { 
            max-width: 600px; 
            margin: 40px auto; 
            padding: 0 20px; 
        }
        .form-container { 
            background: white; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        h2 { color: #333; margin-bottom: 30px; }
        
        /* Form Styling */
        .form-group { margin-bottom: 20px; }
        label { 
            display: block; 
            margin-bottom: 5px; 
            color: #555; 
            font-weight: bold; 
        }
        input, textarea { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            font-size: 14px; 
            font-family: Arial, sans-serif; 
        }
        input:focus, textarea:focus { 
            outline: none; 
            border-color: #667eea; 
        }
        textarea { 
            min-height: 120px; 
            resize: vertical; 
        }
        
        /* Buttons */
        .btn { 
            padding: 12px 24px; 
            background: #667eea; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px; 
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover { background: #5568d3; }
        .btn-secondary { 
            background: #95a5a6; 
            margin-left: 10px; 
        }
        .btn-secondary:hover { background: #7f8c8d; }
        
        /* Error Message */
        .error { 
            background: #fee; 
            color: #c33; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 20px; 
        }
        
        .actions { 
            display: flex; 
            gap: 10px; 
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="navbar">
        <h1>Event Management System</h1>
        <div class="user-info">
            <span>👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <!-- Form Content -->
    <div class="container">
        <div class="form-container">
            <h2>Create New Event</h2>
            
            <?php if ($error): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Event Title *</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="event_date">Event Date *</label>
                    <input type="date" id="event_date" name="event_date" required>
                </div>
                
                <div class="form-group">
                    <label for="location">Location *</label>
                    <input type="text" id="location" name="location" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea>
                </div>
                
                <div class="actions">
                    <button type="submit" class="btn">Create Event</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>