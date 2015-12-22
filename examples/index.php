<?php
/**
 * Index File
 */
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
      <?php
        echo "<h1>Examples</h1>";
        echo "<p><ul>";
        foreach (scandir('.') as $file) {
            if(preg_match("/(.*)\.php/", $file)) {
                echo "<li><a href=\"$file\">" . $file . "</a></li>";
            }
        }
        echo "<li><a href=\"secure/index.html\">Secure folder with .htaccess + .htpasswd Basic Auth</a> <span class=\"label label-warning\">Notice! modify the path to .htpasswd in .htaccess</span></li>";
        echo "</ul></p>";
      ?>
      </div>
  </body>
</html>


