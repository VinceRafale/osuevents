<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>" >
    <div>
        <input onfocus="if (this.value == '<?php echo SRCH; ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo SRCH; ?>';}"  value="<?php echo SRCH; ?>" type="text" value="" name="s" id="searchtxt" />
        <input type="submit" id="searchsubmit" value="<?php echo SRCH; ?>" />
        <input type="hidden" name="post_type" value="<?php echo POST_TYPE; ?>" /> 
    </div>
</form>

