<?php
class CheckinsController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function admin_index()
    {
        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Checkins')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Checkins');
            }
            //atualiza a pagina
            $this->redirect(array(
                'admin' => true
            ));
        }

        //condição padrão
        $arrayConditions = array();
        //se o this->data não está vazio, prepara o filtro
        if (!empty($this->request->data)) {
            if (isset($this->request->data['Filtro']['name']) && !empty($this->request->data['Filtro']['name'])) {
                $arrayConditions['Ticket.nome LIKE'] = '%' . $this->request->data['Filtro']['name'] . '%';
            }
            if (isset($this->request->data['Filtro']['cpf']) && !empty($this->request->data['Filtro']['cpf'])) {
                $arrayConditions['Ticket.cpf'] = $this->request->data['Filtro']['cpf'];
            }
            if (isset($this->request->data['Filtro']['ticket_id']) && !empty($this->request->data['Filtro']['ticket_id'])) {
                $arrayConditions['Checkin.ticket_id'] = $this->request->data['Filtro']['ticket_id'];
            }
            if (isset($this->request->data['Filtro']['order_id']) && !empty($this->request->data['Filtro']['order_id'])) {
                $arrayConditions['Checkin.order_id'] = $this->request->data['Filtro']['order_id'];
            }
            if (isset($this->request->data['Filtro']['date']) && !empty($this->request->data['Filtro']['date'])) {
                $arrayConditions['DATE(Checkin.created)'] = $this->Alv->tratarData($this->request->data['Filtro']['date']);
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Checkins', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Checkins')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Checkins');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'contain' => array(
                'Ticket' => [
                    'id',
                    'event_id',
                    'nome',
                    'cpf',
                    'telefone',
                    'modalidade_nome'
                ],
                'User' => [
                    'name'
                ]
            ),
            'fields' => array(
                'id',
                'created',
                'ticket_id',
                'order_id'
            ),
            'order' => array(
                'Checkin.created DESC'
            ),
            'limit' => Configure::read('Sistema.limit')
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('Checkin'));
    }

    public function admin_add($eventId)
    {
        $this->loadModel('Event');
        $checkPermission = $this->Event->checkPermission($eventId);
        if (!$checkPermission) {
            $this->Flash->warning('Você não tem permissão para acessar este evento!');
            $this->redirect($this->referer());
        }

        $event = $this->Event->find(
            'first',
            array(
                'conditions' => array(
                    'id' => $eventId
                ),
                'fields' => array(
                    'title'
                ),
                'recursive' => -1
            )
        );
        $this->set('event', $event);
        $this->set('title_for_layout', 'Check-in: ' . $event['Event']['title']);
        $isSecure = $this->Alv->isSecure();
        $this->set('isSecure', $isSecure);
    }

    public function admin_checkin()
    {
        $this->autoRender = false;
        $arrayReturn = array(
            'success' => false,
            'message' => 'Não foi possível encontrar a inscrição.',
        );
        $this->log($this->data);
        if (!empty($this->data)) {
            if ($this->Checkin->save($this->data)) {
                $arrayReturn['success'] = true;
            }
        }
        return json_encode($arrayReturn);
    }

    public function admin_check($id)
    {
        $this->layout = 'ajax';
        /**
         * Se o checkin é único, a leitura do qrcode é pelo ID do Pedido,
         * senão, é feito por cada ticket gerado no pedido
         */

        if (isset($this->params->query['data'])) {
            $this->_checkinByOrder($id, $this->params->query['data']);
            $this->render('admin_check_order');
        } else {
            $this->_checkinByTicket($id);
            $this->render('admin_check_ticket');
        }
    }

    function _checkinByOrder($orderId, $date)
    {
        //Busca os dados do pedido
        $this->loadModel('Order');
        $this->data = $this->Order->find(
            'first',
            array(
                'conditions' => array(
                    'Order.id' => $orderId
                ),
                'contain' => array(
                    'Unidade' => array(
                        'id',
                        'name'
                    ),
                    'Event' => array(
                        'id',
                        'title',
                        'status'
                    ),
                    'Checkin' => array(
                        'created',
                        'User' => array(
                            'name'
                        )
                    ),
                    'Ticket' => [
                        'conditions' => [
                            'modalidade_data' => $date
                        ]
                    ]
                ),
                'fields' => array(
                    'id',
                    'event_id',
                    'name',
                    'cpf',
                    'email',
                    'birthday',
                    'payment_type',
                    'value',
                    'status',
                    'reason'
                )
            )
        );
        $this->set('modalidade_data', $this->params->query['data']);


        $checkinExists = false;
        //Verifica se o checkin já foi feito
        if ($this->Checkin->checkinExists($orderId, 'order')) {
            $checkinExists = true;
        }
        $this->set('checkinExists', $checkinExists);

        $bloqueiaCheckinAdiantado = false;
        //Verifica se a data do ticket é MAIOR a hoje
        if ($this->params->query['data'] > date('Y-m-d')) {
            $permiteCheckinAdiantado = Configure::read('Checkin.permitir_adiantado');
            //Se NÃO permite checkin adiantado
            if (!$permiteCheckinAdiantado) {
                $bloqueiaCheckinAdiantado = true;
            }
        }
        $this->set('bloqueiaCheckinAdiantado', $bloqueiaCheckinAdiantado);

        $bloqueiaCheckinAtrasado = false;
        //Verifica se a data do ticket é MENOR que hoje
        if ($this->params->query['data'] < date('Y-m-d')) {
            $permiteCheckinAtrasado = Configure::read('Checkin.permitir_atrasado');
            //Se NÃO permite checkin atrasado
            if (!$permiteCheckinAtrasado) {
                $bloqueiaCheckinAtrasado = true;
            }
        }
        $this->set('bloqueiaCheckinAtrasado', $bloqueiaCheckinAtrasado);
    }

    function _checkinByTicket($ticketId)
    {
        //Busca os dados do pedido
        $this->loadModel('Ticket');
        $this->data = $this->Ticket->find(
            'first',
            array(
                'conditions' => array(
                    'Ticket.id' => $ticketId
                ),
                'contain' => array(
                    'Order' => array(
                        'id',
                        'status',
                        'event_id'
                    ),
                    'Event' => array(
                        'id',
                        'title',
                        'status'
                    ),
                    'Checkin' => array(
                        'created',
                        'User' => array(
                            'name'
                        )
                    )
                )
            )
        );

        $checkinExists = false;
        //Verifica se o checkin já foi feito
        if ($this->Checkin->checkinExists($ticketId)) {
            $checkinExists = true;
        }
        $this->set('checkinExists', $checkinExists);

        $bloqueiaCheckinAdiantado = false;
        //Verifica se a data do ticket é MAIOR a hoje
        if ($this->data['Ticket']['modalidade_data'] > date('Y-m-d')) {
            $permiteCheckinAdiantado = Configure::read('Checkin.permitir_adiantado');
            //Se NÃO permite checkin adiantado
            if (!$permiteCheckinAdiantado) {
                $bloqueiaCheckinAdiantado = true;
            }
        }
        $this->set('bloqueiaCheckinAdiantado', $bloqueiaCheckinAdiantado);

        $bloqueiaCheckinAtrasado = false;
        //Verifica se a data do ticket é MENOR que hoje
        if ($this->data['Ticket']['modalidade_data'] < date('Y-m-d')) {
            $permiteCheckinAtrasado = Configure::read('Checkin.permitir_atrasado');
            //Se NÃO permite checkin atrasado
            if (!$permiteCheckinAtrasado) {
                $bloqueiaCheckinAtrasado = true;
            }
        }
        $this->set('bloqueiaCheckinAtrasado', $bloqueiaCheckinAtrasado);
    }

    public function admin_checkins($eventId)
    {
        $this->layout = 'ajax';
        $presentes = $this->Checkin->find(
            'all',
            array(
                'conditions' => array(
                    'DATE(Checkin.created)' => date('Y-m-d'),
                    'Checkin.event_id' => $eventId
                ),
                'contain' => array(
                    'Ticket' => [
                        'id',
                        'event_id',
                        'nome',
                        'cpf',
                        'telefone',
                        'modalidade_nome'
                    ]
                ),
                'fields' => array(
                    'id',
                    'created'
                ),
                'order' => array(
                    'created DESC'
                )
            )
        );
        // pr($presentes);exit();
        $this->set('presentes', $presentes);
    }

    public function admin_delete($id)
    {
        $this->autoRender = false;
        $arrayReturn = array(
            'success' => false,
            'message' => 'Não foi possível remover o checkin.',
        );
        if ($this->Checkin->delete($id)) {
            $arrayReturn['success'] = true;
        }
        return json_encode($arrayReturn);
    }
}
