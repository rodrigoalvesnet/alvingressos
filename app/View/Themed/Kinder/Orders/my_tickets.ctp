<section id="my_tickets" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Meus Ingressos</h2>
            <p class="section-subtitle">Aqui estão todos os seus ingressos comprados ou reservados!</p>
        </div>
        <?php
        echo $this->Form->create('Filtro');
        ?>
        <div class="row">
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'event_id',
                    array(
                        'label' => 'Evento',
                        'options' => $events,
                        'class' => 'form-control select2',
                        'empty' => 'Qualquer'
                    )
                );
                ?>
            </div>
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'payment_type',
                    array(
                        'label' => 'Tipo de pagamento',
                        'options' => Configure::read('Order.payment_type'),
                        'class' => 'form-control',
                        'empty' => 'Qualquer'
                    )
                );
                ?>
            </div>
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'status',
                    array(
                        'label' => 'Situação',
                        'options' => Configure::read('Order.status'),
                        'class' => 'form-control',
                        'empty' => 'Qualquer'
                    )
                );
                ?>
            </div>
        </div>
        <div class="text-right">
            <?php
            echo $this->Form->submit(
                'Pesquisar',
                array(
                    'type'    => 'submit',
                    'class' => 'btn btn-primary',
                    'div'    => false,
                    'label' => false
                )
            );
            echo $this->Html->link(
                'Limpar',
                array(
                    'controller' => $this->request->params['controller'],
                    'action' => $this->request->params['action'],
                    'limpar:1'
                ),
                array(
                    'class' => 'btn btn-outline-light text-secondary mx-2',
                    'escape' => false
                )
            );
            ?>
        </div>

        <?php echo $this->Form->end(); ?>

        <?php if (!empty($registros)) {
        ?>
            <div class="table-responsive mt-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col"><?php echo $this->Paginator->sort('Order.id', 'Número'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Order.status', 'Situação'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Order.created', 'Data da Compra'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Event.title', 'Evento'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Order.name', 'Pessoa'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Order.payment_type', 'Pagamento'); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($registros as $registro) { ?>
                            <tr>
                                <th scope="row">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php echo str_pad($registro['Order']['id'], 5, '0', STR_PAD_LEFT); ?>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <?php
                                            echo $this->Html->link(
                                                '<i class="fas fa-eye"></i> Ver Pedido',
                                                array(
                                                    'controller' => 'Orders',
                                                    'action' => 'view',
                                                    $registro['Order']['id']
                                                ),
                                                array(
                                                    'title' => 'Imprimir',
                                                    'class' => 'dropdown-item',
                                                    'escape' => false
                                                )
                                            );
                                            if ($registro['Order']['status'] == 'approved') {
                                                echo $this->Html->link(
                                                    '<i class="fas fa-print"></i> Imprimir',
                                                    array(
                                                        'controller' => 'Orders',
                                                        'action' => 'ticket',
                                                        $registro['Order']['id']
                                                    ),
                                                    array(
                                                        'title' => 'Imprimir',
                                                        'target' => '_blank',
                                                        'class' => 'dropdown-item',
                                                        'escape' => false
                                                    )
                                                );
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </th>
                                <td>
                                    <?php
                                    $badgeClass = 'primary';
                                    if ($registro['Order']['status'] == 'rejected') {
                                        $badgeClass = 'danger';
                                    }
                                    if ($registro['Order']['status'] == 'approved') {
                                        $badgeClass = 'success';
                                    }
                                    if ($registro['Order']['status'] == 'canceled') {
                                        $badgeClass = 'secondary';
                                    }
                                    ?>
                                    <span class="badge rounded-pill text-white bg-<?php echo $badgeClass; ?>"><?php echo $status[$registro['Order']['status']]; ?></span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($registro['Order']['created'])); ?></td>
                                <td><?php echo $registro['Event']['title']; ?></td>
                                <td><?php echo $registro['Order']['name']; ?></td>
                                <td><?php echo $payments[$registro['Order']['payment_type']]; ?></td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
            <?php echo $this->element('paginador'); ?>
        <?php } else { ?>
            <hr class="my-3" />
            <div class="alert alert-primary" Order="alert">Nenhum ingresso encontrado</div>
        <?php } ?>
    </div>
</section>