<form method="post" action="index.php"><table width="342px" border="0">
        <tr>
          <td height="338px" align="center" valign="middle" style="background-image:url(images/login.jpg); no-repeat on;"><table border="0">
            <tr>
              <td align="center" valign="middle"><font color="white">Email:</font></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="text" name="user" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><font color="white">Password:</font></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="password" name="pass" /></td>
            </tr>
            <tr>
              <td align="center" valign="middle"><input type="submit" name="submit" value="Login" /></td>
            </tr>
          </table></td>
          </tr>
        </table>
<?php
session_start();
include('../config.php');

$error = '';

if ($_POST['username'] && $_POST['password']) {
    $user = new User();
    $username = $user->clean_sql($_POST['username']);
    $password = $user->salt($_POST['password']);
    
    $query = "SELECT uid, uname, access_level FROM users WHERE uname = $username AND password = '$password' LIMIT 1";
    $q = $user->query($query);
    
    if ($q && $userdata = $q->fetch(PDO::FETCH_OBJ)) {
        $_SESSION['userid'] = $userdata->uid;
        $_SESSION['username'] = $userdata->uname;
        $_SESSION['access'] = $userdata->access_level;
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>War of Ages - Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #000; color: #fff; }
        .login-form { max-width: 400px; margin: 100px auto; padding: 20px; background: #222; border-radius: 5px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin: 5px 0; }
        input[type="submit"] { background: #0066cc; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .error { color: #ff6666; }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>War of Ages - Login</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
        <p><a href="register.php">Register new account</a></p>
    </div>
</body>
</html>
