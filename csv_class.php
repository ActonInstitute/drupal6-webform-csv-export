<?php

/* * *********************************************************************************************************
 * Drupal CSV Maker - Class
 *
 * @author  :  David Lohmeyer
 * @version :  1.0
 * @date    :  11/23/2010
 * Purpose  :  Create a customized CSV from Drupal Webform submissions
 * ********************************************************************************************************* */

Class CsvMaker {

    //The meat values
    public $nid;
    public $sid;
    public $headers;
    public $rows;
    public $groupedComp;
    public $keySelects;
    public $truncate;
    public $uc;
    public $removeIfEmpty;
    //The formatting values
    public $breakValue = '\n';
    public $dateFormat = 'm/d/Y';
    public $checkValue = 'V';
    public $selectTruncate = 60;
    //Date values
    public $today;
    public $fileToday;

    function __construct($ip, $user, $pass, $db) {
        $this->today = date("m/d/y");
        $this->fileToday = date("m-d-y h:i");
        $conn =  mysql_connect($ip, $user, $pass);
        mysql_select_db($db);
	mysql_set_charset('utf8',$conn);
    }

    /*     * *********************************************************************************************************
     * addHeader
     *
     * Purpose   :   Generate the header string from an array of header values and return the string
     * Params    :   $header : The array of header definitions to include in the CSV
     * ********************************************************************************************************* */

    private function addHeader($header) {
        $string = '';
        //Add the columns to the string
        foreach ($header as $i => $k) {
            $string .= '' . $k . ',';
        }
        //Get rid of the trailing comma
        $string = substr_replace($string, '', -1, 1);
        return $string;
    }

    /*     * *********************************************************************************************************
     * addRow
     *
     * Purpose   :   Generate the row string from an array of row values and return the string
     * Params    :   $row : The array of row definitions to include in the CSV
     * ********************************************************************************************************* */

    private function addRow($row) {
        $string = '';
        //Rows
        $string .= "\n";
        //Add the rows to the main string
        foreach ($row as $j => $q) {
            $string .= $this->filterText($q, $j) . ",";
        }
        //Get rid of the trailing comma
        $string = substr_replace($string, '', -1, 1);
        return $string;
    }

    /*     * *********************************************************************************************************
     * mapSingleRow
     *
     * Purpose   :   Row magic happens here.  Returns the proper array of elements for the webform submission row since it's impossible for our
     * 				config file to get these values.  Also makes sure they're all formatted.
     * Params    :   $sid : The submission ID for the submission we're generating
     * 			:	$sub : The node ID for the submission we're generating
     * 			:	$row : Array: The preconfigured blank row defined in configuration
     * 			:	$groupedComp : Array of arrays: Component IDs that are conditional but only show up once, we make sure they only show up
     * 				once in our row
     * 			:	$keySelects : Array: Component IDs that should use the key value in the row, not the actual label value
     * ********************************************************************************************************* */

    private function mapSingleRow($sid, $sub, $row, $groupedComp, $keySelects) {
        $data_query = "SELECT cid FROM webform_submitted_data WHERE sid=$sid AND nid=$sub ORDER BY sid,cid ASC";
        $data_resultset = mysql_query($data_query);
        //echo $data_resultset;
        //Go through the data table's query
        $c = 0;
        //loop through SINGLE CID row
        while ($data = mysql_fetch_assoc($data_resultset)) {
            $data_row[] = $data;
            $cid = (string) $data_row[$c]['cid'];
            //If it's in a conditional group find the CId we have in our template that matches...
            $realCid = $this->getRealConditional($sid, $sub, $cid, $groupedComp, $row, $data_row[$c]['data']);
            //Go through the default row array to filter
            foreach ($row as $e => $r) {
                if ($realCid == $e) {
                    $row[$realCid] = $this->formatRowData($cid, $sid, $sub, $keySelects);
                }
            }
            //Array of actual cids returned from the Drupal db
            $actual[] = $realCid;
            ++$c;
        }
        //$this->print_r_html($actual);
        //RemoveIfEmpty array signifies the fields we want to blank completely from our template if the top key of removeIfEmpty is not present in our data
        foreach ($this->removeIfEmpty as $o => $p) {
            //echo $o;
            if (!in_array($o, $actual) || $row[$o] == '') {
                foreach ($p as $s => $b) {
                    $row[$b] = '';
                }
            }
        }
        //$this->print_r_html($row);
        return $row;
    }

    /*     * *********************************************************************************************************
     * getRealConditional
     *
     * Purpose   :   Returns the true conditional component if there are potentially many that fit in the row template, otherwise return the same cid
     * Params    :   $cid : The component id to identify
     * 			:	$groupedCond : Array of arrays: the groups of groups of conditionals
     * 			:	$row : The preconfigured row template
     * 			:	$data : The data for this component
     * ********************************************************************************************************* */

    private function getRealConditional($sid, $nid, $cid, $groupedComp, $row, $data) {
        $found[1] = '';
        $found[2] = '';
        //See if the cid is contained in the group array
        foreach ($groupedComp as $u => $h) {
            foreach ($h as $u2 => $h2) {
                if ($cid == $h2) {
                    //Define if cid is found
                    $found[1] = $u;
                    $found[2] = $u2;
                    //$this->print_r_html($h);
                }
            }
        }
        //Find the real cide to use
        if ($found[1] != '') {
            //$this->print_r_html($groupedComp[$found[1]]);
            foreach ($groupedComp[$found[1]] as $b => $m) {
                //echo 'CID:'. $m .' SID:'. $sid .' NID:'. $nid.' FOUND2: '.$found[2]. ' FOUND1: '.$found[1]. '<br />';
                //Query for the value of the other CID in our group array
                $data_query = "SELECT data FROM webform_submitted_data WHERE sid=$sid AND nid=$nid AND cid=$m";
                $data_resultset = mysql_query($data_query);
                if ($data_resultset) {
                    while ($rsql = mysql_fetch_array($data_resultset)) {
                        $rswl[$m] = $rsql[0];
                        //echo 'ALT KEY:'.$m.'<br />';
                        //echo 'ALT VALUE:'.$rswl[$m].'<br />';
                    }
                }
            }
            foreach ($row as $rcid => $rvalue) {
                if (in_array($rcid, $groupedComp[$found[1]])) {
                    //echo 'RETURNING:'.$rcid.'<br />';
                    return (string) $rcid;
                }
            }
        }
        return (string) $cid;
    }

    /*     * *********************************************************************************************************
     * formatRowData
     *
     * Purpose   :   Get and format the single row data properly
     * Params    :   $cid : The component id
     * 			:	$sid : Form submission id
     * 			:	$nid : Form node id
     * 			:	$keySelects : Which cids should use key instead of label
     * ********************************************************************************************************* */

    private function formatRowData($cid, $sid, $nid, $keySelects) {

        $real_query = "SELECT data FROM webform_submitted_data WHERE sid=$sid AND nid=$nid AND cid=$cid";
        $real_resultset = mysql_query($real_query);
        $realsql = mysql_fetch_array($real_resultset);
        $type = $this->getCidType($cid);
        if ($type == 'select') {
            $string = substr($this->getSelectKeyvalue($cid, $realsql[0], $nid, $keySelects), 0, $this->selectTruncate);
        } else if ($type == 'date') {
            $string = $this->formatDate($realsql[0], $this->dateFormat);
        } else {
            $string = $realsql[0];
        }
        //Format case
        if (in_array($cid, $this->uc)) {
            $string = ucwords($string);
        }
        //Truncate
        if ($this->truncate[$cid]) {
            $string = substr($string, 0, $this->truncate[$cid]);
        }
        return $this->fixEncoding($string);
    }

    /*     * *********************************************************************************************************
     * genCsvReport
     *
     * Purpose   :   Makes the browser download the CSV file after constructing it with functions in this class
     * Params    :   $nid : The node id of the form
     * 			:	$sids : The submission ID's selected on the front interface... we check this since it also contains other things (a _POST value)
     * ********************************************************************************************************* */

    public function genCsvReport($nid, $sids) {

        $csvstr = $this->addHeader($this->headers);
        foreach ($sids as $id => $value) {
            if ($value == $this->checkValue) {
                //echo 'SID:'.$id.'<br/>';
                //echo 'SID:'.$nid.'<br/>';
                $row = $this->mapSingleRow($id, $nid, $this->rows, $this->groupedComp, $this->keySelects);
                $csvstr .= $this->addRow($row);
            }
        }
        //Allows the file to be downloaded
        $today = $this->fileToday;
        $today = str_replace(" ", "_", $today);
	/**/
	header('Content-Encoding: UTF-8');
        header("Content-Type: text/csv; charset=UTF-8");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Disposition: attachment; filename=AU-$today.csv");
	//UTF8 header
	//echo "\xEF\xBB\xBF";
        echo $csvstr;
        /**/
        //Debug
        //$this->print_r_html($row);
    }

    /*     * *********************************************************************************************************
     * getSelectKeyvalue
     *
     * Purpose   :   Returns the value (label) of the option element from the database instead of the value of the key
     * Params    :   $cid : The component id you want to look up
     * 			:	$data : The provided key
     * 			:	$sub: The node ID of the form
     * ********************************************************************************************************* */

    private function getSelectKeyvalue($cid, $data, $sub, $keySelects) {
        if (!in_array($cid, $keySelects)) {
            $data_query = "SELECT extra FROM webform_component WHERE cid=$cid AND nid=$sub";
            $data_resultset = mysql_query($data_query);
            $extra = mysql_fetch_assoc($data_resultset);
            $pull = $extra[extra];

            //Data is serialized in the Drupal DB for labels
            $readable = unserialize($pull);
            //$this->print_r_html($readable['items']);

            $work = explode("\n", $readable['items']);
            //$this->print_r_html($work);

            $pair = 0;
            //This logic assumes the selects always have a key|label assigned
            foreach($work as $x=>$pairStr){
                $single[$pair] = explode('|', $pairStr);
                $pair++;
            }
            foreach($single as $sNum=>$sArr){
                if($sArr[0] == $data){
                    // echo $sArr[1].'<br />';
                    return trim($sArr[1]);
                }
            }
        } else {
           return $data;
        }


    }

    /*     * *********************************************************************************************************
     * getCidType
     *
     * Purpose   :   Returns the component type string
     * Params    :   $cid : The component id you want to look up
     * ********************************************************************************************************* */

    public function getCidType($cid) {
        $type_query = "SELECT type FROM webform_component WHERE cid=$cid";
        $type_resultset = mysql_query($type_query);
        $type = mysql_fetch_assoc($type_resultset);
        return $type['type'];
    }

    /*     * *********************************************************************************************************
     * formatDate
     *
     * Purpose   :   Return a date in the desired format
     * Params    :   $d : The date itself
     * 			:	$format : The string format we want to return
     * ********************************************************************************************************* */

    public function formatDate($d, $format) {
        $date = date($format, strtotime($d));
        return $date;
    }

    /*     * *********************************************************************************************************
     * print_r_html
     *
     * Purpose   :   Return a readable array for debugging purposes
     * Params    :   $arr : The array to format
     * ********************************************************************************************************* */

    public function print_r_html($arr) {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }

    /*     * *********************************************************************************************************
     * filterText
     *
     * Purpose   :   Return a properly formatted CSV row value with encasing quotes
     * Params    :   $value : The string to format
     * ********************************************************************************************************* */

    private function filterText($value) {
        $val = str_replace('"', "'", $value);

        $value = '"' . $val . '"';
        return $value;
    }

    /**
     *
     * @param $in_str : String
     * @return Row data in the proper encoding
     */
    private function fixEncoding($in_str) {
        $cur_encoding = mb_detect_encoding($in_str);
        if ($cur_encoding == "UTF-8" && mb_check_encoding($in_str, "UTF-8")){
            return $in_str;
	}else{
	  // return htmlentities($in_str, ENT_COMPAT, "UTF-8");
           // return utf8_encode($in_str);
		return $in_str;
	}
    }

}

?>
