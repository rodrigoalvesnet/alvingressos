<?php

class TarifasController extends AppController
{

    public $uses = ['Tarifa', 'TarifaFaixa'];

    public function admin_index()
    {
        $list = $this->Tarifa->find('all', [
            'conditions' => [],
            'contain' => ['TarifaFaixa'],
            'order' => ['Tarifa.id' => 'DESC'],
            'recursive' => -1
        ]);
        $this->set(compact('list'));
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Tarifa->create();
            //Trata so segundos
            $this->request->data['Tarifa']['adicional_bloco_segundos'] =
                (int)$this->request->data['Tarifa']['adicional_bloco_segundos'] * 60;
            $this->request->data['Tarifa']['adicional_tolerancia_segundos'] =
                (int)$this->request->data['Tarifa']['adicional_tolerancia_segundos'] * 60;
            if ($this->Tarifa->save($this->request->data)) {
                $this->Session->setFlash('Tarifa criada.', 'default', [], 'success');
                return $this->redirect(
                    [
                        'action' => 'edit',
                        'admin' => true,
                        $this->Tarifa->id
                    ]
                );
            }
            //Trata so segundos
            $this->request->data['Tarifa']['adicional_bloco_segundos'] =
                (int)$this->request->data['Tarifa']['adicional_bloco_segundos'] / 60;
            $this->request->data['Tarifa']['adicional_tolerancia_segundos'] =
                (int)$this->request->data['Tarifa']['adicional_tolerancia_segundos'] / 60;

            $this->Session->setFlash('Erro ao salvar tarifa.', 'default', [], 'error');
        }
    }

    public function admin_edit($id = null)
    {
        $id = (int)$id;

        $tarifa = $this->Tarifa->find('first', [
            'conditions' => ['Tarifa.id' => $id],
            'contain' => ['TarifaFaixa'],
            'recursive' => -1
        ]);

        if (empty($tarifa)) throw new NotFoundException('Tarifa não encontrada');

        if ($this->request->is(['post', 'put'])) {
            $this->Tarifa->id = $id;
            //Trata so segundos
            $this->request->data['Tarifa']['adicional_bloco_segundos'] =
                (int)$this->request->data['Tarifa']['adicional_bloco_segundos'] * 60;
            $this->request->data['Tarifa']['adicional_tolerancia_segundos'] =
                (int)$this->request->data['Tarifa']['adicional_tolerancia_segundos'] * 60;

            if ($this->Tarifa->save($this->request->data)) {
                $this->Session->setFlash('Tarifa atualizada.', 'default', [], 'success');
                return $this->redirect(['action' => 'edit', $id]);
            }
            $this->Session->setFlash('Erro ao atualizar.', 'default', [], 'error');
        } else {
            //Trata so segundos
            $tarifa['Tarifa']['adicional_bloco_segundos'] =
                (int)$tarifa['Tarifa']['adicional_bloco_segundos'] / 60;
            $tarifa['Tarifa']['adicional_tolerancia_segundos'] =
                (int)$tarifa['Tarifa']['adicional_tolerancia_segundos'] / 60;

            $this->request->data = $tarifa;
        }

        $this->set(compact('tarifa'));
    }

    public function admin_delete($id = null)
    {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();
        $id = (int)$id;

        if ($this->Tarifa->delete($id)) {
            $this->Session->setFlash('Tarifa removida.', 'default', [], 'success');
        } else {
            $this->Session->setFlash('Erro ao remover.', 'default', [], 'error');
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Adiciona faixa na tarifa
     */
    public function admin_add_faixa($tarifaId = null)
    {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();

        $tarifaId = (int)$tarifaId;

        //Trata so segundos
        $this->request->data['TarifaFaixa']['min_segundos'] =
            (int)$this->request->data['TarifaFaixa']['min_segundos'] * 60;
        $this->request->data['TarifaFaixa']['max_segundos'] =
            (int)$this->request->data['TarifaFaixa']['max_segundos'] * 60;

        $data = $this->request->data('TarifaFaixa') ?: [];

        $data['tarifa_id'] = $tarifaId;

        $this->TarifaFaixa->create();
        if ($this->TarifaFaixa->save(['TarifaFaixa' => $data])) {
            $this->Session->setFlash('Faixa adicionada.', 'default', [], 'success');
        } else {
            $this->Session->setFlash('Erro ao adicionar faixa.', 'default', [], 'error');
        }

        return $this->redirect(['action' => 'edit', $tarifaId]);
    }

    /**
     * Remove faixa
     */
    public function admin_delete_faixa($id = null)
    {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();
        $id = (int)$id;

        $faixa = $this->TarifaFaixa->find('first', [
            'conditions' => ['TarifaFaixa.id' => $id],
            'recursive' => -1
        ]);
        if (empty($faixa)) throw new NotFoundException('Faixa não encontrada');

        $tarifaId = (int)$faixa['TarifaFaixa']['tarifa_id'];

        if ($this->TarifaFaixa->delete($id)) {
            $this->Session->setFlash('Faixa removida.', 'default', [], 'success');
        } else {
            $this->Session->setFlash('Erro ao remover faixa.', 'default', [], 'error');
        }

        return $this->redirect(['action' => 'edit', $tarifaId]);
    }
}
