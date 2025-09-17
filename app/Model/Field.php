<?php
class Field extends AppModel
{

    public $validate = array(
        'question' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Campo obrigatório'
            )
        )
    );

    public $belongsTo = array(
        'Event'
    );

    public $hasMany = array(
        'Response'
    );

    public function afterSave($created, $options = array())
    {
        //limpa o cache
        Cache::clear(false, 'Fields');
    }
}
