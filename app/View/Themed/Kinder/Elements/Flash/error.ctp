<?php
$this->start('scriptBottom');
?>
<script>
    toastr.error("<?php echo $message; ?>");
</script>
<?php $this->end(); ?>