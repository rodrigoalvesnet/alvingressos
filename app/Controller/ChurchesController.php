<?php
class ChurchesController extends AppController
{

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Igrejas');
    }

    public function index()
    {

        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Churches')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Churches');
            }
            //atualiza a pagina
            $this->redirect($this->action);
        }

        //condição padrão
        $arrayConditions = array();
        //se o this->data não está vazio, prepara o filtro
        if (!empty($this->request->data)) {
            if (isset($this->request->data['Filtro']['name']) && !empty($this->request->data['Filtro']['name'])) {
                $arrayConditions['Church.name LIKE'] = '%' . $this->request->data['Filtro']['name'] . '%';
            }
            if (isset($this->request->data['Filtro']['active']) && !empty($this->request->data['Filtro']['active'])) {
                $arrayConditions['Church.active'] = $this->request->data['Filtro']['active'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Churches', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Churches')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Churches');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'recursive'     => -1
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('Church'));
        
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Church->create();
            if ($this->Church->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }
        $this->set('bcLinks', array(
            'Igrejas' => '/Churches'
        ));
        $this->set('title_for_layout', 'Adicionar Igreja');
    }

    public function edit($id)
    {
        $this->Church->id = $id;
        if (!$this->Church->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Church->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect($this->referer());
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Church->findById($id);
        }
        $this->set('bcLinks', array(
            'Igrejas' => '/Churches'
        ));
        $this->set('title_for_layout', 'Editar Igreja');
        $this->render('add');
    }
}
