<div class="card">
    <?php
    echo $this->Form->create('Filtro');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'customer',
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
                    'unidade_id',
                    array(
                        'label' => 'Unidade',
                        'options' => $unidades,
                        'class' => 'form-control',
                        'empty' => 'Qualquer'
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
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
            <div class="col-lg-2">
                <label>Data inicial </label>
                <?php
                echo $this->Form->text(
                    'start_date',
                    array(
                        'type' => 'date',
                        'label' => 'Data',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <label>Data final </label>
                <?php
                echo $this->Form->text(
                    'end_date',
                    array(
                        'type' => 'date',
                        'label' => 'Data',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
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
        'controller' => 'orders',
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
                        <th scope="col"><?php echo $this->Paginator->sort('Order.id', 'Número'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Order.created', 'Data do Pedido'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Order.name', 'Pessoa'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Order.value', 'Valor'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Unidade.name', 'Unidade'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Order.payment_type', 'Pagamento'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Order.status', 'Situação'); ?></th>
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
                                            '<i class="fas fa-edit"></i> Editar',
                                            array(
                                                'controller' => 'Orders',
                                                'action' => 'edit',
                                                $registro['Order']['id']
                                            ),
                                            array(
                                                'title' => 'Editar',
                                                'class' => 'dropdown-item',
                                                'escape' => false
                                            )
                                        );
                                        echo $this->Html->link(
                                            '<i class="fas fa-print"></i> Imprimir',
                                            array(
                                                'admin' => false,
                                                'controller' => 'Orders',
                                                'action' => 'ticket',
                                                $registro['Order']['id']
                                            ),
                                            array(
                                                'target' => '_blank',
                                                'title' => 'Imprimir',
                                                'class' => 'dropdown-item',
                                                'escape' => false
                                            )
                                        );
                                        echo $this->Html->link(
                                            '<i class="fas fa-paper-plane"></i> Enviar E-mail',
                                            array(
                                                'admin' => true,
                                                'controller' => 'Orders',
                                                'action' => 'send_mail',
                                                $registro['Order']['id']
                                            ),
                                            array(
                                                'confirm' => 'Tem certeza que deseja reenviar o e-mail?',
                                                'title' => 'Enviar E-mail',
                                                'class' => 'dropdown-item',
                                                'escape' => false
                                            )
                                        );
                                        ?>
                                    </div>
                                </div>
                            </th>
                            <td><?php echo date('d/m/Y H:i', strtotime($registro['Order']['created'])); ?></td>
                            <td><?php echo $registro['Order']['name']; ?></td>
                            <td><?php echo $this->Alv->tratarValor($registro['Order']['value'], 'pt'); ?></td>
                            <td><?php echo $registro['Unidade']['name']; ?></td>
                            <td>
                                <?php
                                echo $payments[$registro['Order']['payment_type']];
                                if (!empty($registro['Attachment'])) {
                                    echo $this->Html->link(
                                        '<i class="fas fa-paperclip"></i> ',
                                        $registro['Attachment'][0]['path'],
                                        array(
                                            'title' => 'Ver Anexo',
                                            'class' => 'mx-1',
                                            'target' => '_blank',
                                            'escape' => false
                                        )
                                    );
                                }
                                ?>
                            </td>
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
                                <span class="badge rounded-pill bg-<?php echo $badgeClass; ?>"><?php echo $status[$registro['Order']['status']]; ?></span>
                            </td>
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