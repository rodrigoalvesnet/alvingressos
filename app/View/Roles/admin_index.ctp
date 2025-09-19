<?php echo $this->Html->link('<i class="fas fa-plus"></i> Adicionar Novo', ['action' => 'add'], ['class' => 'btn btn-primary mb-2', 'escape' => false]); ?>

<div class="card">
    <?php
    echo $this->Form->create('Filtro');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'title',
                    array(
                        'label' => 'Título',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'active',
                    array(
                        'label' => 'Ativo',
                        'options' => array(
                            '1' => 'Sim',
                            '0' => 'Não'
                        ),
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
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Título</th>
                        <th scope="col">Situação</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $registro) { ?>
                        <tr>
                            <th scope="row"><?php echo $registro['Role']['id']; ?></th>
                            <td><?php echo $registro['Role']['title']; ?></td>
                            <td><span class="badge rounded-pill bg-<?php echo $registro['Role']['active'] ? 'success' : 'danger' ?>"><?php echo $registro['Role']['active'] ? 'Ativo' : 'Inativo' ?></span></td>
                            <td>
                                <?php
                                echo $this->Html->link(
                                    '<i class="fas fa-edit"></i>',
                                    array(
                                        'controller' => 'Roles',
                                        'action' => 'edit',
                                        $registro['Role']['id']
                                    ),
                                    array(
                                        'class' => 'btn btn-action',
                                        'escape' => false
                                    )
                                );
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
    <div class="alert alert-primary" role="alert">Nenhum registro encontrado</div>
<?php } ?>