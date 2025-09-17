<h1>Criar Conta.</h1>
<h2>Informe seus dados para se cadastrar.</h2>
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
    'User.church_id',
    array(
        'label' => false,
        'empty' => 'Igreja',
        'div' => 'form-group',
        'class' => 'form-control form-control-lg mb-2',
        'required' => true,
        'options' => $churches
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
<div class="">
    <span class="font-grey">Já tem uma conta? <a href="/Users/login" class="font-bold">Entrar.</a></span>
</div>
<div>
    <a href="/Users/recovery" class="font-bold">Recuperar a senha.</a>
</div>
