<?php
App::uses('AppController', 'Controller');

class GaleriasController extends AppController
{
    public $name = 'Galerias';
    public $uses = array('Galeria', 'GaleriasFoto');
    public $helpers = array('Html', 'Form');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('galeria'));
        $this->set('title_for_layout', 'Galerias');
    }

    // Lista todas as galerias
    public function admin_index()
    {
        $galerias = $this->Galeria->find('all', array(
            'order' => array('Galeria.created' => 'DESC')
        ));
        $this->set('registros', $galerias);
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $data = $this->request->data;

            $this->Galeria->create();
            if ($this->Galeria->save($data)) {
                $galeriaId = $this->Galeria->id;

                // Salvar fotos novas
                if (!empty($data['GaleriasFoto']['new'])) {
                    foreach ($data['GaleriasFoto']['new'] as $foto) {
                        if (!empty($foto['image']['name']) && $foto['image']['error'] === UPLOAD_ERR_OK) {
                            $uploadedPath = $this->_uploadPhoto($foto['image'], $galeriaId);
                            if ($uploadedPath) {
                                $this->GaleriasFoto->create();
                                $this->GaleriasFoto->save(array(
                                    'galeria_id'  => $galeriaId,
                                    'image'       => $uploadedPath,
                                    'title'       => isset($foto['title']) ? $foto['title'] : null,
                                    'description' => isset($foto['description']) ? $foto['description'] : null
                                ));
                            }
                        }
                    }
                }

                $this->Session->setFlash('Galeria criada com sucesso.', 'default', array(), 'success');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Erro ao criar galeria. Tente novamente.', 'default', array(), 'bad');
            }
        }
        $this->set('bcLinks', array(
            'Galerias' => '/admin/Galerias'
        ));
        $this->set('title_for_layout', 'Nova Galeria');
    }

    public function admin_edit($id = null)
    {
        if (!$id || !$this->Galeria->exists($id)) {
            throw new NotFoundException('Galeria inválida');
        }

        if ($this->request->is(array('post', 'put'))) {
            $data = $this->request->data;

            $this->Galeria->id = $id;
            if ($this->Galeria->save($data)) {
                // Atualizar fotos existentes
                if (!empty($data['GaleriasFoto'])) {
                    foreach ($data['GaleriasFoto'] as $fotoId => $foto) {
                        if ($fotoId !== 'new') {
                            $this->GaleriasFoto->id = $fotoId;
                            $this->GaleriasFoto->save(array(
                                'id'          => $fotoId,
                                'title'       => isset($foto['title']) ? $foto['title'] : null,
                                'description' => isset($foto['description']) ? $foto['description'] : null
                            ));
                        }
                    }
                }

                // Salvar novas fotos
                if (!empty($data['GaleriasFoto']['new'])) {
                    foreach ($data['GaleriasFoto']['new'] as $foto) {
                        if (!empty($foto['image']['name']) && $foto['image']['error'] === UPLOAD_ERR_OK) {
                            $uploadedPath = $this->_uploadPhoto($foto['image'], $id);
                            if ($uploadedPath) {
                                $this->GaleriasFoto->create();
                                $this->GaleriasFoto->save(array(
                                    'galeria_id'  => $id,
                                    'image'       => $uploadedPath,
                                    'title'       => isset($foto['title']) ? $foto['title'] : null,
                                    'description' => isset($foto['description']) ? $foto['description'] : null
                                ));
                            }
                        }
                    }
                }

                $this->Session->setFlash('Galeria atualizada com sucesso.', 'default', array(), 'success');
                return $this->redirect(array('action' => 'edit', $id));
            } else {
                $this->Session->setFlash('Erro ao atualizar galeria. Tente novamente.', 'default', array(), 'bad');
            }
        } else {
            $this->request->data = $this->Galeria->find('first', array(
                'conditions' => array('Galeria.id' => $id),
                'contain'    => array('GaleriasFoto')
            ));
        }

        $this->set('bcLinks', array(
            'Galerias' => '/admin/Galerias'
        ));
        $this->set('title_for_layout', 'Editar Galeria');
        $this->render('admin_add');
    }

    // Remove foto individual
    public function admin_deleteFoto($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Foto inválida'));
        }

        $foto = $this->GaleriasFoto->findById($id);
        if ($foto) {
            // Deleta arquivo físico
            $filePath = WWW_ROOT . 'uploads' . DS . $foto['GaleriasFoto']['image'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            if ($this->GaleriasFoto->delete($id)) {
                $this->Session->setFlash('Foto removida com sucesso.');
            } else {
                $this->Session->setFlash('Erro ao remover foto.');
            }
        }

        return $this->redirect($this->referer());
    }

    // Deleta galeria inteira com fotos
    public function admin_delete($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Galeria inválida'));
        }

        $galeria = $this->Galeria->findById($id);
        if ($galeria) {
            // Deleta fotos físicas
            foreach ($galeria['GaleriasFoto'] as $foto) {
                $filePath = WWW_ROOT . 'uploads' . DS . $foto['image'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            if ($this->Galeria->delete($id)) {
                $this->Session->setFlash('Galeria removida com sucesso.');
            } else {
                $this->Session->setFlash('Erro ao remover galeria.');
            }
        }

        return $this->redirect(array('action' => 'index'));
    }

    // Função privada para upload de fotos
    private function _uploadPhoto($file, $galleryId)
    {
        // Validações básicas
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name = uniqid() . '.' . $ext;

        $dir = WWW_ROOT . 'uploads' . DS . 'galerias' . DS . $galleryId . DS;

        // Cria pasta se não existir
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0775, true)) {
                return false;
            }
            // opcional: chmod($dir, 0775);
        }

        $path = $dir . $name;

        if (move_uploaded_file($file['tmp_name'], $path)) {
            // Retorna caminho relativo para salvar no banco
            return 'galerias/' . $galleryId . '/' . $name;
        }

        return false;
    }

    function galeria($id)
    {
        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';
        $cacheKey = 'galeria-' . $id;

        $galeria = Cache::read($cacheKey, 'galerias');

        if ($galeria === false) {
            $galeria = $this->Galeria->find('first', array(
                'conditions' => array(
                    'Galeria.id' => $id,
                    'Galeria.active' => 1
                ),
                'contain' => array(
                    'GaleriasFoto'
                )
            ));

            Cache::write($cacheKey, $galeria, 'galerias');
        }

        return $galeria;
    }
}
