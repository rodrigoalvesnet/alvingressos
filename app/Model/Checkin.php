<?php
class Checkin extends AppModel
{

    public $belongsTo = array(
        'Event',
        'Order',
        'Ticket',
        'User'
    );

    function checkinExists($id, $type = 'ticket')
    {
        if ($type == 'ticket') {
            $checkin = $this->find(
                'first',
                array(
                    'conditions' => array(
                        'ticket_id' => $id
                    ),
                    'recursive' => -1,
                    'fields' => array(
                        'id'
                    )
                )
            );
        } else {
            $checkin = $this->find(
                'first',
                array(
                    'conditions' => array(
                        'order_id' => $id
                    ),
                    'recursive' => -1,
                    'fields' => array(
                        'id'
                    )
                )
            );
        }

        if (empty($checkin)) {
            return false;
        }
        return true;
    }
}
