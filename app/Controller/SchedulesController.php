<?php
class SchedulesController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Calendário');
    }

    public function admin_add($eventId)
    {
        if ($this->request->is('post')) {
            $this->Schedule->create();
            //Vincular o Evento
            $this->request->data['Schedule']['event_id'] = $eventId;
            //trata os dados
            $this->request->data['Schedule']['date'] = $this->Alv->tratarData($this->request->data['Schedule']['date']);
            //Se salvar corretamente
            if ($this->Schedule->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect('/admin/events/edit/' . $eventId . '#Schedules');
            } else {
                //trata os dados
                $this->request->data['Schedule']['date'] = $this->Alv->tratarData($this->request->data['Schedule']['date'], 'pt');
                $this->Flash->error('Não foi possível salvar o registro');
            }
        }

        //Pega o titulo do evento
        $this->loadModel('Event');
        $evento = $this->Event->find(
            'first',
            array(
                'conditions' => array(
                    'id' => $eventId
                ),
                'recursive' => -1,
                'fields' => array('title')
            )
        );
        $this->set('bcLinks', array(
            'Eventos' => '/admin/events',
            $evento['Event']['title'] => '/Events/edit/' . $eventId
        ));
        $this->set('title_for_layout', 'Adicionar Horário');
    }

    public function admin_edit($eventId, $id)
    {
        $this->Schedule->id = $id;
        if (!$this->Schedule->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            //trata os dados
            $this->request->data['Schedule']['date'] = $this->Alv->tratarData($this->request->data['Schedule']['date']);
            if ($this->Schedule->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect('/admin/events/edit/' . $eventId . '#schedules');
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Schedule->findById($id);
            //trata os dados
            $this->request->data['Schedule']['date'] = $this->Alv->tratarData($this->request->data['Schedule']['date'], 'pt');
        }
        //Pega o titulo do evento
        $this->loadModel('Event');
        $evento = $this->Event->find(
            'first',
            array(
                'conditions' => array(
                    'id' => $eventId
                ),
                'recursive' => -1,
                'fields' => array('title')
            )
        );
        $this->set('bcLinks', array(
            'Eventos' => '/admin/events',
            $evento['Event']['title'] => '/Events/edit/' . $eventId
        ));
        $this->set('title_for_layout', 'Editar Horário');
        $this->render('admin_add');
    }

    function admin_delete($id)
    {
        $this->autoRender = false;
        //Pega os dados do evento
        $Schedule = $this->Schedule->find(
            'first',
            array(
                'conditions' => array(
                    'id' => $id
                ),
                'fields' => array(
                    'event_id'
                ),
                'recursive' => -1
            )
        );
        //Se encontrou o evento
        if (!empty($Schedule)) {
            $eventId = $Schedule['Schedule']['event_id'];
            if ($this->Schedule->delete($id)) {
                $this->Flash->success('Horário excluído com sucesso!');
            } else {
                $this->Flash->error('Não foi deletar o registro');
            }
            $this->redirect('/admin/events/edit/' . $eventId . '#schedules');
        } else {
            $this->Flash->error('Não foi deletar o registro');
        }
        $this->redirect('/admin/events');
    }
}
