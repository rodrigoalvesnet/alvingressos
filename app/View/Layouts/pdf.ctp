<?php
App::import('Vendor', 'Dompdf', array('file' => 'dompdf/vendor/autoload.php'));

use Dompdf\Dompdf;
use Dompdf\Options;

//orientação padrão
$orientation = isset($orientation) ? $orientation : 'portrait';
//se está setado a orientação
if (isset($this->params->query['orientation'])) {
    $orientation = $this->params->query['orientation'];
}

//tipo do papel padrão
$size = 'A4';
//se está setado o tipo do papel
if (isset($this->params->query['size'])) {
    $size = $this->params->query['size'];
}
//se está setado o tipo do papel
if (isset($customPaper)) {
    $size = $customPaper;
}
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$html = mb_convert_encoding($content_for_layout, 'HTML-ENTITIES', 'UTF-8');
$dompdf->loadHtml($html);
$dompdf->setPaper($size, $orientation);
$dompdf->render();
$fileName = isset($fileName) ? $fileName . '.pdf' : 'documento.pdf';
$download = isset($download) ? $download : 0;
$dompdf->stream($fileName, array('Attachment' => $download));
exit();