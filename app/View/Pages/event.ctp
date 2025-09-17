<?php echo $this->element('site/navbar'); ?>

<style>
    .video-container {
        position: relative;
        width: 100%;
        max-width: 800px;
        /* Limita a largura máxima, opcional */
        aspect-ratio: 16 / 9;
        /* Mantém proporção 16:9 */
        margin: 0 auto;
        /* Centraliza na tela, opcional */
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>

<!-- BANNER -->
<section id="banner">
    <?php
    $imgBanner = '/img/slider/bg-1.jpg';
    //Se tem banner
    if (!empty($event['Event']['banner_desktop'])) {
        $imgBanner = '/uploads/event-' . $event['Event']['id'] . '/' . $event['Event']['banner_desktop'];
    }
    ?>
    <img src="<?php echo $imgBanner; ?>" class="img-fluid w-100" alt="<?php echo $event['Event']['title']; ?>" title="<?php echo $event['Event']['title']; ?>">
</section>

<!-- INFORMAÇÕES -->
<section id="info" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo $event['Event']['title']; ?></h2>
            <span><?php echo $event['Event']['title']; ?></span>
            <p class="section-subtitle">De <?php echo $this->Alv->tratarData($event['Event']['start_date'], 'pt'); ?> até <?php echo $this->Alv->tratarData($event['Event']['end_date'], 'pt'); ?></p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10 content-event">
                <?php echo $event['Event']['description']; ?>
                <?php if (!empty($event['Event']['youtube'])) { ?>
                    <div class="video-container">
                        <iframe src="<?php echo $event['Event']['youtube']; ?>"
                            title="<?php echo $event['Event']['title']; ?>"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>
                <?php } ?>
                <div class="text-center mt-5">
                    <a href="#lots" class="btn btn-common btn-effect">Comprar Ingresso</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONVIDADOS -->
<?php
if (!empty($event['Talker'])) { ?>
    <section id="talkers" class="section bg-grey">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Convidados</h2>
                <span>Convidados</span>
                <p class="section-subtitle">Confira alguns dos participantes deste evento!</p>
            </div>
            <div class="row">
                <?php foreach ($event['Talker'] as $talker) {
                    $imgTalker = '/img/no-photo.png';
                    if (!empty($talker['photo'])) {
                        $imgTalker = '/uploads/medium/' . $talker['photo'];
                    }
                ?>
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="single-team">
                            <img src="<?php echo $imgTalker; ?>" alt="<?php echo $talker['name']; ?>">
                            <div class="team-details">
                                <div class="team-inner">
                                    <h4 class="team-title"><?php echo $talker['name']; ?></h4>
                                    <p><?php echo $talker['description']; ?></p>
                                    <ul class="social-list">
                                        <?php if (!empty($talker['facebook'])) { ?>
                                            <li class="facebook">
                                                <a href="<?php echo $talker['facebook']; ?>" target="_blank"><i class="lni-facebook-filled"></i></a>
                                            </li>
                                        <?php } ?>
                                        <?php if (!empty($talker['instagram'])) { ?>
                                            <li class="instagram">
                                                <a href="<?php echo $talker['instagram']; ?>" target="_blank"><i class="lni-instagram-filled"></i></a>
                                            </li>
                                        <?php } ?>
                                        <?php if (!empty($talker['youtube'])) { ?>
                                            <li class="youtube">
                                                <a href="<?php echo $talker['youtube']; ?>" target="_blank"><i class="lni-youtube-filled"></i></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>

<!-- PROGRAMAÇÂO -->
<?php
if (!empty($event['Schedule'])) {
?>
    <section id="schedule" class="section">
        <!-- Container Starts -->
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Programação</h2>
                <span>Programação</span>
                <p class="section-subtitle">Veja abaixo toda a nossa programação!</p>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="controls text-center">
                        <a class="filter active btn btn-common btn-effect" data-filter="all">
                            Tudo
                        </a>
                        <?php
                        $arrayLinks = array();
                        foreach ($event['Schedule'] as $k => $schedule) {
                            if (isset($arrayLinks[$schedule['date']])) {
                                continue;
                            }
                            $arrayLinks[$schedule['date']] = $schedule['date'];
                        ?>
                            <a class="filter btn btn-common btn-effect" data-filter=".<?php echo $schedule['date']; ?>">
                                <?php echo date('d/m', strtotime($schedule['date'])); ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div id="portfolio">
                <div class="row thead">
                    <div class="col-lg-2 th">Data/Hora</div>
                    <div class="col-lg-4 th">Adividade</div>
                    <div class="col-lg-6 th">Descrição</div>
                </div>
                <?php
                foreach ($event['Schedule'] as $schedule) { ?>
                    <div class="mix w-100 tr <?php echo $schedule['date']; ?>">
                        <div class="row">
                            <div class="col-lg-2 td"><?php echo date('d/m', strtotime($schedule['date'])) . ' - ' . date('H:i', strtotime($schedule['start'])); ?> </div>
                            <div class="col-lg-4 td"><?php echo $schedule['title']; ?></div>
                            <div class="col-lg-6 td"><?php echo $schedule['description']; ?></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <!-- Container Ends -->
    </section>
<?php } ?>

<!-- LOTES -->
<?php
if (!empty($event['Lot'])) {
?>
    <div id="lots" class="section pricing-section bg-grey">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Comprar</h2>
                <span>Comprar</span>
                <p class="section-subtitle">Fique atento com as datas de cada lote!</p>
            </div>
            <div class="row pricing-tables">
                <?php
                foreach ($event['Lot'] as $k => $lot) {
                    if ($event['Event']['status'] == 'soldoff') {
                        $lotActive = false;
                        $lotLabel = 'Esgotado';
                    } else if ($event['Event']['status'] == 'scheduled' || $event['Event']['status'] == 'oculto') {
                        $lotActive = false;
                        $lotLabel = 'Esgotado';
                        if ($availableLot == $lot['id']) {
                            $lotActive = true;
                            $lotLabel = 'Comprar';
                        } else {
                            //Se este lote ainda vai ser liberado
                            if ($lot['start_date'] > date('Y-m-d')) {
                                $lotLabel = 'Em Breve';
                            }
                        }
                    } else {
                        $lotActive = false;
                        $lotLabel = 'Indisponível';
                    }
                ?>

                    <div class="col-lg-4 col-md-4 col-xs-12">
                        <div class="pricing-table <?php echo $lotActive ? 'pricing-big' : 'pricing-disabled'; ?>">
                            <div class="pricing-details">
                                <h2><?php echo ($k + 1); ?>º Lote</h2>
                                <div class="price"><span>R$</span> <?php echo $this->Alv->tratarValor($lot['value'], 'pt'); ?></div>
                                <ul>
                                    <li>Disponível até <?php echo $this->Alv->tratarData($lot['end_date'], 'pt'); ?><br />ou esgotarem os ingressos deste lote.</li>
                                    <li>Forma de Pagamento:<br />
                                        <?php
                                        $paymentsType = unserialize($lot['payments_type']);
                                        $types = array();
                                        foreach ($paymentsType as $k => $type) {
                                            if (isset($type['active']) && $type['active']) {
                                                $types[] = $type['label'];
                                            }
                                        }
                                        $count = 0;
                                        $total = count($types);
                                        if (!empty($types)) {
                                            foreach ($types as $typeLabel) {
                                                $count++;
                                                echo $typeLabel;
                                                if ($count < $total) {
                                                    echo ' | ';
                                                }
                                            }
                                        } else {
                                            echo 'Nenhuma disponível.';
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="plan-button">
                                <?php if ($lotActive) { ?>
                                    <a href="/Orders/add/<?php echo $event['Event']['id']; ?>" class="btn btn-common btn-effect"><?php echo $lotLabel; ?></a>
                                <?php } else { ?>
                                    <a class="btn btn-secondary disabled"><?php echo $lotLabel; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>