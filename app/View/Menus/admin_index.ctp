<?php echo $this->Html->link('Adicionar Novo', ['action' => 'add'], ['class' => 'btn btn-primary mb-2']); ?>
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
            <table class="table">
                <tr>
                    <th>Título</th>
                    <th>Link</th>
                    <th>Pai</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($registros as $menu): ?>
                    <tr>
                        <td><?php echo h($menu['Menu']['title']); ?></td>
                        <td><?php echo h($menu['Menu']['link']); ?></td>
                        <td><?php echo h($menu['ParentMenu']['title']); ?></td>
                        <td>
                            <?php echo $this->Html->link('Editar', array('action' => 'edit', $menu['Menu']['id'], 'admin' => true)); ?> |
                            <?php echo $this->Html->link('Excluir', array('action' => 'delete', $menu['Menu']['id'], 'admin' => true), null, 'Tem certeza?'); ?> |
                            <?php echo $this->Html->link('↑', array('action' => 'up', $menu['Menu']['id'], 'admin' => true)); ?> |
                            <?php echo $this->Html->link('↓', array('action' => 'down', $menu['Menu']['id'], 'admin' => true)); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <?php echo $this->element('paginador'); ?>
<?php } else { ?>
    <div class="alert alert-primary" role="alert">Nenhum registro encontrado</div>
<?php } ?>