<?php
App::uses('Component', 'Controller');
App::uses('ClassRegistry', 'Utility');

class EstadiasCalculatorComponent extends Component
{

    protected $Estadia;
    protected $Tarifa;
    protected $TarifaFaixa;
    protected $EstadiaItem;

    public function initialize(Controller $controller)
    {
        parent::initialize($controller);

        $this->Estadia     = ClassRegistry::init('Estadia');
        $this->Tarifa      = ClassRegistry::init('Tarifa');
        $this->TarifaFaixa = ClassRegistry::init('TarifaFaixa');
        $this->EstadiaItem = ClassRegistry::init('EstadiaItem');
    }

    // ============================================================
    // CASOS DE USO
    // ============================================================

    /**
     * INICIAR: cria uma estadia "aberta"
     */
    public function iniciar(array $data)
    {
        // campos mínimos
        $required = ['atracao_id', 'tarifa_id', 'pulseira_numero', 'crianca_nome', 'responsavel_nome'];
        foreach ($required as $k) {
            if (empty($data[$k])) return $this->_err("Campo obrigatório: {$k}");
        }

        // evita pulseira duplicada na mesma atração em status aberta/pausada
        $emUso = $this->Estadia->find('count', [
            'conditions' => [
                'Estadia.atracao_id' => (int)$data['atracao_id'],
                'Estadia.pulseira_numero' => $data['pulseira_numero'],
                'Estadia.status' => ['aberta', 'pausada']
            ],
            'recursive' => -1
        ]) > 0;

        if ($emUso) return $this->_err('Pulseira já está em uso nesta atração');

        $save = [
            'Estadia' => [
                'atracao_id' => (int)$data['atracao_id'],
                'tarifa_id' => (int)$data['tarifa_id'],
                'pulseira_numero' => $data['pulseira_numero'],
                'crianca_nome' => $data['crianca_nome'],
                'responsavel_nome' => $data['responsavel_nome'],
                'telefone' => !empty($data['telefone']) ? $data['telefone'] : null,
                'sexo' => $data['sexo'],
                'email' => $data['email'],
                'observacoes' => !empty($data['observacoes']) ? $data['observacoes'] : null,
                'inicio_em' => !empty($data['inicio_em']) ? $data['inicio_em'] : date('Y-m-d H:i:s'),
                'fim_em' => null,
                'pausado_em' => null,
                'pausado_segundos' => 0,
                'duracao_segundos' => null,
                'faixa_id' => null,
                'valor_base' => 0,
                'valor_adicional' => 0,
                'valor_total' => 0,
                'status' => 'aberta',
            ]
        ];

        $this->Estadia->create();
        if (!$this->Estadia->save($save)) {
            return $this->_err('Falha ao iniciar estadia');
        }

        return [
            'ok' => true,
            'id' => (int)$this->Estadia->id
        ];
    }

    /**
     * PAUSAR: muda para "pausada" e marca pausado_em
     */
    public function pausar($estadiaId, $pausadoEm = null)
    {
        $pausadoEm = $pausadoEm ?: date('Y-m-d H:i:s');
        $e = $this->_getEstadiaOrErr($estadiaId);
        if (!$e['ok']) return $e;

        $status = $e['estadia']['Estadia']['status'];
        if ($status !== 'aberta') return $this->_err('Só é possível pausar uma estadia aberta');

        $data = [
            'Estadia' => [
                'id' => (int)$estadiaId,
                'status' => 'pausada',
                'pausado_em' => $pausadoEm
            ]
        ];

        $this->Estadia->create(false);
        if (!$this->Estadia->save($data, ['validate' => false])) {
            return $this->_err('Falha ao pausar');
        }

        return ['ok' => true];
    }

