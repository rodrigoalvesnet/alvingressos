<?php

class TarifaFaixa extends AppModel {

    public $useTable = 'tarifa_faixas';

    public $belongsTo = [
        'Tarifa' => [
            'className' => 'Tarifa',
            'foreignKey' => 'tarifa_id'
        ]
    ];

    // public $validate = [
    //     'min_segundos' => [
    //         'rule' => 'numeric',
    //         'message' => 'Tempo mínimo inválido'
    //     ],
    //     'max_segundos' => [
    //         'rule' => 'numeric',
    //         'message' => 'Tempo máximo inválido'
    //     ],
    //     'valor' => [
    //         'rule' => ['decimal', 2],
    //         'message' => 'Valor inválido'
    //     ]
    // ];
}
