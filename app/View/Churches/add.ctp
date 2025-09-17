<div class="card">
    <?php
    echo $this->Form->create('Church');
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="church-details" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-info-circle"></i> Detalhes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="church-asaas" data-bs-toggle="tab" data-bs-target="#asaas" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-wings"></i> Asaas</button>
            </li>
        </ul>
        <div class="tab-content mt-3" id="myTabContent">
            <!-- DETALHES DA IGREJA -->
            <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="church-details">
                <div class="row">
                    <div class="col-lg-9">
                        <?php
                        echo $this->Form->input(
                            'name',
                            array(
                                'label' => 'Nome',
                                'class' => 'form-control',
                                'div' => 'form-group',
                                'required' => true
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php
                        echo $this->Form->input(
                            'cnpj',
                            array(
                                'label' => 'CNPJ',
                                'class' => 'form-control cnpj',
                                'div' => 'form-group',
                                'required' => true
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <?php
                        echo $this->Form->input(
                            'street',
                            array(
                                'label' => 'Rua',
                                'class' => 'form-control',
                                'div' => 'form-group',
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php
                        echo $this->Form->input(
                            'number',
                            array(
                                'label' => 'Número',
                                'class' => 'form-control',
                                'div' => 'form-group',
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php
                        echo $this->Form->input(
                            'district',
                            array(
                                'label' => 'Bairro',
                                'class' => 'form-control',
                                'div' => 'form-group',
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <?php
                        echo $this->Form->input(
                            'zipcode',
                            array(
                                'label' => 'CEP',
                                'class' => 'form-control cep',
                                'div' => 'form-group',
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-3">
                        <?php
                        echo $this->Form->input(
                            'state',
                            array(
                                'label' => 'Estado',
                                'options' => Configure::read('Sistema.ufs'),
                                'empty' => 'Selecione um estado',
                                'class' => 'form-control',
                                'div' => 'form-group',
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <?php
                        echo $this->Form->input(
                            'city',
                            array(
                                'label' => 'Cidade',
                                'class' => 'form-control',
                                'div' => 'form-group',
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <?php
                        echo $this->Form->input(
                            'phone',
                            array(
                                'label' => 'Telefone',
                                'class' => 'form-control fone',
                                'div' => 'form-group',
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-5">
                        <?php
                        echo $this->Form->input(
                            'email',
                            array(
                                'label' => 'E-mail',
                                'class' => 'form-control',
                                'div' => 'form-group',
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
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>
            <!-- ASAAS -->
            <div class="tab-pane fade show" id="asaas" role="tabpanel" aria-labelledby="church-asaas">
                <div class="row">
                    <div class="col-lg-12 my-2">
                        <?php
                        echo $this->Form->input(
                            'asaas_production',
                            array(
                                'label' => 'Utilizar ambiente de PRODUÇÃO (cuidado)',
                                'type' => 'checkbox',
                                'class' => 'form-check-input',
                                'div' => 'form-check'
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        echo $this->Form->input(
                            'asaas_key_sandbox',
                            array(
                                'label' => 'API KEY do Sandbox',
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
                            'asaas_url_sandbox',
                            array(
                                'label' => 'Url da API do Sandbox',
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
                            'asaas_key',
                            array(
                                'label' => 'API KEY',
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
                            'asaas_url',
                            array(
                                'label' => 'Url da API',
                                'class' => 'form-control',
                                'div' => 'form-group',
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <?php
                        echo $this->Form->input(
                            'asaas_group_name',
                            array(
                                'label' => 'Digite um nome para categorizar o pagamento',
                                'class' => 'form-control',
                                'div' => 'form-group',
                            )
                        );
                        ?>
                    </div>
                    <div class="col-lg-4">
                        <?php
                        echo $this->Form->input(
                            'asaas_due_date',
                            array(
                                'type' => 'number',
                                'label' => 'Dia para pagar o boleto ou o PIX',
                                'class' => 'form-control',
                                'div' => 'form-group',
                                'min' => 1
                            )
                        );
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <?php
                        echo $this->Form->input(
                            'asaas_notification',
                            array(
                                'label' => 'Notificar o cliente (pode gerar custos)',
                                'type' => 'checkbox',
                                'class' => 'form-check-input',
                                'div' => 'form-check'
                            )
                        );
                        ?>
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