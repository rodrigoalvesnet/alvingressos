<?php
class CheckinsController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function admin_index() {}

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

    public function admin_checkin_manual($orderId)
    {
        $this->autoRender = false;
        $arrayReturn = array(
            'success' => false,
            'message' => 'Não foi possível encontrar a inscrição.',
        );
        //Verifica se o checkin já foi feito
        if ($this->Checkin->checkinExists($orderId)) {
            $arrayReturn['message'] = 'O checkin já foi feito!';
            return json_encode($arrayReturn);
        }
        //Busca os dados do pedido
        $this->loadModel('Order');
        $order = $this->Order->find(
            'first',
            array(
                'conditions' => array(
                    'id' => $orderId,
                    'status' => 'approved'
                ),
                'recursive' => -1,
                'fields' => array(
                    'event_id'
                )
            )
        );

        if (!empty($order)) {
            $dataSave = array(
                'order_id' => $$orderId,
                'event_id' => $order['Order']['event_id'],
                'user_id' => AuthComponent::user('id'),
            );
            if ($this->Checkin->save($dataSave)) {
                $arrayReturn['success'] = true;
            }
        }
        return json_encode($arrayReturn);
    }

    public function admin_check($ticketId)
    {

        $this->layout = 'ajax';

        //Busca os dados do pedido
        $this->loadModel('Order');
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
        // pr($this->data);
        // $this->data = $this->Order->find(
        //     'first',
        //     array(
        //         'conditions' => array(
        //             'Order.id' => $orderId
        //         ),
        //         'contain' => array(
        //             'Unidade' => array(
        //                 'id',
        //                 'name'
        //             ),
        //             'Response' => array(
        //                 'response',
        //                 'Field' => array(
        //                     'question'
        //                 )
        //             ),
        //             'Event' => array(
        //                 'title',
        //                 'status'
        //             ),
        //             'Checkin' => array(
        //                 'created',
        //                 'User' => array(
        //                     'name'
        //                 )
        //             )
        //         ),
        //         'fields' => array(
        //             'id',
        //             'event_id',
        //             'name',
        //             'cpf',
        //             'email',
        //             'birthday',
        //             'payment_type',
        //             'value',
        //             'status',
        //             'reason'
        //         )
        //     )
        // );
        $checkinExists = false;
        //Verifica se o checkin já foi feito
        if ($this->Checkin->checkinExists($ticketId)) {
            $checkinExists = true;
        }
        $this->set('checkinExists', $checkinExists);
    }

    public function admin_checkins($eventId)
    {
        $this->layout = 'ajax';
        $presentes = $this->Checkin->find(
            'all',
            array(
                'conditions' => array(
                    'Checkin.event_id' => $eventId
                ),
                'contain' => array(
                    'Order' => array(
                        'event_id',
                        'name',
                        'cpf',
                        'phone',
                        'Unidade' => array(
                            'name'
                        )
                    )
                ),
                'fields' => array(
                    'id',
                    'order_id',
                    'created'
                ),
                'order' => array(
                    'created DESC'
                )
            )
        );
        $this->set('presentes', $presentes);
    }

    public function admin_subscribeds($eventId)
    {
        $this->layout = 'ajax';
        $this->loadModel('Order');
        $subscribeds = $this->Order->find(
            'all',
            array(
                'conditions' => array(
                    'Order.event_id' => $eventId,
                    'Checkin.id IS NULL'
                ),
                'contain' => array(
                    'Checkin' => array(
                        'id',
                        'order_id',
                        'created'
                    ),
                    'Unidade' => array(
                        'name'
                    )
                ),
                'fields' => array(
                    'event_id',
                    'name',
                    'cpf',
                    'phone',
                ),
                'order' => array(
                    'Order.name ASC'
                )
            )
        );
        $this->set('subscribeds', $subscribeds);
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
