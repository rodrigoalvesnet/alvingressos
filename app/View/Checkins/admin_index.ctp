<div class="card">
    <?php
    echo $this->Form->create('Filtro');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'name',
                    array(
                        'label' => 'Nome',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'cpf',
                    array(
                        'type' => 'text',
                        'label' => 'CPF',
                        'class' => 'form-control cpf',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'ticket_id',
                    array(
                        'type' => 'number',
                        'label' => 'Ticket',
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
                        'type' => 'number',
                        'label' => 'Pedido',
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
                'controller' => $this->request->params['controller'],
                'action' => $this->request->params['action'],
                'limpar:1'
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

<?php if (!empty($registros)) { ?>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-filter">
                <thead>
                    <tr>
                        <th scope="col">Check-In</th>
                        <th scope="col">Ticket</th>
                        <th scope="col">Pedido</th>
                        <th scope="col">Nome</th>
                        <th scope="col">CPF</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Modalidade</th>
                        <!-- <th scope="col">Ações</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($registros as $registro) { ?>
                        <tr>
                            <td scope="row"><?php echo date('d/m/Y H:i', strtotime($registro['Checkin']['created'])); ?> - Por <?php echo $registro['User']['name']; ?></td>
                            <td scope="row">
                                <a href="/Orders/ticket/<?php echo $registro['Checkin']['order_id']; ?>" target="_blank" class="btn btn-sm btn-info">
                                    <?php echo $registro['Checkin']['ticket_id']; ?>
                                </a>
                            </td>
                            <td scope="row">
                                <a href="/Orders/view/<?php echo $registro['Checkin']['order_id']; ?>" target="_blank" class="btn btn-sm btn-info">
                                    <?php echo $registro['Checkin']['order_id']; ?>
                                </a>
                            </td>
                            <td scope="row"><?php echo $registro['Ticket']['nome']; ?></td>
                            <td scope="row"><?php echo $registro['Ticket']['cpf']; ?></td>
                            <td scope="row"><?php echo $registro['Ticket']['telefone']; ?></td>
                            <td scope="row"><?php echo $registro['Ticket']['modalidade_nome']; ?></td>
                            <!-- <td>
                                <?php
                                // echo $this->Form->button(
                                //     '<i class="fas fa-check"></i>',
                                //     array(
                                //         'onclick' => 'deleteCheckin(' . $registro['Checkin']['id'] . ', ' . $registro['Ticket']['event_id'] . ')',
                                //         'type'    => 'button',
                                //         'class' => 'btn btn-danger',
                                //         'title' => 'EXCLUIR CHECKIN',
                                //         'escape' => false,
                                //         'div'    => false,
                                //         'label' => false
                                //     )
                                // );
                                ?>
                            </td> -->
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php echo $this->element('paginador'); ?>
<?php } else { ?>
    <div class="alert alert-primary" role="alert">Nenhum registro encontrado</div>
<?php } ?>