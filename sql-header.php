<?php

/**
 * sql-header.php
 *
 * @author  :  David Lohmeyer
 * @version :  2.0
 * @date    :  11/23/2010
 * Purpose  :  Used to provide session info and other meta data to the app
 */
//Get rid of PHPSESSID nonsense in the URL
ini_set("url_rewriter.tags", "");

require('csv_config.php');
require('csv_class.php');


$site = $_GET['s'];
if ($site == '' && $_SESSION['pagename'] != 'choose_site.php') {
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'choose_site.php';
    header("Location: http://$host/$path/$extra");
} else if ($site) {
    $_SESSION['site'] = $site;
    $conn = mysql_connect($db[$site]['ip'], $db[$site]['user'], $db[$site]['pass']);
    mysql_select_db($db[$site]['db']);
mysql_set_charset('utf8',$conn);
}

$n = $_GET["n"];
if ($n == '') {
    $viewing = $db[$site]['defaultnid'];
} else {
    $viewing = $n;
}
?>
