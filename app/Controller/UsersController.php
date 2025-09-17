<?php
class UsersController extends AppController
{

    public $components = array('Alv', 'RequestHandler');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('login', 'register', 'recovery', 'logout');
        $this->set('title_for_layout', 'Usuários');
    }

    public function register()
    {
        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';
        if ($this->request->is('post')) {
            $this->User->create();
            //tratar os dados
            $roleId = 3; //Comprador
            $this->request->data['User']['role_id'] = $roleId;
            $this->request->data['User']['birthday'] = $this->Alv->tratarData($this->request->data['User']['birthday']);
            $this->request->data['User']['active'] = 1;
            //Se salvar corretamente
            if ($this->User->save($this->request->data)) {

                // atualizar Aros
                $aro_foreign_key = $this->User->Aro->find('first', array(
                    'conditions' => array(
                        'foreign_key' => $this->User->read('role_id')['User']['role_id']
                    ),
                    'recursive' => false,
                    'fields' => 'id'
                ));

                $this->User->Aro->save(array(
                    'model' => 'User',
                    'foreign_key' => $this->User->id,
                    'parent_id' => $aro_foreign_key['Aro']['id']
                ));

                // Pega os dados do usuário recém-criado
                $user = $this->User->findById($this->User->id);

                // Faz login automaticamente
                if ($this->Auth->login($user['User'])) {
                    $this->Session->setFlash(__('Bem-vindo, seu cadastro foi realizado com sucesso!'), 'default', array(), 'success');
                    return $this->redirect('/');
                } else {
                    $this->Session->setFlash(__('Não foi possível logar automaticamente. Faça login manualmente.'));
                    return $this->redirect(array('action' => 'login'));
                }

            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }
    }

    public function login()
    {
        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';
        //se foi enviado o form
        if (!empty($this->request->data)) {
            if ($this->Auth->login()) {
                // $this->redirect($this->Auth->redirect());
                if($this->data['User']['isAjax']){
                    return $this->redirect('/checkout/payment');
                }
                return $this->redirect('/');
            } else {
                $this->Flash->error('E-mail ou senha inválidos');
            }
        }
    }

    function account()
    {
        //Se está salvando
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['User']['birthday'] = $this->Alv->tratarData($this->request->data['User']['birthday']);
            if ($this->User->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }
        //Pega os dados da conta
        $this->request->data = $this->User->find(
            'first',
            array(
                'conditions' => array(
                    'User.id' => $this->Auth->user('id')
                )
            )
        );
        $this->request->data['User']['birthday'] = $this->Alv->tratarData($this->request->data['User']['birthday'], 'pt');
        unset($this->request->data['User']['password']);

        $this->set('title_for_layout', 'Minha Conta');

        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';
    }

    public function logout()
    {
        $this->Session->destroy();
        $this->redirect($this->Auth->logout());
    }

    public function admin_index()
    {

        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Users')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Users');
            }
            //atualiza a pagina
            $this->redirect(array(
                'admin' => true
            ));
        }

        //condição padrão
        $arrayConditions = array();
        //se o this->data não está vazio, prepara o filtro
        if (!empty($this->request->data)) {
            if ($this->request->data['Filtro']['name']) {
                $arrayConditions['User.name LIKE'] = '%' . $this->request->data['Filtro']['name'] . '%';
            }
            if ($this->request->data['Filtro']['email']) {
                $arrayConditions['User.email'] = $this->request->data['Filtro']['email'];
            }
            if ($this->request->data['Filtro']['role_id']) {
                $arrayConditions['User.role_id'] = $this->request->data['Filtro']['role_id'];
            }
            if ($this->request->data['Filtro']['unidade_id']) {
                $arrayConditions['User.unidade_id'] = $this->request->data['Filtro']['unidade_id'];
            }
            if ($this->request->data['Filtro']['active']) {
                $arrayConditions['User.active'] = $this->request->data['Filtro']['active'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Users', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Users')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Users');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'contain'     => array(
                'Unidade' => array(
                    'name'
                ),
                'Role' => array(
                    'title'
                )
            ),
            'order' => 'User.name ASC'
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('User'));

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

        $this->loadModel('Role');
        //Pega o grupo de quem está logado
        $roleId = $this->Session->read('Auth.User.role_id');
        //condições padrões
        $arrayConditionsRoles = array(
            'active' => 1
        );
        //Se é um admin
        if ($roleId <> 1) {
            $arrayConditionsRoles['id <> '] = 1;
        }
        $roles = $this->Role->find(
            'list',
            array(
                'conditions' => $arrayConditionsRoles,
                'fields' => array(
                    'id',
                    'title'
                ),
                'order' => 'title ASC',
                'recursive' => -1
            )
        );
        $this->set('roles', $roles);
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->User->create();
            //Tratar os dados
            $this->request->data['User']['birthday'] = $this->Alv->tratarData($this->request->data['User']['birthday']);
            if ($this->User->save($this->request->data)) {

                // atualizar Aros
                $aro_foreign_key = $this->User->Aro->find('first', array(
                    'conditions' => array(
                        'foreign_key' => $this->User->read('role_id')['User']['role_id']
                    ),
                    'recursive' => false,
                    'fields' => 'id'
                ));
                $this->User->Aro->save(array(
                    'model' => 'User',
                    'foreign_key' => $this->User->id,
                    'parent_id' => $aro_foreign_key['Aro']['id']
                ));

                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
                //Tratar os dados
                $this->request->data['User']['birthday'] = $this->Alv->tratarData($this->request->data['User']['birthday'], 'pt');
            }
        }
        $this->loadModel('Role');
        //Pega o grupo de quem está logado
        $roleId = $this->Session->read('Auth.User.role_id');
        //condições padrões
        $arrayConditionsRoles = array(
            'active' => 1
        );
        //Se é um admin
        if ($roleId <> 1) {
            $arrayConditionsRoles['id <> '] = 1;
        }
        $roles = $this->Role->find(
            'list',
            array(
                'conditions' => $arrayConditionsRoles,
                'fields' => array(
                    'id',
                    'title'
                ),
                'order' => 'title ASC',
                'recursive' => -1
            )
        );
        $this->set('roles', $roles);

        $this->set('bcLinks', array(
            'Usuários' => '/Users'
        ));
        $this->loadModel('Unidade');
        $unidades = $this->Unidade->find(
            'list',
            array(
                'fields' => array(
                    'id',
                    'name'
                ),
                'fields' => array(
                    'id',
                    'name'
                )
            )
        );
        $this->set('unidades', $unidades);
        $this->set('title_for_layout', 'Adicionar Usuário');
    }

    public function admin_edit($id = null)
    {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if (empty($this->request->data['User']['password'])) {
                unset($this->request->data['User']['password']);
            }
            //Tratar os dados
            $this->request->data['User']['birthday'] = $this->Alv->tratarData($this->request->data['User']['birthday']);
            if ($this->User->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->User->findById($id);
            unset($this->request->data['User']['password']);
        }
        //Tratar os dados
        $this->request->data['User']['birthday'] = $this->Alv->tratarData($this->request->data['User']['birthday'], 'pt');

        $this->loadModel('Role');
        //Pega o grupo de quem está logado
        $roleId = $this->Session->read('Auth.User.role_id');
        //condições padrões
        $arrayConditionsRoles = array(
            'active' => 1
        );
        //Se é um admin
        if ($roleId <> 1) {
            $arrayConditionsRoles['id <> '] = 1;
        }
        $roles = $this->Role->find(
            'list',
            array(
                'conditions' => $arrayConditionsRoles,
                'fields' => array(
                    'id',
                    'title'
                ),
                'order' => 'title ASC',
                'recursive' => -1
            )
        );
        $this->set('roles', $roles);

        $this->set('bcLinks', array(
            'Usuários' => '/Users'
        ));

        $this->loadModel('Unidade');
        $unidades = $this->Unidade->find(
            'list',
            array(
                'fields' => array(
                    'id',
                    'name'
                )
            )
        );
        $this->set('unidades', $unidades);
        $this->set('title_for_layout', 'Editar Usuário');
        $this->render('admin_add');
    }

    function recovery()
    {
        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';
        //se foi enviada a solicitação de login:
        if ($this->request->data) {
            //procura o email
            $User = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.email' => trim($this->data['User']['email']),
                        'User.active' => 1
                    ),
                    'fields' => array(
                        'User.id',
                        'User.name',
                        'User.password',
                        'User.email',
                    ),
                    'recursive' => -1
                )
            );
            //se encontrou o usuário
            if (!empty($User)) {

                //gerar nova senha
                $nova_senha = $this->User->gerar_senha(8, false, true, true, false);
                //criptografa a nova senha
                $nova_senha_criptografada = Security::hash($nova_senha, null, true);

                //Se atualizar o cadastro com a nova senha:
                if ($this->User->updateAll(
                    array('User.password' =>  "'" . $nova_senha_criptografada . "'"),
                    array('User.id' => $User['User']['id'])
                )) {
                    $arrayDados = array();
                    $arrayDados['nome'] = Configure::read('Sistema.title');
                    $arrayDados['email'] = 'contato@templodasaguias.com.br';
                    $arrayDados['assunto'] =  'Recuperação de Senha';
                    $arrayDados['mensagem'] = '
                        Prezado usuário, <br /><br /> Foi solicitado uma nova senha para acessar <strong>' . Configure::read('Sistema.title') . '</strong>,
                        utilize <b>' . $this->data['User']['email'] . '</b> com a nova senha: <b>' . $nova_senha . '</b><br /><br />
                        <br /><br />
                        <a href="https://ingresso.templodasaguias.com.br/" target="_blank">Clique aqui para acessar o site de ingressos</a>
                        <br /><br />
                        Antenciosamente<br /><br />
                        Templo das Águias';
                    //envia o email                
                    if ($this->Alv->enviarEmail($arrayDados, trim($this->data['User']['email']))) {
                        $this->Flash->success('Uma nova senha foi enviada para o e-mail <b>' . $this->data['User']['email'] . '</b>');
                    } else {
                        $this->Flash->error('Não foi possível enviar o email. Informe o suporte técnico.');
                    }
                    //se deu erro na atualização da nova senha:
                } else {
                    $this->Flash->error('Não foi possível gerar e enviar uma nova senha por email. Informe o suporte técnico.');
                }
                //redireciona para a tela de login
                return $this->redirect('login');
                //se não encontrou  
            } else {
                $this->Flash->error('Nenhum usuário cadastrado com este e-mail.');
            }
        }
    }

    public function autocomplete()
    {
        Configure::write('debug', 1);
        $this->autorender = false;
        //se não está no
        $registros = $this->User->find(
            'all',
            array(
                'conditions' => array(
                    'name LIKE' => '%' . $this->params->query['term'] . '%',
                    'active' => -1
                ),
                'order' => 'name ASC',
                'fields' => array(
                    'id',
                    'name',
                ),
                'recursive' => -1,
                'limit' => 10
            )
        );

        //se NÃO econtrou nenhum registro
        if (empty($registros)) {
            exit();
        }

        $arrayRetorno = array();
        //percorre os registro
        foreach ($registros as $key => $registro) {
            //guarda os dados prontos para serem exibidos
            $arrayRetorno[$key]['value'] = $registro['User']['id'];
            $arrayRetorno[$key]['label'] = $registro['User']['name'];
        }
        //exibe o resultado
        echo json_encode($arrayRetorno);
        exit();
    }
}
