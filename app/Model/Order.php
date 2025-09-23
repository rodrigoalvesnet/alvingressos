<?php

use Illuminate\Support\Facades\Auth;

class Order extends AppModel
{

    public $belongsTo = array(
        'Unidade',
        'User',
        'Lot',
        'Event',
        'Coupon',
    );

    public $hasMany = array(
        'Response',
        'Attachment',
        'Ticket' => array(
            'dependent'  => true,
            'order'      => 'Ticket.modalidade_data ASC'
        )
    );

    public $hasOne = array(
        'Checkin'
    );

    public $hasAndBelongsToMany = array(
        'Product'
    );

    public function afterSave($created, $options = array())
    {
        //Se está criando
        // if($created){
        //     $this->sendVoucher($this->data['Order']['id']);
        // }
        //limpa o cache
        Cache::clear(false, 'Orders');
    }

    public function newOrder($dados, $checkout = false)
    {
        $result = [
            'success' => false,
            'message' => 'Não foi possível salvar o pedido'
        ];

        //tratar os dados
        $orderSave = [];
        $orderSave['status'] = 'pending';
        $orderSave['user_id'] = AuthComponent::user('id');
        if ($checkout) {
            $orderSave['unidade_id'] = 1; //SEMPRE VAI PEGAR A UNIDADE PRINCIAPAL
        }
        //Se está comprando para ele mesmo
        if (!isset($dados['other']) || (isset($dados['other']) && !$dados['other'])) {
            //Pega os dados de quem está comprando
            $userLogged = AuthComponent::user();
            $orderSave['name'] = $userLogged['name'];
            $orderSave['cpf'] = $userLogged['cpf'];
            $orderSave['birthday'] = $userLogged['birthday'];
            $orderSave['phone'] = $userLogged['phone'];
            $orderSave['email'] = $userLogged['email'];
        }

        $orderSave['event_id'] = $dados['Order']['event_id'];

        //Se foi informado o aniversário nos campos adicionais
        if (isset($orderSave['birthday']) && !empty($orderSave['birthday'])) {
            // $orderSave['birthday'] = $this->Alv->tratarData($dados['birthday']);
        }
        //Se não foi informado a igreja nos campos adicionais, pega do usuário logado
        $orderSave['unidade_id'] = AuthComponent::user('unidade_id');
        //Verifica se já foi comprado anteriormente
        $checkDuplicidade = $this->checkDuplicidade($dados, $checkout);

        //Se ainda não foi comprado
        if ($checkDuplicidade['status']) {

            //Verifica se tem produtos adicionados
            if (isset($dados['Order']['products_total']) && !empty($dados['Order']['products_total'])) {
                $orderSave['value'] = ($orderSave['value'] + $dados['Order']['products_total']);
            }

            //Se tem desconto
            if (isset($dados['Order']['coupon_id']) && !empty($dados['Order']['coupon_id'])) {
                //Verifica se tem desconto
                $orderSave['value']  = $this->applyDiscount(
                    $dados['Order']['value'],
                    $dados['Order']['coupon_id']
                );
            }

            //Verifica se tem acréscimos e etc
            $orderSave['value'] = $this->_getPrice($dados);

            $orderSave['installments'] = $dados['Order']['installments'];
            $orderSave['payment_type'] = $dados['Order']['payment_type'];

            //Valcula os valores das parcelas
            $orderSave['installment_value'] = $this->installmentValue($orderSave['value'], $orderSave['installments']);

            //Se é GRATUITO
            if ($orderSave['payment_type'] == 'free') {
                $orderSave['status'] = 'approved';
            }

            //Se salvar corretamente
            $this->create();
            if ($this->saveAll($orderSave)) {
                //Pega o ID da ordem salva

                $orderId = $this->getLastInsertId();

                //Se tem produtos
                if (isset($orderSave['OrdersProduct']) && !empty($orderSave['OrdersProduct'])) {
                    $arrayProducts = array();
                    foreach ($orderSave['OrdersProduct'] as $k => $product) {
                        if ($product['quantity'] > 0) {
                            $arrayProducts[$k] = $product;
                            $arrayProducts[$k]['id'] = null;
                            $arrayProducts[$k]['order_id'] = $orderId;
                        }
                    }
                    if (!empty($arrayProducts)) {
                        //Salva os produtos
                        App::uses('OrdersProduct', 'Model');
                        $OrdersProduct = new OrdersProduct();
                        $OrdersProduct->saveAll($arrayProducts);
                    }
                }
                $orderSave['id'] = $orderId;
                $result['success'] = true;
                $result['Order'] = $orderSave;
                $result['message'] = 'Pedido criado com sucesso!';
            }
        } else {
            $result['success'] = $checkDuplicidade['message'];
        }

        return $result;
    }

