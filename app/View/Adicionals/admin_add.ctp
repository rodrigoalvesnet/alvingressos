<div class="card">
    <?php
    echo $this->Form->create('Adicional');
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'nome',
                    array(
                        'label' => 'Nome',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'descricao',
                    array(
                        'label' => 'Descrição',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => false,
                        'rows' => 2
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'valor',
                    array(
                        'type' => 'text',
                        'label' => 'Valor',
                        'class' => 'form-control money',
                        'div' => 'form-group',
                        'required' => false,
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'ativo',
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