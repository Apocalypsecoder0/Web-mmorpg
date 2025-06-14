
<?php
require_once('config.php');

// Check if database connection exists
if (!isset($GLOBALS['pdo']) || !$GLOBALS['pdo']) {
    die('Database connection not available. Please set up PostgreSQL first.');
}

$pdo = $GLOBALS['pdo'];

try {
    // Read and execute the SQL setup file
    $sql = file_get_contents('setup_database.sql');
    
    // Split by semicolons to execute each statement separately
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
            echo "Executed: " . substr($statement, 0, 50) . "...\n<br>";
        }
    }
    
    echo "<h3>Database setup completed successfully!</h3>";
    echo "<p>Your PostgreSQL database is now ready for the War of Ages application.</p>";
    
} catch (Exception $e) {
    echo "Error setting up database: " . $e->getMessage();
}
?>
