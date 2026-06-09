<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comprovante #<?php echo $estadia['Estadia']['id']; ?></title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            width: 80mm;
            margin: 8mm auto;
            padding: 4mm;
            background: #fff;
            color: #000;
        }
        .center  { text-align: center; }
        .right   { text-align: right; }
        .bold    { font-weight: bold; }
        .big     { font-size: 15px; }
        .linha   { border-top: 1px dashed #555; margin: 5px 0; }
        .row     { display: flex; justify-content: space-between; gap: 4px; }
        .row span:last-child { white-space: nowrap; }
        .bloco   { margin: 3px 0; }
        .titulo  { font-size: 14px; font-weight: bold; text-align: center; letter-spacing: 1px; margin: 4px 0; }

        /* botões visíveis somente em tela */
        .acoes-tela {
            margin-top: 12mm;
            text-align: center;
        }
        .acoes-tela button {
            padding: 7px 20px;
            font-size: 12px;
            cursor: pointer;
            margin: 0 4px;
        }

        @page {
            size: 80mm auto;
            margin: 5mm;
        }
        @media print {
            body    { margin: 0; width: 80mm; }
            .acoes-tela { display: none !important; }
        }
    </style>
</head>
<body>

    <?php
        $e   = $estadia['Estadia'];
        $tar = !empty($estadia['Tarifa'])  ? $estadia['Tarifa']  : [];
        $atr = !empty($estadia['Atracao']) ? $estadia['Atracao'] : [];

        $statusLabel = [
            'aberta'    => 'Aberta',
            'pausada'   => 'Pausada',
            'encerrada' => 'Encerrada',
            'cancelada' => 'Cancelada',
        ];
    ?>

    <!-- Cabeçalho da unidade -->
    <?php if (!empty($unidadeEstadia['Unidade'])): ?>
    <div class="center bloco">
        <div class="bold big"><?php echo h($unidadeEstadia['Unidade']['name']); ?></div>
        <?php if (!empty($unidadeEstadia['Unidade']['cnpj'])): ?>
        <div>CNPJ: <?php echo h($unidadeEstadia['Unidade']['cnpj']); ?></div>
        <?php endif; ?>
        <?php if (!empty($unidadeEstadia['Unidade']['street'])): ?>
        <div>
            <?php echo h($unidadeEstadia['Unidade']['street']); ?>
            <?php echo !empty($unidadeEstadia['Unidade']['number']) ? ', ' . h($unidadeEstadia['Unidade']['number']) : ''; ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($unidadeEstadia['Unidade']['city'])): ?>
        <div><?php echo h($unidadeEstadia['Unidade']['city']); ?> - <?php echo h($unidadeEstadia['Unidade']['state']); ?></div>
        <?php endif; ?>
        <?php if (!empty($unidadeEstadia['Unidade']['phone'])): ?>
        <div>Tel: <?php echo h($unidadeEstadia['Unidade']['phone']); ?></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="linha"></div>
    <div class="titulo">COMPROVANTE DE ESTADIA</div>
    <div class="linha"></div>

    <!-- Identificação -->
    <div class="bloco">
        <div class="row"><span>Estadia #:</span><span class="bold"><?php echo $e['id']; ?></span></div>
        <div class="row"><span>Pulseira:</span><span class="bold"><?php echo h($e['pulseira_numero']); ?></span></div>
        <div class="row"><span>Situação:</span><span class="bold"><?php echo isset($statusLabel[$e['status']]) ? $statusLabel[$e['status']] : h($e['status']); ?></span></div>
    </div>

    <div class="linha"></div>

    <!-- Dados da criança -->
    <div class="bloco">
        <div><span class="bold">CRIANÇA:</span> <?php echo h($e['crianca_nome']); ?></div>
        <?php if (!empty($e['nascimento']) && $e['nascimento'] !== '0000-00-00'): ?>
        <div>Nasc.: <?php echo date('d/m/Y', strtotime($e['nascimento'])); ?></div>
        <?php endif; ?>
        <?php if (!empty($e['sexo'])): ?>
        <div>Sexo: <?php echo ucfirst(h($e['sexo'])); ?></div>
        <?php endif; ?>
    </div>

    <!-- Dados do responsável -->
    <div class="bloco">
        <div><span class="bold">RESPONSÁVEL:</span> <?php echo h($e['responsavel_nome']); ?></div>
        <?php if (!empty($e['telefone'])): ?>
        <div>Tel: <?php echo h($e['telefone']); ?></div>
        <?php endif; ?>
        <?php if (!empty($e['cpf'])): ?>
        <div>CPF: <?php echo h($e['cpf']); ?></div>
        <?php endif; ?>
    </div>

    <div class="linha"></div>

    <!-- Atração e tarifa -->
    <div class="bloco">
        <?php if (!empty($atr['nome'])): ?>
        <div class="row"><span>Atração:</span><span><?php echo h($atr['nome']); ?></span></div>
        <?php endif; ?>
        <?php if (!empty($tar['nome'])): ?>
        <div class="row"><span>Tarifa:</span><span><?php echo h($tar['nome']); ?></span></div>
        <?php endif; ?>
    </div>

    <div class="linha"></div>

    <!-- Tempos -->
    <div class="bloco">
        <?php if (!empty($e['inicio_em'])): ?>
        <div class="row"><span>Entrada:</span><span><?php echo date('d/m/Y H:i', strtotime($e['inicio_em'])); ?></span></div>
        <?php endif; ?>
        <?php if (!empty($e['fim_em'])): ?>
        <div class="row"><span>Saída:</span><span><?php echo date('d/m/Y H:i', strtotime($e['fim_em'])); ?></span></div>
        <?php endif; ?>
        <?php if (!empty($e['duracao_segundos'])): ?>
        <?php
            $dur = (int)$e['duracao_segundos'];
            $hh  = floor($dur / 3600);
            $mm  = floor(($dur % 3600) / 60);
            $ss  = $dur % 60;
        ?>
        <div class="row"><span>Tempo cobrado:</span><span class="bold"><?php echo sprintf('%02d:%02d:%02d', $hh, $mm, $ss); ?></span></div>
        <?php endif; ?>
        <?php if (!empty($e['pausado_segundos']) && (int)$e['pausado_segundos'] > 0): ?>
        <?php
            $ps  = (int)$e['pausado_segundos'];
            $phh = floor($ps / 3600);
            $pmm = floor(($ps % 3600) / 60);
            $pss = $ps % 60;
        ?>
        <div class="row"><span>Tempo pausado:</span><span><?php echo sprintf('%02d:%02d:%02d', $phh, $pmm, $pss); ?></span></div>
        <?php endif; ?>
    </div>

    <div class="linha"></div>

    <!-- Valores -->
    <div class="bloco">
        <?php
            $valorTempo = (float)$e['valor_base'] + (float)$e['valor_adicional'];
        ?>
        <div class="row">
            <span>Tempo de brinquedo:</span>
            <span>R$ <?php echo number_format($valorTempo, 2, ',', '.'); ?></span>
        </div>
    </div>

    <?php if (!empty($itens)): ?>
    <div class="linha"></div>
    <div class="bold bloco">ADICIONAIS:</div>
    <?php foreach ($itens as $item): ?>
    <div class="row bloco">
        <span><?php echo h($item['EstadiaItem']['descricao']); ?> (<?php echo rtrim(rtrim(number_format((float)$item['EstadiaItem']['qtd'], 2, ',', '.'), '0'), ','); ?>x)</span>
        <span>R$ <?php echo number_format((float)$item['EstadiaItem']['valor_total'], 2, ',', '.'); ?></span>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($e['desconto']) && (float)$e['desconto'] > 0): ?>
    <div class="row bloco">
        <span>Desconto concedido:</span>
        <span>- R$ <?php echo number_format((float)$e['desconto'], 2, ',', '.'); ?></span>
    </div>
    <?php endif; ?>

    <div class="linha"></div>

    <!-- Total -->
    <div class="row bold big bloco">
        <span>TOTAL PAGO:</span>
        <span>R$ <?php echo number_format((float)$e['valor_total'], 2, ',', '.'); ?></span>
    </div>
    <?php if (!empty($formaPagamento)): ?>
    <div class="bloco">Pagamento: <?php echo h($formaPagamento); ?></div>
    <?php endif; ?>

    <div class="linha"></div>

    <div class="center bloco">Obrigado pela visita!</div>
    <div class="center bloco"><?php echo date('d/m/Y \à\s H:i'); ?></div>

    <!-- Observações (se houver) -->
    <?php if (!empty($e['observacoes'])): ?>
    <div class="linha"></div>
    <div class="bloco">Obs: <?php echo h($e['observacoes']); ?></div>
    <?php endif; ?>

    <!-- Botões visíveis apenas em tela -->
    <div class="acoes-tela">
        <button onclick="window.print()">&#128438; Imprimir</button>
        <button onclick="window.close()">Fechar</button>
    </div>

    <?php if (Configure::read('Estadias.comprovante.autoprint')): ?>
    <script>
        window.addEventListener('load', function () {
            window.print();
        });
        window.addEventListener('afterprint', function () {
            window.close();
        });
    </script>
    <?php endif; ?>

</body>
</html>
