<?php
if (Configure::read('Google.recaptcha.active')) {
?>
    <div class="g-recaptcha" data-sitekey="<?php echo Configure::read('Google.recaptcha.sitekey'); ?>"></div>
    <script>
        (function () {
            var loaded = false;
            function loadRecaptcha() {
                if (loaded) {
                    return;
                }
                loaded = true;
                var s = document.createElement('script');
                s.src = 'https://www.google.com/recaptcha/api.js';
                s.async = true;
                s.defer = true;
                document.head.appendChild(s);
            }

            var el = document.currentScript.previousElementSibling;
            if ('IntersectionObserver' in window && el) {
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            loadRecaptcha();
                            observer.disconnect();
                        }
                    });
                }, { rootMargin: '200px' });
                observer.observe(el);
            } else {
                loadRecaptcha();
            }
        })();
    </script>

<?php } ?>