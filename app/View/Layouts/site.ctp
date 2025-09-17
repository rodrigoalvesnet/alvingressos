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
    ?>
    <title><?php echo  $this->fetch('title') . ' | ' . Configure::read('Sistema.title'); ?></title>

    <?php
    echo $this->Html->meta('icon');

    echo $this->Html->css(array(
        'bootstrap.min',
        'line-icons',
        'owl.carousel',
        // 'owl.theme',
        // 'nivo-lightbox',
        'magnific-popup',
        'animate',
        'color-switcher',
        'menu_sideslide',
        'main',
        'responsive',
        'site'
    ));

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
</head>

<body>
    <?php echo $this->Flash->render(); ?>
    <?php echo $this->fetch('content'); ?>
    <?php echo $this->element('site/contact'); ?>
    <?php echo $this->element('site/map'); ?>
    <?php echo $this->element('site/footer'); ?>

    <!-- Go To Top Link -->
    <a href="#" class="back-to-top">
        <i class="lni-arrow-up"></i>
    </a>

    <div id="loader">
        <div class="spinner">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
    </div>

    <?php
    echo $this->Html->script(array(
        'jquery-min',
        'popper.min',
        'bootstrap.min',
        'classie',
        // 'color-switcher',
        'jquery.mixitup',
        'nivo-lightbox',
        'owl.carousel',
        'jquery.stellar.min',
        'jquery.nav',
        'scrolling-nav',
        'jquery.easing.min',
        'wow',
        // 'jquery.vide',
        'jquery.counterup.min',
        'jquery.magnific-popup.min',
        'waypoints.min',
        'form-validator.min',
        'contact-form-script',
        'main'
    ));

    echo $this->fetch('scriptBottom');
    echo $this->Js->writeBuffer(); // Write cached scripts
    echo $this->element('sql_dump')
    ?>
</body>

</html>