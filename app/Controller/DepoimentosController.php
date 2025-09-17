<?php
class DepoimentosController extends AppController
{

    public $components = array('RequestHandler', 'Alv', 'Imagem');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('depoimentos'));
        $this->set('title_for_layout', 'Depoimentos');
    }

    public function admin_index()
    {

        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Depoimentos')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Depoimentos');
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
            if (isset($this->request->data['Filtro']['nome']) && !empty($this->request->data['Filtro']['nome'])) {
                $arrayConditions['Depoimento.nome LIKE'] = '%' . $this->request->data['Filtro']['nome'] . '%';
            }
            if (isset($this->request->data['Filtro']['active']) && !empty($this->request->data['Filtro']['active'])) {
                $arrayConditions['Depoimento.active'] = $this->request->data['Filtro']['active'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Depoimentos', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Depoimentos')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Depoimentos');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'recursive'     => -1
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('Depoimento'));
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Depoimento->create();
            if ($this->Depoimento->save($this->request->data)) {
                $id = $this->Depoimento->getLastInsertId();
                $anexoDir = '/uploads/depoimentos';
                //se foi informado a foto
                if (!empty($this->data['Depoimento']['new_foto']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Depoimento']['new_foto'];
                    $this->_salvarImagem($id, $urlFoto, 'foto', false, $anexoDir);
                }
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }
        $this->set('bcLinks', array(
            'Depoimentos' => '/admin/Depoimentos'
        ));
        $this->set('title_for_layout', 'Adicionar Depoimento');
    }

    public function admin_edit($id)
    {
        $this->Depoimento->id = $id;
        if (!$this->Depoimento->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            // pr($this->data);exit();
            if ($this->Depoimento->save($this->request->data)) {
                $anexoDir = '/uploads/depoimentos';
                //se foi informado a foto
                if (!empty($this->data['Depoimento']['new_foto']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Depoimento']['new_foto'];
                    $this->_salvarImagem($id, $urlFoto, 'foto', false, $anexoDir);
                }
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect($this->referer());
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Depoimento->findById($id);
        }
        $this->set('bcLinks', array(
            'Depoimentos' => '/admin/Depoimentos'
        ));
        $this->set('title_for_layout', 'Editar Depoimento');
        $this->render('admin_add');
    }

    public function admin_delete($id = null)
    {
        if (!$id || !$this->Depoimento->exists($id)) {
            throw new NotFoundException('Depoimento não encontrado');
        }
        if ($this->Depoimento->delete($id)) {
            $this->Session->setFlash('Depoimento excluído com sucesso');
        }
        return $this->redirect(array('action' => 'index'));
    }

    function _salvarImagem($registroId, $urlFoto, $field, $resize = true, $anexoDir)
    {
        //faz o upload da imagem
        $imagemPath = $this->Imagem->upload($urlFoto, $resize, $anexoDir);
        //salva o caminho no banco
        if ($this->Depoimento->updateAll(
            array('Depoimento.' . $field => "'" . $imagemPath . "'"),
            array('Depoimento.id' => $registroId)
        )) {
            return true;
        } else {
            return false;
        }
    }

    function depoimentos(){
        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';

        $depoimentos = Cache::read('depoimentos', 'depoimentos');

        if ($depoimentos === false) {
            $depoimentos = $this->Depoimento->find('all', array(
                'conditions' => array(
                    'active' => 1
                ),
                'recursive' => -1
            ));

            Cache::write('depoimentos', $depoimentos, 'depoimentos');
        }

        return $depoimentos;
    }
}