    function _getPrice($order)
    {
        // pr($order);exit();
        //Pega os dados do lote
        if (isset($order['Order']['lot_id'])) {
            App::uses('Lot', 'Model');
            $LotModel = new Lot();
            $lot = $LotModel->find(
                'first',
                array(
                    'conditions' => array(
                        'id' => $order['Order']['lot_id']
                    ),
                    'recursive' => -1
                )
            );
            //Se encontrou o lote
            if (!empty($lot)) {
                //Valor do lote
                // $value = $lot['Lot']['value'];
                $value = $order['Order']['value']; //Pega do que veio do form, por causa do desconto
                //Pega as informações de pagamento
                $paymentsType = unserialize($lot['Lot']['payments_type']);
                if (!empty($paymentsType)) {
                    $paymentConfig = $paymentsType[$order['Order']['payment_type']];
                    //Se tem acrescimo
                    if (isset($paymentConfig['tax_type']) && !empty($paymentConfig['tax_type'])) {
                        //Se o tipo de acrescimo é SEMPRE:
                        if ($paymentConfig['tax_type'] == 1) {
                            return ($value + $paymentConfig['tax_value']);
                        }
                        //Se o tipo de acrescimo é somente se for parcelado:
                        if ($paymentConfig['tax_type'] == 2) {
                            //Se a for acima da parcela 2:
                            if ($order['Order']['installments'] > 1) {
                                return ($value + $paymentConfig['tax_value']);
                            }
                        }
                    }
                }
                return $value;
            }
        } else {
            return $order['Order']['value'];
        }
        return false;
    }

    public function installmentValue($price, $installments)
    {
        //Se tem mais de uma parcela
        if ($installments > 1) {
            $value = ($price / $installments);
            return number_format($value, 2, '.', '');
        }
        return $price;
    }

    public function changeStatus($orderId, $status, $reason = '')
    {
        $arraySave = array(
            'id' => $orderId,
            'status' => $status,
            'reason' => $reason
        );
        //Alterar o status
        if ($this->save($arraySave)) {
            $this->sendVoucher($orderId);
            return true;
        } else {
            $this->log('Erro ao alterar o status da ordem ' . $orderId);
            return false;
        }
    }

    public function ordersByEvent($eventId = null)
    {
        App::uses('Event', 'Model');
        $Event = new Event();
        $conditions = array();
        if ($eventId) {
            $conditions = array(
                'id' => $eventId
            );
        }
        $events = $Event->find(
            'all',
            array(
                'conditions' => $conditions,
                'fields' => array(
                    'id'
                ),
                'order' => 'id ASC',
                'recursive' => -1
            )
        );

        $arrayEvents = array();
        //Se encontrou registros
        if (!empty($events)) {
            foreach ($events as $event) {
                $arrayEvents[$event['Event']['id']] = $event['Event']['id'];
            }
        }

        $orders = $this->find(
            'all',
            array(
                'fields' => array(
                    'event_id',
                    'COUNT(id) as total'
                ),
                'conditions' => array(
                    'status' => array('pending', 'approved'),
                    'event_id' => $arrayEvents
                ),
                'recursive' => -1,
                'group' => 'event_id'
            )
        );

        $arrayOrders = array();

        foreach ($orders as $order) {
            $arrayOrders[$order['Order']['event_id']] = $order[0]['total'];
        }
        //Se foi infomado o id do evento
        if ($eventId) {
            //Se não foi vendido nenhum ingresso
            if (empty($arrayOrders)) {
                return 0;
            }
            return $arrayOrders[$eventId];
        }

        return $arrayOrders;
    }

