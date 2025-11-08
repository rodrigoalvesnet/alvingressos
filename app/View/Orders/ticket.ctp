<?php
App::import('Vendor', 'QrcodeGen', array('file' => 'QrcodeGen/QrcodeGen.php'));
$qrcodeWidth = Configure::read('Sistema.qrcode_width');
$qrCodeUnico = Configure::read('Checkin.qrCodeUnico');
?>
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
        font-size: 24px;
        margin: 0;
        padding: 0;
    }

    h2 {
        font-size: 19px;
        margin: 0;
        padding: 0;
    }

    h3 {
        font-size: 16px;
        margin: 0;
        padding: 0;
    }

    td {
        padding: 0px;
        line-height: 20px;
    }

    .qrcode {
        width: 100%;
        text-align: center;
    }

    .label {
        font-weight: bold;
    }

    .text-center {
        text-align: center;
    }

    .text-italic {
        font-style: italic;
    }

    .border-bottom-dashed {
        border-bottom: 1px dashed #000;
    }

    .rodape {
        position: absolute;
        bottom: 0;
        text-align: center;
        font-size: 11px;
    }
</style>

<div class="container">
    <table>
        <tr>
            <td class="td-logo">
                <?php
                $pathLog = '/theme/' . Configure::read('Site.tema') . '/img/logo-100.png';
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
                <h2 style="margin-bottom: 5px;"><?php echo Configure::read('Sistema.title'); ?></h2>
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
                <h2>Dados da Compra</h2>
            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 50%;">
                <span class="label">Pedido: </span><?php echo str_pad($order['Order']['id'], 6, '0', STR_PAD_LEFT); ?><br />
                <span class="label">Evento: </span><?php echo $order['Event']['title']; ?><br />
                <span class="label">Nome: </span><?php echo $order['Order']['name']; ?><br />
                <span class="label">CPF: </span><?php echo $order['Order']['cpf']; ?><br />
                <span class="label">Telefone: </span><?php echo $order['Order']['phone']; ?><br />
            </td>
            <td style="width: 50%;">
                <span class="label">E-mail: </span><?php echo $order['Order']['email']; ?><br />
                <span class="label">Unidade: </span><?php echo $order['Event']['Unidade']['name']; ?><br />
                <span class="label">Data da Compra: </span><?php echo date('d/m/Y - H:i', strtotime($order['Order']['created'])); ?><br />
                <span class="label">Valor Pago: </span>R$ <?php echo $this->Alv->tratarValor($order['Order']['value'], 'pt'); ?><br />
                <span class="label">Pagamento: </span><?php echo $order['Order']['payment_type']; ?> (<?php echo $order['Order']['installments']; ?>x)<br />
            </td>
        </tr>
    </table>

    <hr />

    <table>
        <tr>
            <td class="text-center">
                <?php if ($qrCodeUnico) { ?>
                    <h2>Participante(s)</h2>
                <?php } else { ?>
                    <h2>Ingresso(s)</h2>
                <?php } ?>
            </td>
        </tr>
    </table>

    <?php
    if ($qrCodeUnico) {
        // Agrupa os tickets por data
        $ticketsPorData = [];
        foreach ($order['Ticket'] as $ticket) {
            $data = $ticket['modalidade_data'];
            if (!isset($ticketsPorData[$data])) {
                $ticketsPorData[$data] = [];
            }
            $ticketsPorData[$data][] = $ticket;
        }

        // Ordena por data
        ksort($ticketsPorData);

        // Para cada data, gera um QRCode único
        foreach ($ticketsPorData as $data => $tickets) {
            $dataFormatada = $this->Alv->tratarData($data, 'pt');
            $qrcode = new QrcodeGen();
            $urlQrCode = Configure::read('Checkin.url') . $order['Order']['id'] . '?data=' . urlencode($data);
            $image = $qrcode->link($urlQrCode, $qrcodeWidth);
    ?>
            <table class="border-bottom-dashed">
                <tr>
                    <td style="width: 70%;">
                        <span class="label">Data: </span><?php echo $dataFormatada; ?><br />
                        <span class="label">Participantes:</span><br />
                        <?php foreach ($tickets as $t) { ?>
                            - <?php echo $t['nome']; ?> (<?php echo $t['cpf']; ?>)<br />
                        <?php } ?>
                    </td>
                    <td style="width: 30%; text-align: right;" class="td-qrcode">
                        <div class="img-qrcode">
                            <img src="data:image/png;base64, <?php echo base64_encode($image); ?>">
                        </div>
                    </td>
                </tr>
            </table>
        <?php
        }
    } else {
        // Caso padrão: um QRCode por ingresso
        foreach ($order['Ticket'] as $ticket) { ?>
            <table class="border-bottom-dashed">
                <tr>
                    <td style="width: 70%;">
                        <span class="label">Número:</span> <?php echo str_pad($ticket['id'], 4, '0', STR_PAD_LEFT); ?><br />
                        <span class="label">Data: </span><?php echo $this->Alv->tratarData($ticket['modalidade_data'], 'pt'); ?> - <?php echo $ticket['modalidade_nome']; ?><br />
                        <span class="label">Nome: </span><?php echo $ticket['nome']; ?><br />
                        <span class="label">CPF: </span><?php echo $ticket['cpf']; ?><br />
                        <span class="label">E-mail: </span><?php echo $ticket['email']; ?><br />
                    </td>
                    <td style="width: 30%; text-align: right;" class="td-qrcode">
                        <?php
                        $qrcode = new QrcodeGen();
                        $urlQrCode = Configure::read('Checkin.url') . $ticket['id'];
                        $image = $qrcode->link($urlQrCode, $qrcodeWidth);
                        ?>
                        <div class="img-qrcode">
                            <img src="data:image/png;base64, <?php echo base64_encode($image); ?>">
                        </div>
                    </td>
                </tr>
            </table>
    <?php }
    }
    ?>
</div>

<div class="rodape">
    Desenvolvido por AlvWorks - https://alvworks.com.br - Impresso em <?php echo date('d/m/Y H:i'); ?>
</div>