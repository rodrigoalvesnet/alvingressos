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
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'email',
                    array(
                        'label' => 'E-mail',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'role_id',
                    array(
                        'label' => 'Grupo',
                        'options' => $roles,
                        'class' => 'form-control',
                        'empty' => 'Qualquer'
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'church_id',
                    array(
                        'label' => 'Igreja',
                        'options' => $churches,
                        'class' => 'form-control',
                        'empty' => 'Qualquer'
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
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
                        <th scope="col"><?php echo $this->Paginator->sort('User.id', '#'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('User.name', 'Nome'); ?></th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Telefone</th>
                        <th scope="col"><?php echo $this->Paginator->sort('Church.name', 'Igreja'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Role.title', 'Grupo'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('User.active', 'Situação'); ?></th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($registros as $registro) { ?>
                        <tr>
                            <th scope="row"><?php echo $registro['User']['id']; ?></th>
                            <td><?php echo $registro['User']['name']; ?></td>
                            <td><?php echo $registro['User']['email']; ?></td>
                            <td><?php echo $registro['User']['phone']; ?></td>
                            <td><?php echo $registro['Church']['name']; ?></td>
                            <td><?php echo $registro['Role']['title']; ?></td>
                            <td><span class="badge rounded-pill bg-<?php echo $registro['User']['active'] ? 'success' : 'danger' ?>"><?php echo $registro['User']['active'] ? 'Ativo' : 'Inativo' ?></span></td>
                            <td>
                                <?php
                                echo $this->Html->link(
                                    '<i class="fas fa-edit"></i>',
                                    array(
                                        'controller' => 'Users',
                                        'action' => 'edit',
                                        $registro['User']['id']
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
    <div class="alert alert-primary" User="alert">Nenhum registro encontrado</div>
<?php } ?>