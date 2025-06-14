
<?php
require_once('config.php');

try {
    // Test basic connection
    $stmt = $pdo->query("SELECT version()");
    $version = $stmt->fetchColumn();
    
    echo "<h3>Database Connection Test</h3>";
    echo "<p><strong>PostgreSQL Version:</strong> " . $version . "</p>";
    
    // Test tables exist
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p><strong>Tables found:</strong></p>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . htmlspecialchars($table) . "</li>";
    }
    echo "</ul>";
    
    // Test user count
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    echo "<p><strong>Total users:</strong> " . $userCount . "</p>";
    
    echo "<p style='color: green;'>✅ Database connection and setup successful!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}
?>
