<section id="account" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Minha Conta</h2>
            <p class="section-subtitle">Abaixo estão os seus dados de acesso a sua conta Kinder Park</p>
        </div>
        <?php
        echo $this->Form->create('User');
        echo $this->Form->hidden('id');
        ?>
        <div class="justify-content-center">
            <div class="row">
                <div class="col-lg-6">
                    <?php
                    echo $this->Form->input(
                        'name',
                        array(
                            'label' => 'Título',
                            'class' => 'form-control',
                            'div' => 'form-group',
                            'disabled' => true,
                        )
                    );
                    ?>
                </div>
                <div class="col-lg-6">
                    <?php
                    echo $this->Form->input(
                        'email',
                        array(
                            'label' => 'E-mail',
                            'class' => 'form-control',
                            'div' => 'form-group',
                            'disabled' => true,
                        )
                    );
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?php
                    echo $this->Form->input(
                        'cpf',
                        array(
                            'label' => 'CPF',
                            'class' => 'form-control cpf',
                            'div' => 'form-group',
                            'disabled' => true,
                        )
                    );
                    ?>
                </div>
                <div class="col-lg-4">
                    <?php
                    echo $this->Form->input(
                        'birthday',
                        array(
                            'type' => 'text',
                            'label' => 'Data de nascimento',
                            'class' => 'form-control datepicker',
                            'div' => 'form-group',
                            'required' => true,
                        )
                    );
                    ?>
                </div>
                <div class="col-lg-4">
                    <?php
                    echo $this->Form->input(
                        'phone',
                        array(
                            'type' => 'text',
                            'label' => 'Telefone',
                            'class' => 'form-control fone',
                            'div' => 'form-group',
                            'required' => true,
                        )
                    );
                    ?>
                </div>
            </div>
            <hr class="my-3" />
            <h4><i class="fas fa-key"></i> Alterar Senha</h4>
            <div class="alert alert-primary" User="alert">Preencha os campos abaixo somente se você desejar trocar a senha.</div>
            <div class="row">

                <div class="col-lg-6">
                    <?php
                    $passwordLabel = 'Senha';
                    if ($this->action == 'edit' || $this->action == 'account') {
                        $passwordLabel = 'Nova Senha (preencha se for para alterar)';
                    }
                    echo $this->Form->input(
                        'password',
                        array(
                            'label' => $passwordLabel,
                            'class' => 'form-control',
                            'div' => 'form-group',
                            'required' => $this->action == 'add' ? true : false,
                        )
                    );
                    ?>
                </div>
                <div class="col-lg-6">
                    <?php
                    $passwordLabel = 'Confirme a senha';
                    if ($this->action == 'edit' || $this->action == 'account') {
                        $passwordLabel = 'Confirme a nova senha';
                    }
                    echo $this->Form->input(
                        'password2',
                        array(
                            'type' => 'password',
                            'label' => $passwordLabel,
                            'class' => 'form-control',
                            'div' => 'form-group',
                            'required' => $this->action == 'add' ? true : false,
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="">
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
</section>