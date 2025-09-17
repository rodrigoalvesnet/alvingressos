<div class="card">
    <?php
    echo $this->Form->create(
        'Talker',
        array(
            'type' => 'file'
        )
    );
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'name',
                    array(
                        'label' => 'Nome',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'description',
                    array(
                        'label' => 'Descrição',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'instagram',
                    array(
                        'label' => 'Perfil no Instagram',
                        'class' => 'form-control',
                        'div' => 'form-group',
                    )
                );
                ?>
            </div>
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'facebook',
                    array(
                        'label' => 'Perfil no Facebook',
                        'class' => 'form-control',
                        'div' => 'form-group',
                    )
                );
                ?>
            </div>
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'youtube',
                    array(
                        'label' => 'Canal do Youtube',
                        'class' => 'form-control',
                        'div' => 'form-group',
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'new_photo',
                    array(
                        'type' => 'file',
                        'label' => 'Foto do Preletor',
                        'class' => 'form-control',
                        'div' => 'form-group'
                    )
                );
                //Se tem imagem para exibir
                if (isset($this->data['Talker']['photo']) && !empty($this->data['Talker']['photo'])) {
                    echo $this->Html->image(
                        '/uploads/small/' . $this->data['Talker']['photo'],
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