<?php
class Lot extends AppModel
{

    public $validate = array(
        'title' => array(
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
        Cache::clear(false, 'Lots');
    }
}
