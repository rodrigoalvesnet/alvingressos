<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php echo $this->Html->charset(); ?>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

    <?php
    echo $this->Html->meta(
        'title',
        $this->fetch('title') . '|' . Configure::read('Sistema.title')
    );
    echo $this->Html->meta(
        'description',
        $siteConfig['Site']['description']
    );
    echo $this->Html->meta(
        'keywords',
        $siteConfig['Site']['keywords']
    );
    ?>
    <title><?php echo  $this->fetch('title') . ' | ' . $siteConfig['Site']['title']; ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- jQuery 2.1.4 -->
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

    <!-- jQuery UI compatível -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" rel="stylesheet">

    <?php
    echo $this->Html->meta('icon');

    echo $this->Html->css(array(
        'bootstrap.min',
        'nivo-lightbox.css',
        'line-icons',
        'owl.carousel',
        'magnific-popup',
        'animate',
        'menu_sideslide'
    ));

    echo $this->Html->css(array(
        'main',
        'site'
    ), null, array('pathPrefix' => 'theme/Kinder/css/'));

    echo $this->Html->css(array(
        'responsive',
    ));

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    //Incluídos na configuração do site
    echo  $siteConfig['Site']['script_header']; 
    ?>
</head>

<body>
    <?php echo $this->element('site/navbar'); ?>
    <?php echo $this->Flash->render(); ?>
    <?php echo $this->fetch('content'); ?>
    <?php echo $this->element('site/compre1'); ?>
    <?php echo $this->element('site/contact'); ?>
    <?php echo $this->element('site/mapa'); ?>
    <?php echo $this->element('site/footer'); ?>

    <!-- Go To Top Link -->
    <a href="#" class="back-to-top">
        <i class="bi bi-arrow-up"></i>
    </a>

    <div id="loader">
        <div class="spinner">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "4000"
        };
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
    <?php
    echo $this->Html->script(array(
        'popper.min',
        'bootstrap.min',
        'classie',
        'jquery.mixitup',
        'nivo-lightbox',
        'owl.carousel',
        'jquery.stellar.min',
        'jquery.nav',
        'scrolling-nav',
        'jquery.easing.min',
        'wow',
        'jquery.counterup.min',
        'jquery.magnific-popup.min',
        'waypoints.min',
        'form-validator.min',
        'contact-form-script',
        'main',
        'admin/jquery.maskedinput.min',
        '/theme/Kinder/js/site.js'
    ));


    echo $this->fetch('scriptBottom');
     //Incluídos na configuração do site
    echo  $siteConfig['Site']['script_bottom']; 
    echo $this->Js->writeBuffer(); // Write cached scripts
    echo $this->element('sql_dump')
    ?>
</body>

</html>