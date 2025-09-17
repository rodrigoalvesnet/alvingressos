<section id="recovery" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Esqueceu sua senha?</h2>
            <p class="section-subtitle">Não se preocupe, informe seu e-mail e nós enviaremos um link para você cadastrar uma nova.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php
                echo $this->Form->create('User');

                echo $this->Form->text(
                    'User.email',
                    array(
                        'label' => false,
                        'div' => 'form-group',
                        'placeholder' => 'Informe o seu e-mail',
                        'class' => 'form-control form-control-lg mb-2',
                        'required' => true
                    )
                );

                echo $this->Form->input(
                    'Enviar nova senha',
                    array(
                        'type'    => 'submit',
                        'class' => 'btn btn-primary btn-lg btn-block',
                        'div'    => array('class' => 'form-group'),
                        'label' => false
                    )
                );

                echo $this->Form->end();
                ?>
                <div class="text-center mt-3">
                    <div class=" mt-3">
                        <a class="btn btn-outline-light text-dark btn-block" href="/Users/login"><i class="bi bi-box-arrow-in-left"></i> Fazer login!</a>
                    </div>
                    <div class=" mt-3">
                        <a class="btn btn-outline-light text-dark btn-block" href="/Users/register"><i class="bi bi-person-add"></i> Criar a minha conta</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>