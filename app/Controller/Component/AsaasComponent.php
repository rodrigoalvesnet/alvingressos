<?php
class AsaasComponent extends Component
{

    var $components = array('Alv');

    public function createOrder($orderId, $order)
    {
        //Pega os dados do Asaas
        $asaasSettings = $this->getSettings($orderId);

        $response = array(
            'success' => true,
            'message' => 'ok'
        );
        //Verifica o usuário já tem uma ID na API do Asaas
        $customerId = $this->getCustomerId($order['Order']['user_id']);
        //Se o usuário NÃO tem uma ID na API do Asaas
        if (!$customerId) {
            //Salva o cliente no Asaas
            $customerData = array(
                'name' => $order['Order']['name'],
                'email' => $order['Order']['email'],
                'mobilePhone' => $this->Alv->somenteNumeros($order['Order']['phone']),
                'cpfCnpj' => $this->Alv->somenteNumeros($order['Order']['cpf']),
                'externalReference' => $order['Order']['user_id'],
                'notificationDisabled' => $asaasSettings['asaas_notification'] ? false : true,
            );
            $customerResponse = $this->createCustomer($customerData, $asaasSettings);
            //Se deu certo a criação
            if ($customerResponse['success']) {
                //Pega o ID do Cliente gravado no Asaas
                $customerId = $customerResponse['data']->id;
                //Se está configurado para salvar o CustomerId
                $saveCustomerId = Configure::read('Asaas.saveCustomerId');
                if ($saveCustomerId) {
                    //Grava o ID Customer
                    $this->setCustomerId($order['Order']['user_id'], $customerId);
                }
            } else {
                $response['success'] = false;
                $response['message'] = $customerResponse['message'];
                return $response;
            }
        }

        //continua gerando a cobrança
        $invoiceResponse = $this->createInvoice($orderId, $order, $customerId, $asaasSettings);
        //Se deu certo a criação da cobrança
        if ($invoiceResponse['success']) {
            //Atualiza o pedidos
            $this->updateOrder($orderId, $invoiceResponse['data']);
        } else {
            $response['success'] = false;
            $response['message'] = $invoiceResponse['message'];
            return $response;
        }
        return $response;
    }

    private function createInvoice($orderId, $order, $customerId, $asaasSettings)
    {
        //Configurações básicas para a conexão com o Asaas
        $apiKey = $asaasSettings['asaas_production'] ? $asaasSettings['asaas_key'] : $asaasSettings['asaas_key_sandbox'];
        $apiUrl = $asaasSettings['asaas_production'] ? $asaasSettings['asaas_url'] : $asaasSettings['asaas_url_sandbox'];

        //Pega o tipo de pagamento
        switch ($order['Order']['payment_type']) {
            case 'pix':
                $billingType = 'PIX';
                break;
            case 'credit':
                $billingType = 'CREDIT_CARD';
                break;
            case 'ticket':
                $billingType = 'BOLETO';
                break;
            default:
                $billingType = 'UNDEFINED';
                break;
        }

        //Data o vencimento da cobrança
        $dueDate = $asaasSettings['asaas_due_date'];
        $prazo = "+$dueDate day";
        $vencimento = date('Y-m-d', strtotime($prazo));

        //Inícia o processo do CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$apiUrl/payments");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        $data = array(
            'customer' => $customerId,
            'billingType' => $billingType,
            'value' => $order['Order']['value'],
            'dueDate' => $vencimento,
            // 'description' =>  Inflector::slug($order['Order']['description'], ' '),
            'description' =>  $order['Order']['description'],
            'externalReference' => $orderId,
            'installmentCount' => $order['Order']['installments'],
            'installmentValue' => $order['Order']['installment_value'],
        );

