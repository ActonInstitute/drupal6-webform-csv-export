<?php
session_start();
//Used for logout purposes
$_SESSION['pagename'] = 'index.php';
?>
<?php

// Function to calculate script execution time. 
function microtime_float() {
    list ($msec, $sec) = explode(' ', microtime());
    $microtime = (float) $msec + (float) $sec;
    return $microtime;
}

// Get starting time. 
$start = microtime_float();
?>
<?php include "sql-header.php"; ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Drupal Webform CSV Generator</title>
        <link href="style.css" rel="stylesheet" type="text/css" />
    </head>

    <body>

        <div class="Content"><h1 class="top">Drupal Webform CSV Generator</h1>

            <ul><li><a href="choose_site.php">Change Website</a></li></ul><br /><p>     <?php
//Include the main script table
include('csv_export.php');
?>

                <?php
                mysql_close();
                ?>&nbsp;</p>
        </div>
                <?php
                $end = microtime_float();

// Print results. 
                echo '<center>Script Execution Time: ' . round($end - $start, 3) . ' seconds</center>';
                ?>
    </body>
</html>
