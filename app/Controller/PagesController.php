<?php
class PagesController extends AppController
{
    public $layout = 'site';
    public $helpers = ['Html', 'Form', 'Shortcode'];
    public $components = array('RequestHandler', 'Alv', 'Imagem');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->theme = Configure::read('Site.tema');
        $this->set('title_for_layout', 'Páginas');
        $this->Auth->allow(array('home', 'signup', 'event', 'contact', 'view'));
    }

    public function home()
    {
        $this->loadModel('Banner');
        $banners = $this->Banner->find(
            'all',
            array(
                'conditions' => array(
                    'active' => 1
                ),
                'recursive' => -1
            )
        );
        $this->set('banners', $banners);
        $this->set('title_for_layout', 'Início');
    }

    function signup($unidadeId = null)
    {
        //Se não tem ID da Unidade
        if (empty($unidadeId)) {
            $unidadeId = $this->_getIdByAlias();
        }
        $this->loadModel('Unidade');
        $this->Unidade->id = $unidadeId;
        //Se esta Unidade não existe
        if (!$this->Unidade->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        //Seta o título da Unidade
        $this->set('title_for_layout', 'Novo Pedido');
        //Se postou o form
        if ($this->request->is('post')) {
            $this->loadModel('Cliente');
            $this->Cliente->create();
            //Completar com a data de final
            $inicio = strtotime($this->request->data['Ordemdeservico']['evento_inicio']);

            //Maior que 15 horas da tarde
            if (((int) date('H', $inicio)) >= 15) {
                $horasAdd = Configure::read('Os.addTimeEndJantar');
            } else {
                $horasAdd = Configure::read('Os.addTimeEndAlmoco');
            }

            $secondsToAdd = $horasAdd * (60 * 60);
            $eventoFim = $inicio + $secondsToAdd;
            $this->request->data['Ordemdeservico']['evento_fim'] = date('H:i', $eventoFim);

            //trata os dados
            if (!empty($this->request->data['Cliente']['nascimento'])) {
                $this->request->data['Cliente']['nascimento'] = $this->Alv->tratarData($this->request->data['Cliente']['nascimento']);
            }

            //Seta a Unidade
            $this->request->data['Cliente']['unidade_id'] =  $unidadeId;
            //Salva o Cliente
            if ($this->Cliente->saveAll($this->request->data['Cliente'])) {
                $this->loadModel('Ordemdeservico');
                $clienteId = $this->Cliente->getLastInsertId();
                //Trata os dados
                $this->request->data['Ordemdeservico']['cliente_id'] = $clienteId;
                $this->request->data['Ordemdeservico']['evento_data'] = $this->Alv->tratarData($this->request->data['Ordemdeservico']['evento_data']);
                $this->request->data['Ordemdeservico']['status'] = 'Criada';
                $arrayBebidas = unserialize($this->request->data['Ordemdeservico']['dados']['cardapio']['bebidas']);
                $this->request->data['Ordemdeservico']['dados']['cardapio']['bebidas'] = $arrayBebidas;
                $this->request->data['Ordemdeservico']['dados'] = serialize($this->request->data['Ordemdeservico']['dados']);
                //Salva a Ordem de Serviço                
                if ($this->Ordemdeservico->saveAll($this->request->data['Ordemdeservico'])) {
                    $osId = $this->Ordemdeservico->getLastInsertId();
                    $mensagem = '
                    Olá, ' . $this->request->data['Cliente']['nome'] . '! <br />
                    Segue em anexo os detalhes do seu evento.
                    <br /><br />
                    Qualquer dúvida estamos a disposição.
                    <br /><br />
                    Att.
                    <br />
                    Michalis Gastronomia
                    ';
                    $dados = array(
                        'nome' => 'Michalis Gastronomia',
                        'email' => 'rodrigoalvesnet@gmail.com',
                        // 'replyTo' => 'rodrigoalvesnet@gmail.com',
                        'assunto' => 'Detalhes do Evento',
                        'mensagem' => $mensagem
                    );
                    $this->Alv->enviarEmail($dados, $this->request->data['Cliente']['email']);
                    $this->Flash->success('Pedido <strong>' . $osId . '</strong> feito com sucesso!');
                    $this->redirect(array('action' => 'confirmation', $osId));
                } else {
                    $mensagem = 'Não foi possível salvar o registro';
                    $invalidFields = $this->Cliente->invalidFields();
                    if ($invalidFields) {
                        $mensagem .= '<ul>';
                        foreach ($invalidFields as $msgs) {
                            $msgAnterior = '';
                            foreach ($msgs as $msg) {
                                if ($msgAnterior != $msg) {
                                    $mensagem .= '<li>' . $msg . '</li>';
                                }
                                $msgAnterior = $msg;
                            }
                        }
                        $mensagem .= '</ul>';
                    }
                    $this->Flash->error($mensagem);
                }
            } else {
                $mensagem = 'Não foi possível salvar o registro';
                $invalidFields = $this->Cliente->invalidFields();
                if ($invalidFields) {
                    $mensagem .= '<ul>';
                    foreach ($invalidFields as $msgs) {
                        $msgAnterior = '';
                        foreach ($msgs as $msg) {
                            if ($msgAnterior != $msg) {
                                $mensagem .= '<li>' . $msg . '</li>';
                            }
                            $msgAnterior = $msg;
                        }
                    }
                    $mensagem .= '</ul>';
                }
                $this->Flash->error($mensagem);
            }
            //trata os dados
            if (!empty($this->request->data['Cliente']['nascimento'])) {
                $this->request->data['Cliente']['nascimento'] = $this->Alv->tratarData($this->request->data['Cliente']['nascimento'], 'pr');
            }
            if (!empty($this->request->data['Ordemdeservico']['evento_data'])) {
                $this->request->data['Ordemdeservico']['evento_data'] = $this->Alv->tratarData($this->request->data['Ordemdeservico']['evento_data'], 'pr');
            }
        }

        $this->loadModel('Cardapio');
        $listCardapios = $this->Cardapio->find(
            'list',
            array(
                'conditions' => array(
                    'ativo' => 1
                ),
                'recursive' => -1,
                'order' => 'nome ASC',
                'fields' => array(
                    'id',
                    'nome'
                )
            )
        );
        $this->set('listCardapios', $listCardapios);

        $this->loadModel('Bebida');
        $this->set('arrayBebidasAlcoolica', $this->Bebida->getBebidasAlcoolica());
        $this->set('arrayBebidasRefri', $this->Bebida->getBebidasRefri());

        //Pega os dados da Unidade
        // $this->_setUnidade($unidadeId);
    }

    function event($eventId = null)
    {
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

        $this->loadModel('Event');
        $event = $this->Event->find(
            'first',
            array(
                'conditions' => $arrayConditions,
                'order' => 'Event.start_date ASC',
                'contain' => array(
                    'Lot' => array(
                        'order' => 'start_date ASC'
                    ),
                    'Talker',
                    'Schedule'
                )
            )
        );
        $this->set('event', $event);
        $this->set('title_for_layout', $event['Event']['title']);
        $availableLot = $this->Event->checkAvailableLot($event['Event']['id']);
        $this->set('availableLot', $availableLot);
    }

    function contact()
    {
        $this->autoRender = false;
        if (!empty($this->data)) {
            $this->loadModel('Site');
            $siteConfig = $this->Site->find('first');
            $emailDestino = $siteConfig['Site']['email'];
            // $emailDestino = 'rodrigoalvesnet@gmail.com';
            $arrayDados = array();
            $arrayDados['nome'] = $this->data['Page']['name'];
            $arrayDados['assunto'] =  'Contato do Site - ' . $this->data['Page']['subject'];
            $arrayDados['mensagem'] = '<strong>Nome:</strong> ' . $this->data['Page']['name'] . '<br />
            <strong>E-mail: </strong>' . $this->data['Page']['email'] . '<br />
            <strong>Assunto: </strong>' . $this->data['Page']['subject'] . '<br/>
            <strong>Mensagem: </strong>' . $this->data['Page']['message'];
            //envia o email                
            if ($this->Alv->enviarEmail($arrayDados, $emailDestino)) {
                $this->Flash->success('Seu e-mail foi enviado com sucesso! Em breve retornaremos!');
            } else {
                $this->Flash->error('Não foi possível enviar o email');
            }
        }
        $this->redirect($this->referer());
    }

    // Lista todas as páginas
    public function admin_index()
    {
        $this->layout = 'default';
        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Pages')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Pages');
            }
            //atualiza a pagina
            $this->redirect($this->action);
        }

        //condição padrão
        $arrayConditions = array();
        //se o this->data não está vazio, prepara o filtro
        if (!empty($this->request->data)) {
            if (isset($this->request->data['Filtro']['title']) && !empty($this->request->data['Filtro']['title'])) {
                $arrayConditions['Page.title LIKE'] = '%' . $this->request->data['Filtro']['title'] . '%';
            }
            if (isset($this->request->data['Filtro']['active']) && !empty($this->request->data['Filtro']['active'])) {
                $arrayConditions['Page.active'] = $this->request->data['Filtro']['active'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Pages', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Pages')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Pages');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'recursive'     => -1
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('Page'));
        
    }

    // Cria página
    public function admin_add()
    {
        $this->layout = 'default';
        
        if ($this->request->is('post')) {
            $this->Page->create();
            if ($this->Page->save($this->request->data)) {
                $id = $this->Page->getLastInsertId();
                $anexoDir = '/uploads/page-' . $id;
                //se foi informado a foto
                if (!empty($this->data['Page']['new_banner_desktop']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Page']['new_banner_desktop'];
                    $this->_salvarImagem($id, $urlFoto, 'banner_desktop', false, $anexoDir);
                }
                //se foi informado a foto
                if (!empty($this->data['Page']['new_banner_mobile']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Page']['new_banner_mobile'];
                    $this->_salvarImagem($id, $urlFoto, 'banner_mobile', true, $anexoDir);
                }
                $this->Session->setFlash('Página criada com sucesso!', 'default', ['class' => 'success']);
                return $this->redirect(['action' => 'edit', $id]);
            }
            $this->Session->setFlash('Erro ao salvar a página.');
        }
        $this->set('bcLinks', array(
            'Páginas' => '/admin/pages/index'
        ));
        $this->set('title_for_layout', 'Nova Página');
    }

    // Edita página
    public function admin_edit($id = null)
    {
        $this->layout = 'default';
        if (!$id) throw new NotFoundException('Página inválida');

        $page = $this->Page->findById($id);
        if (!$page) throw new NotFoundException('Página não encontrada');

        if ($this->request->is(['post', 'put'])) {
            $this->Page->id = $id;
            if ($this->Page->save($this->request->data)) {
                $anexoDir = '/uploads/page-' . $id;
                //se foi informado a foto
                if (!empty($this->data['Page']['new_banner_desktop']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Page']['new_banner_desktop'];
                    $this->_salvarImagem($id, $urlFoto, 'banner_desktop', false, $anexoDir);
                }
                //se foi informado a foto
                if (!empty($this->data['Page']['new_banner_mobile']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Page']['new_banner_mobile'];
                    $this->_salvarImagem($id, $urlFoto, 'banner_mobile', true, $anexoDir);
                }
                $this->Session->setFlash('Página atualizada com sucesso!', 'default', ['class' => 'success']);
                // return $this->redirect(['action' => 'edit', $id]);
            } else {
                $this->Session->setFlash('Erro ao atualizar a página.');
            }
        }

        if (!$this->request->data) {
            $this->request->data = $page;
        }
        $this->set('bcLinks', array(
            'Páginas' => '/admin/pages/index'
        ));
        $this->set('title_for_layout', 'Editar Página');
        $this->render('admin_add');
    }

    // Exclui página
    public function admin_delete($id = null)
    {
        if ($this->request->is('get')) throw new MethodNotAllowedException();
        if ($this->Page->delete($id)) {
            $this->Session->setFlash('Página excluída.');
            return $this->redirect(['action' => 'index']);
        }
    }

    // Exibe a página pelo slug
    public function view($slug = null)
    {
        if (!$slug) throw new NotFoundException('Slug inválido');

        $page = $this->Page->findBySlug($slug);
        if (!$page) throw new NotFoundException('Página não encontrada');

        $this->set('title_for_layout', $page['Page']['title']);
        $this->set('page', $page);
    }

    function _salvarImagem($registroId, $urlFoto, $field, $resize = true, $anexoDir)
    {
        //faz o upload da imagem
        $imagemPath = $this->Imagem->upload($urlFoto, $resize, $anexoDir);
        //salva o caminho no banco
        if ($this->Page->updateAll(
            array('Page.' . $field => "'" . $imagemPath . "'"),
            array('Page.id' => $registroId)
        )) {
            return true;
        } else {
            return false;
        }
    }
}
