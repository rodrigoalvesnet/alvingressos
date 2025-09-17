<?php
App::uses('AppModel', 'Model');

class GaleriasFoto extends AppModel {
    public $name = 'GaleriasFoto';

    // Relação com Galeria
    public $belongsTo = array(
        'Galeria' => array(
            'className' => 'Galeria',
            'foreignKey' => 'galeria_id'
        )
    );

    // Validações
    public $validate = array(
        'image' => array(
            'rule' => 'notBlank',
            'message' => 'A imagem é obrigatória.'
        )
    );
}
