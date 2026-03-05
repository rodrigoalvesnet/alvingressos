<?php

class EstadiasController extends AppController
{

    public $uses = ['Estadia', 'Tarifa', 'TarifaFaixa', 'Atracao', 'EstadiaItem', 'Adicional'];
    public $components = ['Session', 'RequestHandler', 'EstadiasCalculator', 'Alv'];

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

        $adicionals = $this->Adicional->find('all', [
            'conditions' => ['Adicional.ativo' => 1],
            'recursive' => -1,
            'fields' => ['Adicional.id', 'Adicional.nome', 'Adicional.valor'],
            'order' => ['Adicional.nome' => 'ASC']
        ]);
        $this->set('adicionals', $adicionals);



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

        $this->loadModel('FormasPagamento');
        $formasdepagamentos = $this->FormasPagamento->find(
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

        $this->set(compact('atracoes', 'tarifas', 'formasdepagamentos', 'sexo'));
    }


    public function admin_editar($id)
    {
        $this->Estadia->id = $id;
        if (!$this->Estadia->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Estadia']['nascimento'] = $this->Alv->tratarData($this->request->data['Estadia']['nascimento']);
            if ($this->Estadia->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect('index');
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Estadia->findById($id);
            $this->request->data['Estadia']['nascimento'] = $this->Alv->tratarData($this->request->data['Estadia']['nascimento'], 'pt');
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

        $this->loadModel('FormasPagamento');
        $formasdepagamentos = $this->FormasPagamento->find(
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

        $this->set('bcLinks', array(
            'Estadias' => '/admin/estadias'
        ));

        $this->set('title_for_layout', 'Editar Estadia #' . $id);

        $this->set(compact('atracoes', 'tarifas', 'formasdepagamentos', 'sexo'));
        $this->render('admin_iniciar');
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

        // ✅ agora o component já traz valor_tempo, subtotal_adicionals e valor_total
        $preview = $this->EstadiasCalculator->previewEncerramento($row);

        if (empty($preview['ok'])) {
            return $this->response->body(json_encode($preview));
        }

        // Tratar tempo pausado hms (se você quiser manter no controller)
        $pausado = isset($preview['pausado_segundos']) ? (int)$preview['pausado_segundos'] : 0;
        $h = floor($pausado / 3600);
        $m = floor(($pausado % 3600) / 60);
        $s = $pausado % 60;
        $preview['tempo_pausado_hms'] = sprintf('%02d:%02d:%02d', $h, $m, $s);

        // Incluir nomes/dados
        $preview['pulseira'] = $row['Estadia']['pulseira_numero'];
        $preview['crianca_nome'] = $row['Estadia']['crianca_nome'];
        $preview['responsavel_nome'] = $row['Estadia']['responsavel_nome'];
        $preview['entrada'] = date('d/m/Y H:i', strtotime($row['Estadia']['created']));
        $preview['status'] = $row['Estadia']['status'];
        $preview['id'] = (int)$row['Estadia']['id'];

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

    public function admin_dashboard()
    {
        $conditions = [
            'DATE(inicio_em)' => date('Y-m-d')
        ];
        if (!empty($this->data)) {
            $conditions = [
                'DATE(inicio_em) >=' => $this->data['Filtro']['data_inicial'],
                'DATE(fim_em) <=' => $this->data['Filtro']['data_final']
            ];
        }

        $results = [
            'valor_total' => 0,
            'abertas' => [
                'quantidade' => 0,
                'pausado_segundos' => 0,
                'duracao_segundos' => 0
            ],
            'encerradas' => [
                'quantidade' => 0,
                'pausado_segundos' => 0,
                'duracao_segundos' => 0
            ],
            'canceladas' => [
                'quantidade' => 0,
                'pausado_segundos' => 0,
                'duracao_segundos' => 0
            ],
            'unidades' => []
        ];

        /**
         * Estadias ativas
         */
        $abertas = $this->Estadia->find(
            'all',
            array(
                'fields' => [
                    'id',
                    'pausado_segundos',
                    'duracao_segundos',
                    'valor_total'
                ],
                'conditions' => [
                    'status' => 'aberta'
                ],
                'recursive' => -1
            )
        );

        if (!empty($abertas)) {
            foreach ($abertas as $k => $aberta) {
                $results['abertas']['pausado_segundos'] += $aberta['Estadia']['pausado_segundos'];
                $results['abertas']['duracao_segundos'] += $aberta['Estadia']['duracao_segundos'];
                $results['abertas']['quantidade'] += 1;
            }
        }

        /**
         * Estadias Encerradas
         */
        $encerradas = $this->Estadia->find(
            'all',
            array(
                'fields' => [
                    'id',
                    'pausado_segundos',
                    'duracao_segundos',
                    'valor_total'
                ],
                'conditions' => array_merge($conditions, [
                    'status' => 'encerrada'
                ]),
                'recursive' => -1
            )
        );
        if (!empty($encerradas)) {
            foreach ($encerradas as $k => $encerrada) {
                $results['valor_total'] += $encerrada['Estadia']['valor_total'];
                $results['encerradas']['pausado_segundos'] += $encerrada['Estadia']['pausado_segundos'];
                $results['encerradas']['duracao_segundos'] += $encerrada['Estadia']['duracao_segundos'];
                $results['encerradas']['quantidade'] += 1;
            }
        }

        /**
         * Estadias Canceladas
         */
        $canceladas = $this->Estadia->find(
            'all',
            array(
                'fields' => [
                    'id',
                    'pausado_segundos',
                    'duracao_segundos',
                    'valor_total'
                ],
                'conditions' => array_merge($conditions, [
                    'status' => 'cancelada'
                ]),
                'recursive' => -1
            )
        );
        if (!empty($canceladas)) {
            foreach ($canceladas as $k => $cancelada) {
                $results['canceladas']['pausado_segundos'] += $cancelada['Estadia']['pausado_segundos'];
                $results['canceladas']['duracao_segundos'] += $cancelada['Estadia']['duracao_segundos'];
                $results['canceladas']['quantidade'] += 1;
            }
        }
        // pr($abertas);
        // pr($encerradas);
        // pr($results);
        // exit();
        $estadiasByUnidade = $this->Estadia->find(
            'all',
            array(
                'fields' => [
                    'id',
                    'pausado_segundos',
                    'duracao_segundos',
                    'valor_total',
                    'unidade_id'
                ],
                'conditions' => $conditions,
                'contain' => [
                    'Unidade' => [
                        'name'
                    ]
                ]
            )
        );
        // pr($estadiasByUnidade);
        if (!empty($estadiasByUnidade)) {
            foreach ($estadiasByUnidade as $unidade) {
                $unidadeId = $unidade['Estadia']['unidade_id'];
                if (!isset($results['unidades'][$unidadeId])) {
                    $results['unidades'][$unidadeId] = [
                        'nome' => $unidade['Unidade']['name'],
                        'quantidade' => 0,
                        'faturado' => 0,
                        'tempo_segundos' => 0
                    ];
                }

                $pausado  = is_numeric($unidade['Estadia']['pausado_segundos']) ? (int)$unidade['Estadia']['pausado_segundos'] : 0;
                $duracao  = is_numeric($unidade['Estadia']['duracao_segundos']) ? (int)$unidade['Estadia']['duracao_segundos'] : 0;
                $segundos = max(0, $duracao - $pausado); // evita negativo

                $results['unidades'][$unidadeId]['quantidade'] += 1;
                $results['unidades'][$unidadeId]['faturado'] += $unidade['Estadia']['valor_total'];
                $results['unidades'][$unidadeId]['tempo_segundos'] += $segundos;  // <-- soma em número

            }
            foreach ($results['unidades'] as $id => $u) {
                $results['unidades'][$id]['tempo'] = $this->_traitSeconds($u['tempo_segundos']);
            }
        }
        // pr($results);
        // exit();
        $this->set(compact('results'));
    }

    function _traitSeconds($seconds)
    {
        $horas = floor($seconds / 3600);
        $minutos = floor(($seconds % 3600) / 60);

        return sprintf('%02d:%02d', $horas, $minutos); // 02:13
    }
}
