<?php

class EstadiasController extends AppController
{

    public $uses = ['Estadia', 'Tarifa', 'TarifaFaixa', 'Atracao'];
    public $components = ['Session', 'RequestHandler', 'EstadiasCalculator'];

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Estadias');
    }

    /**
     * Lista: abertas/pausadas/encerradas (filtro por querystring)
     * /estadias/estadias/index?status=aberta
     */
    public function admin_index()
    {
        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Estadias')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Estadias');
            }
            //veriica se o cache existe
            if ($this->Session->check('Filtros.ThisData')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.ThisData');
            }
            //atualiza a pagina
            $this->redirect(array(
                'admin' => true
            ));
        }

        //condição padrão
        $arrayConditions = array(
            'OR' => [
                'DATE(Estadia.created)' => date('Y-m-d'),
                'Estadia.status' => ['aberta', 'pausada']
            ]
        );


        //se o this->data não está vazio, prepara o filtro
        if (!empty($this->request->data)) {
            $arrayConditions = array();

            if (isset($this->request->data['Filtro']['pulseira_numero']) && !empty($this->request->data['Filtro']['pulseira_numero'])) {
                $arrayConditions['Estadia.pulseira_numero'] = $this->request->data['Filtro']['pulseira_numero'];
            }
            if (isset($this->request->data['Filtro']['crianca_nome']) && !empty($this->request->data['Filtro']['crianca_nome'])) {
                $arrayConditions['Estadia.crianca_nome LIKE '] = '%' . $this->request->data['Filtro']['crianca_nome'] . '%';
            }
            if (isset($this->request->data['Filtro']['responsavel_nome']) && !empty($this->request->data['Filtro']['responsavel_nome'])) {
                $arrayConditions['Estadia.responsavel_nome LIKE '] = '%' . $this->request->data['Filtro']['responsavel_nome'] . '%';
            }
            if (isset($this->request->data['Filtro']['start_date']) && !empty($this->request->data['Filtro']['start_date'])) {
                $arrayConditions['DATE(Estadia.created) >='] = $this->request->data['Filtro']['start_date'];
            }
            if (isset($this->request->data['Filtro']['end_date']) && !empty($this->request->data['Filtro']['end_date'])) {
                $arrayConditions['DATE(Estadia.created) <='] = $this->request->data['Filtro']['end_date'];
            }
            if (isset($this->request->data['Filtro']['atracao_id']) && !empty($this->request->data['Filtro']['atracao_id'])) {
                $arrayConditions['Estadia.atracao_id'] = $this->request->data['Filtro']['atracao_id'];
            }
            if (isset($this->request->data['Filtro']['unidade_id']) && !empty($this->request->data['Filtro']['unidade_id'])) {
                $arrayConditions['Estadia.unidade_id'] = $this->request->data['Filtro']['unidade_id'];
            }
            if (isset($this->request->data['Filtro']['status']) && !empty($this->request->data['Filtro']['status'])) {
                $arrayConditions['Estadia.status'] = $this->request->data['Filtro']['status'];
            }

            //salva as condições na session            
            $this->Session->write('Filtros.Estadias', $arrayConditions);
            $this->Session->write('Filtros.ThisData', $this->request->data);

            if (isset($this->request->data['button']) && $this->request->data['button'] == 'btnExport') {
                $this->redirect([
                    'controller' => 'Estadia',
                    'action' => 'index',
                    'admin' => true
                ]);
            }
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Estadias')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Estadias');
                $this->request->data = $this->Session->read('Filtros.ThisData');
            }
        }

        //Pega os dados do Event
        $this->loadModel('Atracao');
        $atracoes = $this->Atracao->find(
            'list',
            array(
                'recursive' => -1,
                'fields' => array(
                    'id',
                    'nome'
                ),
                'order' => array(
                    'nome' => 'ASC'
                )
            )
        );
        $this->set('atracoes', $atracoes);

        $status = Configure::read('Estadias.status');
        $this->set('status', $status);

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'order'         => 'Estadia.created DESC',
            'contain'       => array(
                'Atracao' => array(
                    'nome'
                )
            )
        );
        $this->set('registros', $this->paginate('Estadia'));
    }

    /**
     * Form iniciar (GET) + salvar (POST)
     */
    public function admin_iniciar()
    {
        if (!empty($this->data)) {
            $data = $this->request->data('Estadia') ?: [];
            $res = $this->EstadiasCalculator->iniciar($data);
            if ($res['ok']) {
                $this->Flash->success('Estadia ' . $res['id'] . ' iniciada com sucesso!');
                $this->redirect('index');
            } else {
                $this->Flash->error('Erro: ' . $res['error']);
            }
            
        }
        $this->loadModel('Atracao');
        $atracoes = $this->Atracao->find(
            'list',
            array(
                'conditions' => array(
                    'ativo' => 1
                ),
                'recursive' => -1,
                'fields' => array(
                    'id',
                    'nome'
                ),
                'order' => array(
                    'nome' => 'ASC'
                )
            )
        );

        $this->loadModel('Tarifa');
        $tarifas = $this->Tarifa->find(
            'list',
            array(
                'conditions' => array(
                    'ativo' => 1
                ),
                'recursive' => -1,
                'fields' => array(
                    'id',
                    'nome'
                ),
                'order' => array(
                    'nome' => 'ASC'
                )
            )
        );


        $sexo = Configure::read('Estadias.sexo');

        $this->set(compact('atracoes', 'tarifas', 'sexo'));
    }

    /**
     * Pausar (POST)
     */
    public function admin_pausar($id = null)
    {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();
        $id = (int)$id;

        $res = $this->EstadiasCalculator->pausar($id);
        if (!empty($res['ok'])) $res['message'] = 'Estadia pausada.';

        return $this->_respond($res, $this->referer());
    }

    /**
     * Retomar (POST)
     */
    public function admin_retomar($id = null)
    {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();
        $id = (int)$id;

        $res = $this->EstadiasCalculator->retomar($id);
        if (!empty($res['ok'])) $res['message'] = 'Estadia retomada.';

        return $this->_respond($res, $this->referer());
    }

    public function admin_preview_encerrar($id = null)
    {
        $this->autoRender = false;
        $this->response->type('json');

        $id = (int)$id;

        $row = $this->Estadia->find('first', [
            'conditions' => ['Estadia.id' => $id],
            'recursive' => -1
        ]);

        if (empty($row)) {
            return $this->response->body(json_encode(['ok' => false, 'error' => 'Estadia não encontrada']));
        }

        $preview = $this->EstadiasCalculator->previewEncerramento($row);

        // Tratar tempo
        $pausado = isset($preview['pausado_segundos']) ? (int)$preview['pausado_segundos'] : 0;
        $h = floor($pausado / 3600);
        $m = floor(($pausado % 3600) / 60);
        $s = $pausado % 60;
        $preview['tempo_pausado_hms'] = sprintf('%02d:%02d:%02d', $h, $m, $s);

        //Incluir nomes
        $preview['pulseira'] = $row['Estadia']['pulseira_numero'];
        $preview['crianca_nome'] = $row['Estadia']['crianca_nome'];
        $preview['reponsavel_nome'] = $row['Estadia']['responsavel_nome'];
        $preview['entrada'] = date('d/m/Y H:i', strtotime($row['Estadia']['created']));
        $preview['status'] = $row['Estadia']['status'];
        $preview['id'] = $row['Estadia']['id'];
// $this->log($preview);
        return $this->response->body(json_encode($preview));
    }


    /**
     * Encerrar (POST)
     */
    public function admin_encerrar()
    {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();

        $this->request->data('id');

        if (!isset($this->data['Estadia']['id'])) {
            $this->Flash->error('ID inválido');
        }

        $res = $this->EstadiasCalculator->encerrar($this->data['Estadia']['id']);

        if ($res['ok']) {
            $message = 'Encerrada. Tempo cobrado: ' . $res['duracao_cobrada_hms'] .
                ' | Total: R$ ' . number_format($res['valor_total'], 2, ',', '.');
            $this->Flash->success($message);
        } else {
            $this->Flash->error($res['error']);
        }

        $this->redirect('index');
    }

    /**
     * Cancelar (POST) - opcionalmente recebe motivo via POST
     */
    public function admin_cancelar($id = null)
    {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();
        $id = (int)$id;

        $motivo = $this->request->data('motivo');

        $res = $this->EstadiasCalculator->cancelar($id, null, $motivo);
        if (!empty($res['ok'])) $res['message'] = 'Estadia cancelada.';

        return $this->_respond($res, ['action' => 'index', '?' => ['status' => 'aberta']]);
    }

    /**
     * (Opcional) Ver detalhes
     */
    public function admin_view($id = null)
    {
        $id = (int)$id;

        $row = $this->Estadia->find('first', [
            'conditions' => ['Estadia.id' => $id],
            'contain' => ['Tarifa', 'TarifaFaixa', 'Atracao'],
            'recursive' => -1
        ]);

        if (empty($row)) throw new NotFoundException('Estadia não encontrada');

        $this->set(compact('row'));
    }
}
