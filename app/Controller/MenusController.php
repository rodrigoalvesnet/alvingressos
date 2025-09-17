<?php
class MenusController extends AppController
{

    public $uses = array('Menu');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('menuSite'));
    }

    public function admin_index()
    {
        $menus = $this->Menu->find('all', array(
            // 'conditions' => array('Menu.parent_id' => null),
            'order' => array('Menu.position ASC'),
            'recursive' => 1
        ));
        // pr($menus);exit();
        $this->set('registros', $menus);
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Menu->create();

            // pega o parent_id de forma segura
            $parentId = !empty($this->request->data['Menu']['parent_id'])
                ? $this->request->data['Menu']['parent_id']
                : null;

            // define posição automaticamente como última
            $last = $this->Menu->find('first', array(
                'conditions' => array('Menu.parent_id' => $parentId),
                'order' => array('Menu.position DESC')
            ));
            $this->request->data['Menu']['position'] = $last
                ? $last['Menu']['position'] + 1
                : 1;

            if ($this->Menu->save($this->request->data)) {
                Cache::delete('menus', 'menus'); // limpa cache
                $this->Session->setFlash('Menu criado com sucesso', 'default', array(), 'success');
                return $this->redirect(array('action' => 'index', 'admin' => true));
            } else {
                $this->Session->setFlash('Erro ao criar o menu. Verifique os campos.', 'default', array(), 'error');
            }
        }

        // lista de menus pais (só menus principais)
        $parents = $this->Menu->find('list', array(
            'conditions' => array('Menu.parent_id' => null),
            'order' => 'Menu.title ASC'
        ));
        $this->set('parents', $parents);
    }


    public function admin_edit($id = null)
    {
        if (!$id || !$this->Menu->exists($id)) {
            throw new NotFoundException('Menu não encontrado');
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->Menu->save($this->request->data)) {
                Cache::delete('menus', 'menus'); // limpa cache
                $this->Session->setFlash('Menu atualizado com sucesso');
                return $this->redirect(array('action' => 'index'));
            }
        } else {
            $this->request->data = $this->Menu->findById($id);
        }

        $parents = $this->Menu->find('list', array(
            'conditions' => array('Menu.parent_id' => null, 'Menu.id !=' => $id),
            'order' => 'Menu.title ASC'
        ));
        $this->set('parents', $parents);
    }

    public function admin_delete($id = null)
    {
        if (!$id || !$this->Menu->exists($id)) {
            throw new NotFoundException('Menu não encontrado');
        }
        if ($this->Menu->delete($id)) {
            Cache::delete('menus', 'menus'); // limpa cache
            $this->Session->setFlash('Menu excluído com sucesso');
        }
        return $this->redirect(array('action' => 'index'));
    }

    // mover para cima
    public function admin_up($id)
    {
        $menu = $this->Menu->findById($id);
        if (!$menu) throw new NotFoundException();

        $prev = $this->Menu->find('first', array(
            'conditions' => array(
                'Menu.parent_id' => $menu['Menu']['parent_id'],
                'Menu.position <' => $menu['Menu']['position']
            ),
            'order' => array('Menu.position DESC')
        ));

        if ($prev) {
            $this->Menu->id = $menu['Menu']['id'];
            $this->Menu->saveField('position', $prev['Menu']['Menu']['position']);
            $this->Menu->id = $prev['Menu']['Menu']['id'];
            $this->Menu->saveField('position', $menu['Menu']['position']);
            Cache::delete('menus', 'menus'); // limpa cache
        }

        return $this->redirect(array('action' => 'index'));
    }

    // mover para baixo
    public function admin_down($id)
    {
        $menu = $this->Menu->findById($id);
        if (!$menu) throw new NotFoundException();

        $next = $this->Menu->find('first', array(
            'conditions' => array(
                'Menu.parent_id' => $menu['Menu']['parent_id'],
                'Menu.position >' => $menu['Menu']['position']
            ),
            'order' => array('Menu.position ASC')
        ));

        if ($next) {
            $this->Menu->id = $menu['Menu']['id'];
            $this->Menu->saveField('position', $next['Menu']['Menu']['position']);
            $this->Menu->id = $next['Menu']['Menu']['id'];
            $this->Menu->saveField('position', $menu['Menu']['position']);
            Cache::delete('menus', 'menus'); // limpa cache
        }

        return $this->redirect(array('action' => 'index'));
    }

    public function menuSite()
    {
        $this->theme = Configure::read('Site.tema');
        $this->layout = 'ajax';

        $menus = Cache::read('menus', 'menus');

        if ($menus === false) {
            $menus = $this->Menu->find('all', array(
                'conditions' => array(
                    'Menu.parent_id' => null,
                    'Menu.active' => 1
                ),
                'order' => array('Menu.position ASC'),
                'recursive' => 1
            ));

            Cache::write('menus', $menus, 'menus');
        }

        $this->set('menus', $menus);
    }
}
