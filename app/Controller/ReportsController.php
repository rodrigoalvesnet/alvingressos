<?php
class ReportsController extends AppController
{

    var $helpers = array('Alv');
    var $components = array('Alv', 'Security');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Security->unlockedActions = array(
            'admin_orders',
            'admin_tickets'
        );
    }

    public function admin_index() {}

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

    public function admin_orders()
    {
        $this->layout = 'planilha';
        

        //verifica se tem condições na session
        if ($this->Session->check('Filtros.Orders')) {
            //utiliza os filtros do cache
            $arrayConditions = $this->Session->read('Filtros.Orders');
            $this->request->data = $this->Session->read('Filtros.ThisData');
        } else {
            $this->Flash->warning('Você não tem permissão para acessar este registro!');
            $this->redirect($this->referer());
        }

        $this->loadModel('Order');
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
                    'status',
                    'created'
                ),
                'order' => 'Order.name ASC',
                // 'limit' => 15
            )
        );
        // pr($orders);exit();
        $this->set('registros', $orders);
        $this->set('status', Configure::read('Order.status'));
        $this->set('paymentsTypes', Configure::read('Order.payment_type'));

        $fileName = Inflector::slug($orders[0]['Event']['title'], '-');
        $this->set('fileName', $fileName);
        $this->set('title', $orders[0]['Event']['title']);
    }

    public function admin_tickets()
    {
        $this->layout = 'planilha';

        if ($this->Session->check('Filtros.Tickets')) {
            $arrayConditions = $this->Session->read('Filtros.Tickets');
        } else {
            $this->Flash->warning('Nenhum filtro encontrado para exportar.');
            $this->redirect(['controller' => 'Tickets', 'action' => 'index', 'admin' => true]);
        }

        $this->loadModel('Ticket');
        $tickets = $this->Ticket->find('all', array(
            'conditions' => $arrayConditions,
            'contain' => array(
                'Event' => array('title'),
                'Order' => array('id', 'status'),
                'Checkin' => array('created')
            ),
            'fields' => array(
                'id', 'order_id', 'event_id', 'nome', 'cpf', 'email',
                'telefone', 'modalidade_nome', 'modalidade_data', 'created'
            ),
            'order' => 'Ticket.nome ASC'
        ));

        $this->set('registros', $tickets);
        $this->set('fileName', 'passaportes-' . date('Y-m-d'));
    }
}
