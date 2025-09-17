<?php
require __DIR__ . '/vendor/autoload.php';

use \App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

class QrcodeGen
{
    function chavePix($key, $description, $value, $size = 400)
    {
        //INSTANCIA PRINCIPAL DO PAYLOAD PIX
        $obPayload = (new Payload)->setPixKey($key)
            ->setDescription($description)
            ->setMerchantName(Configure::read('Sistema.title'))
            ->setMerchantCity('Curitiba')
            ->setAmount($value)
            ->setTxid('ALVINGRESSOS');

        //CÓDIGO DE PAGAMENTO PIX
        $payloadQrCode = $obPayload->getPayload();
        //QR CODE
        $obQrCode = new QrCode($payloadQrCode);
        //IMAGEM DO QRCODE
        $image = (new Output\Png)->output($obQrCode, $size);
        return $image;
    }

    function link($url, $size = 400)
    {
        //QR CODE
        $obQrCode = new QrCode($url);
        //IMAGEM DO QRCODE
        $image = (new Output\Png)->output($obQrCode, $size);
        return $image;
    }
}
