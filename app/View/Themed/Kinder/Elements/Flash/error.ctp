<?php
$this->start('scriptBottom');
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        toastr.error("<?php echo $message; ?>");
    });
</script>
<?php $this->end(); ?>