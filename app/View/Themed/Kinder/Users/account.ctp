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
                            'disabled' => false,
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
<script>
    /**
     * Valida CPF (Brasil)
     * - Aceita com ou sem máscara
     * - Rejeita CPFs com todos os dígitos iguais
     * - Valida dígitos verificadores
     */
    function validarCPF(cpf) {
        if (cpf == null) return false;

        // Remove tudo que não for número
        cpf = String(cpf).replace(/\D/g, '');

        // CPF deve ter 11 dígitos
        if (cpf.length !== 11) return false;

        // Rejeita CPFs com dígitos repetidos (ex: 00000000000, 11111111111...)
        if (/^(\d)\1{10}$/.test(cpf)) return false;

        // Calcula 1º dígito verificador
        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i), 10) * (10 - i);
        }
        let dig1 = (soma * 10) % 11;
        if (dig1 === 10) dig1 = 0;

        // Confere 1º dígito
        if (dig1 !== parseInt(cpf.charAt(9), 10)) return false;

        // Calcula 2º dígito verificador
        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i), 10) * (11 - i);
        }
        let dig2 = (soma * 10) % 11;
        if (dig2 === 10) dig2 = 0;

        // Confere 2º dígito
        return dig2 === parseInt(cpf.charAt(10), 10);
    }

    /**
     * Exemplo de uso em input:
     * - Coloque id="cpf"
     * - Adiciona máscara ao digitar (opcional)
     * - Valida no blur e mostra feedback (Bootstrap 4/5 friendly)
     */
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('UserCpf');
        if (!input) return;

        // máscara (opcional)
        input.addEventListener('input', function() {
            const pos = input.selectionStart; // tenta manter o cursor (não perfeito em todos os casos)
            const old = input.value;
            input.value = maskCPF(input.value);
            // ajuste simples de cursor
            const diff = input.value.length - old.length;
            input.setSelectionRange(pos + diff, pos + diff);
        });

        // validação
        input.addEventListener('blur', function() {
            const ok = validarCPF(input.value);

            // estilo tipo Bootstrap (opcional)
            input.classList.toggle('is-valid', ok);
            input.classList.toggle('is-invalid', !ok);

            // mensagem (se existir um .invalid-feedback logo depois)
            const feedback = input.parentElement?.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = ok ? '' : 'CPF inválido.';
            }
        });
    });
</script>