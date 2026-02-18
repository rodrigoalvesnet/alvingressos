<?php
App::uses('AppModel', 'Model');

class EstadiaItem extends AppModel
{
    public $useTable = 'estadia_itens';

    public $belongsTo = [
        'Estadia' => ['className' => 'Estadia', 'foreignKey' => 'estadia_id'],
        'Produto' => ['className' => 'Produto', 'foreignKey' => 'produto_id'],
    ];
}
