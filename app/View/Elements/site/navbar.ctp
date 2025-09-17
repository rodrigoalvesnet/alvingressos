<nav class="navbar navbar-expand-md fixed-top scrolling-navbar bg-white">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/img/logo-ieta-preta.png" class="img-fluid img-logo" />
            Ingressos</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <i class="lni-menu"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto w-100 justify-content-end">
                <?php
                //Se está na página do evento
                if ($this->params['action'] == 'home') {
                ?>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="#events">Eventos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="#contact">Contato</a>
                    </li>
                <?php } ?>
                <?php
                //Se está na página do evento
                if ($this->params['action'] == 'event') {
                ?>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="#info">Informações</a>
                    </li>
                    <?php
                    if (!empty($event['Talker'])) { ?>
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="#talkers">Convidados</a>
                        </li>
                    <?php } ?>
                    <?php
                    if (!empty($event['Schedule'])) { ?>
                        <li class="nav-item">
                            <a class="nav-link page-scroll" href="#schedule">Programação</a>
                        </li>
                    <?php } ?>
                    <?php
                    if (!empty($event['Lot'])) { ?>
                        <li class="nav-item text-center">
                            <a class="nav-link page-scroll btn-sm btn btn-common" href="#lots">Comprar Ingresso</a>
                        </li>
                    <?php } ?>
                <?php } ?>
                <li class="nav-item">
                    <?php
                    //Se está logado
                    if (AuthComponent::user('id')) {
                    ?>
                        <a class="nav-link" href="/Users/account"><i class="lni-user icon-navbar"></i> Minha Conta</a>
                    <?php } else { ?>
                        <a class="nav-link" href="/Users/login"><i class="lni-user icon-enter"></i> Entrar</a>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</nav>