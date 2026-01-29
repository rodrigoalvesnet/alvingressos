<?php

class AtracoesController extends AppController {

    public $uses = ['Atracao'];

    /**
     * Lista atrações
     * /estadias-atracoes
     */
    public function admin_index() {
        $ativo = $this->request->query('ativo');

        $conditions = [];
        if ($ativo !== null && $ativo !== '') {
            $conditions['Atracao.ativo'] = (int)$ativo;
        }

        $list = $this->Atracao->find('all', [
            'conditions' => $conditions,
            'order' => ['Atracao.nome' => 'ASC', 'Atracao.id' => 'DESC'],
            'limit' => 200,
            'recursive' => -1
        ]);

        $this->set(compact('list', 'ativo'));
    }

    /**
     * Criar atração
     */
    public function admin_add() {
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
    }

    /**
     * Editar atração
     */
    public function admin_edit($id = null) {
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

        // GET
        $this->request->data = $row;
        $this->set(compact('row'));
        $this->render('admin_add');
    }

    /**
     * Alternar ativo/inativo (POST)
     */
    public function admin_toggle($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $id = (int)$id;

        $row = $this->Atracao->find('first', [
            'conditions' => ['Atracao.id' => $id],
            'recursive' => -1
        ]);
        if (empty($row)) {
            return $this->_respond(['ok' => false, 'error' => 'Atração não encontrada']);
        }

        $novo = empty($row['Atracao']['ativo']) ? 1 : 0;

        $this->Atracao->create(false);
        $ok = (bool)$this->Atracao->save([
            'Atracao' => [
                'id' => $id,
                'ativo' => $novo
            ]
        ], ['validate' => false]);

        if (!$ok) {
            return $this->_respond(['ok' => false, 'error' => 'Falha ao alterar status.']);
        }

        return $this->_respond([
            'ok' => true,
            'message' => $novo ? 'Atração ativada.' : 'Atração desativada.'
        ], ['action' => 'index']);
    }

    /**
     * Excluir atração (POST)
     * Obs.: se você quiser impedir excluir quando existir estadia vinculada,
     * faça essa regra no Model ou cheque aqui antes.
     */
    public function admin_delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $id = (int)$id;

        // opcional: bloquear delete se houver estadias
        // $this->loadModel('Estadias.Estadia');
        // $count = $this->Estadia->find('count', ['conditions' => ['Estadia.atracao_id' => $id], 'recursive' => -1]);
        // if ($count > 0) return $this->_respond(['ok'=>false,'error'=>'Não é possível excluir: existem estadias vinculadas.']);

        if ($this->Atracao->delete($id)) {
            return $this->_respond(['ok' => true, 'message' => 'Atração removida.'], ['action' => 'index']);
        }

        return $this->_respond(['ok' => false, 'error' => 'Erro ao remover atração.'], ['action' => 'index']);
    }
}
