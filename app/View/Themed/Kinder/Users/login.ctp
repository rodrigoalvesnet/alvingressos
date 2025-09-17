<?php

$isAjax = isset($this->params->params['requested']) ? $this->params->params['requested'] : 0;
if (!$isAjax) {
?>
    <section id="login" class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Acesse a sua conta!</h2>
                <p class="section-subtitle">Digite os dados abaixo para ter acesso a sua conta Kinder Park!</p>
            </div>
        <?php }
    $largura = 'col-md-6';
    //Se está acessando por ajax
    if ($isAjax) {
        $largura = 'col-md-12';
        ?>
            <h4><i class="bi bi-person"></i> Faça o Login ou Cadastre-se para finalizar a compra</h4>
        <?php } ?>
        <div class="row justify-content-center">
            <div class="<?php echo $largura; ?>">
                <?php
                echo $this->Form->create('User');
                echo $this->Form->hidden('isAjax', array(
                    'value' => 1
                ));

                echo $this->Form->input(
                    'User.email',
                    array(
                        'label' => false,
                        'div' => 'form-group',
                        'placeholder' => 'Informe o seu e-mail',
                        'class' => 'form-control mb-2',
                        'required' => true
                    )
                );

                echo $this->Form->input(
                    'User.password',
                    array(
                        'label' => false,
                        'div' => 'form-group',
                        'placeholder' => 'Digite a sua senha',
                        'class' => 'form-control  mb-2',
                        'required' => true
                    )
                );

                echo $this->Form->input(
                    'Entrar',
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
                        <a class="btn btn-outline-light text-dark btn-block" href="/Users/recovery"><i class="bi bi-key"></i> Esqueci a senha</a>
                    </div>
                    <div class=" mt-3">
                        <a class="btn btn-outline-light text-dark btn-block" href="/Users/register"><i class="bi bi-person-add"></i> Criar a minha conta</a>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!$isAjax) { ?>
        </div>
    </section>
<?php } ?>