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
        Configure::read('Sistema.title') . ' | ' . $this->fetch('title')
    );
    ?>
    <title><?php echo Configure::read('Sistema.title') . ' | ' . $this->fetch('title'); ?></title>

    <?php
    echo $this->Html->meta('icon');

    echo $this->Html->css(array(
        'bootstrap.min',
        'site',
        'datepicker',
        'jquery-ui.min',
        'site'
    ));

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
</head>

<body>
    <div id="login">
        <div class="row h-100">
            <div class="col-lg-5 div-login-left">
                <?php echo $this->Flash->render(); ?>
                <?php echo $this->fetch('content'); ?>
            </div>
            <div class="col-lg-7 div-login-right">
                <div class="d-flex justify-content-center align-items-center w-100 h-100">
                    <img src="/img/logo.webp" class="img-fluid" />
                </div>
            </div>
        </div>
    </div>

    <?php
    echo $this->Html->script(array(
        'jquery-min',
        'popper.min',
        'bootstrap.min',
        '/assets/js/moment.min',
        'jquery.maskedinput.min',
        'bootstrap-datetimepicker.min.js',
        '/assets/js/bootstrap-datetimepicker.min.js',
        'jquery.maskMoney.min',
        'locale_moment_pt-br',
        'validate/jquery.validate.min',
        'validate/localization/messages_pt_BR',
        'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js',
        'alv'
    ));

    echo $this->fetch('scriptBottom');
    echo $this->Js->writeBuffer(); // Write cached scripts
    echo $this->element('sql_dump')
    ?>
</body>

</html>