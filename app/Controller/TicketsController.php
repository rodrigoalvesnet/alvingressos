<?php
class TicketsController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function admin_index() {}

    public function view()
    {
        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';
    }

    public function print($id)
    {
        // $this->theme = Configure::read('Site.tema');
        $this->layout = 'pdf';
        $ticket = $this->Ticket->find(
            'first',
            array(
                'conditions' => array(
                    'Ticket.id' => $id,
                    'Order.status' => 'approved'
                ),
                'contain' => array(
                    'Order',
                    'Event' => array(
                        'Unidade' => array(
                            'name',
                            'cnpj',
                            'street',
                            'number',
                            'state',
                            'city',
                            'zipcode',
                            'email',
                            'phone',
                            'district'
                        ),
                        'fields' => array(
                            'title'
                        )
                    )
                )
            )
        );
        // pr($ticket);exit();
        $this->set('ticket', $ticket);
        $fileName = Inflector::slug($ticket['Ticket']['id'] . '-' . $ticket['Ticket']['nome'], '-');
        $this->set('fileName', $fileName);
    }

    function generate_qrcode_checkin($orderid)
    {
        $this->layout = 'image';
        App::import('Vendor', 'QrcodeGen', array('file' => 'QrcodeGen/QrcodeGen.php'));
        $qrcode = new QrcodeGen();
        $urlQrCode = Configure::read('Checkin.url') . $this->params['pass'][0];
        $image = $qrcode->link($urlQrCode);
        $this->set('imgData', $image);
        $this->set('fileName', $orderid);
    }

    function generate_qrcode_pix()
    {
        $this->layout = 'ajax';
    }
}
