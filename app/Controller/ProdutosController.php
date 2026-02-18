<?php
class ProdutosController extends AppController
{

var $components = array('RequestHandler', 'Alv', 'Imagem');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Produtos');
    }

    public function admin_index()
    {
        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Produtos')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Produtos');
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
                $arrayConditions['Produto.title LIKE'] = '%' . $this->request->data['Filtro']['title'] . '%';
            }
            if (isset($this->request->data['Filtro']['active']) && !empty($this->request->data['Filtro']['active'])) {
                $arrayConditions['Produto.active'] = $this->request->data['Filtro']['active'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Produtos', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Produtos')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Produtos');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'recursive'     => -1
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('Produto'));
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Produto->create();
            if (isset($this->request->data['Produto']['valor_custo']) && !empty($this->request->data['Produto']['valor_custo'])) {
                $this->request->data['Produto']['valor_custo'] = $this->Alv->tratarValor($this->request->data['Produto']['valor_custo']);
            }
            if (isset($this->request->data['Produto']['valor_venda']) && !empty($this->request->data['Produto']['valor_venda'])) {
                $this->request->data['Produto']['valor_venda'] = $this->Alv->tratarValor($this->request->data['Produto']['valor_venda']);
            }
            if ($this->Produto->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }
        $this->loadModel('ProdutosCategoria');
        $categorias = $this->ProdutosCategoria->find(
            'list',
            array(
                'fields' => [
                    'id',
                    'nome'
                ],
                'order' => 'nome ASC',
                'recursive' => -1
            )
        );
        $this->set('categorias', $categorias);
        $this->set('bcLinks', array(
            'Produtos' => '/admin/Produtos'
        ));
        $this->set('title_for_layout', 'Adicionar Produto');
    }

    public function admin_edit($id)
    {
        $this->Produto->id = $id;
        if (!$this->Produto->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Produto']['valor_custo']) && !empty($this->request->data['Produto']['valor_custo'])) {
                $this->request->data['Produto']['valor_custo'] = $this->Alv->tratarValor($this->request->data['Produto']['valor_custo']);
            }
            if (isset($this->request->data['Produto']['valor_venda']) && !empty($this->request->data['Produto']['valor_venda'])) {
                $this->request->data['Produto']['valor_venda'] = $this->Alv->tratarValor($this->request->data['Produto']['valor_venda']);
            }
            if ($this->Produto->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect($this->referer());
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Produto->findById($id);
            if (isset($this->request->data['Produto']['valor_custo']) && !empty($this->request->data['Produto']['valor_custo'])) {
                $this->request->data['Produto']['valor_custo'] = $this->Alv->tratarValor($this->request->data['Produto']['valor_custo'], 'pt');
            }
            if (isset($this->request->data['Produto']['valor_venda']) && !empty($this->request->data['Produto']['valor_venda'])) {
                $this->request->data['Produto']['valor_venda'] = $this->Alv->tratarValor($this->request->data['Produto']['valor_venda'], 'pt');
            }
        }
        $this->loadModel('ProdutosCategoria');
        $categorias = $this->ProdutosCategoria->find(
            'list',
            array(
                'fields' => [
                    'id',
                    'nome'
                ],
                'order' => 'nome ASC',
                'recursive' => -1
            )
        );
        $this->set('categorias', $categorias);
        $this->set('bcLinks', array(
            'Produtos' => '/admin/Produtos'
        ));
        $this->set('title_for_layout', 'Editar Produto');
        $this->render('admin_add');
    }
}
