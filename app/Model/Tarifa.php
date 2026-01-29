<?php

class Tarifa extends AppModel {

    public $useTable = 'tarifas';

    public $hasMany = [
        'TarifaFaixa' => [
            'className' => 'TarifaFaixa',
            'foreignKey' => 'tarifa_id',
            'dependent' => true,
            'order' => 'TarifaFaixa.ordem ASC'
        ]
    ];

    public $validate = [
        'nome' => [
            'rule' => 'notBlank',
            'message' => 'Informe o nome da tarifa'
        ]
    ];
}
