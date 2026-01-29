<?php

class Atracao extends AppModel {

    public $useTable = 'atracoes';

    public $belongsTo = [
        'Unidade' => [
            'className' => 'Unidade',
            'foreignKey' => 'unidade_id'
        ]
    ];

    public $hasMany = [
        'Estadia' => [
            'className' => 'Estadia',
            'foreignKey' => 'atracao_id'
        ]
    ];

    public $validate = [
        'nome' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'Informe o nome da atração'
            ]
        ],
        'unidade_id' => [
            'numeric' => [
                'rule' => 'numeric',
                'message' => 'Unidade inválida'
            ]
        ]
    ];
}
