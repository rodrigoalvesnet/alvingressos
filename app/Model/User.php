<?php
App::uses('AuthComponent', 'Controller/Component');
App::uses('AclComponent', 'Controller/Component');
class User extends AppModel
{

    public $name = 'User';

    public $belongsTo = array(
        'Role',
        'Unidade'
    );
    public $actsAs = array('Acl' => array('type' => 'requester', 'enabled' => false));

    public function parentNode()
    {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['role_id'])) {
            $RoleId = $this->data['User']['role_id'];
        } else {
            $RoleId = $this->field('role_id');
        }
        if (!$RoleId) {
            return null;
        }
        return array('Role' => array('id' => $RoleId));
    }

    public function bindNode($user)
    {
        return array('model' => 'Role', 'foreign_key' => $user['User']['role_id']);
    }

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'O campo nome é obrigatório'
            )
        ),
        'email' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'O campo nome é obrigatório'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'on' => 'create',
                'message' => 'Já existe um registro com este email'
            ),
        ),
        // 'cpf' => array(
        //     'required' => array(
        //         'rule' => array('notBlank'),
        //         'message' => 'O campo nome é obrigatório'
        //     ),
        //     'unique' => array(
        //         'rule' => 'isUnique',
        //         'on' => 'create',
        //         'message' => 'Já existe um registro com este CPF'
        //     ),
        // ),
        'password' => array(
            'rule' => array('confirmPassword'),
            'message' => 'A confirmação da senha está errada!'
        )
    );

    function confirmPassword()
    {
        if ($this->data['User']['password2'] != $this->data['User']['password']) {
            return false;
        }
        return true;
    }

    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }

    function gerar_senha($tamanho, $maiuscula, $minuscula, $numeros, $codigos)
    {
        $maius = "ABCDEFGHIJKLMNOPQRSTUWXYZ";
        $minus = "abcdefghijklmnopqrstuwxyz";
        $numer = "0123456789";
        $codig = '!@#$%&*()';

        $base = '';
        $base .= ($maiuscula) ? $maius : '';
        $base .= ($minuscula) ? $minus : '';
        $base .= ($numeros) ? $numer : '';
        $base .= ($codigos) ? $codig : '';

        srand((float) microtime() * 10000000);
        $senha = '';
        for ($i = 0; $i < $tamanho; $i++) {
            $senha .= substr($base, rand(0, strlen($base) - 1), 1);
        }
        return $senha;
    }
}
