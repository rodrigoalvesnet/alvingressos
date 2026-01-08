<div class="card">
    <?php
    echo $this->Form->create('Site');
    ?>
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="site-details" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-info-circle"></i> Detalhes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="site-scripts" data-bs-toggle="tab" data-bs-target="#asaas" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-wings"></i> Scripts</button>
            </li>
        </ul>
        <div class="tab-content mt-3" id="myTabContent">
            <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="site-details">
                <div class="row">
                    <div class="col-lg-12">
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
                    <div class="col-lg-12">
                        <?php
                        echo $this->Form->input(
                            'description',
                            array(
                                'label' => 'Descrição do Site',
                                'type' => 'textarea',
                                'rows' => 2,
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-12">
                        <?php
                        echo $this->Form->input(
                            'footer',
                            array(
                                'label' => 'Texto do Rodapé do Site',
                                'type' => 'textarea',
                                'rows' => 2,
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-12">
                        <?php
                        echo $this->Form->input(
                            'keywords',
                            array(
                                'label' => 'Palavras-chaves',
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                </div>
                <hr class="my-3" />
                <h4>Contato</h4>
                <div class="row">
                    <div class="col-lg-4">
                        <?php
                        echo $this->Form->input(
                            'email',
                            array(
                                'label' => 'Email de contato',
                                'type' => 'email',
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-4">
                        <?php
                        echo $this->Form->input(
                            'telefone',
                            array(
                                'label' => 'Telefone',
                                'class' => 'form-control fone',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-4">
                        <?php
                        echo $this->Form->input(
                            'instagram',
                            array(
                                'label' => 'Instagram',
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-4">
                        <?php
                        echo $this->Form->input(
                            'facebook',
                            array(
                                'label' => 'Facebook',
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-4">
                        <?php
                        echo $this->Form->input(
                            'video_youtube',
                            array(
                                'label' => 'Vídeo do youtube',
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                </div>
                <hr class="my-3" />
                <h4>Endereço</h4>
                <div class="row">
                    <div class="col-lg-4">
                        <?php
                        echo $this->Form->input(
                            'endereco',
                            array(
                                'label' => 'Rua e número',
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php
                        echo $this->Form->input(
                            'bairro',
                            array(
                                'label' => 'Bairro',
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-2">
                        <?php
                        echo $this->Form->input(
                            'cidade',
                            array(
                                'label' => 'Cidade',
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-2">
                        <?php
                        echo $this->Form->input(
                            'uf',
                            array(
                                'label' => 'Estado',
                                'options' => Configure::read('Sistema.ufs'),
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-1">
                        <?php
                        echo $this->Form->input(
                            'cep',
                            array(
                                'label' => 'CEP',
                                'class' => 'form-control cep',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show" id="asaas" role="tabpanel" aria-labelledby="site-scripts">
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        echo $this->Form->input(
                            'script_header',
                            array(
                                'label' => 'Scripts no Header',
                                'type' => 'textarea',
                                'rows' => 4,
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-12">
                        <?php
                        echo $this->Form->input(
                            'script_bottom',
                            array(
                                'label' => 'Scripts no Bottom',
                                'type' => 'textarea',
                                'rows' => 4,
                                'class' => 'form-control',
                                'div' => 'form-group'
                            )
                        );
                        ?>
                    </div>
                </div>
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