<?php
App::uses('AppModel', 'Model');

class EstadiaItem extends AppModel
{
    public $useTable = 'estadia_itens';

    public $belongsTo = [
        'Estadia' => ['className' => 'Estadia', 'foreignKey' => 'estadia_id'],
        'Adicional' => ['className' => 'Adicional', 'foreignKey' => 'adicional_id'],
    ];
}
