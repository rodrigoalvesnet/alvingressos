<div class="card">
    <?php
    echo $this->Form->create('User');
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'name',
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
                    'email',
                    array(
                        'label' => 'E-mail',
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
                    'cpf',
                    array(
                        'label' => 'CPF',
                        'class' => 'form-control cpf',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
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
            <div class="col-lg-3">
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
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'unidade_id',
                    array(
                        'label' => 'Unidade',
                        'options' => $unidades,
                        'class' => 'form-control select2',
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
                $passwordLabel = 'Senha';
                if ($this->action == 'edit') {
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
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'password2',
                    array(
                        'type' => 'password',
                        'label' => 'Confirme a senha',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => $this->action == 'add' ? true : false,
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'role_id',
                    array(
                        'label' => 'Grupo',
                        'options' => $roles,
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'default' => 3,
                        'required' => true,
                        'disabled' => AuthComponent::user('role_id') == 1 ? false : true,
                        'readonly' => AuthComponent::user('role_id') == 1 ? false : true
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
                        'empty' => false,
                        'disabled' => AuthComponent::user('role_id') == 1 ? false : true,
                        'readonly' => AuthComponent::user('role_id') == 1 ? false : true
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