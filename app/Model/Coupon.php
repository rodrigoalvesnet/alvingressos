<?php
class Coupon extends AppModel
{
    public $belongsTo = array(
        'Event'
    );

    public $validate = array(
        'end_date' => array(
            'depoisDoInicio' => array(
                'rule' => 'validateEndDate',
                'allowEmpty' => true,
                'message' => 'A data final deve ser igual ou posterior à data inicial.'
            )
        )
    );

    public function validateEndDate($check)
    {
        if (empty($this->data['Coupon']['start_date'])) {
            return true;
        }
        return current($check) >= $this->data['Coupon']['start_date'];
    }

    public function afterSave($created, $options = array())
    {
        //limpa o cache
        Cache::clear(false, 'Coupons');
    }

    /**
     * Verifica se o cupom pode ser usado no evento pelo usuário.
     * Respeita o período de validade (start_date/end_date, ambos opcionais).
     * Se o cupom está marcado como "somente uma vez por usuário" (unique_by_user),
     * bloqueia quando já existe pedido não cancelado com o cupom para o mesmo
     * usuário OU para o mesmo CPF.
     *
     * @param array $conditions Condições de busca do cupom (ex: ['Coupon.code' => 'X'] ou ['Coupon.id' => 1])
     * @return array ['success' => bool, 'message' => string, 'coupon' => array|null]
     */
    public function validateUse($conditions, $eventId, $userId = null, $cpf = null)
    {
        $result = array(
            'success' => false,
            'message' => '',
            'coupon' => null
        );

        $conditions['Coupon.event_id'] = $eventId;
        $coupon = $this->find('first', array(
            'conditions' => $conditions,
            'fields' => array('id', 'code', 'unique_by_user', 'start_date', 'end_date'),
            'recursive' => -1
        ));

        //Se NÃO encontrou o cupom
        if (empty($coupon)) {
            $result['message'] = 'Cupom não localizado.';
            return $result;
        }

        //Período de validade (datas vazias = sem limite)
        $hoje = date('Y-m-d');
        if (!empty($coupon['Coupon']['start_date']) && $hoje < $coupon['Coupon']['start_date']) {
            $result['message'] = 'O cupom <strong>' . h($coupon['Coupon']['code']) . '</strong> só pode ser usado a partir de ' . date('d/m/Y', strtotime($coupon['Coupon']['start_date'])) . '.';
            return $result;
        }
        if (!empty($coupon['Coupon']['end_date']) && $hoje > $coupon['Coupon']['end_date']) {
            $result['message'] = 'O cupom <strong>' . h($coupon['Coupon']['code']) . '</strong> expirou em ' . date('d/m/Y', strtotime($coupon['Coupon']['end_date'])) . '.';
            return $result;
        }

        //Se o cupom só pode ser usado uma vez por usuário
        if (!empty($coupon['Coupon']['unique_by_user'])) {
            $owner = array();
            if (!empty($userId)) {
                $owner['Order.user_id'] = $userId;
            }
            if (!empty($cpf)) {
                $owner['Order.cpf'] = $cpf;
            }
            if (!empty($owner)) {
                App::uses('Order', 'Model');
                $Order = new Order();
                $used = $Order->find('count', array(
                    'conditions' => array(
                        'Order.coupon_id' => $coupon['Coupon']['id'],
                        'Order.status !=' => 'canceled',
                        'OR' => $owner
                    ),
                    'recursive' => -1
                ));
                if ($used > 0) {
                    $result['message'] = 'O cupom <strong>' . h($coupon['Coupon']['code']) . '</strong> já foi utilizado e só pode ser usado uma vez por usuário.';
                    return $result;
                }
            }
        }

        $result['success'] = true;
        $result['coupon'] = $coupon;
        return $result;
    }
}
