<?php
App::uses('AppModel', 'Model');

class Galeria extends AppModel {
    public $name = 'Galeria';

    // Relação com GaleriasFotos
    public $hasMany = array(
        'GaleriasFoto' => array(
            'className' => 'GaleriasFoto',
            'foreignKey' => 'galeria_id',
            'dependent' => true, // Apaga fotos quando a galeria é deletada
        )
    );

    // Validações
    public $validate = array(
        'title' => array(
            'rule' => 'notBlank',
            'message' => 'O título é obrigatório.'
        ),
        'active' => array(
            'rule' => 'boolean',
            'message' => 'Informe se a galeria está ativa ou não.'
        )
    );
}