    public function checkPermission($id)
    {
        //Pega o ID do usuário logado
        $userId = AuthComponent::user('id');
        $roleId = AuthComponent::user('role_id');
        $cpf = AuthComponent::user('cpf');
        //Se administrado
        if ($roleId == 1) {
            return true;
        }
        //Pega os dados do pedido
        $order = $this->find(
            'first',
            array(
                'conditions' => array(
                    'Order.id' => $id
                ),
                'contain' => array(
                    'Event' => array(
                        'User' => array(
                            'id'
                        ),
                        'Admin' => array(
                            'id'
                        ),
                    )
                ),
                'fields' => array(
                    'id',
                    'user_id',
                    'cpf'
                )
            )
        );

        //Se encontrou o pedido
        if (!empty($order)) {
            //Se o usuário logado é o criador do pedido ou tem o mesmo cpf
            if ($order['Order']['user_id'] == $userId || $order['Order']['cpf'] == $cpf) {
                return true;
            }
            //Se o usuário logado tem permissões no evento do pedido
            foreach ($order['Event']['Admin'] as $userEvent) {
                //Se encontrou o usuário na lista de permissões
                if ($userEvent['id'] == $userId) {
                    return true;
                }
            }
        }
        return false;
    }

    public function checkDuplicidade($data, $checkout)
    {

        $arrayReturn = array(
            'status' => true,
            'message' => ''
        );
        if ($checkout) {
            return $arrayReturn;
        }
        //Busca pedidos para este evento usando o mesmo cpf
        $pedidos = $this->find(
            'first',
            array(
                'conditions' => array(
                    'event_id' => $data['Order']['event_id'],
                    'cpf' => $data['Order']['cpf'],
                    'status' => array(
                        'pending',
                        'approved'
                    )
                ),
                'recursive' => -1,
                'fields' => array(
                    'id',
                    'created',
                    'status'
                ),
                'order' => 'id DESC'
            )
        );
        //Se encontoru uma venda
        if (!empty($pedidos)) {
            $message = 'Já existe a compra <a href="/orders/view/' . $pedidos['Order']['id'] . '"><strong>#' . $pedidos['Order']['id'] . '</strong></a> para este CPF feita no dia <strong>' . date('d/m/Y', strtotime($pedidos['Order']['created'])) . ' às ' . date('H:i', strtotime($pedidos['Order']['created'])) . '</strong>';
            $arrayReturn['status'] = false;
            $arrayReturn['message'] = $message;
        }
        return $arrayReturn;
    }

    function applyDiscount($value, $couponId)
    {
        if ($couponId) {
            //Verifica se o cupom existe
            App::uses('Coupon', 'Model');
            $Coupon = new Coupon();
            $cupom = $Coupon->find(
                'first',
                array(
                    'conditions' => array(
                        'id' => $couponId
                    ),
                    'recursive' => -1
                )
            );

            //Se o cupom existe
            if (!empty($cupom)) {
                //Se o dsconto é por percentual
                if ($cupom['Coupon']['type'] == 'percent') {
                    $valueDiscount = ($value / 100 * $cupom['Coupon']['percent']);
                    $valueDiscount = number_format($valueDiscount, 2, '.', '');
                    $value = ($value - $valueDiscount);
                } else {
                    $value = ($value - $cupom['Coupon']['value']);
                }
            }
        }
        return $value;
    }