    /**
     * RETOMAR: volta para "aberta" e acumula pausado_segundos
     */
    public function retomar($estadiaId, $retomadoEm = null)
    {
        $retomadoEm = $retomadoEm ?: date('Y-m-d H:i:s');
        $e = $this->_getEstadiaOrErr($estadiaId);
        if (!$e['ok']) return $e;

        $row = $e['estadia']['Estadia'];
        if ($row['status'] !== 'pausada') return $this->_err('Só é possível retomar uma estadia pausada');
        if (empty($row['pausado_em'])) return $this->_err('Estadia pausada sem pausado_em (dados inconsistentes)');

        $delta = max(0, strtotime($retomadoEm) - strtotime($row['pausado_em']));
        $novoPausado = (int)$row['pausado_segundos'] + (int)$delta;

        $data = [
            'Estadia' => [
                'id' => (int)$estadiaId,
                'status' => 'aberta',
                'pausado_em' => null,
                'pausado_segundos' => $novoPausado
            ]
        ];

        $this->Estadia->create(false);
        if (!$this->Estadia->save($data, ['validate' => false])) {
            return $this->_err('Falha ao retomar');
        }

        return ['ok' => true, 'pausado_segundos' => $novoPausado];
    }

    /**
     * CANCELAR: encerra sem cobrança (ou com cobrança zero) e trava a estadia
     */
    public function cancelar($estadiaId, $canceladoEm = null, $motivo = null)
    {
        $canceladoEm = $canceladoEm ?: date('Y-m-d H:i:s');
        $e = $this->_getEstadiaOrErr($estadiaId);
        if (!$e['ok']) return $e;

        $status = $e['estadia']['Estadia']['status'];
        if (!in_array($status, ['aberta', 'pausada'], true)) {
            return $this->_err('Só é possível cancelar uma estadia aberta/pausada');
        }

        // Se estiver pausada, primeiro "fecha" a pausa acumulando até o cancelamento
        $row = $e['estadia']['Estadia'];
        $pausadoSeg = (int)$row['pausado_segundos'];
        if ($status === 'pausada' && !empty($row['pausado_em'])) {
            $delta = max(0, strtotime($canceladoEm) - strtotime($row['pausado_em']));
            $pausadoSeg += (int)$delta;
        }

        $data = [
            'Estadia' => [
                'id' => (int)$estadiaId,
                'fim_em' => $canceladoEm,
                'pausado_em' => null,
                'pausado_segundos' => $pausadoSeg,
                'duracao_segundos' => 0,
                'faixa_id' => null,
                'valor_base' => 0,
                'valor_adicional' => 0,
                'valor_total' => 0,
                'status' => 'cancelada',
            ]
        ];

        // se você quiser guardar motivo dentro de observacoes
        if ($motivo) {
            $obs = trim((string)$row['observacoes']);
            $data['Estadia']['observacoes'] = trim($obs . "\n[CANCELADO] " . $motivo);
        }

        $this->Estadia->create(false);
        if (!$this->Estadia->save($data, ['validate' => false])) {
            return $this->_err('Falha ao cancelar');
        }

        return ['ok' => true];
    }

    public function previewEncerramento(array $estadiaRow, $fimEm = null)
    {
        $fimEm = $fimEm ?: date('Y-m-d H:i:s');

        $row = $estadiaRow['Estadia'];
        if (empty($row['inicio_em'])) return ['ok' => false, 'error' => 'Estadia sem início'];
        if (empty($row['tarifa_id'])) return ['ok' => false, 'error' => 'Estadia sem tarifa'];

        // Se estiver pausada, considera pausa até o fimEm
        $pausadoSeg = (int)($row['pausado_segundos'] ?? 0);
        if (($row['status'] ?? null) === 'pausada' && !empty($row['pausado_em'])) {
            $pausadoSeg += max(0, strtotime($fimEm) - strtotime($row['pausado_em']));
        }

        $duracaoTotal = max(0, strtotime($fimEm) - strtotime($row['inicio_em']));
        $duracaoCobrada = max(0, $duracaoTotal - $pausadoSeg);

        // Reaproveita seu cálculo interno (faixa + adicional), sem salvar
        $calc = $this->_calcularCobranca($estadiaRow, $duracaoCobrada);
        if (empty($calc['ok'])) return $calc;

        $subtotalProdutos = $this->_subtotalProdutos((int)$row['id']);
        $valorTempo = (float)$calc['valor_total'];
        $valorTotalFinal = $valorTempo + $subtotalProdutos;


        return [
            'ok' => true,
            'fim_em' => $fimEm,
            'duracao_total_segundos' => $duracaoTotal,
            'pausado_segundos' => $pausadoSeg,
            'duracao_cobrada_segundos' => $duracaoCobrada,
            'duracao_cobrada_hms' => $this->_secondsToTime($duracaoCobrada),
            'faixa_id' => (int)$calc['faixa_id'],
            'valor_base' => (float)$calc['valor_base'],
            'valor_adicional' => (float)$calc['valor_adicional'],
            'valor_tempo' => $valorTempo,
            'subtotal_produtos' => $subtotalProdutos,
            'valor_total' => $valorTotalFinal,
        ];
    }

