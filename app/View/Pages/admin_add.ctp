<style>
    .cke_notification_warning {
        display: none;
    }
</style>
<div class="card">
    <?php
    echo $this->Form->create('Page', array(
        'type' => 'file'
    ));
    echo $this->Form->hidden('Page.id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'Page.title',
                    array(
                        'label' => 'Título',
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
                    'Page.content',
                    array(
                        'type' => 'textarea',
                        'label' => 'Conteúdo da da página:',
                        'class' => 'form-control ckeditor',
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
                    'Page.youtube',
                    array(
                        'label' => 'Link do vídeo do Youtube',
                        'class' => 'form-control',
                        'required' => false,
                        'div' => 'form-group',
                        'type' => 'text'
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'Page.fluid',
                    array(
                        'label' => 'Estilo da página',
                        'options' => array(
                            '1' => 'Largo (fluid)',
                            '0' => 'Justo'
                        ),
                        'class' => 'form-control',
                        'required' => true,
                        'div' => 'form-group'
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'Page.show_title',
                    array(
                        'label' => 'Exibir Título',
                        'options' => array(
                            '1' => 'Sim',
                            '0' => 'Não'
                        ),
                        'class' => 'form-control',
                        'required' => true,
                        'div' => 'form-group'
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'Page.active',
                    array(
                        'label' => 'Ativo',
                        'options' => array(
                            '1' => 'Sim',
                            '0' => 'Não'
                        ),
                        'class' => 'form-control',
                        'required' => true,
                        'div' => 'form-group'
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'Page.description',
                    array(
                        'label' => 'Breve descrição da página (SEO)',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'type' => 'text'
                    )
                );
                ?>
            </div>
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'Page.keywords',
                    array(
                        'label' => 'Palavras-chaves da página (SEO)',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'type' => 'text'
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'Page.new_banner_desktop',
                    array(
                        'type' => 'file',
                        'label' => 'Banner para a exibição no desktop (1920px por 1080px)',
                        'class' => 'form-control',
                        'div' => 'form-group'
                    )
                );
                //Se tem imagem para exibir
                if (isset($this->data['Page']['banner_desktop']) && !empty($this->data['Page']['banner_desktop'])) {
                    echo $this->Html->image(
                        '/uploads/page-' . $this->data['Page']['id'] . '/' . $this->data['Page']['banner_desktop'],
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
                    'Page.new_banner_mobile',
                    array(
                        'type' => 'file',
                        'label' => 'Banner para a exibição no mobile (720px por 1280px)',
                        'class' => 'form-control',
                        'div' => 'form-group'
                    )
                );
                //Se tem imagem para exibir
                if (isset($this->data['Page']['banner_mobile']) && !empty($this->data['Page']['banner_mobile'])) {
                    echo $this->Html->image(
                        '/uploads/page-' . $this->data['Page']['id'] . '/small/' . $this->data['Page']['banner_mobile'],
                        array(
                            'class' => 'img-thumbnail'
                        )
                    );
                }
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