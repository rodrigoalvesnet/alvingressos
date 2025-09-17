<?php
class Menu extends AppModel
{
    public $name = 'Menu';

    public $belongsTo = array(
        'ParentMenu' => array(
            'className' => 'Menu',
            'foreignKey' => 'parent_id'
        )
    );

    public $hasMany = array(
        'ChildMenu' => array(
            'className' => 'Menu',
            'foreignKey' => 'parent_id',
            'order' => 'ChildMenu.position ASC'
        )
    );

    public $validate = array(
        'title' => array(
            'rule' => 'notBlank',
            'message' => 'Informe o título do menu'
        )
    );
}
