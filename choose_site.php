<?php
/**
 * choose_site.php
 *
 * @author  :  David Lohmeyer
 * @version :  2.0
 * @date    :  11/23/2010
 * Purpose  :  Used to choose a site to connect to the correct Drupal DB
 */
session_start();
$_SESSION['pagename'] = 'choose_site.php'
?>
<?php include "sql-header.php"; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Drupal Webform CSV Maker</title>
        <link href="style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="Content"><h1 class="top">Choose a Drupal Site</h1>

            <ul><li><a href="choose_site.php">Change Website</a></li></ul><br /><br /><p><b>Pick:</b><br /><?php
//Include the main script table
echo '<ul>';
foreach ($db as $u => $l) {
    echo '<li><a href="index.php?s=' . $u . '">' . $l['sitename'] . '</a></li>';
}
echo '</ul>';
?>
            </p>
        </div>
    </body>
</html>
