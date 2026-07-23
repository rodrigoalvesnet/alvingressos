<div class="card">
    <?php
    echo $this->Form->create('Filtro');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
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
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'unidade_id',
                    array(
                        'label'   => 'Unidade',
                        'options' => $unidades,
                        'class'   => 'form-control',
                        'empty'   => 'Qualquer'
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
                'type'  => 'submit',
                'class' => 'btn btn-primary mx-1',
                'div'   => false,
                'label' => false
            )
        );
        echo $this->Html->link(
            'Nova Cortesia',
            array(
                'admin' => true,
                'controller' => 'Cortesias',
                'action' => 'add'
            ),
            array(
                'class' => 'btn btn-outline-primary mx-1',
                'escape' => false
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
                'class' => 'btn btn-outline-secondary mx-1',
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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Número</th>
                        <th scope="col">Unidade</th>
                        <th scope="col">Pessoa</th>
                        <th scope="col">Criada em</th>
                        <th scope="col">Criada por</th>
                        <th scope="col">Motivo</th>
                        <th scope="col">Válida até</th>
                        <th scope="col">Situação</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $registro) {
                        $hoje = date('Y-m-d');
                        $vencida = !empty($registro['Ticket']['valido_ate']) && $registro['Ticket']['valido_ate'] < $hoje;
                        $utilizada = !empty($registro['Checkin']['id']);
                    ?>
                        <tr>
                            <td><?php echo str_pad($registro['Ticket']['id'], 5, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo !empty($registro['Unidade']['name']) ? $registro['Unidade']['name'] : '—'; ?></td>
                            <td><?php echo !empty($registro['Ticket']['nome']) ? $registro['Ticket']['nome'] : '—'; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($registro['Ticket']['created'])); ?></td>
                            <td><?php echo !empty($registro['CriadoPor']['name']) ? $registro['CriadoPor']['name'] : '—'; ?></td>
                            <td><?php echo !empty($registro['Ticket']['motivo']) ? h($registro['Ticket']['motivo']) : '—'; ?></td>
                            <td><?php echo !empty($registro['Ticket']['valido_ate']) ? $this->Alv->tratarData($registro['Ticket']['valido_ate'], 'pt') : '—'; ?></td>
                            <td>
                                <?php
                                if ($utilizada) {
                                    echo 'Utilizada';
                                } elseif ($vencida) {
                                    echo 'Expirada';
                                } else {
                                    echo 'Válida';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $this->Html->link(
                                    '<i class="fas fa-print"></i>',
                                    array(
                                        'admin' => true,
                                        'controller' => 'Cortesias',
                                        'action' => 'print',
                                        $registro['Ticket']['id']
                                    ),
                                    array(
                                        'target' => '_blank',
                                        'title' => 'Imprimir',
                                        'class' => 'btn btn-sm btn-secondary mx-1',
                                        'escape' => false
                                    )
                                );
                                if (!$utilizada) {
                                    echo $this->Form->postLink(
                                        '<i class="fas fa-trash"></i>',
                                        array(
                                            'admin' => true,
                                            'controller' => 'Cortesias',
                                            'action' => 'delete',
                                            $registro['Ticket']['id']
                                        ),
                                        array(
                                            'title' => 'Excluir',
                                            'class' => 'btn btn-sm btn-outline-danger mx-1',
                                            'escape' => false,
                                            'confirm' => 'Tem certeza que deseja excluir esta cortesia?'
                                        )
                                    );
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php echo $this->element('paginador'); ?>
<?php } else { ?>
    <div class="alert alert-primary">Nenhuma cortesia encontrada</div>
<?php } ?>
