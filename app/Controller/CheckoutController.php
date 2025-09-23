<?php
class CheckoutController extends AppController
{

    public $components = array('Session', 'RequestHandler', 'Cart', 'Asaas', 'Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Checkout');
        $this->Auth->allow(array(
            'cart',
            'payment'
        ));
    }

    public function payment()
    {
        
        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';
        //Quando posta
        if ($this->request->is('post')) {
            $resultPayment = $this->_sendPayment($this->data);
            //Se salvar corretamente
            if ($resultPayment['success']) {
                $this->Session->delete('Cart');
                $this->Flash->success('Compra efetuada com sucesso!');
                $this->redirect(array(
                    'controller' => 'Orders',
                    'action' => 'view',
                    $resultPayment['order_id']
                ));
            } else {
                $this->Flash->error($resultPayment['message']);
                return $this->redirect($this->referer());
            }
        }

        $cart = $this->Cart->checkCart();

        if ($cart === false) {
            $this->Session->setFlash('Seu carrinho expirou. Adicione os itens novamente.');
            return $this->redirect('/');
        }

        $this->Session->write('Cart.url_referer', '/checkout/payment');

        $this->loadModel('Event');
        $this->loadModel('Lot');
        $ingressos = [];
        foreach ($cart['cart'] as $eventId => $tickets) {
            //Pega os dados do Evento
            $evento = $this->Event->find(
                'first',
                array(
                    'conditions' => array(
                        'id' => $eventId
                    ),
                    'fields' => [
                        'id',
                        'title'
                    ],
                    'recursive' => -1
                )
            );
            $ingressos['cart']['eventos'][$eventId]['id'] = $evento['Event']['id'];
            $ingressos['cart']['eventos'][$eventId]['title'] = $evento['Event']['title'];
            $ingressos['cart']['eventos'][$eventId]['event_id'] = $eventId;
            foreach ($tickets['ingressos'] as $date => $pessoas) {
                foreach ($pessoas as $pessoa) {
                    //Busca o valor da modalidade
                    $modalidade = $this->Lot->find('first', [
                        'conditions' => [
                            'id' => $pessoa['modalidade']
                        ],
                        'fields' => [
                            'id',
                            'value',
                            'name'
                        ],
                        'recursive' => -1
                    ]);
                    $ingressos['cart']['eventos'][$eventId]['ingressos'][$date][] = [
                        'nome' => $pessoa['nome'],
                        'modalidade_id' => $pessoa['modalidade'],
                        'modalidade_nome' => $modalidade['Lot']['name'],
                        'modalidade_valor' => $modalidade['Lot']['value']
                    ];
                }
            }
        }

        $paymentsType = Configure::read('Site.pagamentos');
        foreach ($paymentsType as $key => $value) {
            $optionsPayment[$key] = $value['label'];
        }

        $this->set('optionsPayment', $optionsPayment);
        $this->set('ingressos', $ingressos['cart']);
        $this->set('remaining', $cart['remaining']);
    }

    function _sendPayment($dados)
    {
        $result = [
            'success' => false,
            'message' => ''
        ];
        // pr($dados);
        // exit();
        $this->loadModel('Order');
        //Cria o pedido
        $createNewOrder = $this->Order->newOrder($dados, true);
        //Gerar Descrição para o Asaas
        $description = $this->_gerarAsaasDescription($createNewOrder['Order']['id'], $this->data);

        //Substitui o Order do this->data
        unset($dados['Order']);
        $dados['Order'] =  $createNewOrder['Order'];
        $dados['Order']['description'] = $description;

        //Se salvar corretamente
        if ($createNewOrder['success']) {
            //Cria o pedido no Asaas
            $invoiceCreated = $this->Asaas->createOrder($dados['Order']['id'], $dados);

            if ($invoiceCreated['success']) {
                //Cria o tickets
                $this->_gerarTickets($dados['Order']['id'], $this->data);
                $result['order_id'] = $dados['Order']['id'];
                // $this->Session->delete('Cart');
                $result['success'] = true;
            } else {
                $this->Order->changeStatus($dados['Order']['id'], 'canceled');
                $result['message'] = $invoiceCreated['message'];
            }
        } else {
            $this->Flash->error($dados['message']);
            $result['message'] = 'Não foi possível gerar o pedido.';
        }
        return $result;
    }

    function get_installments()
    {
        $this->layout = 'ajax';
        $this->theme = Configure::read('Site.tema');

        $paymentsType = Configure::read('Site.pagamentos');

        $this->set('paymentsType', $paymentsType);

        $price = 0;
        foreach ($this->data['Checkout'] as $checkout) {
            foreach ($checkout as $eventId => $data) {
                foreach ($data as $ingresso) {
                    $price +=  $ingresso['modalidade_valor'];
                }
            }
        }

        $this->set('price', $price);
    }

    function _gerarAsaasDescription($orderId, $dados)
    {
        $description = 'Pedido: ' . $orderId;
        $ingressosCount = 0;
        foreach ($dados['Checkout'] as $eventId => $checkout) {
            foreach ($checkout as $data => $ingressos) {
                foreach ($ingressos as $pessoa) {
                    $ingressosCount++;
                    $description .= ' - ' . $pessoa['nome'] . ' (' . $pessoa['modalidade_nome'] . ')';
                }
            }
        }
        $description .= ' - Totalizando ' . $ingressosCount . ' ingresso(s)';
        return $description;
    }

    function _gerarTickets($orderId, $dados)
    {
        $this->loadModel('Ticket');

        foreach ($dados['Checkout'] as $eventId => $checkout) {
            foreach ($checkout as $data => $ingressos) {
                foreach ($ingressos as $pessoa) {
                    $arraySave = [
                        'id' => null,
                        'order_id' => $orderId,
                        'event_id' => $eventId,
                        'nome' => $pessoa['nome'],
                        'cpf' => $pessoa['cpf'],
                        'email' => $pessoa['email'],
                        'telefone' => $pessoa['telefone'],
                        'modalidade_id' => $pessoa['modalidade_id'],
                        'modalidade_nome' => $pessoa['modalidade_nome'],
                        'modalidade_valor' => $pessoa['modalidade_valor'],
                        'modalidade_data' => $this->Alv->tratarData($data)
                    ];
                    $this->Ticket->save($arraySave);
                }
            }
        }
    }
}
