<?php
$this->start('scriptBottom');
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        toastr.warning("<?php echo $message; ?>");
    });
</script>
<?php $this->end(); ?>