    function sendVoucher($orderId, $test = false)
    {
        $sendEmails = Configure::read('Orders.sendEmails');
        if ($sendEmails) {
            //Pega os dados do usuário
            $order = $this->find(
                'first',
                array(
                    'conditions' => array(
                        'Order.id' => $orderId
                    ),
                    'contain' => array(
                        'Event' => array(
                            'title'
                        )
                    ),
                    'fields' => array(
                        'id',
                        'reason',
                        'status',
                        'invoice_url',
                        'invoice_boleto',
                        'invoice_receipt',
                        'name',
                        'email'
                    )
                )
            );

            //Prepara os dados do email
            $arrayDadosEmail = array(
                'nome' => Configure::read('Sistema.title')
            );

            $status = $order['Order']['status'];
            $arrayAttachments = array();
            //Trata de acordo com cada situação
            switch ($status) {
                case 'pending':
                    $linkInvoice = 'https://kinderpark.com.br/Orders/view/' . $order['Order']['id'];
                    //Se a cobrança é pelo Asaas
                    if (!empty($order['Order']['invoice_url'])) {
                        $linkInvoice = $order['Order']['invoice_url'];
                    }
                    $arrayDadosEmail['assunto'] =  Configure::read('Orders.mail_pending_subject');
                    $arrayDadosEmail['mensagem'] =  str_replace(
                        array(
                            '{{orderId}}',
                            '{{userName}}',
                            '{{eventTitle}}',
                            '{{linkInvoice}}'
                        ),
                        array(
                            $orderId,
                            $order['Order']['name'],
                            $order['Event']['title'],
                            $linkInvoice
                        ),
                        Configure::read('Orders.mail_pending_body')
                    );
                    break;
                case 'approved':
                    $enviarAnexo = Configure::read('Orders.sendAttachments');
                    if ($enviarAnexo) {
                        // Gera o PDF em memória
                        $pdfBytes = $this->gerarPdf($orderId);

                        // Configura os anexos
                        $arrayAttachments = [
                            'ticket-' . $order['Order']['id'] . '.pdf' => [
                                'data' => $pdfBytes,
                                'mimetype' => 'application/pdf'
                            ]
                        ];
                    }

                    $arrayDadosEmail['assunto'] =  Configure::read('Orders.mail_approved_subject');
                    $tagImgQrcode = $this->getImgQrCodeCheckin($order['Order']['id']);
                    $arrayDadosEmail['mensagem'] =  str_replace(
                        array(
                            '{{orderId}}',
                            '{{userName}}',
                            '{{eventTitle}}',
                            '{{imgQrcode}}'
                        ),
                        array(
                            $orderId,
                            $order['Order']['name'],
                            $order['Event']['title'],
                            $tagImgQrcode
                        ),
                        Configure::read('Orders.mail_approved_body')
                    );
                    break;
                case 'rejected':
                    $arrayDadosEmail['assunto'] =  Configure::read('Orders.mail_rejected_subject');
                    $arrayDadosEmail['mensagem'] =  str_replace(
                        array(
                            '{{orderId}}',
                            '{{userName}}',
                            '{{eventTitle}}',
                            '{{reason}}'
                        ),
                        array(
                            $orderId,
                            $order['Order']['name'],
                            $order['Event']['title'],
                            $order['Order']['reason']
                        ),
                        Configure::read('Orders.mail_rejected_body')
                    );
                    break;
                case 'canceled':
                    $arrayDadosEmail['assunto'] =  Configure::read('Orders.mail_canceled_subject');
                    $arrayDadosEmail['mensagem'] =  str_replace(
                        array(
                            '{{orderId}}',
                            '{{userName}}',
                            '{{eventTitle}}',
                            '{{reason}}'
                        ),
                        array(
                            str_pad($orderId, 5, '0', STR_PAD_LEFT),
                            $order['Order']['name'],
                            $order['Event']['title'],
                            $order['Order']['reason']
                        ),
                        Configure::read('Orders.mail_canceled_body')
                    );
                    break;
                default:
                    # code...
                    break;
            }
            //Enviar e-mail 
            $email = $test ? 'rodrigoalvesnet@gmail.com' : $order['Order']['email'];
            $sendedMail = AlvComponent::enviarEmail($arrayDadosEmail, $email, $arrayAttachments);
            if ($sendedMail) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function gerarPdf($orderId)
    {
        $order = $this->findById($orderId);
        if (!$order) {
            throw new NotFoundException(__('Pedido não encontrado'));
        }

        // Renderiza o HTML da view "ticket" usando o layout pdf_email
        App::uses('View', 'View');
        $View = new View(null);
        $View->viewPath = 'Orders';
        $View->set(compact('order'));
        $html = $View->render('ticket', 'pdf_email');

        // Gera o PDF com dompdf
        App::import('Vendor', 'Dompdf', ['file' => 'dompdf/vendor/autoload.php']);
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output(); // retorna os bytes do PDF
    }

    function getImgQrCodeCheckin($orderId)
    {
        $sendQrcode = Configure::read('Checkin.sendQrcode');
        if ($sendQrcode) {
            $urlQrcode = Configure::read('Checkin.urlQrcode');
            $tagImg = '<img src="' . $urlQrcode . $orderId . '">';
            return $tagImg;
        }
        return '';
    }
}
