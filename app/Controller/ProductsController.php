<?php
class ProductsController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv', 'Imagem');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Produtos');
    }

    public function admin_add($eventId)
    {
        if ($this->request->is('post')) {
            $this->Product->create();
            //Vincular o Evento
            $this->request->data['Product']['event_id'] = $eventId;
            $this->request->data['Product']['price'] = $this->Alv->tratarValor($this->request->data['Product']['price']);
            //Se salvar corretamente
            if ($this->Product->save($this->request->data)) {
                //se foi informado a foto
                if (!empty($this->data['Product']['new_photo']['tmp_name'])) {
                    $id = $this->Product->getLastInsertId();
                    $this->_salvarImagem($id, $this->data['Product']['new_photo']);
                }
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect('/admin/events/edit/' . $eventId . '#Products');
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
            $evento['Event']['title'] => '/admin/Events/edit/' . $eventId
        ));
        $this->set('title_for_layout', 'Adicionar Produto');
    }

    public function admin_edit($eventId, $id)
    {
        $this->Product->id = $id;
        if (!$this->Product->exists()) {
            throw new NotFoundException(__('Registro Inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            // pr($this->data);
            // exit();
            $this->request->data['Product']['price'] = $this->Alv->tratarValor($this->request->data['Product']['price']);
            if ($this->Product->save($this->request->data)) {
                //se foi informado a foto
                if (!empty($this->data['Product']['new_photo'][0]['tmp_name'])) {
                    $this->_salvarImagem($id, $this->data['Product']['new_photo']);
                }
                $this->Flash->success('Registro salvo com sucesso');
                $this->redirect('/admin/events/edit/' . $eventId . '#Products');
            } else {
                $this->Flash->error('Não foi possível salvar o registro');
            }
        } else {
            $this->request->data = $this->Product->find(
                'first',
                array(
                    'conditions' => array(
                        'Product.id' => $id
                    ),
                    'contain' => array(
                        'ProductsImage'
                    )
                )
            );
            $this->request->data['Product']['price'] = $this->Alv->tratarValor($this->request->data['Product']['price'], 'pt');
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
            $evento['Event']['title'] => '/admin/Events/edit/' . $eventId
        ));
        $this->set('title_for_layout', 'Editar Produto');
        $this->render('admin_add');
    }

    function admin_delete($id)
    {
        $this->autoRender = false;
        //Pega os dados do evento
        $Product = $this->Product->find(
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
        if (!empty($Product)) {
            $eventId = $Product['Product']['event_id'];
            if ($this->Product->delete($id)) {
                $this->Flash->success('Produto excluído com sucesso!');
            } else {
                $this->Flash->error('Não foi deletar o registro');
            }
            $this->redirect('/admin/events/edit/' . $eventId . '#Products');
        } else {
            $this->Flash->error('Não foi deletar o registro');
        }
        $this->redirect('/admin/events');
    }

    function _salvarImagem($registroId, $images)
    {
        if (!empty($images)) {
            $this->loadModel('ProductsImage');
            $imagesSave = array();
            //Percorre as imagens
            foreach ($images as $image) {
                if (!empty($image['tmp_name'])) {
                    //faz o upload da imagem
                    $imagemPath = $this->Imagem->upload($image, true);
                    $imagesSave[] = array(
                        'id' => null,
                        'product_id' => $registroId,
                        'name' => $image['name'],
                        'type' => $image['type'],
                        'size' => $image['size'],
                        'filename' => $imagemPath
                    );
                }
            }
            if (!empty($imagesSave)) {
                //salva o caminho no banco
                if ($this->ProductsImage->saveAll($imagesSave)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    function admin_remove_image($id)
    {
        $this->autoRender = false;
        $this->loadModel('ProductsImage');
        if ($this->ProductsImage->delete($id)) {
            return true;
        } else {
            return false;
        }
    }

    function view($id)
    {
        $this->layout = 'ajax';
        $this->loadModel('Product');
        $product = $this->Product->find(
            'first',
            array(
                'conditions' => array(
                    'Product.id' => $id
                ),
                'contain' => array(
                    'ProductsImage'
                )
            )
        );
        $this->set('product', $product);
    }
}