        //Se o pagamento é por cartão de crédito
        if ($order['Order']['payment_type'] == 'credit') {
            //Se foi informado os campos de cartão de crédito corretamente
            if (isset($order['Order']['payment_type']) && !empty($order['Order']['payment_type'])) {
                //Dados do cartão
                $data['creditCard'] = array(
                    'holderName' => $order['CreditCard']['holder_name'],
                    'number' => $this->Alv->somenteNumeros($order['CreditCard']['number']),
                    'expiryMonth' => $order['CreditCard']['expiry_month'],
                    'expiryYear' => $order['CreditCard']['expiry_year'],
                    'ccv' => trim($order['CreditCard']['ccv']),
                );
                //Dados do dono do cartão
                $data['creditCardHolderInfo'] = array(
                    'name' => $order['CreditCardHolderInfo']['name'],
                    'email' => $order['CreditCardHolderInfo']['email'],
                    'cpfCnpj' => $this->Alv->somenteNumeros($order['CreditCardHolderInfo']['cpf_cnpj']),
                    'postalCode' => $order['CreditCardHolderInfo']['postal_code'],
                    'addressNumber' => $order['CreditCardHolderInfo']['address_number'],
                    'phone' => $this->Alv->somenteNumeros($order['CreditCardHolderInfo']['phone'])
                );
                //IP local
                $data['remoteIp'] = $this->Alv->getIpAddress();
            } else {
                $result['success'] = false;
                $result['message'] = 'Ocorreu um erro ao tentar gerar um pedido (010)';
                return $result;
            }
        }

