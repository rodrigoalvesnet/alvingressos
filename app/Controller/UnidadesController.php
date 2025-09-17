<?php
class UnidadesController extends AppController
{

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Unidades');
    }

    public function admin_index()
    {

        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Unidades')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Unidades');
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
            if (isset($this->request->data['Filtro']['name']) && !empty($this->request->data['Filtro']['name'])) {
                $arrayConditions['Unidade.name LIKE'] = '%' . $this->request->data['Filtro']['name'] . '%';
            }
            if (isset($this->request->data['Filtro']['active']) && !empty($this->request->data['Filtro']['active'])) {
                $arrayConditions['Unidade.active'] = $this->request->data['Filtro']['active'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Unidades', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Unidades')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Unidades');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'recursive'     => -1
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('Unidade'));
        
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Unidade->create();
            if ($this->Unidade->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }
        $this->set('bcLinks', array(
            'Unidades' => '/Unidades'
        ));
        $this->set('title_for_layout', 'Adicionar Unidade');
    }

    public function admin_edit($id)
    {
        $this->Unidade->id = $id;
        if (!$this->Unidade->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Unidade->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect($this->referer());
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Unidade->findById($id);
        }
        $this->set('bcLinks', array(
            'Unidades' => '/Unidades'
        ));
        $this->set('title_for_layout', 'Editar Unidade');
        $this->render('admin_add');
    }
}
