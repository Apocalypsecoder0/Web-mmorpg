<?php
include("config.php");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['userid']) || !$_SESSION['userid']) {
    // User not logged in, redirect to login
    header("Location: indexpages/login.php");
    exit;
}

// Initialize User class for validation
$s = new User();

// Check if user still exists and is valid
try {
    $query = "SELECT uid, uname FROM users WHERE uid = " . intval($_SESSION['userid']) . " LIMIT 1";
    $q = $s->query($query);

    if (!$q || !$q->fetch(PDO::FETCH_OBJ)) {
        // User no longer exists, destroy session
        session_destroy();
        header("Location: indexpages/login.php");
        exit;
    }
} catch (Exception $e) {
    // Database error, redirect to login
    session_destroy();
    header("Location: indexpages/login.php");
    exit;
}

// User is authenticated, continue with the page
?>
<?php
$s = new User();
$query = "
	SELECT aname,alevel 
	FROM ".$s->db_prefix."access
	";
$q = $s->query($query);
showPage();
// mysql_free_result is not needed with MySQLi/PDO
?>