        $data = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$data");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "User-Agent: Ingresso",
            "access_token: $apiKey"
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);

        //Array de retorno padrão
        $result = array(
            'success' => true,
            'message' => 'ok',
            'data' => null
        );
        //Se retornou erros
        if (isset($response->errors)) {
            $result['success'] = false;
            $result['message'] = $response->errors[0]->description;
        } else {
            $result['data'] = $response;
        }
        return $result;
    }

    private function createCustomer($customer, $asaasSettings)
    {
        //Configurações básicas para a conexão com o Asaas
        $apiKey = $asaasSettings['asaas_production'] ? $asaasSettings['asaas_key'] : $asaasSettings['asaas_key_sandbox'];
        $apiUrl = $asaasSettings['asaas_production'] ? $asaasSettings['asaas_url'] : $asaasSettings['asaas_url_sandbox'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$apiUrl/customers");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        $data = json_encode($customer);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$data");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "User-Agent: Ingresso",
            "access_token: $apiKey"
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        //Array de retorno padrão
        $result = array(
            'success' => true,
            'message' => 'ok',
            'data' => null
        );
        //Se retornou erros
        if (isset($response->errors)) {
            $result['success'] = false;
            $result['message'] = $response->errors[0]->description;
        } else {
            $result['data'] = $response;
        }
        return $result;
    }

    private function updateOrder($orderId, $invoice)
    {
        $invoiceId = $invoice->id;
        $invoiceUrl = $invoice->invoiceUrl;
        $invoiceNumber = $invoice->invoiceNumber;
        $invoiceBoleto = $invoice->bankSlipUrl;
        $invoiceReceipt = $invoice->transactionReceiptUrl;
        //Atualiza o ID Customer no Order
        App::uses('Order', 'Model');
        $Order = new Order();
        $Order->updateAll(
            array(
                'Order.invoice_id' => "'$invoiceId'",
                'Order.invoice_url' => "'$invoiceUrl'",
                'Order.invoice_number' => "'$invoiceNumber'",
                'Order.invoice_boleto' => "'$invoiceBoleto'",
                'Order.invoice_receipt' => "'$invoiceReceipt'",
            ),
            array(
                'Order.id' => $orderId
            )
        );

        //Se o pagamento foi confirmado
        if ($invoice->status == 'CONFIRMED') {
            $Order->changeStatus($orderId, 'approved');
        } else {
            // $this->sendMail($orderId);
        }
    }

    private function getCustomerId($userId)
    {
        App::uses('User', 'Model');
        $User = new User();
        $response = $User->find(
            'first',
            array(
                'conditions' => array(
                    'id' => $userId
                ),
                'recursive' => -1,
                'fields' => array(
                    'id',
                    'customer_id'
                )
            )
        );
        if (!empty($response)) {
            return $response['User']['customer_id'];
        } else {
            return 0;
        }
    }

    private function setCustomerId($userId, $customerId)
    {
        //Grava o ID Customer no User
        App::uses('User', 'Model');
        $User = new User();
        $User->updateAll(
            array(
                'User.customer_id' => "'$customerId'"
            ),
            array(
                'User.id' => $userId
            )
        );
    }

    private function getSettings($orderId)
    {
        $arraySettings = array();
        $porUnidade = Configure::read('Asaas.porUnidade');
        if ($porUnidade) {
            App::uses('Order', 'Model');
            $OrderModel = new Order();

            $order = $OrderModel->find(
                'first',
                array(
                    'conditions' => array(
                        'Order.id' => $orderId
                    ),
                    'contain' => array(
                        'Event' => array(
                            'fields' => array(
                                'unidade_id'
                            ),
                            'Unidade' => array(
                                'id',
                                'asaas_production',
                                'asaas_key_sandbox',
                                'asaas_url_sandbox',
                                'asaas_key',
                                'asaas_url',
                                'asaas_notification',
                                'asaas_group_name',
                                'asaas_due_date'
                            )
                        )
                    ),
                    'fields' => array(
                        'id'
                    )
                )
            );
            //Se encontrou o pedido
            if (!empty($order)) {
                //Se tem igreja vinculado
                if (!empty($order['Event']['Unidade'])) {
                    $arraySettings = $order['Event']['Unidade'];
                }
            }

        }else{
            $arraySettings = [
                'asaas_production' => Configure::read('Asaas.asaas_production'),
                'asaas_key_sandbox' => Configure::read('Asaas.asaas_key_sandbox'),
                'asaas_url_sandbox' => Configure::read('Asaas.asaas_url_sandbox'),
                'asaas_key' => Configure::read('Asaas.asaas_key'),
                'asaas_url' => Configure::read('Asaas.asaas_url'),
                'asaas_notification' => Configure::read('Asaas.asaas_notification'),
                'asaas_group_name' => Configure::read('Asaas.asaas_group_name'),
                'asaas_due_date' => Configure::read('Asaas.asaas_due_date'),
            ];
        }

        return $arraySettings;
    }

    public function deleteInvoice($orderId)
    {
        //Pega os dados do Asaas
        $asaasSettings = $this->getSettings($orderId);

        $response = array(
            'success' => true,
            'message' => 'ok'
        );

        //Configurações básicas para a conexão com o Asaas
        $apiKey = $asaasSettings['asaas_production'] ? $asaasSettings['asaas_key'] : $asaasSettings['asaas_key_sandbox'];
        $apiUrl = $asaasSettings['asaas_production'] ? $asaasSettings['asaas_url'] : $asaasSettings['asaas_url_sandbox'];

        //Atualiza o ID Customer no Order
        App::uses('Order', 'Model');
        $Order = new Order();
        $orderData = $Order->find(
            'first',
            array(
                'conditions' => array(
                    'id' => $orderId
                ),
                'fields' => array(
                    'id',
                    'invoice_id'
                ),
                'recursive' => -1
            )
        );

        //Se encontrou o pedido
        if (!empty($orderData)) {
            //Se tem invoice ID
            if (!empty($orderData['Order']['invoice_id'])) {
                $invoiceId = $orderData['Order']['invoice_id'];
                //Inícia o processo do CURL
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "$apiUrl/payments/$invoiceId");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                // $data = array(
                //     'paymentDate' => date('Y-m-d'),
                //     'value' => $orderData['Order']['value'],
                // );

                // $data = json_encode($data);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, "$data");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/json",
                    "User-Agent: Ingresso",
                    "access_token: $apiKey"
                ));
                $response = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($response);

                //Array de retorno padrão
                $result = array(
                    'success' => true,
                    'message' => 'ok',
                    'data' => null
                );
                //Se retornou erros
                if (isset($response->errors)) {
                    $result['success'] = false;
                    $result['message'] = $response->errors[0]->description;
                } else {
                    $result['data'] = $response;
                }
            } else {
                $result['success'] = false;
                $result['message'] = 'Fatura não encontrada';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Pedido não encontrado';
        }

        return $result;
    }

    function sendMail($orderId)
    {
        //Atualiza o ID Customer no Order
        App::uses('Order', 'Model');
        $Order = new Order();
        if ($Order->sendVoucher($orderId)) {
            return true;
        } else {
            return false;
        }
    }
}
