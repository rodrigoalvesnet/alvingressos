<div class="card">
    <div class="card-body">
        <h4><i class="fas fa-calendar"></i> Eventos</h4><br />
        <?php
        //Se tem eventos
        if (!empty($events)) { ?>
            <div class="row">
                <?php foreach ($events as $event) { ?>
                    <div class="col-lg-3 col-md-6 col-sm-12 pb-3">
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
                                        'class' => 'img-thumbnail img-fluid',
                                        'alt' => $event['Event']['title'],
                                        'title' => $event['Event']['title'],
                                    )
                                ) . $tag,
                                array(
                                    'controller' => 'Orders',
                                    'action' => 'add',
                                    $event['Event']['id']
                                ),
                                array(
                                    'escape' => false
                                )
                            );
                            ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="alert alert-primary" Order="alert">Nenhum evento agendado :(</div>
        <?php } ?>
    </div>
</div>