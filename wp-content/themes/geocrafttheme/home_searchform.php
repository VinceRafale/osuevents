<?php
if (isset($_REQUEST['sfrom']) && $_REQUEST['sfrom'] != "") {
    $search = $_REQUEST['sfrom'];
}
if (isset($_REQUEST['location']) && $_REQUEST['location'] != "") {
    $snear = $_REQUEST['location'];
}
?>
<!--Start Main Search-->
<div class="main_search">
    <form role="search" method="get" id="searchform" action="<?php echo home_url('/'.GC_SEARCH.'/'); ?>" >
        <div class="search_for">
            <input  placeholder="<?php echo SRCH; ?>"   type="text" value="" name="sfrom" id="search_for" />
        </div>
        <div class="search_location">
            <input  placeholder="<?php echo LKTION; ?>"  type="text" value="" name="location" id="search_location" />
        </div>
        <input type="submit" id="searchsubmit" value="<?php echo SRCH_LISTING; ?>" />
    </form>
</div>
<!--End Main Search-->

