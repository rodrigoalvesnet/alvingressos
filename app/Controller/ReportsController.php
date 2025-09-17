<?php
class ReportsController extends AppController
{

    var $helpers = array('Alv');
    var $components = array('Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function admin_index()
    {
    }

    public function admin_by_event($id, $status = 'all')
    {
        $this->layout = 'planilha';
        $this->loadModel('Event');
        $checkPermission = $this->Event->checkPermission($id);
        if (!$checkPermission) {
            $this->Flash->warning('Você não tem permissão para acessar este relatório!');
            $this->redirect($this->referer());
        }

        $this->loadModel('Order');
        $arrayConditions = array(
            'Order.event_id' => $id
        );
        if ($status != 'all') {
            $arrayConditions['Order.status'] = $status;
        }
        $orders = $this->Order->find(
            'all',
            array(
                'conditions' => $arrayConditions,
                'contain' => array(
                    'Unidade' => array(
                        'name'
                    ),
                    'Event' => array(
                        'title',
                        'status',
                        'Field' => array(
                            'id',
                            'question'
                        )
                    ),
                    'Coupon' => array(
                        'code'
                    ),
                    'Response',
                    'Checkin'
                ),
                'fields' => array(
                    'id DISTINCT',
                    'name',
                    'cpf',
                    'email',
                    'phone',
                    'installments',
                    'value',
                    'payment_type',
                    'status'
                ),
                'order' => 'Order.name ASC',
                // 'limit' => 15
            )
        );
        $this->set('registros', $orders);
        $this->set('status', Configure::read('Order.status'));
        $this->set('paymentsTypes', Configure::read('Order.payment_type'));

        $fileName = Inflector::slug($orders[0]['Event']['title'], '-');
        $this->set('fileName', $fileName);
        $this->set('title', $orders[0]['Event']['title']);
    }
}
