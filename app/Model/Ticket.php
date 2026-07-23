<?php
class Ticket extends AppModel
{
    public $belongsTo = array(
        'Order',
        'Event',
        'Unidade',
        'CriadoPor' => array(
            'className'  => 'User',
            'foreignKey' => 'criado_por'
        )
    );

    public $hasOne = array(
        'Checkin'
    );
}
