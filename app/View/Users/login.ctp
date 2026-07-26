<h1>Acesse.</h1>
<h2>Entre com os seus dados de acesso.</h2>
<?php
echo $this->Form->create(
    'User',
    array(
        'class' => 'form'
    )
);

echo $this->Form->input(
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
    'User.password',
    array(
        'label' => false,
        'div' => 'form-group',
        'placeholder' => 'Digite a sua senha',
        'class' => 'form-control form-control-lg mb-2',
        'required' => true
    )
);

if (Configure::read('Recaptcha.site_key')) {
    echo $this->Html->script('https://www.google.com/recaptcha/api.js', array('async' => true, 'defer' => true));
    echo '<div class="g-recaptcha mb-2" data-sitekey="' . h(Configure::read('Recaptcha.site_key')) . '"></div>';
}

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
<div class="">
    <span class="font-grey">Não tem uma conta? <a href="/Users/register" class="font-bold">Registre-se.</a></span>
</div>
<div>
    <a href="/Users/recovery" class="font-bold">Recuperar a senha.</a>
</div>