<?php
require_once 'config.php';

// PROTECT THIS PAGE - Only logged in users can access
if (!isLoggedIn()) {
    redirect('login.php');
}

// Get all events created by this user
$stmt = $pdo->prepare("SELECT * FROM events WHERE created_by = ? ORDER BY event_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$events = $stmt->fetchAll();

// Handle delete request
if (isset($_GET['delete'])) {
    $eventId = (int)$_GET['delete'];
    
    // Delete only if this user owns the event
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ? AND created_by = ?");
    $stmt->execute([$eventId, $_SESSION['user_id']]);
    
    redirect('dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Event Management</title>
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        .navbar h1 { font-size: 24px; }
        .navbar .user-info { 
            display: flex; 
            align-items: center; 
            gap: 20px; 
        }
        .navbar .username { font-weight: bold; }
        .navbar a { 
            color: white; 
            text-decoration: none; 
            padding: 8px 16px; 
            background: rgba(255,255,255,0.2); 
            border-radius: 5px; 
        }
        .navbar a:hover { background: rgba(255,255,255,0.3); }
        
        /* Main Content */
        .container { 
            max-width: 1200px; 
            margin: 40px auto; 
            padding: 0 20px; 
        }
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 30px; 
        }
        .header h2 { color: #333; }
        
        /* Buttons */
        .btn { 
            padding: 10px 20px; 
            background: #667eea; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
            display: inline-block; 
        }
        .btn:hover { background: #5568d3; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-edit { background: #3498db; }
        .btn-edit:hover { background: #2980b9; }
        
        /* Events Grid */
        .events-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
            gap: 20px; 
        }
        .event-card { 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        .event-card h3 { color: #333; margin-bottom: 10px; }
        .event-card p { color: #666; margin-bottom: 8px; }
        .event-actions { 
            display: flex; 
            gap: 10px; 
            margin-top: 15px; 
        }
        .event-actions a { font-size: 14px; padding: 8px 16px; }
        
        /* Empty State */
        .empty { 
            text-align: center; 
            padding: 60px 20px; 
            background: white; 
            border-radius: 10px; 
        }
        .empty p { 
            color: #999; 
            font-size: 18px; 
            margin-bottom: 20px; 
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="navbar">
        <h1>Event Management System</h1>
        <div class="user-info">
            <span class="username">👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="container">
        <div class="header">
            <h2>My Events</h2>
            <a href="create_event.php" class="btn">+ Create New Event</a>
        </div>
        
        <?php if (empty($events)): ?>
            <!-- Show this if no events exist -->
            <div class="empty">
                <p>You haven't created any events yet.</p>
                <a href="create_event.php" class="btn">Create Your First Event</a>
            </div>
        <?php else: ?>
            <!-- Show event cards -->
            <div class="events-grid">
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <h3><?= htmlspecialchars($event['title']) ?></h3>
                        <p><strong>Date:</strong> <?= date('F j, Y', strtotime($event['event_date'])) ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                        <p><strong>Description:</strong> <?= htmlspecialchars($event['description']) ?></p>
                        <div class="event-actions">
                            <a href="edit_event.php?id=<?= $event['id'] ?>" class="btn btn-edit">Edit</a>
                            <a href="dashboard.php?delete=<?= $event['id'] ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>