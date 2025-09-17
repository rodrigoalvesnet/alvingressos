<?php
class Product extends AppModel
{

    public $validate = array(
        'name' => array(
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
        'ProductsImage'
    );

    public function afterSave($created, $options = array())
    {
        //limpa o cache
        Cache::clear(false, 'Products');
    }
}
