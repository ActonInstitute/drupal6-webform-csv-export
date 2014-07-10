<?php
/**
 * csv_export.php
 *
 * @author  :  David Lohmeyer
 * @version :  2.0
 * @date    :  11/23/2010
 * Purpose  :  Display the front end of the app
 */
?>

<h2>Viewing submissions for <?php echo $db[$site]['sitename']; ?></h2>
<h3>View Form Submissions:</h3>
<ul class="forms"><?php
foreach ($db[$site]['nids'] as $q => $k) {
?><li>
    <?php if ($viewing != $q) {
 ?>
        <a href="index.php?s=<?php echo $_SESSION['site']; ?>&n=<?php echo $q; ?>">
            <?php } ?>
            <?php echo $db[$site]['nids'][$q]['meta']['name']; ?>
            <?php if ($viewing != $q) {
 ?>
            </a>
<?php } ?>

        </li><?php } ?></ul>

<?php
// find out how many rows are in the table 
        $sql = "SELECT COUNT(sid) FROM webform_submissions WHERE nid=$viewing";
        $result = mysql_query($sql);
        $r = mysql_fetch_row($result);
        $numrows = $r[0];

// number of rows to show per page
        if (!$_GET["rpp"]) {
            $rowsperpage = 50;
        } else {
            $rowsperpage = $_GET["rpp"];
        }
// find out total pages
        $totalpages = ceil($numrows / $rowsperpage);
// get the current page or set a default
        if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
            // cast var as int
            $currentpage = (int) $_GET['currentpage'];
        } else {
            // default page num
            $currentpage = 1;
        } // end if
// if current page is greater than total pages...
        if ($currentpage > $totalpages) {
            // set current page to last page
            $currentpage = $totalpages;
        } // end if
// if current page is less than first page...
        if ($currentpage < 1) {
            // set current page to first page
            $currentpage = 1;
        } // end if
// the offset of the list, based on current page 
        $offset = (($currentpage - 1) * $rowsperpage) * 2;
        $rowsperpage*=2;

        $first = $db[$_SESSION['site']]['nids'][$q]['meta']['firstCid'];
        $last = $db[$_SESSION['site']]['nids'][$q]['meta']['lastCid'];
        $defaultn = $db[$site]['defaultnid'];
//Stuff to generate the listing table
        if ($n != 0) {
            $data_query = "SELECT sid,cid,data FROM webform_submitted_data WHERE nid=$n AND (cid=$first OR cid=$last) ORDER BY sid DESC, cid ASC LIMIT $offset, $rowsperpage";
        } else {
            $data_query = "SELECT sid,cid,data FROM webform_submitted_data WHERE nid=$defaultn AND (cid=$first OR cid=$last) ORDER BY sid DESC, cid ASC LIMIT $offset, $rowsperpage";
            $n = $defaultn;
        }
        $data_resultset = mysql_query($data_query);
        //Go through the data table's query
        $c = 0;
        if ($data_resultset) {
            while ($data = mysql_fetch_assoc($data_resultset)) {
                $data_row[] = $data;
                $sid = $data_row[$c]['sid'];

                //The main array
                $sub[$sid][$data_row[$c]['cid']] = $data_row[$c]['data'];
                ++$c;
            }
        }
?>
        <p>Total Submissions: <?php echo $numrows; ?></p>
        <br />
        <form action="csv_make.php" method="post">
            <input type="hidden" name="nid" value="<?php echo $n; ?>"/>
            <input type="hidden" name="site" value="<?php echo $site; ?>"/>
            <input type="submit" value="Download CSV Files for Selections"/>

<?php include 'pager.php'; ?>


            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td width="70%" valign="top"><h4>Name</h4></td>
                    <td width="10%" valign="top"><h4>Sub. ID</h4></td>
                    <td width="20%" valign="top"><h4>CSV</h4></td>
                </tr>
<?php
        if ($sub) {
            foreach ($sub as $q => $i) {
?>
                    <tr>
                        <td width="75%" valign="top"><?php echo $i[$first] . ' ' . $i[$last]; ?></td>
                    <td width="10%" valign="top">

<?php echo $q; ?>

                    </td>
                    <td width="15%" valign="top"> <input type="checkbox" name="<?php echo $q; ?>" id="<?php echo $q; ?>" value="V"/>
                    </td>
                </tr>
<?php
                $last_sid = $data_row[$c]['sid'];
            }
        } else {
?>
            <tr>
                <td width="75%" valign="top">No records</td>
                <td width="10%" valign="top"></td>
                <td width="15%" valign="top"></td>
            </tr>
<?php } ?>
    </table>
<?php include 'pager.php'; ?>
        <?php if ($r[0] > $rowsperpage / 2) {
 ?>
        <p align="center"><a href="index.php?s=<?php echo $site; ?>&rpp=<?php echo $rowsperpage; ?>">Show more entries per page...</a></p><br />
    <?php } ?>
    <input type="submit" value="Download CSV Files for Selections"/>
</form>
