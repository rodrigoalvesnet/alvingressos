<div class="card">
    <?php
    echo $this->Form->create('Banner', array(
        'type' => 'file'
    ));
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'Banner.title',
                    array(
                        'label' => 'Título',
                        'class' => 'form-control',
                        'div' => 'form-group'
                    )
                );
                ?>
            </div>
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'Banner.subtitle',
                    array(
                        'type' => 'textarea',
                        'label' => 'Subtítulo',
                        'class' => 'form-control',
                        'div' => 'form-group'
                    )
                );
                ?>
            </div>
        </div>        
        <div class="row">
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'Banner.button_link',
                    array(
                        'label' => 'Link no Botão',
                        'class' => 'form-control',
                        'div' => 'form-group',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'Banner.button_title',
                    array(
                        'label' => 'Título no Botão',
                        'class' => 'form-control',
                        'div' => 'form-group',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'Banner.button_class',
                    array(
                        'label' => 'Classe do Botão',
                        'class' => 'form-control',
                        'div' => 'form-group',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'Banner.position',
                    array(
                        'label' => 'Posição das Letras',
                        'options' => array(
                            'left' => 'Esquerda',
                            'center' => 'Centro',
                            'right' => 'Direita',
                        ),
                        'class' => 'form-control',
                        'required' => true,
                        'div' => 'form-group',
                        'empty' => false
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'Banner.showtitle',
                    array(
                        'label' => 'Exibir título sobre o banner',
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
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'Banner.linkbanner',
                    array(
                        'label' => 'Ativar clique no banner',
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
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'Banner.active',
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
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'Banner.new_image',
                    array(
                        'type' => 'file',
                        'label' => 'Banner para a exibição no desktop (1920px por 572px)',
                        'class' => 'form-control',
                        'div' => 'form-group'
                    )
                );
                //Se tem imagem para exibir
                if (isset($this->data['Banner']['image']) && !empty($this->data['Banner']['image'])) {
                    echo $this->Html->image(
                        '/uploads/banners/' . $this->data['Banner']['image'],
                        array(
                            'class' => 'img-thumbnail'
                        )
                    );
                }
                ?>
            </div>
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'Banner.new_image_mobile',
                    array(
                        'type' => 'file',
                        'label' => 'Banner para a exibição no mobile (510px por 350px)',
                        'class' => 'form-control',
                        'div' => 'form-group'
                    )
                );
                //Se tem imagem para exibir
                if (isset($this->data['Banner']['image_mobile']) && !empty($this->data['Banner']['image_mobile'])) {
                    echo $this->Html->image(
                        '/uploads/banners/' . $this->data['Banner']['image_mobile'],
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