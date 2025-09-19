<?php
class Ticket extends AppModel
{
    public $belongsTo = array(
        'Order',
        'Event'
    );

    public $hasOne = array(
        'Checkin'
    );
}
