<section id="register" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Criar uma conta Kinder Park!</h2>
            <p class="section-subtitle">Digite os dados abaixo para criar a sua conta Kinder Park!</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php
                echo $this->Form->create(
                    'User',
                    array(
                        'class' => 'form'
                    )
                );

                echo $this->Form->hidden('User.id');

                echo $this->Form->input(
                    'User.name',
                    array(
                        'label' => false,
                        'type'    => 'text',
                        'div' => 'form-group',
                        'placeholder' => 'Nome completo',
                        'class' => 'form-control form-control-lg mb-2',
                        'required' => true
                    )
                );

                echo $this->Form->input(
                    'User.cpf',
                    array(
                        'label' => false,
                        'type'    => 'text',
                        'div' => 'form-group',
                        'placeholder' => 'CPF',
                        'class' => 'form-control form-control-lg mb-2 cpf',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );

                echo $this->Form->input(
                    'User.birthday',
                    array(
                        'label' => false,
                        'type'    => 'text',
                        'div' => 'form-group',
                        'placeholder' => 'Data de nascimento',
                        'class' => 'form-control form-control-lg mb-2 datemask',
                        'required' => true
                    )
                );

                echo $this->Form->input(
                    'User.phone',
                    array(
                        'label' => false,
                        'type'    => 'text',
                        'div' => 'form-group',
                        'placeholder' => 'Telefone',
                        'class' => 'form-control form-control-lg mb-2 fone',
                        'required' => true
                    )
                );

                echo $this->Form->input(
                    'User.email',
                    array(
                        'label' => false,
                        'type'    => 'email',
                        'div' => 'form-group',
                        'placeholder' => 'Informe o seu e-mail',
                        'class' => 'form-control form-control-lg mb-2',
                        'required' => true
                    )
                );

                echo $this->Form->input(
                    'User.password',
                    array(
                        'label' => false,
                        'type' => 'password',
                        'div' => 'form-group',
                        'placeholder' => 'Digite a sua senha',
                        'class' => 'form-control form-control-lg mb-2',
                        'required' => true,
                        'min' => 6
                    )
                );

                echo $this->Form->input(
                    'User.password2',
                    array(
                        'label' => false,
                        'type' => 'password',
                        'div' => 'form-group',
                        'placeholder' => 'Confirme a senha',
                        'class' => 'form-control form-control-lg mb-2',
                        'required' => true
                    )
                );

                echo $this->Form->input(
                    'Cadastrar',
                    array(
                        'type'    => 'submit',
                        'class' => 'btn btn-primary btn-lg btn-block',
                        'div'    => array('class' => 'form-group'),
                        'label' => false
                    )
                );

                echo $this->Form->end();
                echo $this->Html->script('register', array('block' => 'scriptBottom'));
                ?>
                <div class="text-center mt-3">
                    <div class=" mt-3">
                        <a class="btn btn-outline-light text-dark btn-block" href="/Users/login"><i class="bi bi-box-arrow-in-left"></i> Fazer login!</a>
                    </div>
                    <div class=" mt-3">
                        <a class="btn btn-outline-light text-dark btn-block" href="/Users/recovery"><i class="bi bi-key"></i> Esqueci a senha</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>