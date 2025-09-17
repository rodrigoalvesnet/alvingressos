<?php
class MigratesController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv');


    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('index', 'users'));
    }

    public function index()
    {
    }

    function users()
    {
        $arraySave = array();
        //Muda o banco dados
        $this->Migrate->useDbConfig = 'old_ingressos';
        $sql = 'SELECT id, name, email, cpf, birthday, whatsapp, church_id, created_at, updated_at FROM users ORDER BY id ASC;';
        $query = $this->Migrate->query($sql);

        if (!empty($query)) {

            foreach ($query as $k => $data) {
                //Monta o novo password
                $newPassword = $this->Alv->somenteNumeros(trim($data['users']['cpf']));
                //Começa a montar o array
                $arraySave[$k] = $data['users'];
                $arraySave[$k]['role_id'] = 3;
                $arraySave[$k]['active'] = 1;
                $arraySave[$k]['password'] = $newPassword;
                $arraySave[$k]['password2'] = $newPassword;
                $arraySave[$k]['phone'] = $data['users']['whatsapp'];
                $arraySave[$k]['created'] = $data['users']['created_at'];
                $arraySave[$k]['modidfied'] = $data['users']['updated_at'];
            }
        }
        //Muda o banco dados
        $this->Migrate->useDbConfig = 'default';
        //Se tem dados para salvar
        if (!empty($arraySave)) {
            $arrayErrors = array();
            $this->loadModel('User');
            foreach ($arraySave as $save) {
                if (!$this->User->save($save)) {
                    $arrayErrors[] = $save;
                }
            }
            if (!empty($arrayErrors)) {
                pr($arrayErrors);
                exit();
            }
            $this->Flash->success('Usuários importados com sucesso!');
        } else {
            $this->Flash->error('nenhum registro para importar de Usuários');
        }
        //redireciona
        $this->redirect(array(
            'controller' => 'Migrates',
            'action' => 'index'
        ));
    }

    function churches()
    {
        $arraySave = array();
        //Muda o banco dados
        $this->Migrate->useDbConfig = 'old_ingressos';
        $sql = 'SELECT * FROM churches ORDER BY id ASC;';
        $query = $this->Migrate->query($sql);
        // pr($query);
        // exit();
        if (!empty($query)) {
            foreach ($query as $k => $data) {
                //Começa a montar o array
                $arraySave[$k] = $data['churches'];
                $arraySave[$k]['active'] = 1;
            }
        }
        //Muda o banco dados
        $this->Migrate->useDbConfig = 'default';
        //Se tem dados para salvar
        if (!empty($arraySave)) {
            // pr($arraySave);exit();
            $arrayErrors = array();
            $this->loadModel('Unidade');
            foreach ($arraySave as $save) {
                if (!$this->Unidade->save($save)) {
                    $arrayErrors[] = $save;
                }
            }
            if (!empty($arrayErrors)) {
                pr($arrayErrors);
                exit();
            }
            $this->Flash->success('Igrejas importados com sucesso!');
        } else {
            $this->Flash->error('nenhum registro para importar de Igrejas');
        }
        //redireciona
        $this->redirect(array(
            'controller' => 'Migrates',
            'action' => 'index'
        ));
    }

    function events()
    {
        $arraySave = array();
        //Muda o banco dados
        $this->Migrate->useDbConfig = 'old_ingressos';
        $sql = 'SELECT * FROM events ORDER BY id ASC;';
        $query = $this->Migrate->query($sql);
        // pr($query);
        // exit();
        if (!empty($query)) {
            foreach ($query as $k => $data) {
                //Começa a montar o array
                $arraySave[$k] = $data['events'];
                $arraySave[$k]['title'] = $data['events']['name'];
                $arraySave[$k]['description'] = $data['events']['about'];
                $arraySave[$k]['banner_desktop'] = $data['events']['banner_web'];
                $arraySave[$k]['status'] = 'closed';
                $arraySave[$k]['created'] = $data['events']['created_at'];
                $arraySave[$k]['modified'] = $data['events']['updated_at'];
            }
        }
        //Muda o banco dados
        $this->Migrate->useDbConfig = 'default';
        //Se tem dados para salvar
        if (!empty($arraySave)) {
            // pr($arraySave);exit();
            $arrayErrors = array();
            $this->loadModel('Event');
            foreach ($arraySave as $save) {
                if (!$this->Event->save($save)) {
                    $arrayErrors[] = $save;
                }
            }
            if (!empty($arrayErrors)) {
                pr($arrayErrors);
                exit();
            }
            $this->Flash->success('Igrejas importados com sucesso!');
        } else {
            $this->Flash->error('nenhum registro para importar de eventos');
        }
        //redireciona
        $this->redirect(array(
            'controller' => 'Migrates',
            'action' => 'index'
        ));
    }

    function orders()
    {
        $arraySave = array();
        //Muda o banco dados
        $this->Migrate->useDbConfig = 'old_ingressos';
        $sql = 'SELECT * FROM orders ORDER BY id ASC;';
        $query = $this->Migrate->query($sql);
        // pr($query);
        // exit();
        if (!empty($query)) {
            foreach ($query as $k => $data) {
                switch ($data['orders']['status']) {
                    case 'P':
                        $status = 'pending';
                        break;
                    case 'A':
                        $status = 'approved';
                        break;
                    case 'R':
                        $status = 'rejected';
                        break;
                }
                $valor = substr($data['orders']['value'], 0, -2);
                //Começa a montar o array
                $arraySave[$k] = $data['orders'];
                $arraySave[$k]['status'] = $status;
                $arraySave[$k]['payment_type'] = 'pix_old';
                $arraySave[$k]['value'] = number_format($valor, 2, '.', '');
                $arraySave[$k]['phone'] = $data['orders']['whatsapp'];
                $arraySave[$k]['created'] = $data['orders']['created_at'];
                $arraySave[$k]['modified'] = $data['orders']['updated_at'];
            }
        }
        //Muda o banco dados
        $this->Migrate->useDbConfig = 'default';
        //Se tem dados para salvar
        if (!empty($arraySave)) {
            // pr($arraySave);
            // exit();
            $arrayErrors = array();
            $this->loadModel('Order');
            foreach ($arraySave as $save) {
                if (!$this->Order->save($save)) {
                    $arrayErrors[] = $save;
                }
            }
            if (!empty($arrayErrors)) {
                pr($arrayErrors);
                exit();
            }
            $this->Flash->success('Pedidos importados com sucesso!');
        } else {
            $this->Flash->error('nenhum registro para importar de Pedidos');
        }
        //redireciona
        $this->redirect(array(
            'controller' => 'Migrates',
            'action' => 'index'
        ));
    }

    function anexos()
    {
        $arraySave = array();
        //Muda o banco dados
        $this->Migrate->useDbConfig = 'old_ingressos';
        $sql = 'SELECT * FROM documents WHERE created_at > "2023-01-01 00:00:00" ORDER BY id ASC;';
        $query = $this->Migrate->query($sql);
        // pr($query);
        // exit();
        if (!empty($query)) {
            foreach ($query as $k => $data) {
                //Começa a montar o array
                $arraySave[$k] = $data['documents'];
                $arraySave[$k]['path'] = '/uploads/' . $data['documents']['path'];
                $arraySave[$k]['created'] = $data['documents']['created_at'];
                $arraySave[$k]['modified'] = $data['documents']['updated_at'];
            }
        }
        //Muda o banco dados
        $this->Migrate->useDbConfig = 'default';
        //Se tem dados para salvar
        if (!empty($arraySave)) {
            // pr($arraySave);
            // exit();
            $arrayErrors = array();
            $this->loadModel('Attachment');
            foreach ($arraySave as $save) {
                if (!$this->Attachment->save($save)) {
                    $arrayErrors[] = $save;
                }
            }
            if (!empty($arrayErrors)) {
                pr($arrayErrors);
                exit();
            }
            $this->Flash->success('Igrejas importados com sucesso!');
        } else {
            $this->Flash->error('nenhum registro para importar de Anexos');
        }
        //redireciona
        $this->redirect(array(
            'controller' => 'Migrates',
            'action' => 'index'
        ));
    }

    function respostas()
    {
        $arraySave = array();
        //Muda o banco dados
        $this->Migrate->useDbConfig = 'old_ingressos';
        $sql = 'SELECT * FROM field_order WHERE field_id = 8 ORDER BY id ASC;';
        $query = $this->Migrate->query($sql);
        // pr($query);
        // exit();
        if (!empty($query)) {
            foreach ($query as $k => $data) {
                //Começa a montar o array
                $arraySave[$k] = $data['field_order'];
                $arraySave[$k]['created'] = $data['field_order']['created_at'];
                $arraySave[$k]['modified'] = $data['field_order']['updated_at'];
            }
        }
        //Muda o banco dados
        $this->Migrate->useDbConfig = 'default';
        //Se tem dados para salvar
        if (!empty($arraySave)) {
            // pr($arraySave);
            // exit();
            $arrayErrors = array();
            $this->loadModel('Response');
            foreach ($arraySave as $save) {
                if (!$this->Response->save($save)) {
                    $arrayErrors[] = $save;
                }
            }
            if (!empty($arrayErrors)) {
                pr($arrayErrors);
                exit();
            }
            $this->Flash->success('Repostas importados com sucesso!');
        } else {
            $this->Flash->error('nenhum registro para importar de Repostas');
        }
        //redireciona
        $this->redirect(array(
            'controller' => 'Migrates',
            'action' => 'index'
        ));
    }
}
