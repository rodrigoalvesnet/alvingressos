<?php
class Adicional extends AppModel {
    public $name = 'Adicional';
    
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Campo obrigatório'
            )
        )        
    );

    public function afterSave($created, $options = Array()) {
        //limpa o cache
        Cache::clear(false,'Adicionals');
	}
}