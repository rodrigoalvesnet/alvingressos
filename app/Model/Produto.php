<?php
class Produto extends AppModel {
    public $name = 'Produto';

    public $belongsTo = array(
        'ProdutosCategoria'
    );
    
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
        Cache::clear(false,'Produtos');
	}
}