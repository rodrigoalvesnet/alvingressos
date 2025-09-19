<?php
class DashController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function admin_index()
    {
        $this->loadModel('Order');
        $ordersCountToday = $this->Order->find(
            'count',
            array(
                'conditions' => array(
                    'status' => 'approved',
                    'DATE(created)' => date('Y-m-d')
                ),
                'recursive' => -1
            )
        );
        $this->set('ordersCountToday', $ordersCountToday);

        $ordersTotalToday = $this->Order->find(
            'all',
            array(
                'conditions' => array(
                    'status' => 'approved',
                    'DATE(created)' => date('Y-m-d')
                ),
                'fields' => array('SUM(value) AS total'),
                'recursive' => -1
            )
        );
        $this->set('ordersTotalToday', $ordersTotalToday[0][0]['total']);

        $this->loadModel('Ticket');
        $ticketsToday = $this->Ticket->find(
            'count',
            array(
                'conditions' => array(
                    'Order.status' => 'approved',
                    'DATE(Ticket.modalidade_data)' => date('Y-m-d')
                ),
                'contain' => array(
                    'Order' => [
                        'fields' => [
                            'id',
                            'status'
                        ]
                    ]
                )
            )
        );
        $this->set('ticketsToday', $ticketsToday);

        $this->loadModel('Checkin');
        $checkinsToday = $this->Checkin->find(
            'count',
            array(
                'conditions' => array(
                    'DATE(created)' => date('Y-m-d')
                ),
                'recursive' => -1
            )
        );
        // pr($checkinsToday);exit();
        $this->set('checkinsToday', $checkinsToday);

        $this->set('nobc', true);
        $this->set('notitle', true);
        
    }
}
