<?php
class OrdersController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv', 'Security', 'Imagem', 'Asaas');

    public function beforeFilter()
    {

        parent::beforeFilter();
        $this->set('title_for_layout', 'Pedidos');
        $this->Security->unlockedActions = array(
            'generate_qrcode_pix',
            'apply_discount',
            'get_installments',
            'generate_qrcode_checkin',
            'sum_products',
            'view',
            'admin_index'
        );
        $this->Auth->allow(array(
            'generate_qrcode_pix',
            'generate_qrcode_checkin',
            'sum_products'
        ));

        //verifica as permissões
        if (in_array($this->action, array(
            'edit',
            'view',
            'approve',
            'disapprove',
            'cancel',
            'pending',
            'ticket',
            'send_mail'
        ))) {
            //Verifica se tem permissão para ver
            $id = $this->params['pass'][0];
            $checkPermission = $this->Order->checkPermission($id);
            if (!$checkPermission) {
                $this->Flash->warning('Você não tem permissão para acessar este registro!');
                $this->redirect($this->referer());
            }
        }
    }

    public function admin_index()
    {

        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Orders')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Orders');
            }
            //veriica se o cache existe
            if ($this->Session->check('Filtros.ThisData')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.ThisData');
            }
            //atualiza a pagina
            $this->redirect(array(
                'admin' => true
            ));
        }

        //condição padrão
        $arrayConditions = array();
        //Se quem está logado é um comprador
        if (AuthComponent::user('role_id') == 3) {
            $arrayConditions['OR'] =  array(
                'EventsUser.user_id' => AuthComponent::user('id'),
                'EventsAdmin.user_id' => AuthComponent::user('id')
            );
        }
        //se o this->data não está vazio, prepara o filtro
        if (!empty($this->request->data)) {
            // pr($this->request->data['button']);exit();

            if (isset($this->request->data['Filtro']['customer']) && !empty($this->request->data['Filtro']['customer'])) {
                $arrayConditions['Order.name LIKE '] = '%' . $this->request->data['Filtro']['customer'] . '%';
            }
            if (isset($this->request->data['Filtro']['unidade_id']) && !empty($this->request->data['Filtro']['unidade_id'])) {
                $arrayConditions['Order.unidade_id'] = $this->request->data['Filtro']['unidade_id'];
            }
            if (isset($this->request->data['Filtro']['start_date']) && !empty($this->request->data['Filtro']['start_date'])) {
                $arrayConditions['DATE(Order.created) >='] = $this->request->data['Filtro']['start_date'];
            }
            if (isset($this->request->data['Filtro']['end_date']) && !empty($this->request->data['Filtro']['end_date'])) {
                $arrayConditions['DATE(Order.created) <='] = $this->request->data['Filtro']['end_date'];
            }
            if (isset($this->request->data['Filtro']['payment_type']) && !empty($this->request->data['Filtro']['payment_type'])) {
                $arrayConditions['Order.payment_type'] = $this->request->data['Filtro']['payment_type'];
            }
            if (isset($this->request->data['Filtro']['status']) && !empty($this->request->data['Filtro']['status'])) {
                $arrayConditions['Order.status'] = $this->request->data['Filtro']['status'];
            }

            //salva as condições na session            
            $this->Session->write('Filtros.Orders', $arrayConditions);
            $this->Session->write('Filtros.ThisData', $this->request->data);

            if(isset($this->request->data['button']) && $this->request->data['button'] == 'btnExport'){
                $this->redirect([
                    'controller' => 'Reports',
                    'action' => 'orders',
                    'admin' => true
                ]);
            }
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Orders')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Orders');
                $this->request->data = $this->Session->read('Filtros.ThisData');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'order'         => 'Order.created DESC',
            'joins' => array(
                array(
                    'table' => 'events_users',
                    'alias' => 'EventsUser',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Event.id = EventsUser.event_id',
                    )
                ),
                array(
                    'table' => 'events_admins',
                    'alias' => 'EventsAdmin',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Event.id = EventsAdmin.event_id',
                    )
                )
            ),
            'contain'       => array(
                'Event' => array(
                    'title'
                ),
                'Unidade' => array(
                    'name'
                ),
                'Attachment' => array(
                    'path'
                )
            ),
            'fields' => array(
                'DISTINCT id',
                'created',
                'name',
                'value',
                'payment_type',
                'status'
            ),
            'group' => array(
                'Order.id'
            )
        );
        $this->set('registros', $this->paginate('Order'));

        //envia os dados para a view
        $status = Configure::read('Order.status');
        $this->set('status', $status);
        $payments = Configure::read('Order.payment_type');
        $this->set('payments', $payments);

        //Pega os dados do Event
        $this->loadModel('Unidade');
        $unidades = $this->Unidade->find(
            'list',
            array(
                'recursive' => -1,
                'fields' => array(
                    'id',
                    'name'
                ),
                'order' => array(
                    'name' => 'ASC'
                )
            )
        );
        $this->set('unidades', $unidades);
    }



    public function admin_edit($id)
    {
        if ($this->request->is('post') || $this->request->is('put')) {
            //Se foi informado o aniversário nos campos adicionais
            if (!empty($this->request->data['Order']['birthday'])) {
                $this->request->data['Order']['birthday'] = $this->Alv->tratarData($this->request->data['Order']['birthday']);
            }
            //Se tem perguntas adicionais
            if (isset($this->data['Response']) && !empty($this->data['Response'])) {
                //Apaga os registros de respostas anteriores
                $this->loadModel('Response');
                $this->Response->deleteAll(
                    array(
                        'order_id' => $this->data['Order']['id']
                    )
                );
            }

            //Se salvar corretamente
            if ($this->Order->saveAll($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
            $this->redirect(array(
                'controller' => 'Orders',
                'action' => 'edit',
                $id
            ));
        } else {
            $this->request->data = $this->Order->find(
                'first',
                array(
                    'conditions' => array(
                        'Order.id' => $id
                    ),
                    'contain' => array(
                        'User',
                        'Response',
                        'Product',
                        'Event' => array(
                            'Field'
                        ),
                        'Attachment' => array(
                            'order' => 'created DESC'
                        ),
                        'Lot'
                    )
                )
            );
            //Se foi informado o aniversário nos campos adicionais
            if (!empty($this->request->data['Order']['birthday'])) {
                $this->request->data['Order']['birthday'] = $this->Alv->tratarData($this->request->data['Order']['birthday'], 'pt');
            }
        }

        $this->loadModel('Unidade');
        $unidades = $this->Unidade->find(
            'list',
            array(
                'fields' => array(
                    'id',
                    'name'
                ),
                'recursive' => -1,
                'order' => 'name ASC'
            )
        );
        $this->set('unidades', $unidades);
        $this->set('bcLinks', array(
            'Pedidos' => '/admin/orders'
        ));
        $this->set('status', Configure::read('Order.status'));
        $this->set('title_for_layout', 'Editar Pedido');
    }

    function admin_approve($id)
    {
        $this->autoRender = false;

        if ($this->Order->changeStatus($id, 'approved')) {
            $this->Flash->success('Pedido aprovado com sucesso');
        } else {
            $this->Flash->error('Não foi possível aprovar o pedido.');
        }
        //Volta para a página que estava
        $this->redirect($this->referer());
    }

    function admin_disapprove($id)
    {
        $this->autoRender = false;

        //Se foi postado
        if (!empty($this->data)) {
            $this->Order->changeStatus($id, 'rejected', $this->data['Order']['reason']);
            if ($this->Order->save($this->data)) {
                $this->Flash->success('Pedido reprovado com sucesso');
            } else {
                $this->Flash->error('Não foi possível reprovar o pedido.');
            }
        }
        //Volta para a página que estava
        $this->redirect($this->referer());
    }

    function admin_cancel($id)
    {
        $this->autoRender = false;

        //Cancela o pedido no Asaas
        $invoiceCreated = $this->Asaas->deleteInvoice($id);
        if ($invoiceCreated['success']) {
            $this->Order->changeStatus($id, 'canceled');
            $this->Flash->success('Pedido cancelado com sucesso!');
        } else {
            $this->Flash->error($invoiceCreated['message']);
        }
        //Volta para a página que estava
        $this->redirect($this->referer());
    }

    function admin_pending($id)
    {
        $this->autoRender = false;

        if ($this->Order->changeStatus($id, 'pending')) {
            $this->Flash->success('Pedido alterado para PENDENTE com sucesso');
        } else {
            $this->Flash->error('Não foi possível altera o pedido.');
        }
        //Volta para a página que estava
        $this->redirect($this->referer());
    }

    function delete_attach($id)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->autoRender = false;
        $this->loadModel('Attachment');
        $attach = $this->Attachment->findById($id);
        if (!$attach) {
            $this->Flash->error('Anexo não encontrado.');
            return $this->redirect($this->referer());
        }

        if ($this->Attachment->delete($id)) {
            // Deletar arquivo físico
            $filePath = WWW_ROOT . $attach['Attachment']['path'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $this->Flash->success('Anexo EXCLUÍDO com sucesso');
        } else {
            $this->Flash->error('Não foi possível EXCLUIR o anexo.');
        }
        //Volta para a página que estava
        $this->redirect($this->referer());
    }

    function admin_send_mail($orderId)
    {
        $this->autoRender = false;
        //Tenta enviar o voucher, mas sem quebrar o webhook
        try {
            if ($this->Order->sendVoucher($orderId)) {
                $this->Flash->success('Email enviado com sucesso');
            } else {
                $this->Flash->error('Não foi possível enviar o email (0)');
            }
        } catch (Exception $e) {
            $this->Flash->error('Não foi possível enviar o email (1)');
            //Registra log para investigar depois
            CakeLog::write('error', 'Falha ao enviar voucher do pedido ' . $orderId . ': ' . $e->getMessage());
        }

        //Volta para a página que estava
        $this->redirect($this->referer());
    }

    public function add($eventId)
    {
        if ($this->request->is('post')) {

            $createNewOrder = $this->Order->newOrder($this->request->data);
            //Se salvar corretamente
            if ($createNewOrder['success']) {
                //Pega o ID da ordem salva
                $orderId = $createNewOrder['order_id'];
                //Se é GRATUITO
                if ($this->request->data['Order']['payment_type'] == 'free') {
                    $this->Flash->success('Inscrição efetuada com sucesso!');
                    $this->redirect(array(
                        'controller' => 'Orders',
                        'action' => 'view',
                        $orderId
                    ));
                    try {
                        //Envia o email com o QRCode
                        $this->Order->sendVoucher($orderId);
                    } catch (Exception $e) {
                        //Registra log para investigar depois
                        CakeLog::write('error', 'Falha ao enviar voucher do pedido ' . $orderId . ': ' . $e->getMessage());
                    }
                }
                //Se a forma de pagamento é do método antigo ainda
                if ($this->request->data['Order']['payment_type'] == 'pix_old') {
                    $this->Flash->success('Compra efetuada com sucesso!');
                    $this->redirect(array(
                        'controller' => 'Orders',
                        'action' => 'view',
                        $orderId
                    ));
                } else {
                    //Cria o pedido no Asaas
                    $invoiceCreated = $this->Asaas->createOrder($orderId, $createNewOrder['data']);
                    if ($invoiceCreated['success']) {
                        $this->Flash->success('Compra efetuada com sucesso!');
                        $this->redirect(array(
                            'controller' => 'Orders',
                            'action' => 'view',
                            $orderId
                        ));
                    } else {
                        $this->Order->changeStatus($orderId, 'canceled');
                        $this->Flash->error($invoiceCreated['message']);
                    }
                }
            } else {
                $this->Flash->error($createNewOrder['message']);
            }
        }
        $this->loadModel('Event');
        /**
         * Verifica se tem ingressos disponíveis
         */
        $availableLot = $this->Event->checkAvailableLot($eventId);

        //Pega os dados do Event        
        $event = $this->Event->find(
            'first',
            array(
                'conditions' => array(
                    'Event.id' => $eventId
                ),
                'contain' => array(
                    'Lot' => array(
                        'conditions' => array(
                            'id' => $availableLot
                        )
                    ),
                    'Field',
                    'Product' => array(
                        'ProductsImage'
                    ),
                    'Coupon' => array(
                        'id'
                    )
                )
            )
        );
        $this->set('event', $event);
        $this->loadModel('Unidade');
        $unidades = $this->Unidade->find(
            'list',
            array(
                'fields' => array(
                    'id',
                    'name'
                ),
                'recursive' => -1,
                'order' => 'name ASC'
            )
        );
        $this->set('unidades', $unidades);

        if (isset($event['Field']) && !empty($event['Field'])) {
            $listOptions = array();
            $listDisableds = array();
            foreach ($event['Field'] as $field) {
                $fieldId = $field['id'];
                //Se é campo do tipo lista
                if ($field['type'] == 'list') {
                    $options = explode(PHP_EOL, $field['options']);
                    foreach ($options as $option) {
                        $option = rtrim($option);
                        $option = preg_replace('/([\r\n\t])/', '', $option);
                        $listOptions[$fieldId][$option] = $option;
                    }
                    //Se é o evento Conlider
                    if ($event['Event']['id'] == '14') {
                        $this->loadModel('Response');
                        $reponsesTotal = $this->Response->getTotalByResponse($fieldId);
                        $arrayOptions = $this->Response->checkAvailableFields($reponsesTotal, $listOptions);
                        $listOptions = $arrayOptions['available'];
                        $listDisableds = $arrayOptions['unavailable'];
                    }
                }
            }
            $this->set('listOptions', $listOptions);
            $this->set('listDisableds', $listDisableds);
        }

        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';
        $this->set('title_for_layout', 'Comprar Ingresso');
    }

    function ticket($id)
    {
        $this->layout = 'pdf';
        $order = $this->Order->find(
            'first',
            array(
                'conditions' => array(
                    'Order.id' => $id
                ),
                'contain' => array(
                    'Ticket',
                    'Response',
                    'Unidade' => array(
                        'name'
                    ),
                    'Event' => array(
                        'Field' => array(
                            'id',
                            'question'
                        ),
                        'Unidade' => array(
                            'name',
                            'cnpj',
                            'street',
                            'number',
                            'state',
                            'city',
                            'zipcode',
                            'email',
                            'phone',
                            'district'
                        ),
                        'fields' => array(
                            'title'
                        )
                    )
                )
            )
        );

        // pr($order);exit();
        $this->set('order', $order);
        $tipoEvento = Configure::read('Sistema.evento');
        if ($tipoEvento == 'continuo') {
            $this->render('ticket');
        }
        $fileName = Inflector::slug($order['Order']['id'] . '-' . $order['Order']['name'], '-');
        $this->set('fileName', $fileName);
    }

    function generate_qrcode_pix()
    {
        $this->layout = 'ajax';
    }

    function get_installments()
    {
        $this->layout = 'ajax';
        $this->theme = Configure::read('Site.tema');
        $this->loadModel('Event');

        /**
         * Verifica se tem ingressos disponíveis
         */
        $availableLot = $this->Event->checkAvailableLot($this->data['Order']['event_id']);
        $event = $this->Event->find(
            'first',
            array(
                'conditions' => array(
                    'Event.id' => $this->data['Order']['event_id']
                ),
                'contain' => array(
                    'Lot' => array(
                        'conditions' => array(
                            'id' => $availableLot
                        ),
                        'fields' => array(
                            'payments_type'
                        )
                    )
                ),
                'fields' => array(
                    'id'
                )
            )
        );
        $paymentsType = unserialize($event['Lot'][0]['payments_type']);

        $this->set('paymentsType', $paymentsType);
        //Pega o valor do pedido
        $price = $this->data['Order']['value'];

        //Verifica se tem produtos adicionados
        if (isset($this->data['Order']['products_total']) && !empty($this->data['Order']['products_total'])) {
            $price = ($price + $this->data['Order']['products_total']);
        }

        //Verifica se tem desconto
        if (isset($this->data['Order']['coupon_id']) && !empty($this->data['Order']['coupon_id'])) {
            $price = $this->Order->applyDiscount(
                $price,
                $this->data['Order']['coupon_id']
            );
        }

        $this->set('price', $price);
    }

    function apply_discount()
    {
        $this->layout = 'ajax';
        $this->theme = Configure::read('Site.tema');

        $arrayResult = array(
            'success' =>  true,
            'message' => '',
            'coupon_id' => null
        );
        //Se foi preenchido o cupom
        if (!empty($this->data['Order']['coupon'])) {
            $code = trim($this->data['Order']['coupon']);
            $this->loadModel('Coupon');
            //Procura pelo cupom
            $coupon = $this->Coupon->find(
                'first',
                array(
                    'conditions' => array(
                        'Coupon.code' => $code,
                        'Coupon.event_id' => $this->data['Order']['event_id']
                    ),
                    'fields' => array(
                        'id',
                        'code'
                    ),
                    'recursive' => -1
                )
            );
            //Se NÃO encontoru o cupom
            if (empty($coupon)) {
                $arrayResult['success'] = false;
                $arrayResult['message'] = 'Cupom não localizado.';
            } else {
                //Se foi preenchido o CPF
                if (!empty($this->data['Order']['cpf'])) {
                    $cpf = $this->data['Order']['cpf'];
                } else {
                    $cpf = AuthComponent::user('cpf');
                }
                //Verifica se já foi usado por este usuário
                $orderUsed = $this->Order->find(
                    'first',
                    array(
                        'conditions' => array(
                            'coupon_id' => $coupon['Coupon']['id'],
                            'cpf' => $cpf,
                            'status !=' => 'canceled'
                        ),
                        'recursive' => -1
                    )
                );
                //Se já foi usado
                if (!empty($orderUsed)) {
                    $arrayResult['success'] = false;
                    $arrayResult['message'] = 'O cupom informado já foi utilizado para o CPF informado.';
                } else {
                    $arrayResult['coupon_id'] = $coupon['Coupon']['id'];
                    $arrayResult['message'] = 'Cupom <strong>' . $coupon['Coupon']['code'] . '</strong> aplicado com sucesso!';
                }
            }
        }
        $this->set('result', $arrayResult);
    }

    function sum_products()
    {
        $this->layout = 'ajax';
        $this->theme = Configure::read('Site.tema');
        $arrayResult = array(
            'success' =>  true,
            'message' => '',
            'total' => 0
        );
        //Se tem produtos
        if (!empty($this->data['OrdersProduct'])) {
            $total = 0;
            foreach ($this->data['OrdersProduct'] as $product) {
                $total += ($product['price'] * $product['quantity']);
            }
            $arrayResult['total'] = $total;
        }
        $this->set('result', $arrayResult);
    }

    function generate_qrcode_checkin($orderid)
    {
        $this->layout = 'image';
        App::import('Vendor', 'QrcodeGen', array('file' => 'QrcodeGen/QrcodeGen.php'));
        $qrcode = new QrcodeGen();
        $urlQrCode = Configure::read('Checkin.url') . $this->params['pass'][0];
        $image = $qrcode->link($urlQrCode);
        $this->set('imgData', $image);
        $this->set('fileName', $orderid);
    }

    public function my_tickets()
    {
        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Orders')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Orders');
            }
            //atualiza a pagina
            $this->redirect($this->action);
        }

        //condição padrão
        $arrayConditions = array(
            'OR' => array(
                'Order.user_id' => AuthComponent::user('id'),
                'Order.cpf' => AuthComponent::user('cpf')
            )
        );
        //se o this->data não está vazio, prepara o filtro
        if (!empty($this->request->data)) {
            if (isset($this->request->data['Filtro']['event_id']) && !empty($this->request->data['Filtro']['event_id'])) {
                $arrayConditions['Order.event_id'] = $this->request->data['Filtro']['event_id'];
            }
            if (isset($this->request->data['Filtro']['status']) && !empty($this->request->data['Filtro']['status'])) {
                $arrayConditions['Order.status'] = $this->request->data['Filtro']['status'];
            }
            if (isset($this->request->data['Filtro']['payment_type']) && !empty($this->request->data['Filtro']['payment_type'])) {
                $arrayConditions['Order.payment_type'] = $this->request->data['Filtro']['payment_type'];
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'order'         => 'Order.created DESC',
            'contain'       => array(
                'Event' => array(
                    'title'
                ),
            )
        );
        $this->set('registros', $this->paginate('Order'));

        //envia os dados para a view
        $status = Configure::read('Order.status');
        $this->set('status', $status);
        $payments = Configure::read('Order.payment_type');
        $this->set('payments', $payments);

        //Pega os dados do Event
        $this->loadModel('Event');
        $events = $this->Event->find(
            'list',
            array(
                'recursive' => -1,
                'fields' => array(
                    'id',
                    'title'
                )
            )
        );
        $this->set('events', $events);

        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';

        $this->set('title_for_layout', 'Meus Ingressos');
    }

    public function view($id)
    {

        if (!empty($this->data)) {
            //se foi informado o anexo
            if (!empty($this->data['Attachment']['new_file']['tmp_name'])) {
                $anexoDir = '/uploads/event-' . $this->data['Order']['event_id'];
                //faz o upload do anexo
                $anexoPath = $this->Imagem->upload($this->data['Attachment']['new_file'], false, $anexoDir);
                //Se fez o updaload corretamente
                if ($anexoPath) {
                    $this->loadModel('Attachment');
                    $arraySave = array(
                        'id' => null,
                        'order_id' => $this->data['Order']['id'],
                        'path' => $anexoDir . '/' . $anexoPath,
                        'status' => 'pending'
                    );
                    //Se salvar corretamente
                    if ($this->Attachment->save($arraySave)) {
                        $this->Flash->success('Registro salvo com sucesso');
                    } else {
                        $this->Flash->error('Não foi possível salvar o registro');
                    }
                } else {
                    $this->Flash->error('Não foi possível enviar o anexo.');
                }
            }
            $this->redirect(array(
                'controller' => 'Orders',
                'action' => 'view',
                $this->data['Order']['id']
            ));
        } else {
            $this->request->data = $this->Order->find(
                'first',
                array(
                    'conditions' => array(
                        'Order.id' => $id
                    ),
                    'contain' => array(
                        'User',
                        'Response',
                        'Product',
                        'Event' => array(
                            'Field'
                        ),
                        'Attachment' => array(
                            'order' => 'created DESC'
                        ),
                        'Lot',
                        'Ticket'
                    )
                )
            );
            // pr($this->request->data);exit();
            //Se foi informado o aniversário nos campos adicionais
            if (!empty($this->request->data['Order']['birthday'])) {
                $this->request->data['Order']['birthday'] = $this->Alv->tratarData($this->request->data['Order']['birthday'], 'pt');
            }
        }

        $this->loadModel('Unidade');
        $unidades = $this->Unidade->find(
            'list',
            array(
                'fields' => array(
                    'id',
                    'name'
                ),
                'recursive' => -1,
                'order' => 'name ASC'
            )
        );
        $this->set('unidades', $unidades);
        $this->set('bcLinks', array(
            'Meus Ingressos' => '/admin/orders/my_tickets'
        ));

        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';

        $this->set('status', Configure::read('Order.status'));
        $this->set('title_for_layout', 'Ver Ingresso');
    }
}
