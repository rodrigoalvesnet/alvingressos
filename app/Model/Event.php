<?php
class Event extends AppModel
{

    public $validate = array(
        'title' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Campo obrigatório'
            )
        ),
        'slug' => array(
            'rule' => array('isUnique'),
            'message' => 'Já existe um registro com este nome'
        )
    );

    public $hasMany = array(
        'Talker',
        'Schedule',
        'Mod',
        'Lot',
        'Field',
        'Order',
        'Coupon',
        'Product',
        'EventsDate'
    );

    public $belongsTo = array(
        'Unidade'
    );


    public $hasAndBelongsToMany = array(
        'User',
        'Admin' => array(
            'className' => 'User',
            'joinTable' => 'events_admins',
            // 'foreignKey' => 'user_id',
        )
    );

    public function afterSave($created, $options = array())
    {
        //limpa o cache
        Cache::clear(false, 'Events');
    }

    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['title'])) {
            $this->data[$this->alias]['slug'] = Inflector::slug(strtolower($this->data[$this->alias]['title']), '-');
        }
        return true;
    }


    public function checkPermission($id)
    {
        //Pega o ID do usuário logado
        $userId = AuthComponent::user('id');
        $roleId = AuthComponent::user('role_id');
        //Se administrado
        if ($roleId == 1) {
            return true;
        }
        //Pega os dados do evento
        $event = $this->find(
            'first',
            array(
                'conditions' => array(
                    'Event.id' => $id
                ),
                'contain' => array(
                    'User' => array(
                        'id'
                    ),
                    'Admin' => array(
                        'id'
                    ),

                ),
                'fields' => array(
                    'id',
                    'user_id'
                )
            )
        );
        //Se encontrou o evento
        if (!empty($event)) {
            //Se o usuário logado é o criador do evento
            if ($event['Event']['user_id'] == $userId) {
                return true;
            }
            //Se o usuário logado tem permissões no evento do pedido
            foreach ($event['User'] as $userEvent) {
                //Se encontrou o usuário na lista de permissões
                if ($userEvent['id'] == $userId) {
                    return true;
                }
            }
            //Se o usuário logado tem permissões no evento do pedido
            foreach ($event['Admin'] as $userEvent) {
                //Se encontrou o usuário na lista de permissões
                if ($userEvent['id'] == $userId) {
                    return true;
                }
            }
        }
        return false;
    }

    function checkAvailableLot($eventId)
    {
        //Pega a quantidade vendida
        App::uses('Order', 'Model');
        $Order = new Order();
        $ordersSold = $Order->ordersByEvent($eventId);

        //Pega quantidade de tickets disponibilizados pelos lotes
        $totalTotalTicketsAvailable = $this->getTotalTotalTicketsAvailable($eventId);

        //Se ainda pode vender ingressos
        if ($ordersSold < $totalTotalTicketsAvailable) {

            //Verifica quais são os lotes
            App::uses('Lot', 'Model');
            $Lot = new Lot();
            $lots = $Lot->find(
                'all',
                array(
                    'conditions' => array(
                        'event_id' => $eventId
                    ),
                    'recursive' => -1,
                    'order' => 'start_date ASC'
                )
            );

            //Se encontrou lotes
            if (!empty($lots)) {
                $quantityByLote = 0;
                foreach ($lots as $lot) {
                    $quantityByLote += $lot['Lot']['quantity'];
                    //Se está dentro da quantidade permitida do lote
                    if ($ordersSold < $quantityByLote) {
                        if ($lot['Lot']['end_date'] >= date('Y-m-d')) {
                            return $lot['Lot']['id'];
                        }
                    }
                }
            }
        } else {
            $event = $this->find(
                'first',
                array(
                    'conditions' => array(
                        'id' => $eventId
                    ),
                    'fields' => array(
                        'id',
                        'status'
                    ),
                    'recursive' => -1
                )
            );
            //Se o evento está agendado
            if ($event['Event']['status'] == 'scheduled' && $totalTotalTicketsAvailable > 0) {
                //Atualiza o estaus para Esgotado
                $this->setSoldOut($eventId);
            }
        }
        return false;
    }

    function getTotalTotalTicketsAvailable($eventId)
    {
        //Verifica quais são os lotes
        App::uses('Lot', 'Model');
        $Lot = new Lot();
        $lots = $Lot->find(
            'all',
            array(
                'fields' => array(
                    'SUM(quantity) AS total'
                ),
                'conditions' => array(
                    'event_id' => $eventId
                ),
                'recursive' => -1
            )
        );
        return $lots[0][0]['total'] ? $lots[0][0]['total'] : 0;
    }

    function setSoldOut($eventId)
    {
        //Atualiza o estaus para Esgotado
        $this->updateAll(
            array(
                'Event.status' => "'soldoff'",
                'Event.modified' => "'" . date('Y-m-d H:i:s') . "'"
            ),
            array(
                'Event.id' => $eventId
            )
        );
    }

    function getBlockedDates($id, $future = false, $json = true)
    {
        App::uses('EventsDate', 'Model');
        $EventsDate = new EventsDate();

        $conditions = ['event_id' => $id]; // normalmente é event_id
        if ($future) {
            $conditions['DATE(date) >='] = date('Y-m-d');
        }

        // Pega só os valores da coluna 'date' como array simples
        $results = $EventsDate->find('all', [
            'conditions' => $conditions,
            'fields' => ['date'],
            'recursive' => -1
        ]);

        // Extrai os valores para um array simples
        $dates = [];
        foreach ($results as $r) {
            $dates[] = $r['EventsDate']['date'];
        }

        if($json){
            return json_encode($dates); // já retorna JSON pronto para o JS
        }
        return $dates;
    }
}
