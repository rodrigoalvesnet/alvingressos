<?php
class FormasPagamentosController extends AppController
{

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Formas de Pagamentos');
    }

    public function admin_index()
    {

        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.FormasPagamentos')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.FormasPagamentos');
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
                $arrayConditions['FormasPagamento.nome LIKE'] = '%' . $this->request->data['Filtro']['nome'] . '%';
            }
            if (isset($this->request->data['Filtro']['acativotive']) && !empty($this->request->data['Filtro']['ativo'])) {
                $arrayConditions['FormasPagamento.ativo'] = $this->request->data['Filtro']['ativo'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.FormasPagamentos', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.FormasPagamentos')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.FormasPagamentos');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'recursive'     => -1
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('FormasPagamento'));
        
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->FormasPagamento->create();
            if ($this->FormasPagamento->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }
        $this->set('bcLinks', array(
            'Formas de Pagamentos' => '/admin/FormasPagamentos'
        ));
        $this->set('title_for_layout', 'Adicionar Forma de Pagamento');
    }

    public function admin_edit($id)
    {
        $this->FormasPagamento->id = $id;
        if (!$this->FormasPagamento->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->FormasPagamento->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect($this->referer());
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->FormasPagamento->findById($id);
        }
        $this->set('bcLinks', array(
            'Formas de Pagamentos' => '/admin/FormasPagamentos'
        ));
        $this->set('title_for_layout', 'Editar Forma de Pagamento');
        $this->render('admin_add');
    }

}
