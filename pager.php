<p align="center" style="margin: 15px 0 15px 0;" class="pager">
    <?php
    /*     * ****  build the pagination links ***** */
// range of num links to show
    $range = 3;

// if not on page 1, don't show back links
    if ($currentpage > 1) {
        // show << link to go back to page 1
        echo " <a href='{$_SERVER['PHP_SELF']}?s=$site&currentpage=1'><<</a> ";
        // get previous page num
        $prevpage = $currentpage - 1;
        // show < link to go back to 1 page
        echo " <a href='{$_SERVER['PHP_SELF']}?s=$site&currentpage=$prevpage'><</a> ";
    } // end if
// loop to show links to range of pages around current page
    for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
        // if it's a valid page number...
        if (($x > 0) && ($x <= $totalpages)) {
            // if we're on current page...
            if ($x == $currentpage) {
                // 'highlight' it but don't make a link
                echo " <span class='pager-active'>$x</span> ";
                // if not current page...
            } else {
                // make it a link
                $rpp = $rowsperpage / 2;
                echo " <a href='{$_SERVER['PHP_SELF']}?s=$site&currentpage=$x&rpp=$rpp'>$x</a> ";
            } // end else
        } // end if
    } // end for
// if not on last page, show forward and last page links        
    if ($currentpage != $totalpages && $totalpages != 0) {
        // get next page
        $nextpage = $currentpage + 1;
        // echo forward link for next page

        echo " <a href='{$_SERVER['PHP_SELF']}?s=$site&currentpage=$nextpage&rpp=$rpp'>></a> ";
        // echo forward link for lastpage
        echo " <a href='{$_SERVER['PHP_SELF']}?s=$site&currentpage=$totalpages&rpp=$rpp'>>></a> ";
    } // end if
    /*     * **** end build pagination links ***** */
    ?>
</p>