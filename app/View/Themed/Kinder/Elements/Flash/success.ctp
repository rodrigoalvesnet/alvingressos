<?php
$this->start('scriptBottom');
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        toastr.success("<?php echo $message; ?>");
    });
</script>
<?php $this->end(); ?>