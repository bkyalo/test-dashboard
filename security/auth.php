<?php
/**
 * Simple authentication for live testing
 * Remove or enhance for production
 */

session_start();

// Simple password protection for testing
define('TEST_PASSWORD', 'somas2024'); // Change this!

if (!isset($_SESSION['authenticated'])) {
    if (isset($_POST['password']) && $_POST['password'] === TEST_PASSWORD) {
        $_SESSION['authenticated'] = true;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Show login form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>SOMAS Analytics - Access Required</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 400px; margin: 100px auto; padding: 20px; }
            .login-form { background: #f8f9fa; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
            button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
            button:hover { background: #0056b3; }
        </style>
    </head>
    <body>
        <div class="login-form">
            <h2>🔒 SOMAS Analytics Access</h2>
            <p>This is a test deployment. Please enter the access password:</p>
            <form method="post">
                <input type="password" name="password" placeholder="Enter password" required>
                <button type="submit">Access Dashboard</button>
            </form>
            <p><small>For testing purposes only</small></p>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
