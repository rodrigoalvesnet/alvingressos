<?php
if (Configure::read('Google.recaptcha.active')) {
?>
    <div class="g-recaptcha" data-sitekey="<?php echo Configure::read('Google.recaptcha.sitekey'); ?>"></div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php } ?>