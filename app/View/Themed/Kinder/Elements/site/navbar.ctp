<nav class="navbar navbar-expand-md fixed-top scrolling-navbar bg-themed-primary">
    <div class="container">
        <a class="navbar-brand" href="/">
            <?php
            echo $this->Html->image('logo-300.png', [
                'class' => 'img-fluid img-logo',
                'fullBase' => false,
                'pathPrefix' => 'theme/Kinder/img/'
            ]);
            ?>
        </a>

        <?php
        echo $this->requestAction(array(
            'admin' => false,
            'controller' => 'menus',
            'action' => 'menuSite'
        ), array('return'));
        ?>
    </div>
</nav>