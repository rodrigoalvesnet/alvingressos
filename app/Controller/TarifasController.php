<?php

class TarifasController extends AppController
{

    public $uses = ['Tarifa', 'TarifaFaixa'];

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Tarifas');
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
            if ($this->Session->check('Filtros.Tarifas')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Tarifas');
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
                $arrayConditions['Tarifa.nome LIKE'] = '%' . $this->request->data['Filtro']['nome'] . '%';
            }
            if (isset($this->request->data['Filtro']['active']) && !empty($this->request->data['Filtro']['active'])) {
                $arrayConditions['Tarifa.ativo'] = $this->request->data['Filtro']['active'];
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Tarifas', $arrayConditions);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Tarifas')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Tarifas');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'contain'     => [
                'TarifaFaixa'
            ]
        );

        //envia os dados para a view
        $this->set('registros', $this->paginate('Tarifa'));
        
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
