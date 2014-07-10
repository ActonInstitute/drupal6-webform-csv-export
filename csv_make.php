<?php

/**
 * Acton CSV Exporter
 *
 * @author  :  David Lohmeyer
 * @version :  1.0
 * @date    :  09/15/2010
 * Purpose  :  Generate CSV files from Drupal webform submissions
 */
session_start();
require('csv_config.php');
require('csv_class.php');


//This file is used to generate a CSV from the list of submissions one at a time
$sub = $_POST["nid"];
$site = $_POST["site"];

$form1 = new CSVMaker($db[$site]['ip'], $db[$site]['user'], $db[$site]['pass'], $db[$site]['db']);
$form1->headers = $db[$site]['nids'][$sub]['headers'];
$form1->rows = $db[$site]['nids'][$sub]['rows'];
$form1->groupedComp = $db[$site]['nids'][$sub]['groupedComponents'];
$form1->keySelects = $db[$site]['nids'][$sub]['meta']['keySelects'];
$form1->uc = $db[$site]['nids'][$sub]['ucwords'];
$form1->truncate = $db[$site]['nids'][$sub]['truncate'];
$form1->removeIfEmpty = $db[$site]['nids'][$sub]['removeIfEmpty'];

$form1->genCsvReport($sub, $_POST);
?>