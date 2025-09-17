<?php echo $this->Html->link('Adicionar Novo', ['action' => 'add'], ['class' => 'btn btn-primary']); ?>

<div class="card mt-2">
    <?php
    echo $this->Form->create('Filtro');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3">
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
                        <th scope="col"><?php echo $this->Paginator->sort('Page.id', '#'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Page.title', 'Título'); ?></th>
                        <th scope="col"><?php echo $this->Paginator->sort('Page.active', 'Ativo'); ?></th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($registros as $registro) { ?>
                        <tr>
                            <th scope="row"><?php echo $registro['Page']['id']; ?></th>
                            <td><?php echo $registro['Page']['title']; ?></td>
                            <td><span class="badge rounded-pill bg-<?php echo $registro['Page']['active'] ? 'success' : 'danger' ?>"><?php echo $registro['Page']['active'] ? 'Ativo' : 'Inativo' ?></span></td>
                            <td>
                                <?php
                                echo $this->Html->link(
                                    '<i class="fas fa-eye"></i>',
                                    '/' . $registro['Page']['slug'],
                                    array(
                                        'target' => '_blank',
                                        'class' => 'btn btn-action pr-2',
                                        'escape' => false
                                    )
                                );
                                echo "&nbsp;&nbsp;&nbsp;";
                                echo $this->Html->link(
                                    '<i class="fas fa-edit"></i>',
                                    array(
                                        'controller' => 'Pages',
                                        'action' => 'edit',
                                        $registro['Page']['id']
                                    ),
                                    array(
                                        'class' => 'btn btn-action pr-2',
                                        'escape' => false
                                    )
                                );
                                echo "&nbsp;&nbsp;&nbsp;";
                                echo $this->Form->postLink(
                                    '<i class="fas fa-trash"></i>',
                                    array(
                                        'controller' => 'Excluir',
                                        'action' => 'delete',
                                        $registro['Page']['id']
                                    ),
                                    array(
                                        'confirm' => 'Tem certeza que deseja EXCLUIR esta página?',
                                        'class' => 'btn btn-action text-danger',
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