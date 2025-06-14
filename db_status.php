
<?php
// Simple database status check without full config
echo '<h2>Database Connection Status</h2>';

// Check environment variables
$envVars = ['PGHOST', 'PGUSER', 'PGPASSWORD', 'PGDATABASE', 'PGPORT', 'DATABASE_URL'];
$hasAllVars = true;

echo '<h3>Environment Variables:</h3>';
echo '<ul>';
foreach ($envVars as $var) {
    $value = getenv($var);
    $status = $value ? '✅' : '❌';
    echo "<li>$status $var: " . ($value ? 'Set' : 'Not set') . "</li>";
    if (!$value && $var !== 'DATABASE_URL') {
        $hasAllVars = false;
    }
}
echo '</ul>';

if ($hasAllVars) {
    echo '<p style="color: green;">✅ All required environment variables are set!</p>';
    echo '<p><a href="/setup_db.php">Run Database Setup</a> | <a href="/test_db.php">Test Database Connection</a></p>';
} else {
    echo '<p style="color: red;">❌ Missing environment variables. Please set up PostgreSQL in Replit first.</p>';
    echo '<h3>Setup Instructions:</h3>';
    echo '<ol>';
    echo '<li>Open a new tab in Replit</li>';
    echo '<li>Search for "Database"</li>';
    echo '<li>Click "create a database"</li>';
    echo '<li>Wait for setup to complete</li>';
    echo '<li>Refresh this page</li>';
    echo '</ol>';
}
?>
