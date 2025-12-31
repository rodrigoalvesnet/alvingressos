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
        $this->loadModel('Ticket');
        $this->loadModel('Checkin');

        // =========================
        // KPIs de HOJE (seus atuais)
        // =========================
        $ordersCountToday = $this->Order->find('count', array(
            'conditions' => array(
                'Order.status' => 'approved',
                'DATE(Order.created)' => date('Y-m-d')
            ),
            'recursive' => -1
        ));
        $this->set('ordersCountToday', $ordersCountToday);

        $ordersTotalToday = $this->Order->find('all', array(
            'conditions' => array(
                'Order.status' => 'approved',
                'DATE(Order.created)' => date('Y-m-d')
            ),
            'fields' => array('SUM(value) AS total'),
            'recursive' => -1
        ));
        $this->set('ordersTotalToday', $ordersTotalToday[0][0]['total']);

        $ticketsToday = $this->Ticket->find('count', array(
            'conditions' => array(
                'Order.status' => 'approved',
                'DATE(Ticket.modalidade_data)' => date('Y-m-d')
            ),
            'contain' => array(
                'Order' => array('fields' => array('id', 'status'))
            )
        ));
        $this->set('ticketsToday', $ticketsToday);

        $checkinsToday = $this->Checkin->find('count', array(
            'conditions' => array(
                'DATE(Checkin.created)' => date('Y-m-d')
            ),
            'recursive' => -1
        ));
        $this->set('checkinsToday', $checkinsToday);

        // ============================================
        // NOVOS KPIs (somente Admin/Gerente) por mês/ano
        // ============================================
        $roleId = (int)$this->Auth->user('role_id');
        $this->set('roleId', $roleId);

        // mês/ano vindos do GET (?m=12&y=2025)
        $m = isset($this->request->query['m']) ? (int)$this->request->query['m'] : (int)date('m');
        $y = isset($this->request->query['y']) ? (int)$this->request->query['y'] : (int)date('Y');

        // validação básica
        if ($m < 1 || $m > 12) $m = (int)date('m');
        if ($y < 2000 || $y > 2100) $y = (int)date('Y');

        $this->set('filterMonth', $m);
        $this->set('filterYear', $y);

        // intervalo [start, end)
        $start = sprintf('%04d-%02d-01 00:00:00', $y, $m);
        $endTs = strtotime($start . ' +1 month');
        $end = date('Y-m-d H:i:s', $endTs);

        if ($roleId === 1 || $roleId === 2) {

            // 1) Passaportes vendidos (TOTAL em R$) no mês/ano
            $ordersTotalMonthRow = $this->Order->find('first', array(
                'conditions' => array(
                    'Order.status' => 'approved',
                    'Order.created >=' => $start,
                    'Order.created <'  => $end,
                ),
                'fields' => array('COALESCE(SUM(Order.value), 0) AS total'),
                'recursive' => -1
            ));
            $ordersTotalMonth = (float)$ordersTotalMonthRow[0]['total'];
            $this->set('ordersTotalMonth', $ordersTotalMonth);

            // 2) Checkins realizados (QUANTIDADE) no mês/ano
            $checkinsCountMonth = $this->Checkin->find('count', array(
                'conditions' => array(
                    'Checkin.created >=' => $start,
                    'Checkin.created <'  => $end,
                ),
                'recursive' => -1
            ));
            $this->set('checkinsCountMonth', (int)$checkinsCountMonth);
        } else {
            // para não dar "undefined" na view
            $this->set('ordersTotalMonth', 0);
            $this->set('checkinsCountMonth', 0);
        }

        $this->set('nobc', true);
        $this->set('notitle', true);
    }
}
