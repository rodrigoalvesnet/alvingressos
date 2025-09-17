<?php
class Banner extends AppModel {
    public $name = 'Banner';
    
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
        Cache::clear(false,'Banners');
	}
}