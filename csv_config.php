<?php

/*
  ##############################################
  Updating this application?

  This configuration file should contain all you need for the rest of the application
  to run itself. Below you'll find arrays that you can duplicate with values for a new site.
  The array needs information about webform node id's, titles, database connections, and then finally
  the preset header and row values.

  ##############################################
 */
$path = 'admin/drupal_csvmaker';
//$path = 'admin/drupal_csvmaker';
//The default date format
$today = date("m/d/y");

//Default site
$defaultsite = 'au';


/* * ***************************************

  BEGIN EDITABLE ARRAYS FOR CUSTOM CSVS

 * **************************************** */

/*
  For AU

  Not imported:
  Achievements since last application
  All Expenses
  Are you currently a student?
  Are you an alumni?
  Blog Description
  How you heard of Acton University
  Where you attended a past AU/TFAVS
 */


//Connection info, form node id's, default node id, site name
$db['au'] = array(
    //ip=>'localhost',
    ip => 'MYSQL_SERVER_IP',
    user => 'MYSQL_USER',
    pass => 'MYSQL_PASS',
    db => 'DRUPAL_DB_NAME',
    nids => array(1 => array(), 173 => array()),
    defaultnid => 173,
    sitename => 'Acton University',
);
//Meta data for this DB listing
$db['au']['nids'][173]['meta'] = array(
    name => 'Acton University 2011',
    firstCid => '6',
    lastCid => '8',
    keySelects => array('91')
);
//Default headers definition
$db['au']['nids'][173]['headers'] = array(
    'a' => 'REGStatus',
    'b' => 'EventID',
    'c' => 'REGRegistration',
    'd' => 'REGInvite',
    'e' => 'REGKeyInd',
    'f' => 'C Attr Category',
    'g' => 'C Attr Description',
    'h' => 'C Attr Comments',
    'i' => 'C Notepad Type',
    'j' => 'C Notepad Description',
    'k' => 'C Notepad Notes',
    'l' => 'C Notepad Date',
    'm' => 'C Org Rel Primary',
    'n' => 'C Org Rel Contact',
    'o' => 'C Org Rel Print Org',
    '4' => 'C Field Title 1',
    '6' => 'C Field First Name',
    '7' => 'C Field Middle Name',
    '8' => 'C Field Last Name',
    '9' => 'C Addr Field Address',
    '10' => 'C Addr Field City',
    '12' => 'C Addr Field ZIP',
    '14' => 'C Addr Field Type',
    '15' => 'C Phone Number Home',
    '16' => 'C Email Address Email 1',
    '18' => 'C Email Address Blog',
    '21' => 'C Field Martial Status',
    '22' => 'C Field Gender',
    '23' => 'C Field Birth Date',
    '24' => 'C Field Birthplace',
    '29' => 'Org Rel Org Name',
    '56b' => 'REGAttrCat',
    '56' => 'REGAttrDesc',
    '59b' => 'REGAttrCat',
    '59' => 'REGAttrDesc',
    '62b' => 'REGAttrCat',
    '62' => 'REGAttrDesc',
    '74b' => 'REGAttrCat',
    '74' => 'REGAttrDesc',
    '75b' => 'REGAttrCat',
    '75' => 'REGAttrDesc',
    '76b' => 'REGAttrCat',
    '76' => 'REGAttrDesc',
    '77b' => 'REGAttrCat',
    '77' => 'REGAttrDesc',
    '78b' => 'REGAttrCat',
    '78' => 'REGAttrDesc',
    '79b' => 'REGAttrCat',
    '79' => 'REGAttrDesc',
    '80b' => 'REGAttrCat',
    '80' => 'REGAttrDesc',
    '81b' => 'REGAttrCat',
    '81' => 'REGAttrDesc',
    '90' => 'C Addr Field Country',
    '91' => 'C Addr Field State',
    '92' => 'C Field Religion',
    '96b' => 'REGAttrCat',
    '96' => 'REGAttrDesc',
    '108b' => 'REGAttrCat',
    '108' => 'REGAttrDesc',
    '111' => 'C Phone Number Cell',
    '113d' => 'CAttrCat',
    '113c' => 'CAttrDesc',
    '113' => 'CAttrComm',
    '113b' => 'CAttrDate',
    '114' => 'Org Rel Position',
);
//Default row definition
$db['au']['nids'][173]['rows'] = array(
    'a' => 'Not Confirmed',
    'b' => '2011AU',
    'c' => 'Registered',
    'd' => 'Not Invited',
    'e' => 'I',
    'f' => 'Source',
    'g' => '2011 Import',
    'h' => $today,
    'i' => 'Added to the database',
    'j' => '2011 AU Registration',
    'k' => '2011 AU Registration',
    'l' => $today,
    'm' => 'Y',
    'n' => 'N',
    'o' => 'Y',
    '4' => '',
    '6' => '',
    '7' => '',
    '8' => '',
    '9' => '',
    '10' => '',
    '12' => '',
    '14' => '',
    '15' => '',
    '16' => '',
    '18' => '',
    '21' => '',
    '22' => '',
    '23' => '',
    '24' => '',
    '29' => '',
    '56b' => '11 Session 2',
    '56' => '',
    '59b' => '11 Session 3',
    '59' => '',
    '62b' => '11 Session 4',
    '62' => '',
    '74b' => '11 Session 5',
    '74' => '',
    '75b' => '11 Session 6',
    '75' => '',
    '76b' => '11 Session 7',
    '76' => '',
    '77b' => '11 Session 8',
    '77' => '',
    '78b' => '11 Session 9',
    '78' => '',
    '79b' => '11 Session 10',
    '79' => '',
    '80b' => '11 Session 11',
    '80' => '',
    '81b' => '11 Session 12',
    '81' => '',
    '90' => '',
    '91' => '',
    '92' => '',
    '96b' => '11 Session 1',
    '96' => '',
    '108b' => 'Opt Out',
    '108' => 'No',
    '111' => '',
    '113d' => 'Reffered to AU by',
    '113' => '',
    '113c' => '2011 AU',
    '113b' => $today,
    '114' => '',
);
//Define which fields to trim with truncate array
$db['au']['nids'][173]['truncate'][114] = 50;
$db['au']['nids'][173]['truncate'][29] = 50;
$db['au']['nids'][173]['truncate'][18] = 50;
//Which should be made into uppercase words
$db['au']['nids'][173]['ucwords'] = array(16);
$db['au']['nids'][173]['removeIfEmpty']['113'] = array('113', '113b', '113c', '113d');
$db['au']['nids'][173]['removeIfEmpty']['29'] = array('114', 'm', 'n', 'o');
//Grouped components that may show up in one conditional scenario but don't in another (courses)
$db['au']['nids'][173]['groupedComponents'][1] = array('57', '56');
$db['au']['nids'][173]['groupedComponents'][2] = array('59', '60');
$db['au']['nids'][173]['groupedComponents'][3] = array('62', '63');
$db['au']['nids'][173]['groupedComponents'][4] = array('96', '97');
?>
