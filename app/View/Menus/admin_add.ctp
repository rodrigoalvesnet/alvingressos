<div class="card">
    <?php
    echo $this->Form->create('Menu');
    echo $this->Form->hidden('id');
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
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'link',
                    array(
                        'label' => 'Link (URL)',
                        'placeholder' => '/pagina-exemplo ou http://externo.com',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'parent_id',
                    array(
                        'label' => 'Menu Pai',
                        'empty' => 'Nenhum (menu principal)', // permite criar menu de topo
                        'options' => $parents,
                        'class' => 'form-control',
                        'div' => 'form-group',
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'class',
                    array(
                        'label' => 'Classe (HTML)',
                        'class' => 'form-control',
                        'div' => 'form-group',
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'icon',
                    array(
                        'label' => 'Classe do Icone (HTML)',
                        'class' => 'form-control',
                        'div' => 'form-group',
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
                        'required' => true,
                        'div' => 'form-group',
                        'empty' => false
                    )
                );
                ?>
            </div>
        </div>
    </div>
</div>
<div class="card-footer border-top">
    <?php
    echo $this->Form->submit(
        'Salvar',
        array(
            'type'    => 'submit',
            'class' => 'btn btn-primary',
            'div'    => false,
            'label' => false
        )
    );
    ?>
</div>
<?php echo $this->Form->end(); ?>
</div>