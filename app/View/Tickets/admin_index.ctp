<div class="card">
    <?php
    echo $this->Form->create('Filtro');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'nome',
                    array(
                        'label' => 'Pessoa',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'ticket_id',
                    array(
                        'type' => 'text',
                        'label' => 'Número do Ticket',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'order_id',
                    array(
                        'type' => 'text',
                        'label' => 'Número do Pedido',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'date',
                    array(
                        'label' => 'Data',
                        'class' => 'form-control datepicker ',
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'modalidade',
                    array(
                        'type' => 'text',
                        'label' => 'Nome da Modalidade',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
        </div>
    </div>
    <div class="card-footer border-top">
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
                'admin' => true,
                'controller' => $this->params['controller'],
                'action' => 'index',
                'limpar' => 1
            ),
            array(
                'class' => 'btn btn-outline-secondary mx-2',
                'escape' => false
            )
        );
        ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>

<?php if (!empty($registros)) {
    // pr($registros);
?>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col"><?php echo $this->Paginator->sort('Ticket.id', 'Ticket'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Ticket.order_id', 'Pedido'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Ticket.nome', 'Pessoa'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Ticket.modalidade_nome', 'Modalidade'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Ticket.modalidade_data', 'Data Agendada'); ?></th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($registros as $registro) { ?>
                        <tr>
                            <th scope="row">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php echo str_pad($registro['Ticket']['id'], 5, '0', STR_PAD_LEFT); ?>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <?php
                                        echo $this->Html->link(
                                            '<i class="fas fa-print"></i> Imprimir',
                                            array(
                                                'admin' => false,
                                                'controller' => 'Orders',
                                                'action' => 'ticket',
                                                $registro['Ticket']['order_id']
                                            ),
                                            array(
                                                'target' => '_blank',
                                                'title' => 'Imprimir',
                                                'class' => 'dropdown-item',
                                                'escape' => false
                                            )
                                        );
                                        echo $this->Html->link(
                                            '<i class="fas fa-cart"></i> Ver Pedido',
                                            array(
                                                'admin' => false,
                                                'controller' => 'Orders',
                                                'action' => 'view',
                                                $registro['Ticket']['order_id']
                                            ),
                                            array(
                                                'target' => '_blank',
                                                'title' => 'Ver Pedido',
                                                'class' => 'dropdown-item',
                                                'escape' => false
                                            )
                                        );
                                        ?>
                                    </div>
                                </div>
                            </th>
                            <td><?php echo $registro['Ticket']['order_id']; ?></td>
                            <td><?php echo $registro['Ticket']['nome']; ?></td>
                            <td><?php echo $registro['Ticket']['modalidade_nome']; ?></td>
                            <td><?php echo $this->Alv->tratarData($registro['Ticket']['modalidade_data'], 'pt'); ?></td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
    <?php echo $this->element('paginador'); ?>
<?php } else { ?>
    <div class="alert alert-primary" Order="alert">Nenhum registro encontrado</div>
<?php } ?>