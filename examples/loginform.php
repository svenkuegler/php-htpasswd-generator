<?php
/**
 * PHP Loginform Example
 */
require '../src/HtpasswdGenerator.php';

$auth = new HtpasswdGenerator("secure/.htpasswd");

session_start();

if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    if ($auth->isValid($username, $password)) {
        $_SESSION['user'] = $username;
    } else {
        die ('Incorrect username or password<br /><a href="loginform.php">back</a>');
    }
} else if (isset($_REQUEST['logout'])) {
    session_destroy();
    die ('Logout successfull<br /><a href="loginform.php">back</a>');
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <title>Login Sample</title>
  </head>
  <body>
      <div class="container">
      
<?php if (! isset($_SESSION['user'])):?>
    <br /><br />
    <form class="form-horizontal" method="post">
        <fieldset>
            <legend>Login required</legend>
            <div class="form-group"><label for="username" class="col-sm-2 control-label">Username:</label><div class="col-sm-6"><input class="form-control" type="text" name="username" id="username"></div></div>
            <div class="form-group"><label for="password" class="col-sm-2 control-label">Password:</label><div class="col-sm-6"><input class="form-control" type="password" name="password" id="password"></div></div>
            <div class="form-group"><div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="Login" class="btn btn-primary"></div></div>
        </fieldset>
    </form>
<?php else: ?>
    <h1>Login Successfull with &quot;<?php echo $_SESSION['user'] ?>&quot;</h1>
    <p><a href="?logout=1" class="btn btn-default">Logout</a></p>
<?php endif; ?>
    
    </div>
  </body>
</html>