    /**
     * ENCERRAR: calcula cobrança (considerando pausas) e salva valores
     * - Se estiver "pausada", considera o tempo pausado até o momento do encerramento.
     */
    public function encerrar($estadiaId, $fimEm = null)
    {
        $fimEm = $fimEm ?: date('Y-m-d H:i:s');

        $e = $this->_getEstadiaOrErr($estadiaId, true); // com Tarifa
        if (!$e['ok']) return $e;

        $estadia = $e['estadia'];
        $row = $estadia['Estadia'];

        if (!in_array($row['status'], ['aberta', 'pausada'], true)) {
            return $this->_err('Só é possível encerrar uma estadia aberta/pausada');
        }

        // Se estiver pausada, acumula pausa até fimEm
        $pausadoSeg = (int)$row['pausado_segundos'];
        if ($row['status'] === 'pausada' && !empty($row['pausado_em'])) {
            $delta = max(0, strtotime($fimEm) - strtotime($row['pausado_em']));
            $pausadoSeg += (int)$delta;
        }

        $duracaoTotal = max(0, strtotime($fimEm) - strtotime($row['inicio_em']));
        $duracaoCobrada = max(0, $duracaoTotal - $pausadoSeg);

        $calc = $this->_calcularCobranca($estadia, $duracaoCobrada);
        if (!$calc['ok']) return $calc;

        $subtotalProdutos = $this->_subtotalProdutos($estadiaId);

        $valorTempo = (float)$calc['valor_total']; // tempo = base + adicional
        $valorTotalFinal = $valorTempo + (float)$subtotalProdutos;


        $data = [
            'Estadia' => [
                'id' => (int)$estadiaId,
                'fim_em' => $fimEm,
                'pausado_em' => null,
                'pausado_segundos' => $pausadoSeg,
                'duracao_segundos' => (int)$duracaoCobrada,
                'faixa_id' => (int)$calc['faixa_id'],
                'valor_base' => $calc['valor_base'],
                'valor_adicional' => $calc['valor_adicional'],
                'valor_total' => $valorTotalFinal,
                'status' => 'encerrada',
            ]
        ];

        $this->Estadia->create(false);
        if (!$this->Estadia->save($data, ['validate' => false])) {
            return $this->_err('Falha ao salvar encerramento');
        }

        return [
            'ok' => true,
            'fim_em' => $fimEm,
            'duracao_total_segundos' => (int)$duracaoTotal,
            'pausado_segundos' => (int)$pausadoSeg,
            'duracao_cobrada_segundos' => (int)$duracaoCobrada,
            'duracao_cobrada_hms' => $this->_secondsToTime($duracaoCobrada),
            'faixa_id' => (int)$calc['faixa_id'],
            'valor_base' => (float)$calc['valor_base'],
            'valor_adicional' => (float)$calc['valor_adicional'],
            'valor_tempo' => (float)$valorTempo,
            'subtotal_produtos' => (float)$subtotalProdutos,
            'valor_total' => (float)$valorTotalFinal,
        ];
    }

    // ============================================================
    // CÁLCULO DE COBRANÇA (faixa + adicional) com duração já “cobrada”
    // ============================================================

