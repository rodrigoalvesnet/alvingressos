<?php
class TalkersController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv', 'Imagem');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Convidados');
    }

    public function admin_add($eventId)
    {
        if ($this->request->is('post')) {
            $this->Talker->create();
            //Vincular o Evento
            $this->request->data['Talker']['event_id'] = $eventId;
            //Se salvar corretamente
            if ($this->Talker->save($this->request->data)) {
                //se foi informado a foto
                if (!empty($this->data['Talker']['new_photo']['tmp_name'])) {
                    $id = $this->Talker->getLastInsertId();
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Talker']['new_photo'];
                    $this->_salvarImagem($id, $urlFoto);
                }
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect(
                    '/admin/events/edit/' . $eventId . '#talkers'
                );
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
        $this->set('title_for_layout', 'Adicionar Preletor');
    }

    public function admin_edit($eventId, $id)
    {
        $this->Talker->id = $id;
        if (!$this->Talker->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Talker->save($this->request->data)) {
                //se foi informado a foto
                if (!empty($this->data['Talker']['new_photo']['tmp_name'])) {
                    //pega o caminho temporário da imagem
                    $urlFoto = $this->data['Talker']['new_photo'];
                    $this->_salvarImagem($id, $urlFoto);
                }
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect('/admin/events/edit/' . $eventId . '#talkers');
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Talker->findById($id);
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
        $this->set('title_for_layout', 'Editar Preletor');
        $this->render('admin_add');
    }

    function admin_delete($id)
    {
        $this->autoRender = false;
        //Pega os dados do evento
        $talker = $this->Talker->find(
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
        if(!empty($talker)){
            $eventId = $talker['Talker']['event_id'];
            if ($this->Talker->delete($id)) {
                $this->Flash->success('Preletor excluído com sucesso!');
            } else {
                $this->Flash->error('Não foi deletar o registro');
            }
            $this->redirect('/admin/events/edit/' . $eventId . '#talkers');  
        }else{
            $this->Flash->error('Não foi deletar o registro');
        }    
        $this->redirect('/admin/events');  
    }

    function _salvarImagem($registroId, $urlFoto)
    {
        //faz o upload da imagem
        $imagemPath = $this->Imagem->upload($urlFoto, true);
        //salva o caminho no banco
        if ($this->Talker->updateAll(
            array('Talker.photo' => "'" . $imagemPath . "'"),
            array('Talker.id' => $registroId)
        )) {
            return true;
        } else {
            return false;
        }
    }
}
