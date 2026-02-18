<?php
App::uses('AppController', 'Controller');

class EstadiaItensController extends AppController
{
    public $uses = ['Estadia', 'EstadiaItem', 'Produto'];
    public $components = ['RequestHandler'];

    public function admin_listar($estadiaId = null)
    {
        $this->autoRender = false;
        $this->response->type('json');

        $estadiaId = (int)$estadiaId;

        $itens = $this->EstadiaItem->find('all', [
            'conditions' => ['EstadiaItem.estadia_id' => $estadiaId],
            'order' => ['EstadiaItem.id' => 'DESC'],
            'recursive' => -1
        ]);

        $subtotal = 0;
        $rows = [];
        foreach ($itens as $r) {
            $subtotal += (float)$r['EstadiaItem']['valor_total'];
            $rows[] = [
                'id' => (int)$r['EstadiaItem']['id'],
                'descricao' => $r['EstadiaItem']['descricao'],
                'qtd' => (float)$r['EstadiaItem']['qtd'],
                'valor_unit' => (float)$r['EstadiaItem']['valor_unit'],
                'valor_total' => (float)$r['EstadiaItem']['valor_total'],
            ];
        }

        return $this->response->body(json_encode([
            'ok' => true,
            'subtotal_produtos' => $subtotal,
            'itens' => $rows,
        ]));
    }

    public function admin_adicionar()
    {
        $this->autoRender = false;
        $this->response->type('json');

        try {
            if (!$this->request->is('post')) {
                return $this->response->body(json_encode(['ok' => false, 'error' => 'Método inválido']));
            }

            $estadiaId = (int)$this->request->data('estadia_id');
            $produtoId = (int)$this->request->data('produto_id');
            $qtd = (float)str_replace(',', '.', (string)$this->request->data('qtd'));
            $valorUnitReq = $this->request->data('valor_unit');
            $valorUnitReq = (float)str_replace(',', '.', (string)$valorUnitReq);


            if ($estadiaId <= 0 || $produtoId <= 0 || $qtd <= 0) {
                return $this->response->body(json_encode(['ok' => false, 'error' => 'Dados inválidos.']));
            }

            $estadia = $this->Estadia->find('first', [
                'conditions' => ['Estadia.id' => $estadiaId],
                'recursive' => -1
            ]);
            if (empty($estadia)) {
                return $this->response->body(json_encode(['ok' => false, 'error' => 'Estadia não encontrada.']));
            }

            // ✅ permite adicionar no visualizar mesmo se encerrada; bloqueia só cancelada
            if ($estadia['Estadia']['status'] === 'cancelada') {
                return $this->response->body(json_encode(['ok' => false, 'error' => 'Estadia cancelada não permite alterações.']));
            }

            $produto = $this->Produto->find('first', [
                'conditions' => ['Produto.id' => $produtoId, 'Produto.ativo' => 1],
                'recursive' => -1
            ]);
            if (empty($produto)) {
                return $this->response->body(json_encode(['ok' => false, 'error' => 'Produto não encontrado.']));
            }

            // ⚠️ ajuste o campo se o seu banco não for valor_venda
            $valorUnitPadrao = (float)$produto['Produto']['valor_venda'];
            $valorUnit = ($valorUnitReq > 0) ? $valorUnitReq : $valorUnitPadrao;

            if ($valorUnit <= 0) {
                return $this->response->body(json_encode(['ok' => false, 'error' => 'Produto sem preço de venda.']));
            }

            $valorTotal = $qtd * $valorUnit;

            $this->EstadiaItem->create();
            $ok = $this->EstadiaItem->save([
                'EstadiaItem' => [
                    'estadia_id' => $estadiaId,
                    'produto_id' => $produtoId,
                    'descricao' => $produto['Produto']['nome'],
                    'qtd' => $qtd,
                    'valor_unit' => $valorUnit,
                    'valor_total' => $valorTotal,
                ]
            ], ['validate' => false]);

            if (!$ok) {
                return $this->response->body(json_encode(['ok' => false, 'error' => 'Falha ao salvar item.']));
            }

            return $this->response->body(json_encode(['ok' => true, 'id' => (int)$this->EstadiaItem->id]));
        } catch (Exception $e) {
            return $this->response->body(json_encode([
                'ok' => false,
                'error' => 'Erro interno: ' . $e->getMessage()
            ]));
        }
    }

    public function admin_remover($id = null)
    {
        $this->autoRender = false;
        $this->response->type('json');

        if (!$this->request->is('post')) {
            return $this->response->body(json_encode([
                'ok' => false,
                'error' => 'Método inválido (precisa ser POST).'
            ]));
        }


        $id = (int)$id;

        $item = $this->EstadiaItem->find('first', [
            'conditions' => ['EstadiaItem.id' => $id],
            'recursive' => -1
        ]);
        if (empty($item)) {
            return $this->response->body(json_encode(['ok' => false, 'error' => 'Item não encontrado.']));
        }

        if (!$this->EstadiaItem->delete($id)) {
            return $this->response->body(json_encode(['ok' => false, 'error' => 'Falha ao remover item.']));
        }

        $this->_atualizarTotalEstadia($id);

        return $this->response->body(json_encode(['ok' => true]));
    }

    protected function _atualizarTotalEstadia($estadiaId)
    {
        $sumRow = $this->EstadiaItem->find('first', [
            'fields' => ['COALESCE(SUM(EstadiaItem.valor_total),0) AS total'],
            'conditions' => ['EstadiaItem.estadia_id' => (int)$estadiaId],
            'recursive' => -1
        ]);
        $subtotalProdutos = (float)$sumRow[0]['total'];

        $e = $this->Estadia->find('first', [
            'conditions' => ['Estadia.id' => (int)$estadiaId],
            'recursive' => -1
        ]);
        if (empty($e)) return;

        // tempo já gravado na estadia (valor_base + valor_adicional)
        $valorTempo = (float)$e['Estadia']['valor_base'] + (float)$e['Estadia']['valor_adicional'];

        $this->Estadia->id = (int)$estadiaId;
        $this->Estadia->save([
            'valor_total' => $valorTempo + $subtotalProdutos
        ], ['validate' => false]);
    }
}
