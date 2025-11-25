<?php
class EventsController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv', 'Imagem', 'Session', 'RequestHandler', 'Cart');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Evento');
        $this->Auth->allow(array(
            'buy',
            'index'
        ));
        //verifica as permissões
        if (in_array($this->action, array(
            'edit'
        ))) {
            //Verifica se tem permissão para ver
            $id = $this->params['pass'][0];
            $checkPermission = $this->Event->checkPermission($id);
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
            if ($this->Session->check('Filtros.Events')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Events');
            }
            $this->redirect(array(
                'admin' => true
            ));
        }

        //condição padrão
        $arrayConditions = array(
            // 'Event.status != ' => 'closed'
        );
        //Se quem está logado é um comprador
        if (AuthComponent::user('role_id') == 3) {
            $arrayConditions['OR'] =  array(
                'EventsUser.user_id' => AuthComponent::user('id'),
                'EventsAdmin.user_id' => AuthComponent::user('id')
            );
        }
        //se o this->data não está vazio, prepara o filtro
        if (!empty($this->request->data)) {
            if (isset($this->request->data['Filtro']['title']) && !empty($this->request->data['Filtro']['title'])) {
                $arrayConditions['Event.title LIKE'] = '%' . $this->request->data['Filtro']['title'] . '%';
            }
            if (isset($this->request->data['Filtro']['unidade_id']) && !empty($this->request->data['Filtro']['unidade_id'])) {
                $arrayConditions['Event.unidade_id'] = $this->request->data['Filtro']['unidade_id'];
            }
            if (isset($this->request->data['Filtro']['status']) && !empty($this->request->data['Filtro']['status'])) {
                $arrayConditions['Event.status'] = $this->request->data['Filtro']['status'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Events', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Events')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Events');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'order'         => 'Event.start_date ASC',
            'fields' => array(
                'DISTINCT id',
                'title',
                'start_date',
                'end_date',
                'status',
                'slug'
            ),
            'recursive' => -1,
            'contain' => array(
                'Unidade' => array(
                    'name'
                ),
                'User' => array(
                    'id'
                ),
                'Admin' => array(
                    'id'
                )
            ),
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
            )
        );
        $events = $this->paginate('Event');

        //envia os dados para a view
        $this->set('registros', $events);

        $this->loadModel('Unidade');
        $unidades = $this->Unidade->find(
            'list',
            array(
                'conditions' => array(
                    'active' => 1
                ),
                'recursive' => -1,
                'order' => 'name ASC'
            )
        );
        $this->set('unidades', $unidades);

        $this->loadModel('Order');
        $orders = $this->Order->ordersByEvent();
        $this->set('orders', $orders);
        $this->set('status', Configure::read('Events.status'));
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Event->create();
            //trata os dados
            $this->request->data['Event']['user_id'] = $this->Session->read('Auth.User.id');
            $this->request->data['Event']['start_date'] = $this->Alv->tratarData($this->request->data['Event']['start_date']);
            $this->request->data['Event']['end_date'] = $this->Alv->tratarData($this->request->data['Event']['end_date']);
            if (!empty($this->request->data['Event']['display_date'])) {
                $this->request->data['Event']['display_date'] = $this->Alv->tratarData($this->request->data['Event']['display_date']);
            }
            unset($this->request->data['Event']['banner_desktop']);
            unset($this->request->data['Event']['banner_mobile']);
            if ($this->Event->save($this->request->data)) {
                $id = $this->Event->getLastInsertId();
                $anexoDir = '/uploads/event-' . $id;
                //se foi informado a foto
                if (!empty($this->data['Event']['new_banner_desktop']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Event']['new_banner_desktop'];
                    $this->_salvarImagem($id, $urlFoto, 'banner_desktop', false, $anexoDir);
                }
                //se foi informado a foto
                if (!empty($this->data['Event']['new_banner_mobile']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Event']['new_banner_mobile'];
                    $this->_salvarImagem($id, $urlFoto, 'banner_mobile', true, $anexoDir);
                }
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }

        $this->loadModel('Unidade');
        $unidades = $this->Unidade->find(
            'list',
            array(
                'conditions' => array(
                    'active' => 1
                ),
                'recursive' => -1,
                'order' => 'name ASC'
            )
        );
        $this->set('unidades', $unidades);

        $this->set('bcLinks', array(
            'Eventos' => '/admin/Events'
        ));
        $this->set('title_for_layout', 'Adicionar Evento');
        $this->set('status', Configure::read('Events.status'));
    }

    public function admin_edit($id)
    {
        $this->Event->id = $id;
        if (!$this->Event->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            // pr($this->data);exit();
            //trata os dados
            $this->request->data['Event']['start_date'] = $this->Alv->tratarData($this->request->data['Event']['start_date']);
            $this->request->data['Event']['end_date'] = $this->Alv->tratarData($this->request->data['Event']['end_date']);
            // $this->request->data['Event']['dates'] = serialize($this->request->data['Event']['dates']);
            if (!empty($this->request->data['Event']['display_date'])) {
                $this->request->data['Event']['display_date'] = $this->Alv->tratarData($this->request->data['Event']['display_date']);
            }
            //Se tem AJUDANTES selecionados
            if (!empty(!empty($this->request->data['Event']['Users']))) {
                $users = json_decode($this->request->data['Event']['Users']);
                if (!empty($users)) {
                    foreach ($users as $user) {
                        $this->request->data['User'][] = $user->value;
                    }
                }
            } else {
                $this->request->data['User'][] = null;
            }

            //Se tem ADMINS selecionados
            if (!empty(!empty($this->request->data['Event']['Admins']))) {
                $users = json_decode($this->request->data['Event']['Admins']);
                if (!empty($users)) {
                    foreach ($users as $user) {
                        $this->request->data['Admin'][] = $user->value;
                    }
                }
            } else {
                $this->request->data['Admin'][] = null;
            }

            // pr($this->request->data);exit();
            if ($this->Event->saveAll($this->request->data)) {
                //Salva as datas bloqueadas
                $this->_saveBlockedDates($this->request->data);
                $anexoDir = '/uploads/event-' . $id;
                //se foi informado a foto
                if (!empty($this->data['Event']['new_banner_desktop']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Event']['new_banner_desktop'];
                    $this->_salvarImagem($id, $urlFoto, 'banner_desktop', false, $anexoDir);
                }
                //se foi informado a foto
                if (!empty($this->data['Event']['new_banner_mobile']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Event']['new_banner_mobile'];
                    $this->_salvarImagem($id, $urlFoto, 'banner_mobile', true, $anexoDir);
                }
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect($this->referer());
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            //Pega os dados do Evento
            $this->request->data = $this->Event->find(
                'first',
                array(
                    'conditions' => array(
                        'Event.id' => $id
                    ),
                    'contain' => array(
                        'Talker',
                        'Schedule',
                        'Lot',
                        'Field',
                        'Coupon',
                        'Product' => array(
                            'ProductsImage'
                        ),
                        'User' => array(
                            'id',
                            'name'
                        ),
                        'Admin' => array(
                            'id',
                            'name'
                        )
                    )
                )
            );
            $blockedDates = $this->Event->getBlockedDates($id, false);
            $this->set('blockedDates', $blockedDates);
            //trata os dados
            $this->request->data['Event']['start_date'] = $this->Alv->tratarData($this->request->data['Event']['start_date'], 'pt');
            $this->request->data['Event']['end_date'] = $this->Alv->tratarData($this->request->data['Event']['end_date'], 'pt');
            if (!empty($this->request->data['Event']['display_date'])) {
                $this->request->data['Event']['display_date'] = $this->Alv->tratarData($this->request->data['Event']['display_date'], 'pt');
            }
        }

        $this->loadModel('Unidade');
        $unidades = $this->Unidade->find(
            'list',
            array(
                'conditions' => array(
                    'active' => 1
                ),
                'recursive' => -1,
                'order' => 'name ASC'
            )
        );
        $this->set('unidades', $unidades);

        $this->set('bcLinks', array(
            'Eventos' => '/admin/Events'
        ));
        $this->set('title_for_layout', 'Editar Evento');
        $this->set('status', Configure::read('Events.status'));
        $this->render('admin_add');
    }

    function _saveBlockedDates($thisData)
    {

        if (!empty($thisData['datasBloqueadas'])) {

            $this->loadModel('EventsDate');
            //Deleta os antigos
            $this->EventsDate->deleteAll([
                'event_id' => $thisData['Event']['id']
            ]);
            //Salva os novos
            $datasBloqueadas = json_decode($thisData['datasBloqueadas'], true);
            foreach ($datasBloqueadas as $date) {
                $this->EventsDate->save([
                    'id' => null,
                    'event_id' => $thisData['Event']['id'],
                    'date' => $date
                ]);
            }
        }
    }

    function _salvarImagem($registroId, $urlFoto, $field, $resize = true, $anexoDir)
    {
        //faz o upload da imagem
        $imagemPath = $this->Imagem->upload($urlFoto, $resize, $anexoDir);
        //salva o caminho no banco
        if ($this->Event->updateAll(
            array('Event.' . $field => "'" . $imagemPath . "'"),
            array('Event.id' => $registroId)
        )) {
            return true;
        } else {
            return false;
        }
    }

    public function buy($eventId = null)
    {
        if ($this->request->is('post')) {
            //se não escolheu nenhuma data
            if (empty($this->data)) {
                $this->Flash->error('Você precisa escolher uma data!');
                $this->redirect($this->referer());
            }
            //Guarda a sessão
            $this->Cart->saveCart($this->request->data);
            $this->Flash->success('Produto adicionado ao carrinho!');
            $this->redirect(
                array(
                    'controller' => 'Checkout',
                    'action' => 'payment'
                )
            );
        }

        $arrayConditions = array();
        //Se tem slug
        if (isset($this->params['slug']) && !empty($this->params['slug'])) {
            $arrayConditions = array(
                'Event.slug' => $this->params['slug']
            );
        }
        //Se tem ID
        if (!empty($eventId)) {
            $arrayConditions = array(
                'Event.id' => $eventId
            );
        }
        //Se não conseguiu pegar o ID nem o Slug
        if (empty($arrayConditions)) {
            $this->Flash->error('O endereço solicitado não foi encontrado :(');
            $this->redirect('/');
        }

        $this->Cart->checkCart();

        $this->set('cart', $this->Session->read('Cart'));

        $this->loadModel('Event');

        //Pega os dados do Event        
        $event = $this->Event->find(
            'first',
            array(
                'conditions' => $arrayConditions,
                'contain' => array(
                    'Lot',
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

        $modalidades = array();
        $regras = array();
        if (!empty($event['Lot'])) {
            foreach ($event['Lot'] as $lot) {
                $modalidades[$lot['id']] = [
                    'name' => $lot['name'],
                    'valor' => $lot['value']
                ];
                $regras[$lot['id']] = unserialize($lot['rules']);
            }
        }
        $this->set('modalidades', $modalidades);
        $this->set('regras', $regras);

        $blockedDates = $this->Event->getBlockedDates($eventId, true, false);

        $dates = [];
        foreach ($blockedDates as $date) {
            $dates[] = date('d/m/Y', strtotime($date));
        }
        $this->set('blockedDates', json_encode($dates));

        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';
        $this->set('title_for_layout', 'Comprar Ingresso');
    }

    function index()
    {
        $this->loadModel('Event');
        $events = $this->Event->find(
            'all',
            array(
                'conditions' => array(
                    'status != ' => array('sketch', 'oculto'),
                    'end_date >= ' => date('Y-m-d')
                ),
                'order' => array(
                    'Event.priority' => 'ASC',
                    'Event.start_date' => 'ASC'
                ),
                'recursive' => -1,
                'fields' => array(
                    'id',
                    'title',
                    'slug',
                    'banner_mobile',
                    'banner_desktop',
                    'end_date',
                    'start_date',
                    'status'
                )
            )
        );
        $this->set('events', $events);


        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';
        $this->set('title_for_layout', 'Eventos');
    }
}
