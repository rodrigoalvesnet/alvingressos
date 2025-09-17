<?php
class Role extends AppModel {
    public $name = 'Role';

    public $actsAs = array('Acl' => array('type' => 'requester'));

    public function parentNode() {
        return null;
    }
    
    public $validate = array(
        'title' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Campo obrigatório'
            )
        )        
    );

    public function afterSave($created, $options = Array()) {
        //limpa o cache
        Cache::clear(false,'Roles');
	}
}