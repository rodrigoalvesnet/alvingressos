<?php
class Page extends AppModel
{
    public $validate = [
        'title' => [
            'rule' => 'notBlank',
            'message' => 'O título é obrigatório'
        ]
    ];

    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['title'])) {
            $this->data[$this->alias]['slug'] = Inflector::slug(strtolower($this->data[$this->alias]['title']), '-');
        }
        return true;
    }
}
