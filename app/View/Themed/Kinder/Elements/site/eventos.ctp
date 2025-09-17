<section id="events" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Eventos</h2>
            <span>Eventos</span>
            <p class="section-subtitle">Vejas logo abaixo os próximos eventos!</p>
        </div>
        <div class="row">
            <?php foreach ($events as $k => $event) { ?>
                <div class="col-lg-4 col-md-6 col-xs-12 mb-3">
                    <div class="div-thumb-event position-relative">
                        <?php
                        //trata os tags da miniatura
                        $tagImg = '/img/faixa-comprar.png';
                        //Se foi cancelado
                        if ($event['Event']['status'] == 'canceled') {
                            $tagImg = '/img/faixa-cancelado.png';
                        }
                        //Se já foi concluído
                        if ($event['Event']['status'] == 'closed') {
                            $tagImg = '/img/faixa-concluido.png';
                        }
                        //Se está esgotado
                        if ($event['Event']['status'] == 'soldoff') {
                            $tagImg = '/img/faixa-esgotado.png';
                        }
                        $tag = '<img src="' . $tagImg . '" class="tag-thumb-event" />';
                        echo $this->Html->link(
                            $this->Html->image(
                                '/uploads/event-' . $event['Event']['id'] . '/medium/' . $event['Event']['banner_mobile'],
                                array(
                                    'class' => 'wow fadeInDown img-thumbnail img-fluid',
                                    'alt' => $event['Event']['title'],
                                    'title' => $event['Event']['title'],
                                    'data-wow-delay' => '0.2s'
                                )
                            ) . $tag,
                            '/event/' . $event['Event']['slug'],
                            array(
                                'escape' => false
                            )
                        );
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>