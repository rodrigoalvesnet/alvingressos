<?php
class LotsController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Lotes');
    }

    public function admin_add($eventId)
    {
        if ($this->request->is('post')) {
            $this->Lot->create();
            //Vincular o Evento
            $this->request->data['Lot']['event_id'] = $eventId;
            //trata os dados
            $this->request->data['Lot']['value'] = $this->Alv->tratarValor($this->request->data['Lot']['value']);
            $this->request->data['Lot']['start_date'] = $this->Alv->tratarData($this->request->data['Lot']['start_date']);
            $this->request->data['Lot']['end_date'] = $this->Alv->tratarData($this->request->data['Lot']['end_date']);
            //trata os valores dos tipos dos pagamentos
            foreach ($this->request->data['Lot']['payments_type'] as $k => $paymnentType) {
                if (!empty($paymnentType['tax_value'])) {
                    $this->request->data['Lot']['payments_type'][$k]['tax_value'] = $this->Alv->tratarValor($paymnentType['tax_value']);
                }
            }
            $this->request->data['Lot']['payments_type'] = serialize($this->request->data['Lot']['payments_type']);
            //Se salvar corretamente
            if ($this->Lot->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect('/admin/events/edit/' . $eventId . '#lots');
            } else {
                //trata os dados
                $this->request->data['Lot']['value'] = $this->Alv->tratarValor($this->request->data['Lot']['value'], 'pt');
                $this->request->data['Lot']['start_date'] = $this->Alv->tratarData($this->request->data['Lot']['start_date'], 'pt');
                $this->request->data['Lot']['end_date'] = $this->Alv->tratarData($this->request->data['Lot']['end_date'], 'pt');
                $this->request->data['Lot']['payments_type'] = unserialize($this->request->data['Lot']['payments_type']);
                //trata os valores dos tipos dos pagamentos
                foreach ($this->request->data['Lot']['payments_type'] as $k => $paymnentType) {
                    if (!empty($paymnentType['tax_value'])) {
                        $this->request->data['Lot']['payments_type'][$k]['tax_value'] = $this->Alv->tratarValor($paymnentType['tax_value'], 'pt');
                    }
                }
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
        $this->set('title_for_layout', 'Adicionar Lote');
    }

    public function admin_edit($eventId, $id)
    {
        $this->Lot->id = $id;
        if (!$this->Lot->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            // pr($this->data);exit();
            //trata os dados
            $this->request->data['Lot']['value'] = $this->Alv->tratarValor($this->request->data['Lot']['value']);
            $this->request->data['Lot']['start_date'] = $this->Alv->tratarData($this->request->data['Lot']['start_date']);
            $this->request->data['Lot']['end_date'] = $this->Alv->tratarData($this->request->data['Lot']['end_date']);
            $this->request->data['Lot']['rules'] = serialize($this->request->data['Lot']['rules']);
            //trata os valores dos tipos dos pagamentos
            foreach ($this->request->data['Lot']['payments_type'] as $k => $paymnentType) {
                if (!empty($paymnentType['tax_value'])) {
                    $this->request->data['Lot']['payments_type'][$k]['tax_value'] = $this->Alv->tratarValor($paymnentType['tax_value']);
                }
            }
            $this->request->data['Lot']['payments_type'] = serialize($this->request->data['Lot']['payments_type']);
            if ($this->Lot->save($this->request->data)) {
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect('/admin/events/edit/' . $eventId . '#Lots');
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Lot->findById($id);
            //trata os dados
            $this->request->data['Lot']['value'] = $this->Alv->tratarValor($this->request->data['Lot']['value'], 'pt');
            $this->request->data['Lot']['start_date'] = $this->Alv->tratarData($this->request->data['Lot']['start_date'], 'pt');
            $this->request->data['Lot']['end_date'] = $this->Alv->tratarData($this->request->data['Lot']['end_date'], 'pt');
            $this->request->data['Lot']['rules'] = unserialize($this->request->data['Lot']['rules']);
            $this->request->data['Lot']['payments_type'] = unserialize($this->request->data['Lot']['payments_type']);
            //trata os valores dos tipos dos pagamentos
            foreach ($this->request->data['Lot']['payments_type'] as $k => $paymnentType) {
                if (!empty($paymnentType['tax_value'])) {
                    $this->request->data['Lot']['payments_type'][$k]['tax_value'] = $this->Alv->tratarValor($paymnentType['tax_value'], 'pt');
                }
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
        $this->set('title_for_layout', 'Editar Lote');
        $this->render('admin_add');
    }

    function admin_delete($id)
    {
        $this->autoRender = false;
        //Pega os dados do evento
        $Lot = $this->Lot->find(
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
        if (!empty($Lot)) {
            $eventId = $Lot['Lot']['event_id'];
            if ($this->Lot->delete($id)) {
                $this->Flash->success('Lote excluído com sucesso!');
            } else {
                $this->Flash->error('Não foi deletar o registro');
            }
            $this->redirect('/admin/events/edit/' . $eventId . '#lots');
        } else {
            $this->Flash->error('Não foi deletar o registro');
        }
        $this->redirect('/admin/events');
    }
}
