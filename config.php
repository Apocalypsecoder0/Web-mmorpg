
<?php
// Start session first before any output - no whitespace or BOM before this
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if we're in Replit environment with PostgreSQL
$isReplitDB = getenv('DATABASE_URL') !== false;

// Database configuration for Replit PostgreSQL
if ($isReplitDB) {
    // Use Replit's PostgreSQL environment variables
    $conf = array(
        'db_server' => getenv('PGHOST'),
        'db_username' => getenv('PGUSER'),
        'db_password' => getenv('PGPASSWORD'),
        'db_name' => getenv('PGDATABASE'),
        'db_port' => getenv('PGPORT'),
        'db_prefix' => ''
    );
} else {
    // Fallback configuration (will show setup message)
    $conf = array(
        'db_server' => 'localhost',
        'db_username' => 'postgres',
        'db_password' => '',
        'db_name' => 'warofages',
        'db_port' => '5432',
        'db_prefix' => ''
    );
}

// Define constants
define('DEBUG', true);
define('TEMPLATES_PATH', 'templates/');

// Initialize global variables
$GLOBALS['subs'] = array();

// Include compatibility layer for MySQL functions
require_once('compat.php');

// Include all class files
require_once('base/functions.php');
require_once('base/Debug.class.php');
require_once('base/Chive.class.php');
require_once('base/User.class.php');
require_once('base/Game.class.php');

// Global database connection using PostgreSQL PDO
try {
    if (!$isReplitDB) {
        // Store setup message in session to display later
        $_SESSION['db_setup_message'] = '<div style="background: #f0f0f0; padding: 20px; margin: 20px; border: 1px solid #ccc;">
            <h3>Database Setup Required</h3>
            <p>To set up PostgreSQL in Replit:</p>
            <ol>
                <li>Open a new tab and search for "Database"</li>
                <li>Click "create a database" to set up PostgreSQL</li>
                <li>This will automatically create the necessary environment variables</li>
                <li>Then visit <a href="/setup_db.php">/setup_db.php</a> to create the database schema</li>
            </ol>
        </div>';
        return; // Don't exit, just return
    }
    
    $dsn = "pgsql:host={$conf['db_server']};port={$conf['db_port']};dbname={$conf['db_name']}";
    $pdo = new PDO($dsn, $conf['db_username'], $conf['db_password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // For backward compatibility, create a global connection variable
    $GLOBALS['db_connection'] = $pdo;
    $GLOBALS['pdo'] = $pdo;
    
} catch (Exception $e) {
    $_SESSION['db_error_message'] = '<div style="background: #ffe6e6; padding: 20px; margin: 20px; border: 1px solid #ff0000;">
        <h3>Database Connection Error</h3>
        <p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>
        <p>Please ensure you have set up PostgreSQL in Replit:</p>
        <ol>
            <li>Open a new tab and search for "Database"</li>
            <li>Click "create a database"</li>
            <li>Wait for the setup to complete</li>
            <li>Then visit <a href="/setup_db.php">/setup_db.php</a></li>
        </ol>
    </div>';
    return; // Don't exit, just return
}
?>
