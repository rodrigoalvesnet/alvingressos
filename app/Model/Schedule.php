<?php
class Schedule extends AppModel
{

    public $validate = array(
        'titles' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Campo obrigatório'
            )
        )
    );

    public $belongsTo = array(
        'Event'
    );

    public function afterSave($created, $options = array())
    {
        //limpa o cache
        Cache::clear(false, 'Schedules');
    }
}
