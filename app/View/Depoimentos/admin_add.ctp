<div class="card">
    <?php
    echo $this->Form->create('Depoimento', array(
        'type' => 'file'
    ));
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
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
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'estrelas',
                    array(
                        'label' => 'Estrelas',
                        'type' => 'number',
                        'min' => 1,
                        'max' => 5,
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-4">
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
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'texto',
                    array(
                        'label' => 'Texto',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'new_foto',
                    array(
                        'type' => 'file',
                        'label' => 'Foto do Cliente',
                        'class' => 'form-control',
                        'div' => 'form-group'
                    )
                );
                //Se tem imagem para exibir
                if (isset($this->data['Depoimento']['foto']) && !empty($this->data['Depoimento']['foto'])) {
                    echo $this->Html->image(
                        '/uploads/depoimentos/' . $this->data['Depoimento']['foto'],
                        array(
                            'class' => 'img-thumbnail'
                        )
                    );
                }
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