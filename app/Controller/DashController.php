<?php
class DashController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function admin_index()
    {
        $this->loadModel('Order');
        $this->loadModel('Ticket');
        $this->loadModel('Checkin');

        $unidadeId = (int)$this->Auth->user('unidade_id');

        // =========================
        // KPIs de HOJE (seus atuais)
        // =========================
        $ordersCountToday = $this->Order->find('count', array(
            'conditions' => array(
                'Order.unidade_id' => $unidadeId,
                'Order.status' => 'approved',
                'DATE(Order.created)' => date('Y-m-d')
            ),
            'recursive' => -1
        ));
        $this->set('ordersCountToday', $ordersCountToday);

        $ordersTotalToday = $this->Order->find('all', array(
            'conditions' => array(
                'Order.unidade_id' => $unidadeId,
                'Order.status' => 'approved',
                'DATE(Order.created)' => date('Y-m-d')
            ),
            'fields' => array('SUM(value) AS total'),
            'recursive' => -1
        ));
        $this->set('ordersTotalToday', $ordersTotalToday[0][0]['total']);

        $ticketsToday = $this->Ticket->find('count', array(
            'conditions' => array(
                'Order.unidade_id' => $unidadeId,
                'Order.status' => 'approved',
                'DATE(Ticket.modalidade_data)' => date('Y-m-d')
            ),
            'contain' => array(
                'Order' => array('fields' => array('id', 'status'))
            )
        ));
        $this->set('ticketsToday', $ticketsToday);

        $checkinsToday = $this->Checkin->find('count', array(
            'conditions' => array(
                'Order.unidade_id' => $unidadeId,
                'DATE(Checkin.created)' => date('Y-m-d')
            ),
            'contain' => [
                'Order'
            ],
            // 'recursive' => -1
        ));
        $this->set('checkinsToday', $checkinsToday);

        // ============================================
        // NOVOS KPIs (somente Admin/Gerente) por mês/ano
        // ============================================
        $roleId = (int)$this->Auth->user('role_id');
        $this->set('roleId', $roleId);

        // mês/ano vindos do GET (?m=12&y=2025)
        $m = isset($this->request->query['m']) ? (int)$this->request->query['m'] : (int)date('m');
        $y = isset($this->request->query['y']) ? (int)$this->request->query['y'] : (int)date('Y');

        // validação básica
        if ($m < 1 || $m > 12) $m = (int)date('m');
        if ($y < 2000 || $y > 2100) $y = (int)date('Y');

        $this->set('filterMonth', $m);
        $this->set('filterYear', $y);

        // intervalo [start, end)
        $start = sprintf('%04d-%02d-01 00:00:00', $y, $m);
        $endTs = strtotime($start . ' +1 month');
        $end = date('Y-m-d H:i:s', $endTs);

        if ($roleId === 1 || $roleId === 2) {

            // 1) Passaportes vendidos (TOTAL em R$) no mês/ano
            $ordersTotalMonthRow = $this->Order->find('first', array(
                'conditions' => array(
                    'Order.unidade_id' => $unidadeId,
                    'Order.status' => 'approved',
                    'Order.created >=' => $start,
                    'Order.created <'  => $end,
                ),
                'fields' => array('COALESCE(SUM(Order.value), 0) AS total'),
                'recursive' => -1
            ));
            $ordersTotalMonth = (float)$ordersTotalMonthRow[0]['total'];
            $this->set('ordersTotalMonth', $ordersTotalMonth);

            // 2) Checkins realizados (QUANTIDADE) no mês/ano
            $checkinsCountMonth = $this->Checkin->find('count', array(
                'conditions' => array(
                    'Order.unidade_id' => $unidadeId,
                    'Checkin.created >=' => $start,
                    'Checkin.created <'  => $end,
                ),
                'contain' => [
                    'Order'
                ],
                // 'recursive' => -1
            ));
            // pr($checkinsCountMonth);exit();
            $this->set('checkinsCountMonth', (int)$checkinsCountMonth);
        } else {
            // para não dar "undefined" na view
            $this->set('ordersTotalMonth', 0);
            $this->set('checkinsCountMonth', 0);
        }

        $this->set('nobc', true);
        $this->set('notitle', true);
    }

    public function admin_gerente()
    {
        // Acesso restrito: Admin (1) e Gerente (2)
        $roleId = (int)$this->Auth->user('role_id');
        if ($roleId !== 1 && $roleId !== 2) {
            $this->Session->setFlash('Acesso restrito a gerentes.', 'default', [], 'error');
            return $this->redirect(['admin' => true, 'controller' => 'dash', 'action' => 'index']);
        }

        $this->loadModel('Order');
        $this->loadModel('Ticket');
        $this->loadModel('Checkin');
        $this->loadModel('Unidade');
        $this->loadModel('Estadia');
        $this->loadModel('EstadiaItem');
        $this->loadModel('FormasPagamento');

        // --- Filtro de data ---
        if (!empty($this->data['Filtro']['data_inicial'])) {
            $dataInicial = $this->data['Filtro']['data_inicial'];
            $dataFinal   = $this->data['Filtro']['data_final'];
        } else {
            $dataInicial = $dataFinal = date('Y-m-d');
            $this->request->data['Filtro']['data_inicial'] = $dataInicial;
            $this->request->data['Filtro']['data_final']   = $dataFinal;
        }

        // --- Unidades ---
        $unidades = $this->Unidade->find('list', [
            'fields'    => ['id', 'name'],
            'order'     => ['name' => 'ASC'],
            'recursive' => -1,
        ]);

        // --- Mapa de FormasPagamento (estadias) ---
        $formasPgtoAll = $this->FormasPagamento->find('all', [
            'fields'    => ['id', 'nome'],
            'recursive' => -1,
        ]);
        $formasPgtoMap = [];
        foreach ($formasPgtoAll as $fp) {
            $formasPgtoMap[$fp['FormasPagamento']['id']] = $fp['FormasPagamento']['nome'];
        }

        // Labels de forma de pagamento (orders)
        $paymentLabels = Configure::read('Order.payment_type') ?: [];

        // =====================================================
        // ORDERS — Passaportes Vendidos
        // =====================================================
        $condOrders = [
            'Order.status'           => 'approved',
            'DATE(Order.created) >=' => $dataInicial,
            'DATE(Order.created) <=' => $dataFinal,
        ];

        $rowOrdersGlobal = $this->Order->find('first', [
            'conditions' => $condOrders,
            'fields'     => [
                'COUNT(Order.id) AS total_pedidos',
                'COALESCE(SUM(Order.value), 0) AS total_valor',
            ],
            'recursive' => -1,
        ]);
        $totalOrdersPedidos = (int)(isset($rowOrdersGlobal[0]['total_pedidos']) ? $rowOrdersGlobal[0]['total_pedidos'] : 0);
        $totalOrdersValor   = (float)(isset($rowOrdersGlobal[0]['total_valor'])  ? $rowOrdersGlobal[0]['total_valor']  : 0);

        // Por forma de pagamento (global)
        $ordersFormaGlobal = $this->Order->find('all', [
            'conditions' => $condOrders,
            'fields'     => [
                'Order.payment_type',
                'COALESCE(SUM(Order.value), 0) AS total_valor',
            ],
            'group'     => ['Order.payment_type'],
            'order'     => ['Order.payment_type ASC'],
            'recursive' => -1,
        ]);

        // Por unidade (quantidade + valor)
        $ordersUnidade = $this->Order->find('all', [
            'conditions' => $condOrders,
            'fields'     => [
                'Order.unidade_id',
                'COUNT(Order.id) AS total_pedidos',
                'COALESCE(SUM(Order.value), 0) AS total_valor',
            ],
            'group'     => ['Order.unidade_id'],
            'recursive' => -1,
        ]);

        // Por unidade + forma de pagamento
        $ordersUnidadeForma = $this->Order->find('all', [
            'conditions' => $condOrders,
            'fields'     => [
                'Order.unidade_id',
                'Order.payment_type',
                'COALESCE(SUM(Order.value), 0) AS total_valor',
            ],
            'group'     => ['Order.unidade_id', 'Order.payment_type'],
            'recursive' => -1,
        ]);

        // =====================================================
        // TICKETS — Passaportes Agendados
        // =====================================================
        $ticketJoin = [[
            'table'      => 'orders',
            'alias'      => 'Order',
            'type'       => 'INNER',
            'conditions' => ['Order.id = Ticket.order_id'],
        ]];
        $condTickets = [
            'Order.status'                    => 'approved',
            'DATE(Ticket.modalidade_data) >=' => $dataInicial,
            'DATE(Ticket.modalidade_data) <=' => $dataFinal,
        ];

        $totalTickets = $this->Ticket->find('count', [
            'joins'      => $ticketJoin,
            'conditions' => $condTickets,
            'recursive'  => -1,
        ]);

        $ticketsUnidade = $this->Ticket->find('all', [
            'joins'      => $ticketJoin,
            'conditions' => $condTickets,
            'fields'     => [
                'Order.unidade_id',
                'COUNT(Ticket.id) AS total_tickets',
            ],
            'group'     => ['Order.unidade_id'],
            'recursive' => -1,
        ]);

        // =====================================================
        // CHECKINS
        // =====================================================
        $checkinJoin = [[
            'table'      => 'orders',
            'alias'      => 'Order',
            'type'       => 'INNER',
            'conditions' => ['Order.id = Checkin.order_id'],
        ]];
        $condCheckins = [
            'DATE(Checkin.created) >=' => $dataInicial,
            'DATE(Checkin.created) <=' => $dataFinal,
        ];

        $totalCheckins = $this->Checkin->find('count', [
            'conditions' => $condCheckins,
            'recursive'  => -1,
        ]);

        $checkinsUnidade = $this->Checkin->find('all', [
            'joins'      => $checkinJoin,
            'conditions' => $condCheckins,
            'fields'     => [
                'Order.unidade_id',
                'COUNT(Checkin.id) AS total_checkins',
            ],
            'group'     => ['Order.unidade_id'],
            'recursive' => -1,
        ]);

        // =====================================================
        // ESTADIAS — por unidade
        // =====================================================
        $condEstPeriodo = [
            'DATE(Estadia.inicio_em) >=' => $dataInicial,
            'DATE(Estadia.fim_em) <='    => $dataFinal,
        ];
        $condEstItens = [
            'Estadia.status'           => 'encerrada',
            'DATE(Estadia.fim_em) >=' => $dataInicial,
            'DATE(Estadia.fim_em) <=' => $dataFinal,
        ];

        // Ativas (sem filtro de data — são as abertas agora)
        $ativasPorUnidade = $this->Estadia->find('all', [
            'conditions' => ['Estadia.status' => 'aberta'],
            'fields'     => [
                'Estadia.unidade_id',
                'COUNT(Estadia.id) AS qtd',
            ],
            'group'     => ['Estadia.unidade_id'],
            'recursive' => -1,
        ]);

        // Encerradas no período
        $encerradasPorUnidade = $this->Estadia->find('all', [
            'conditions' => array_merge($condEstPeriodo, ['Estadia.status' => 'encerrada']),
            'fields'     => [
                'Estadia.unidade_id',
                'COUNT(Estadia.id) AS qtd',
                'COALESCE(SUM(Estadia.valor_total), 0) AS total_valor',
                'COALESCE(SUM(Estadia.valor_base + Estadia.valor_adicional), 0) AS total_tempo',
            ],
            'group'     => ['Estadia.unidade_id'],
            'recursive' => -1,
        ]);

        // Canceladas no período
        $canceladasPorUnidade = $this->Estadia->find('all', [
            'conditions' => array_merge($condEstPeriodo, ['Estadia.status' => 'cancelada']),
            'fields'     => [
                'Estadia.unidade_id',
                'COUNT(Estadia.id) AS qtd',
            ],
            'group'     => ['Estadia.unidade_id'],
            'recursive' => -1,
        ]);

        // Adicionais de estadias encerradas no período, por unidade
        $adicionaisUnidade = $this->EstadiaItem->find('all', [
            'joins' => [[
                'table'      => 'estadias',
                'alias'      => 'Estadia',
                'type'       => 'INNER',
                'conditions' => ['Estadia.id = EstadiaItem.estadia_id'],
            ]],
            'conditions' => $condEstItens,
            'fields'     => [
                'Estadia.unidade_id',
                'COALESCE(SUM(EstadiaItem.valor_total), 0) AS total_adicionais',
            ],
            'group'     => ['Estadia.unidade_id'],
            'recursive' => -1,
        ]);

        // Formas de pagamento das estadias encerradas, por unidade
        $estadiasFormaUnidade = $this->Estadia->find('all', [
            'conditions' => $condEstItens,
            'fields'     => [
                'Estadia.unidade_id',
                'Estadia.formadepagamento_id',
                'COALESCE(SUM(Estadia.valor_total), 0) AS total_valor',
            ],
            'group'     => ['Estadia.unidade_id', 'Estadia.formadepagamento_id'],
            'recursive' => -1,
        ]);

        // =====================================================
        // Consolidar por unidade
        // =====================================================
        $dadosPorUnidade = [];
        foreach ($unidades as $uid => $nome) {
            $dadosPorUnidade[$uid] = [
                'nome'                 => $nome,
                'orders_count'         => 0,
                'orders_valor'         => 0.0,
                'orders_por_forma'     => [],
                'tickets_agendados'    => 0,
                'checkins'             => 0,
                'estadias_ativas'      => 0,
                'estadias_encerradas'  => 0,
                'estadias_canceladas'  => 0,
                'estadias_valor_total' => 0.0,
                'estadias_tempo'       => 0.0,
                'estadias_adicionais'  => 0.0,
                'estadias_por_forma'   => [],
            ];
        }

        foreach ($ordersUnidade as $row) {
            $uid = (int)$row['Order']['unidade_id'];
            if (!isset($dadosPorUnidade[$uid])) continue;
            $dadosPorUnidade[$uid]['orders_count'] = (int)$row[0]['total_pedidos'];
            $dadosPorUnidade[$uid]['orders_valor']  = (float)$row[0]['total_valor'];
        }

        foreach ($ordersUnidadeForma as $row) {
            $uid  = (int)$row['Order']['unidade_id'];
            $tipo = $row['Order']['payment_type'];
            if (!isset($dadosPorUnidade[$uid])) continue;
            $dadosPorUnidade[$uid]['orders_por_forma'][$tipo] = (float)$row[0]['total_valor'];
        }

        foreach ($ticketsUnidade as $row) {
            $uid = (int)$row['Order']['unidade_id'];
            if (!isset($dadosPorUnidade[$uid])) continue;
            $dadosPorUnidade[$uid]['tickets_agendados'] = (int)$row[0]['total_tickets'];
        }

        foreach ($checkinsUnidade as $row) {
            $uid = (int)$row['Order']['unidade_id'];
            if (!isset($dadosPorUnidade[$uid])) continue;
            $dadosPorUnidade[$uid]['checkins'] = (int)$row[0]['total_checkins'];
        }

        foreach ($ativasPorUnidade as $row) {
            $uid = (int)$row['Estadia']['unidade_id'];
            if (!isset($dadosPorUnidade[$uid])) continue;
            $dadosPorUnidade[$uid]['estadias_ativas'] = (int)$row[0]['qtd'];
        }

        foreach ($encerradasPorUnidade as $row) {
            $uid = (int)$row['Estadia']['unidade_id'];
            if (!isset($dadosPorUnidade[$uid])) continue;
            $dadosPorUnidade[$uid]['estadias_encerradas']  = (int)$row[0]['qtd'];
            $dadosPorUnidade[$uid]['estadias_valor_total'] = (float)$row[0]['total_valor'];
            $dadosPorUnidade[$uid]['estadias_tempo']       = (float)$row[0]['total_tempo'];
        }

        foreach ($canceladasPorUnidade as $row) {
            $uid = (int)$row['Estadia']['unidade_id'];
            if (!isset($dadosPorUnidade[$uid])) continue;
            $dadosPorUnidade[$uid]['estadias_canceladas'] = (int)$row[0]['qtd'];
        }

        foreach ($adicionaisUnidade as $row) {
            $uid = (int)$row['Estadia']['unidade_id'];
            if (!isset($dadosPorUnidade[$uid])) continue;
            $dadosPorUnidade[$uid]['estadias_adicionais'] = (float)$row[0]['total_adicionais'];
        }

        foreach ($estadiasFormaUnidade as $row) {
            $uid  = (int)$row['Estadia']['unidade_id'];
            $fid  = (int)$row['Estadia']['formadepagamento_id'];
            $nome = isset($formasPgtoMap[$fid]) ? $formasPgtoMap[$fid] : 'Não informado';
            if (!isset($dadosPorUnidade[$uid])) continue;
            if (!isset($dadosPorUnidade[$uid]['estadias_por_forma'][$nome])) {
                $dadosPorUnidade[$uid]['estadias_por_forma'][$nome] = 0.0;
            }
            $dadosPorUnidade[$uid]['estadias_por_forma'][$nome] += (float)$row[0]['total_valor'];
        }

        // Remove unidades sem nenhum dado no período
        $dadosPorUnidade = array_filter($dadosPorUnidade, function ($u) {
            return $u['orders_count'] > 0
                || $u['tickets_agendados'] > 0
                || $u['checkins'] > 0
                || $u['estadias_ativas'] > 0
                || $u['estadias_encerradas'] > 0
                || $u['estadias_canceladas'] > 0;
        });

        $this->set(compact(
            'dataInicial',
            'dataFinal',
            'totalOrdersPedidos',
            'totalOrdersValor',
            'ordersFormaGlobal',
            'totalTickets',
            'totalCheckins',
            'dadosPorUnidade',
            'paymentLabels',
            'formasPgtoMap'
        ));

        $this->set('title_for_layout', 'Dashboard Gerencial');
        $this->set('nobc', true);
        $this->set('notitle', true);
    }
}
