<?php
class CouponsController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv', 'Imagem');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Grupos');
    }

    public function admin_add($eventId)
    {
        if ($this->request->is('post')) {
            $this->Coupon->create();
            //Vincular o Evento
            $this->request->data['Coupon']['event_id'] = $eventId;
            $this->request->data['Coupon']['value'] = $this->Alv->tratarValor($this->request->data['Coupon']['value']);
            //Se salvar corretamente
            if ($this->Coupon->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect('/admin/events/edit/' . $eventId . '#Coupons');
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
        $this->set('title_for_layout', 'Adicionar Cupom');
    }

    public function admin_edit($eventId, $id)
    {
        $this->Coupon->id = $id;
        if (!$this->Coupon->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Coupon']['value'] = $this->Alv->tratarValor($this->request->data['Coupon']['value']);
            if ($this->Coupon->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect('/admin/events/edit/' . $eventId . '#coupons');
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Coupon->findById($id);
            $this->request->data['Coupon']['value'] = $this->Alv->tratarValor($this->request->data['Coupon']['value'], 'pt');
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
        $this->set('title_for_layout', 'Editar Cupom');
        $this->render('admin_add');
    }

    function admin_delete($id)
    {
        $this->autoRender = false;
        //Pega os dados do evento
        $Coupon = $this->Coupon->find(
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
        if(!empty($Coupon)){
            $eventId = $Coupon['Coupon']['event_id'];
            if ($this->Coupon->delete($id)) {
                $this->Flash->success('Cupom excluído com sucesso!');
            } else {
                $this->Flash->error('Não foi deletar o registro');
            }
            $this->redirect('/admin/events/edit/' . $eventId . '#coupons');  
        }else{
            $this->Flash->error('Não foi deletar o registro');
        }    
        $this->redirect('/admin/events');  
    }
}
