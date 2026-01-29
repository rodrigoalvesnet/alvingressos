<?php
class Estadia extends AppModel {

    public $useTable = 'estadias';

    public $belongsTo = [
        'Tarifa' => [
            'className' => 'Tarifa',
            'foreignKey' => 'tarifa_id'
        ],
        'TarifaFaixa' => [
            'className' => 'TarifaFaixa',
            'foreignKey' => 'faixa_id'
        ],
        'Atracao' => [
            'className' => 'Atracao',
            'foreignKey' => 'atracao_id'
        ]
    ];

    public $validate = [
        'pulseira_numero' => [
            'rule' => 'notBlank',
            'message' => 'Informe o número da pulseira'
        ],
        'crianca_nome' => [
            'rule' => 'notBlank',
            'message' => 'Informe o nome da criança'
        ],
        'responsavel_nome' => [
            'rule' => 'notBlank',
            'message' => 'Informe o responsável'
        ]
    ];

    public function pulseiraEmUso($atracaoId, $pulseira) {
        return $this->find('count', [
            'conditions' => [
                'Estadia.atracao_id' => $atracaoId,
                'Estadia.pulseira_numero' => $pulseira,
                'Estadia.status' => 'aberta'
            ]
        ]) > 0;
    }

    public function calcularDuracao($inicio, $fim = null) {
        return max(0, strtotime($fim ?: 'now') - strtotime($inicio));
    }

    public function timeToSeconds($time) {
        if (!$time) return 0;
        if (is_numeric($time)) return (int)$time;

        list($h, $m, $s) = array_pad(explode(':', $time), 3, 0);
        return ($h * 3600) + ($m * 60) + (int)$s;
    }

    public function secondsToTime($seconds) {
        $seconds = max(0, (int)$seconds);
        return sprintf(
            '%02d:%02d:%02d',
            floor($seconds / 3600),
            floor(($seconds % 3600) / 60),
            $seconds % 60
        );
    }
}
