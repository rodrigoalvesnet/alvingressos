<?php
class FieldsController extends AppController
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
            $this->Field->create();
            //Vincular o Evento
            $this->request->data['Field']['event_id'] = $eventId;
            //Se salvar corretamente
            if ($this->Field->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect('/admin/events/edit/' . $eventId . '#fields');
            } else {
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
        $this->set('title_for_layout', 'Adicionar Pergunta');
    }

    public function admin_edit($eventId, $id)
    {
        $this->Field->id = $id;
        if (!$this->Field->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Field->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect('/admin/events/edit/' . $eventId . '#fields');
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Field->findById($id);
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
        $this->set('title_for_layout', 'Editar Pergunta');
        $this->render('admin_add');
    }

    function admin_delete($id)
    {
        $this->autoRender = false;
        //Pega os dados do evento
        $Field = $this->Field->find(
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
        if (!empty($Field)) {
            $eventId = $Field['Field']['event_id'];
            if ($this->Field->delete($id)) {
                $this->Flash->success('Pergunta excluída com sucesso!');
            } else {
                $this->Flash->error('Não foi deletar o registro');
            }
            $this->redirect('/admin/events/edit/' . $eventId . '#fields');
        } else {
            $this->Flash->error('Não foi deletar o registro');
        }
        $this->redirect('/admin/events');
    }
}
