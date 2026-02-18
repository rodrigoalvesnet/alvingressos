<?php
class ProdutosCategoriasController extends AppController
{

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Categorias de Produtos');
    }

    public function admin_index()
    {
        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.ProdutosCategorias')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.ProdutosCategorias');
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
                $arrayConditions['ProdutosCategoria.nome LIKE'] = '%' . $this->request->data['Filtro']['title'] . '%';
            }
            if (isset($this->request->data['Filtro']['active']) && !empty($this->request->data['Filtro']['active'])) {
                $arrayConditions['ProdutosCategoria.ativo'] = $this->request->data['Filtro']['active'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.ProdutosCategorias', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.ProdutosCategorias')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.ProdutosCategorias');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'recursive'     => -1
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('ProdutosCategoria'));
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->ProdutosCategoria->create();
            if ($this->ProdutosCategoria->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }
        $this->set('bcLinks', array(
            'Categorias de Produtos' => '/admin/produtos_categorias'
        ));
        $this->set('title_for_layout', 'Adicionar Categoria');
    }

    public function admin_edit($id)
    {
        $this->ProdutosCategoria->id = $id;
        if (!$this->ProdutosCategoria->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->ProdutosCategoria->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect($this->referer());
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->ProdutosCategoria->findById($id);
        }
        $this->set('bcLinks', array(
            'Categorias de Produtos' => '/admin/produtos_categorias'
        ));
        $this->set('title_for_layout', 'Editar Categoria');
        $this->render('admin_add');
    }

}
