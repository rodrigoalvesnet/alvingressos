<?php
class AdicionalsController extends AppController
{

var $components = array('RequestHandler', 'Alv', 'Imagem');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Adicionais');
    }

    public function admin_index()
    {
        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Adicionals')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Adicionals');
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
            if (isset($this->request->data['Filtro']['title']) && !empty($this->request->data['Filtro']['title'])) {
                $arrayConditions['Adicional.title LIKE'] = '%' . $this->request->data['Filtro']['title'] . '%';
            }
            if (isset($this->request->data['Filtro']['active']) && !empty($this->request->data['Filtro']['active'])) {
                $arrayConditions['Adicional.active'] = $this->request->data['Filtro']['active'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Adicionals', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Adicionals')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Adicionals');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'recursive'     => -1
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('Adicional'));
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Adicional->create();
            if (isset($this->request->data['Adicional']['valor']) && !empty($this->request->data['Adicional']['valor'])) {
                $this->request->data['Adicional']['valor'] = $this->Alv->tratarValor($this->request->data['Adicional']['valor']);
            }
            if ($this->Adicional->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }
        $this->set('bcLinks', array(
            'Adicionals' => '/admin/Adicionals'
        ));
        $this->set('title_for_layout', 'Adicionar Adicional');
    }

    public function admin_edit($id)
    {
        $this->Adicional->id = $id;
        if (!$this->Adicional->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Adicional']['valor']) && !empty($this->request->data['Adicional']['valor'])) {
                $this->request->data['Adicional']['valor'] = $this->Alv->tratarValor($this->request->data['Adicional']['valor']);
            }
            if ($this->Adicional->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect($this->referer());
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Adicional->findById($id);
            if (isset($this->request->data['Adicional']['valor_custo']) && !empty($this->request->data['Adicional']['valor_custo'])) {
                $this->request->data['Adicional']['valor_custo'] = $this->Alv->tratarValor($this->request->data['Adicional']['valor_custo'], 'pt');
            }
            if (isset($this->request->data['Adicional']['valor']) && !empty($this->request->data['Adicional']['valor'])) {
                $this->request->data['Adicional']['valor'] = $this->Alv->tratarValor($this->request->data['Adicional']['valor'], 'pt');
            }
        }

        
        $this->set('bcLinks', array(
            'Adicionals' => '/admin/Adicionals'
        ));
        $this->set('title_for_layout', 'Editar Adicional');
        $this->render('admin_add');
    }
}
