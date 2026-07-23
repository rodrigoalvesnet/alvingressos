<?php
App::import('Vendor', 'QrcodeGen', array('file' => 'QrcodeGen/QrcodeGen.php'));
$qrcodeWidth = Configure::read('Sistema.qrcode_width');

$qrcode = new QrcodeGen();
$urlQrCode = Configure::read('Checkin.url') . $ticket['Ticket']['id'];
$image = $qrcode->link($urlQrCode, $qrcodeWidth);
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
        position: relative;
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

    td {
        padding: 0px;
        line-height: 20px;
    }

    .label {
        font-weight: bold;
    }

    .text-center {
        text-align: center;
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

    .watermark {
        position: absolute;
        top: 280px;
        left: -80px;
        width: 860px;
        text-align: center;
        font-size: 90px;
        font-weight: bold;
        letter-spacing: 6px;
        color: rgba(150, 150, 150, 0.28);
        transform: rotate(-28deg);
    }

    .faixa-cortesia {
        background: #fff3cd;
        border: 1px solid #e0a800;
        color: #7a5b00;
        text-align: center;
        font-weight: bold;
        padding: 6px 0;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }

    .aviso-cortesia {
        margin-top: 15px;
        padding: 10px;
        border: 1px dashed #c0392b;
        color: #c0392b;
        font-size: 11px;
        line-height: 15px;
    }
</style>

<div class="container">
    <div class="watermark">CORTESIA</div>

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
                    <?php echo $ticket['Unidade']['street']; ?>, <?php echo $ticket['Unidade']['number']; ?> - <?php echo $ticket['Unidade']['district']; ?><br />
                    <?php echo $ticket['Unidade']['city']; ?>/<?php echo $ticket['Unidade']['state']; ?> - CEP: <?php echo $ticket['Unidade']['zipcode']; ?><br />
                    Telefone: <?php echo $ticket['Unidade']['phone']; ?> - E-mail: <?php echo $ticket['Unidade']['email']; ?>
                </div>
            </td>
        </tr>
    </table>
    <hr />

    <div class="faixa-cortesia">INGRESSO CORTESIA &mdash; NÃO COMERCIALIZÁVEL</div>

    <table class="border-bottom-dashed">
        <tr>
            <td style="width: 70%;">
                <span class="label">Número:</span> <?php echo str_pad($ticket['Ticket']['id'], 5, '0', STR_PAD_LEFT); ?><br />
                <span class="label">Unidade: </span><?php echo $ticket['Unidade']['name']; ?><br />
                <?php if (!empty($ticket['Ticket']['nome'])) { ?>
                    <span class="label">Nome: </span><?php echo $ticket['Ticket']['nome']; ?><br />
                <?php } ?>
                <span class="label">Emitida em: </span><?php echo date('d/m/Y', strtotime($ticket['Ticket']['created'])); ?><br />
                <span class="label">Válida até: </span><?php echo date('d/m/Y', strtotime($ticket['Ticket']['valido_ate'])); ?><br />
            </td>
            <td style="width: 30%; text-align: right;">
                <div class="img-qrcode">
                    <img src="data:image/png;base64, <?php echo base64_encode($image); ?>">
                </div>
            </td>
        </tr>
    </table>

    <div class="aviso-cortesia">
        <strong>Aviso:</strong> este ingresso de cortesia é pessoal, gratuito e intransferível, válido apenas
        para uma única entrada dentro do prazo indicado acima. Após a data de validade, a cortesia perde o
        efeito automaticamente e não poderá ser utilizada. A adulteração, cópia, reprodução ou qualquer
        tentativa de fraude relacionada a este documento sujeitará o responsável às medidas legais cabíveis.
    </div>
</div>

<div class="rodape">
    Desenvolvido por AlvWorks - https://alvworks.com.br - Impresso em <?php echo date('d/m/Y H:i'); ?>
</div>
