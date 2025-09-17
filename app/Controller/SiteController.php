<?php
class SiteController extends AppController
{
    var $components = array('Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    function admin_index()
    {
        $this->loadModel('Site');
        if (!empty($this->data)) {
            $this->request->data['Site']['id'] = 1;
            if ($this->Site->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }
        $this->data = $this->Site->find('first');
        $this->set('title_for_layout', 'Informações do Site');
    }
}
