<?php
class Depoimento extends AppModel
{

    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Campo obrigatório'
            )
        )
    );

}
