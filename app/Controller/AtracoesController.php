<?php

class AtracoesController extends AppController
{

    public $uses = ['Atracao'];

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Atrações');
    }

    /**
     * Lista atrações
     * /estadias-atracoes
     */

    public function admin_index()
    {

        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Atracoes')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Atracoes');
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
                $arrayConditions['Atracao.nome LIKE'] = '%' . $this->request->data['Filtro']['nome'] . '%';
            }
            if (isset($this->request->data['Filtro']['active']) && !empty($this->request->data['Filtro']['active'])) {
                $arrayConditions['Atracao.active'] = $this->request->data['Filtro']['active'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Atracoes', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Atracoes')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Atracoes');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'contain'     => [
                'Unidade' => [
                    'name'
                ]
            ]
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('Atracao'));
        
    }

    /**
     * Criar atração
     */
    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Atracao->create();

            // defaults (se não vier)
            if (!isset($this->request->data['Atracao']['ativo'])) {
                $this->request->data['Atracao']['ativo'] = 1;
            }

            if ($this->Atracao->save($this->request->data)) {
                $payload = ['ok' => true, 'message' => 'Atração criada.'];
                return $this->_respond($payload, ['action' => 'index']);
            }

            $payload = ['ok' => false, 'error' => 'Erro ao salvar atração.'];
            return $this->_respond($payload);
        }

        $this->loadModel('Unidade');
        $unidades = $this->Unidade->find(
            'list',
            array(
                'conditions' => array(
                    'active' => 1
                ),
                'recursive' => -1,
                'fields' => array(
                    'id',
                    'name'
                ),
                'order' => array(
                    'name' => 'ASC'
                )
            )
        );
        $this->set(compact('unidades'));
        $this->set('title_for_layout', 'Adicionar Atração');
    }

    /**
     * Editar atração
     */
    public function admin_edit($id = null)
    {
        $id = (int)$id;

        $row = $this->Atracao->find('first', [
            'conditions' => ['Atracao.id' => $id],
            'recursive' => -1
        ]);
        if (empty($row)) {
            throw new NotFoundException('Atração não encontrada');
        }

        if ($this->request->is(['post', 'put'])) {
            $this->Atracao->id = $id;

            if ($this->Atracao->save($this->request->data)) {
                $payload = ['ok' => true, 'message' => 'Atração atualizada.'];
                return $this->_respond($payload, ['action' => 'index']);
            }

            $payload = ['ok' => false, 'error' => 'Erro ao atualizar atração.'];
            return $this->_respond($payload);
        }

        $this->loadModel('Unidade');
        $unidades = $this->Unidade->find(
            'list',
            array(
                'conditions' => array(
                    'active' => 1
                ),
                'recursive' => -1,
                'fields' => array(
                    'id',
                    'name'
                ),
                'order' => array(
                    'name' => 'ASC'
                )
            )
        );
        // GET
        $this->request->data = $row;
        $this->set(compact('row', 'unidades'));
        $this->set('title_for_layout', 'Editar Atração');
        $this->render('admin_add');
    }

    /**
     * Excluir atração (POST)
     * Obs.: se você quiser impedir excluir quando existir estadia vinculada,
     * faça essa regra no Model ou cheque aqui antes.
     */
    public function admin_delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $id = (int)$id;

        if ($this->Atracao->delete($id)) {
            return $this->_respond(['ok' => true, 'message' => 'Atração removida.'], ['action' => 'index']);
        }

        return $this->_respond(['ok' => false, 'error' => 'Erro ao remover atração.'], ['action' => 'index']);
    }
}
