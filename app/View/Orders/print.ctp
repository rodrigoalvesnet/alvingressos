<style>
    * {
        font-size: 14px;
        font-family: Arial, Helvetica, sans-serif;
    }

    table {
        width: 100%;
    }

    .container {
        width: 700px;
        margin-left: auto;
        margin-right: auto;
        padding: 20px 0;
    }

    .logo {
        max-width: 200px;
    }

    h1 {
        font-size: 26px;
        margin: 0;
        padding: 0;
    }

    h2 {
        font-size: 21px;
        margin: 0;
        padding: 0;
    }

    h3 {
        font-size: 18px;
        margin: 0;
        padding: 0;
    }

    .td-logo {
        /* width: 35%; */
    }

    td {
        padding: 10px;
        line-height: 20px;
    }

    .qrcode {
        width: 100%;
        text-align: center;
    }

    .label {
        font-weight: bold;
    }

    .td-qrcode {
        width: 50%;
    }

    .text-center {
        text-align: center;
    }

    .text-italic {
        font-style: italic;
    }
</style>
<div class="container">
    <table>
        <tr>
            <td class="td-logo">
                <?php
                $pathLog ='/theme/' . Configure::read('Site.tema') . '/img/logo-100.png';
                echo $this->Html->image(
                    $pathLog,
                    array(
                        'fullBase' => true,
                        'class' =>  'logo'
                    )
                );
                ?>
            </td>
            <td>
                <h2 style="margin-bottom: 5px;"><?php echo Configure::read('Sistema.title');?></h2>
                <div class="description">
                    <?php echo $order['Event']['Unidade']['street']; ?>, <?php echo $order['Event']['Unidade']['number']; ?> - <?php echo $order['Event']['Unidade']['district']; ?><br />
                    <?php echo $order['Event']['Unidade']['city']; ?>/<?php echo $order['Event']['Unidade']['state']; ?> - CEP: <?php echo $order['Event']['Unidade']['zipcode']; ?><br />
                    Telefone: <?php echo $order['Event']['Unidade']['phone']; ?> - E-mail: <?php echo $order['Event']['Unidade']['email']; ?>
                </div>
            </td>
        </tr>
    </table>
    <hr />
    <table>
        <tr>
            <td class="text-center">
                <h2>Comprovante de Inscrição</h2>
            </td>
        </tr>
    </table>
    <hr />

    <table>
        <tr>
            <td>
                <h1>#<?php echo str_pad($order['Order']['id'], 6, '0', STR_PAD_LEFT); ?></h1><br />
                <span class="label">Evento: </span><?php echo $order['Event']['title']; ?><br />
                <span class="label">Nome: </span><?php echo $order['Order']['name']; ?><br />
                <span class="label">CPF: </span><?php echo $order['Order']['cpf']; ?><br />
                <span class="label">Nascimento: </span><?php echo $this->Alv->tratarData($order['Order']['birthday'], 'pt'); ?><br />
                <span class="label">Idade: </span><?php echo $this->Alv->getIdade($order['Order']['birthday']); ?> anos (<?php echo $this->Alv->getFaixaEtaria($order['Order']['birthday']); ?>)<br />
                <span class="label">Telefone: </span><?php echo $order['Order']['phone']; ?><br />
                <span class="label">E-mail: </span><?php echo $order['Order']['email']; ?><br />
                <span class="label">Unidade: </span><?php echo $order['Unidade']['name']; ?><br />
                <span class="label">Data da Compra: </span><?php echo date('d/m/Y - H:i', strtotime($order['Order']['created'])); ?><br />
                <span class="label">Valor Pago: </span>R$<?php echo $this->Alv->tratarValor($order['Order']['value'], 'pt'); ?><br />
                <span class="label">Pagamento: </span><?php echo $order['Order']['payment_type']; ?><br />
                <span class="label">Parcelas: </span><?php echo $order['Order']['installments']; ?>x<br />
            </td>
            <td class="td-qrcode">
                <?php
                App::import('Vendor', 'QrcodeGen', array('file' => 'QrcodeGen/QrcodeGen.php'));
                $qrcode = new QrcodeGen();
                $urlQrCode = Configure::read('Checkin.url') . $order['Order']['id'];
                $image = $qrcode->link($urlQrCode)
                ?>
                <div class="img-qrcode">
                    <img src="data:image/png;base64, <?php echo base64_encode($image) ?>" class="img-thumbnail img-fluid">
                </div>
            </td>
        </tr>
    </table>
    <?php
    //Se tem informações adicionais
    if (isset($order['Event']['Field']) && !empty($order['Event']['Field'])) {
    ?>
        <hr /><br />
        <h2>Informações Adicionais</h2>
        <ul>
            <?php foreach ($order['Event']['Field'] as $field) {
                foreach ($order['Response'] as $response) {
                    if ($response['field_id'] == $field['id']) {
                        $responseValue = $response['response'];
                        continue;
                    }
                }
            ?>
                <li style="margin-bottom:5px;"><span class="label"><?php echo $field['question']; ?>: </span><?php echo $responseValue; ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
    <hr /><br />
    <div class="text-center text-italic">Este documento não serve para comprovante de pagamento e não tem valor fiscal.</div>
    <div class="text-center">Impressão/geração do arquivo: <?php echo date('d/m/Y H:i'); ?></div><br />
    <div class="text-center">Todos os direitos reservados à <?php echo Configure::read('Sistema.title');?></div>
    <br />
    <div class="text-center">Sistema desenvolvido por AlvWorks - alvworks.com.br</div>
</div>