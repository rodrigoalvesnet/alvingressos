<?php
class EcdController extends AppController
{

    var $helpers = array('Alv');
    var $components = array('Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('etiquetas');
    }

    public function etiquetas($eventId, $status = 'all', $gender = 'female', $laranjinhas = false)
    {
        $this->loadModel('Order');
        $conditions = array(
            'Order.event_id' => $eventId,
            'Order.status' => array(
                'approved',
                'pending'
            )
        );
        if ($status != 'all') {
            $conditions['Order.status'] = $status;
        }

        $orders = $this->Order->find(
            'all',
            array(
                'conditions' => $conditions,
                'contain' => array(
                    'Unidade' => array(
                        'name'
                    ),
                    'Coupon' => array(
                        'id'
                    ),
                    'Response' => array(
                        'conditions' => array(
                            'response' => $gender == 'female' ? 'Feminino' : 'Masculino'
                        )
                    )
                ),
                'fields' => array(
                    'name'
                ),
                'order' => 'Order.id ASC'
            )
        );
        
        $arrayResult = array();
        $counter = 1;
        if (!empty($orders)) {
            foreach ($orders as $order) {
                if (!empty($order['Response'])) {
                    if ($laranjinhas) {
                        if (empty($order['Coupon']['id'])) {
                            continue;
                        }
                    } else {
                        if (!empty($order['Coupon']['id'])) {
                            continue;
                        }
                    }
                    $nameArray = explode(' ', trim($order['Order']['name']));
                    $name = '';
                    if (!$laranjinhas) {
                        $name .= $counter . ' - ';
                    }
                    $name .= $nameArray[0];
                    if (isset($nameArray[1])) {
                        $name .= ' ' . $nameArray[1];
                    }
                    if (isset($nameArray[2]) && (strlen(trim($nameArray[1])) == 2 || strlen(trim($nameArray[1])) == 3)) {
                        $name .= ' ' . $nameArray[2];
                    }
                    $arrayResult[$counter]['name'] = strtoupper($name);
                    $arrayResult[$counter]['church'] = $order['Unidade']['name'];
                    $counter++;
                }
            }
        }

        //Tamanho da etiqueta em "points" - 1 centimetro é igual a 28.34 points
        //5,5cm por 2,5cm
        // $customPaper = array(0, 0, 615.18, 793.70);
        $customPaper = 'letter';
        $this->set('customPaper', $customPaper);
        $this->layout = 'pdf';
        $this->set('pessoas', $arrayResult);
    }
}
