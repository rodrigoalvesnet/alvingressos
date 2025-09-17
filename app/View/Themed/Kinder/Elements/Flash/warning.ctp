<?php
$this->start('scriptBottom');
?>
<script>
    toastr.warning("<?php echo $message; ?>");
</script>
<?php $this->end(); ?>