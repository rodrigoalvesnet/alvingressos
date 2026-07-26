<h1>Esqueceu sua senha?</h1>
<h2>Não se preocupe, informe seu e-mail e nós enviaremos um link
para você cadastrar uma nova.</h2>
<?php
echo $this->Form->create(
    'User',
    array(
        'class' => 'form'
    )
);

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

if (Configure::read('Recaptcha.site_key')) {
    echo $this->Html->script('https://www.google.com/recaptcha/api.js', array('async' => true, 'defer' => true));
    echo '<div class="g-recaptcha mb-2" data-sitekey="' . h(Configure::read('Recaptcha.site_key')) . '"></div>';
}

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
<div class="">
    <span class="font-grey">Já tem uma conta? <a href="/Users/login" class="font-bold">Entrar.</a></span>
</div>