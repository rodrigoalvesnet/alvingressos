<?php
class Checkin extends AppModel
{

    public $belongsTo = array(
        'Event',
        'Order',
        'Ticket',
        'User'
    );

    function checkinExists($ticketId){
        $checkin = $this->find(
            'first',
            array(
                'conditions' => array(
                    'ticket_id' => $ticketId 
                ),
                'recursive' => -1,
                'fields' => array(
                    'id'
                )
            )
        );
        if(empty($checkin)){
            return false;
        }
        return true;
    }

}
