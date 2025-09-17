<?php
class BannersController extends AppController
{
    public $helpers = ['Html', 'Form'];
    public $components = array('RequestHandler', 'Alv', 'Imagem');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Banners');
    }

    public function admin_index()
    {

        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Banners')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Banners');
            }
            //atualiza a pagina
            $this->redirect($this->action);
        }

        //condição padrão
        $arrayConditions = array();
        //se o this->data não está vazio, prepara o filtro
        if (!empty($this->request->data)) {
            if (isset($this->request->data['Filtro']['title']) && !empty($this->request->data['Filtro']['title'])) {
                $arrayConditions['Banner.title LIKE'] = '%' . $this->request->data['Filtro']['title'] . '%';
            }
            if (isset($this->request->data['Filtro']['active']) && !empty($this->request->data['Filtro']['active'])) {
                $arrayConditions['Banner.active'] = $this->request->data['Filtro']['active'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Banners', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Banners')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Banners');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'recursive'     => -1
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('Banner'));
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Banner->create();
            if ($this->Banner->save($this->request->data)) {
                $id = $this->Banner->getLastInsertId();
                $anexoDir = '/uploads/banners';
                //se foi informado a foto
                if (!empty($this->data['Banner']['new_image']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Banner']['new_image'];
                    $this->_salvarImagem($id, $urlFoto, 'image', false, $anexoDir);
                }
                //se foi informado a foto
                if (!empty($this->data['Banner']['new_image']['tmp_name_mobile'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Banner']['tmp_name_mobile'];
                    $this->_salvarImagem($id, $urlFoto, 'image_mobile', false, $anexoDir);
                }
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }
        $this->set('bcLinks', array(
            'Banners' => '/admin/banners/index'
        ));
        $this->set('title_for_layout', 'Adicionar Grupo');
    }

    public function admin_edit($id)
    {
        $this->Banner->id = $id;
        if (!$this->Banner->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Banner->save($this->request->data)) {
                $anexoDir = '/uploads/banners';
                //se foi informado a foto
                if (!empty($this->data['Banner']['new_image']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Banner']['new_image'];
                    $this->_salvarImagem($id, $urlFoto, 'image', false, $anexoDir);
                }
                //se foi informado a foto
                if (!empty($this->data['Banner']['new_image_mobile']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Banner']['new_image_mobile'];
                    $this->_salvarImagem($id, $urlFoto, 'image_mobile', false, $anexoDir);
                }
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect($this->referer());
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Banner->findById($id);
        }
        $this->set('bcLinks', array(
            'Banners' => '/admin/banners/index'
        ));
        $this->set('title_for_layout', 'Editar Grupo');
        $this->render('admin_add');
    }

    public function admin_delete($id = null)
    {
        if ($this->request->is('get')) throw new MethodNotAllowedException();
        if ($this->Banner->delete($id)) {
            $this->Session->setFlash('Banner Excluído.');
            return $this->redirect(['action' => 'index']);
        }
    }

    function _salvarImagem($registroId, $urlFoto, $field, $resize = true, $anexoDir)
    {
        //faz o upload da imagem
        $imagemPath = $this->Imagem->upload($urlFoto, $resize, $anexoDir);
        //salva o caminho no banco
        if ($this->Banner->updateAll(
            array('Banner.' . $field => "'" . $imagemPath . "'"),
            array('Banner.id' => $registroId)
        )) {
            return true;
        } else {
            return false;
        }
    }
}