    protected function _calcularCobranca(array $estadia, $duracaoCobrada)
    {
        $duracaoCobrada = max(0, (int)$duracaoCobrada);

        // ✅ como suas faixas são em MINUTOS (pela tela), converte aqui
        $duracaoMin = (int)ceil($duracaoCobrada / 60);

        $tarifaId = (int)Hash::get($estadia, 'Estadia.tarifa_id');
        $tarifa   = Hash::get($estadia, 'Tarifa');

        if (!$tarifaId) return $this->_err('Estadia sem tarifa_id');

        if (empty($tarifa)) {
            $t = $this->Tarifa->find('first', [
                'conditions' => ['Tarifa.id' => $tarifaId],
                'recursive' => -1
            ]);
            $tarifa = $t ? $t['Tarifa'] : null;
        }
        if (empty($tarifa)) return $this->_err('Tarifa não encontrada');

        // ✅ encaixe em faixa (MINUTOS)
        $faixa = $this->TarifaFaixa->find('first', [
            'conditions' => [
                'TarifaFaixa.tarifa_id' => $tarifaId,
                'TarifaFaixa.ativo' => 1,
                'TarifaFaixa.min_segundos <=' => $duracaoMin,
                'TarifaFaixa.max_segundos >=' => $duracaoMin,
            ],
            'order' => ['TarifaFaixa.min_segundos' => 'ASC', 'TarifaFaixa.ordem' => 'ASC'],
            'recursive' => -1
        ]);

        $faixaRow = null;
        if (!empty($faixa)) {
            $faixaRow = $faixa['TarifaFaixa'];
        } else {
            $ultima = $this->TarifaFaixa->find('first', [
                'conditions' => [
                    'TarifaFaixa.tarifa_id' => $tarifaId,
                    'TarifaFaixa.ativo' => 1,
                ],
                'order' => ['TarifaFaixa.max_segundos' => 'DESC', 'TarifaFaixa.min_segundos' => 'DESC'],
                'recursive' => -1
            ]);
            if (empty($ultima)) return $this->_err('Nenhuma faixa ativa cadastrada');
            $faixaRow = $ultima['TarifaFaixa'];

            if (empty($tarifa['adicional_ativo'])) {
                return $this->_err('Tempo cobrado excedeu as faixas e adicional não está configurado');
            }
        }

        $valorBase = (float)$faixaRow['valor'];
        $faixaId   = (int)$faixaRow['id'];
        $valorAdicional = 0.0;

        // ✅ adicional agora também em MINUTOS (se seus campos de adicional foram pensados em minutos)
        if (!empty($tarifa['adicional_ativo'])) {
            $maxBaseMin = (int)$faixaRow['max_segundos'];

            if ($duracaoMin > $maxBaseMin) {
                $blocoMin = (int)$tarifa['adicional_bloco_segundos'];
                $valorBloco = (float)$tarifa['adicional_valor_bloco'];
                $tolMin = (int)($tarifa['adicional_tolerancia_segundos'] ?: 0);

                if ($blocoMin <= 0 || $valorBloco < 0) {
                    return $this->_err('Tarifa com adicional inválido (bloco/valor)');
                }

                $excedenteMin = $duracaoMin - $maxBaseMin;
                $excedenteLiquido = $excedenteMin - $tolMin;

                if ($excedenteLiquido > 0) {
                    $blocos = (int)ceil($excedenteLiquido / $blocoMin);
                    $valorAdicional = $blocos * $valorBloco;
                }
            }
        }

        return [
            'ok' => true,
            'faixa_id' => $faixaId,
            'valor_base' => $valorBase,
            'valor_adicional' => (float)$valorAdicional,
            'valor_total' => (float)($valorBase + $valorAdicional),
        ];
    }


    // ============================================================
    // Helpers
    // ============================================================

    protected function _getEstadiaOrErr($id, $withTarifa = false)
    {
        $contain = $withTarifa ? ['Tarifa'] : [];

        $e = $this->Estadia->find('first', [
            'conditions' => ['Estadia.id' => (int)$id],
            'contain' => $contain,
            'recursive' => -1
        ]);

        if (empty($e)) return $this->_err('Estadia não encontrada');

        return ['ok' => true, 'estadia' => $e];
    }

    protected function _err($msg)
    {
        return ['ok' => false, 'error' => $msg];
    }

    protected function _secondsToTime($seconds)
    {
        $seconds = max(0, (int)$seconds);
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }

    protected function _subtotalProdutos($estadiaId)
    {
        $sumRow = $this->EstadiaItem->find('first', [
            'fields' => ['COALESCE(SUM(EstadiaItem.valor_total),0) AS total'],
            'conditions' => ['EstadiaItem.estadia_id' => (int)$estadiaId],
            'recursive' => -1
        ]);

        return (float)$sumRow[0]['total'];
    }
}
