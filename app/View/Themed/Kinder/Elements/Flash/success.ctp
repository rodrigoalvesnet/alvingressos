<?php
$this->start('scriptBottom');
?>
<script>
    toastr.success("<?php echo $message; ?>");
</script>
<?php $this->end(); ?>