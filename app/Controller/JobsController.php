<?php
class JobsController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('send_subscription');
    }

    public function send_subscription($test = false)
    {
        Configure::write('debug', 1);
        $this->autoRender = false;
        $this->loadModel('Order');

        $currentDate = date('Y-m-d'); // Data atual
        $endDate = date('Y-m-d', strtotime('+3 days')); // Data 3 dias a partir de agora

        //Procura pedidos aprovados
        $order = $this->Order->find(
            'first',
            array(
                'conditions' => array(
                    'Event.start_date >=' => $currentDate,
                    'Event.start_date <=' => $endDate,
                    'Order.alert' => 0,
                    'Order.email != ' => null,
                    'Order.status' => 'approved',
                    'Event.status' => array(
                        'oculto',
                        'scheduled',
                        'soldoff'
                    )
                ),

                'fields' => array(
                    'id',
                    'name',
                    'email'
                ),
                'contain' => array(
                    'Event' => array(
                        'id',
                        'title'
                    )
                ),
                'order' => array(
                    'Event.start_date' => 'DESC',
                    'Order.id' => 'ASC'
                )
            )
        );

        // pr($order);
        // exit();
        //Se encontrou algum pedido
        if (!empty($order)) {
            //Pega alguns dados
            $eventTitle = trim($order['Event']['title']);
            $emailTitle = $eventTitle . ' - Ingresso';
            $orderId = $order['Order']['id'];

            //Prepara os dados do email
            $arrayDadosEmail = array(
                'nome' => Configure::read('Sistema.title')
            );
            //Assunto do email
            $arrayDadosEmail['assunto'] =  $emailTitle;
            //Gera o QRCode
            $tagImgQrcode = $this->Order->getImgQrCodeCheckin($order['Order']['id']);
            //Corpo da Mensagem
            $mailBody = 'Prezado(a), <strong>' . trim($order['Order']['name']) . '</strong>, <br /><br />
                O seu pedido número <strong>' . $orderId . '</strong> do <strong>' . $eventTitle . '</strong> precisa ser impresso ou apresentado no momento da entrada do evento!
                <br /><br />
                <a href="https://ingresso.templodasaguias.com.br/Orders/ticket/' . $orderId . '" target="_blank">Clique aqui para acessar o seu pedido.</a>
                <br /><br />
                <strong>Ou, exiba o QRCode abaixo no momento do Check-In do Evento!</strong><br /><br />
               ' . $tagImgQrcode . '
                <br /><br />
                Antenciosamente<br /><br />
                Templo das Águias';
            $arrayDadosEmail['mensagem'] =  $mailBody;
            //Se tiver anexos
            $arrayAttanchments = array();
            //Envia um anexo.
            // $arrayAttanchments = array(
            //     'congresso-mulheres-2023.pdf' => array(
            //         'file' => WWW_ROOT . 'anexos' . DS . 'congresso-mulheres-2023.pdf',
            //         'mimetype' => 'application/pdf',
            //         'contentId' => 'congresso-mulheres-2023'
            //     )
            // );
            //Enviar e-mail 
            $email = trim(strtolower($order['Order']['email']));
            if($test){
                $email = 'rodrigoalvesnet@gmail.com';
            }
            if ($tagImgQrcode != '') {
                $sendedMail = $this->Alv->enviarEmail($arrayDadosEmail, $email, $arrayAttanchments);
                if ($sendedMail) {
                    $sql = "UPDATE orders SET alert = 1 WHERE id = $orderId;";
                    $this->Order->query($sql);
                    $message = "SUCESSO > Pedido $orderId";
                } else {
                    $message = "ERRO > Pedido $orderId";
                }
            } else {
                $message = 'O QRCode do pedido' . $orderId . ' não pôde ser gerado.';
            }
        } else {
            $message = 'Nenhum registro encontrado.';
        }
        // $this->log($message, "debug");
        // $this->set('message', $message);
    }
}
