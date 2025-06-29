
<?php
// Start session first
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle logout request
if (isset($_GET['logout']) && $_GET['logout']) {
    require_once('base/User.class.php');
    User::logOut();
    header("Location: /");
    exit;
}

// Include config file
if (!file_exists('config.php')) {
    die('Config file not found. Please check the file path.');
}

include_once("config.php");

// Display database setup messages if they exist
if (isset($_SESSION['db_setup_message'])) {
    echo $_SESSION['db_setup_message'];
    unset($_SESSION['db_setup_message']);
    exit;
}

if (isset($_SESSION['db_error_message'])) {
    echo $_SESSION['db_error_message'];
    unset($_SESSION['db_error_message']);
    exit;
}

// Initialize variables
$s = null;

// Handle login form submission
if (isset($_POST['submit']) && $_POST['submit'] == "Login") {
    if (class_exists('User') && isset($_POST['user']) && isset($_POST['pass'])) {
        $s = new User($_POST['user'], $_POST['pass']);
    }
}

// Handle registration form submission
if (isset($_POST['submit']) && $_POST['submit'] == "Register") {
    if (class_exists('User')) {
        $s = new User();
        if (isset($_POST['number']) && isset($_SESSION['image_value'])) {
            $number = $_POST['number'];
            if (md5($number) != $_SESSION['image_value']) {
                echo 'Validation string not valid! Please try again!<br>';
            } else {
                if (isset($_POST['user'], $_POST['pass'], $_POST['email'], $_POST['rid'], $_POST['hpname'])) {
                    $s->addUser($_POST['user'], $_POST['pass'], $_POST['email'], $_POST['rid'], $_POST['hpname'], $_SERVER["REMOTE_ADDR"]);
                }
            }
        }
    }
}

// Check if user is logged in (either from session or fresh login)
$loggedIn = false;
if (isset($_SESSION['userid']) && $_SESSION['userid']) {
    $loggedIn = true;
} elseif ($s && isset($s->loggedIn) && $s->loggedIn) {
    $loggedIn = true;
}

if (!$loggedIn) {
    // Show login page
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/auto.js"></script>
    <script type="text/javascript" src="js/train.js"></script>
    <script type="text/javascript" src="js/images.js"></script>
    <script type="text/javascript" src="js/bbfix.js"></script>
    <link rel="meta" href="http://codenamelantea.com/labels.rdf" type="application/rdf+xml" title="ICRA labels" />
    <meta http-equiv="pics-Label" content='(pics-1.1 "http://www.icra.org/pics/vocabularyv03/" l gen true for "http://codenamelantea.com" r (n 0 s 0 v 0 l 2 oa 0 ob 0 oc 0 od 0 oe 0 of 0 og 0 oh 0 c 3) gen true for "http://www.codenamelantea.com" r (n 0 s 0 v 0 l 2 oa 0 ob 0 oc 0 od 0 oe 0 of 0 og 0 oh 0 c 3))' />
    <title>Codename: Lantea</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <LINK REL=STYLESHEET TYPE='text/css' HREF='main.css' />
</head>

<body background="images/stars.jpg" onLoad="mainUpdate('login','Login'); MM_preloadImages('images/galaxy1-2.jpg','images/galaxy2-2.jpg','images/galaxy3-2.jpg'); autoclear(); bb_init('divBody', false);">

<div id="divBody">
<table border="0">
  <tr>
    <td colspan="2" align="left" valign="middle"><a href="javascript:void(0)" onClick="mainUpdate('login','Login'); return false" onMouseOver="rollUpDate('Login'); return false" onMouseOut="autoclear(); return false"><img src="images/galaxy1.jpg" name="Image12" width="373" height="188" border="0" id="Image12" onMouseOver="MM_swapImage('Image12','','images/galaxy1-2.jpg',1)" onMouseOut="MM_swapImgRestore()" /></a></td>
    <td colspan="2"></td>
    <td width="23%" colspan="2" align="center" valign="middle"><a href="javascript:void(0)" onMouseOver="rollUpDate('Register To Play'); return false" onClick="mainUpdate('register','Register To Play'); return false" onMouseOut="autoclear(); return false"><img src="images/galaxy2.jpg" name="Image11" width="202" height="78" border="0" id="Image11" onMouseOver="MM_swapImage('Image11','','images/galaxy2-2.jpg',1)" onMouseOut="MM_swapImgRestore()"/></a></td>
  </tr>
  
  <tr>
    <td width="28%"> <table width="100%" height="100%" border="0">
      <tr>
        <td height="10%"></td>
      </tr>
      <tr>
        <td height="90%"><div id="up2date"></div></td>
      </tr>
    </table>    </td>
    <td colspan="3" align="center"><h1>Codename: Lantea</h1><h2><div id="rollover"></div></h2>
    <div id="mainDisplay"></div><span id=""> Graphics Done by test</span><br><a href="http://www.icra.org/sitelabel/" target="_blank"><img src="images/icra.gif"></a></td>
    <td colspan="2"></td>
  </tr>
  
  <tr>
    <td colspan="3" align="center" valign="middle"><a href="javascript:void(0)" onMouseOver="rollUpDate('Updates'); return false" onClick="mainUpdate('updates','Updates'); return false" onMouseOut="autoclear(); return false"><img src="images/galaxy3.JPG" name="Image13" width="366" height="126" border="0" id="Image13" onMouseOver="MM_swapImage('Image13','','images/galaxy3-2.jpg',1)" onMouseOut="MM_swapImgRestore()"/></a></td>
    <td colspan="3"></td>
  </tr>
</table>
</div>
</body>
</html>

<?php
} else {
    // User is logged in, show game interface
    if (class_exists('Game')) {
        try {
            if (!$s) {
                $s = new Game();
            }
            
            // Call the showPage function if it exists
            if (function_exists('showPage')) {
                showPage();
            } else {
                // Fallback if showPage doesn't exist
                include('templates/header.tpl');
                echo "<h1>Welcome to War of Ages</h1>";
                echo "<p>Game interface will be loaded here.</p>";
                include('templates/footer.tpl');
            }
        } catch (Exception $e) {
            echo 'Error initializing game: ' . $e->getMessage();
        }
    } else {
        echo 'Game class not found. Please check your class files.';
    }
}
?>
