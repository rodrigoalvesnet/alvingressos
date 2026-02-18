<?php
class ProdutosCategoria extends AppModel
{
    public $name = 'ProdutosCategoria';

    public $hasMany = array(
        'Produto'
    );

    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Campo obrigatório'
            )
        )
    );

    public function afterSave($created, $options = array())
    {
        //limpa o cache
        Cache::clear(false, 'ProdutosCategoria');
    }
}
