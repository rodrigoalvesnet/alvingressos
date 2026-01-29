<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    // public $theme = 'V1';

    function beforeRender()
    {
        if ($this->name == 'CakeError') {
            $this->layout = 'site';
        }

        // tenta recuperar do cache
        $siteConfig = Cache::read('site_config');

        if ($siteConfig === false) {
            // não existe em cache, buscar do banco
            App::import('Model', 'Site'); // supondo que sua tabela é Config
            $Site = new Site();

            $siteConfig = $Site->find('first');

            // salva no cache
            Cache::write('site_config', $siteConfig);
        }

        // disponibiliza para as views
        $this->set('siteConfig', $siteConfig);
    }

    public $components = array(
        'Flash',
        'Session',
        'Paginator',
        'Acl',
        'Auth' => array(
            'authorize' => array(
                'Controller',
                'Actions' => array('actionPath' => 'controllers')
            ),
            'loginAction' => array(
                'controller' => 'Users',
                'action' => 'login',
                'admin' => false
            ),
            'loginRedirect' => array(
                'controller' => 'Pages',
                'action' => 'home',
                'admin' => false
            ),
            'unauthorizedRedirect' => array(
                'controller' => 'Pages',
                'action' => 'home',
                'admin' => false
            ),
            'logoutRedirect'    => array(
                'controller' => 'Users',
                'action' => 'login',
                'admin' => false
            ),
            'authError'         => 'Você não está autorizado a acessar está página.',
            'flash' => array(
                'element' => 'warning'
            ),
            'authenticate' => array(
                'Form' => array(
                    'fields' => array(
                        'username' => 'email',
                        'password' => 'password'
                    ),
                    'userModel' => 'User'
                )
            )
        )
    );

    public $helpers = array('Session');

    function isAuthorized($user)
    {
        // Exemplo: só admin pode acessar controllers restritos
        // if (isset($user['role']) && $user['role'] === 'admin') {
        //     return true;
        // }
        return false;
    }

    protected function _isJson()
    {
        return !empty($this->request->params['ext']) && $this->request->params['ext'] === 'json'
            || ($this->RequestHandler && $this->RequestHandler->prefers('json'))
            || ($this->request->query('json') == 1);
    }

    protected function _respond($payload, $redirect = null)
    {
        if ($this->_isJson()) {
            $this->set(['payload' => $payload, '_serialize' => ['payload']]);
            return;
        }

        if (!empty($payload['ok'])) {
            $this->Session->setFlash(!empty($payload['message']) ? $payload['message'] : 'Operação realizada.', 'default', [], 'success');
        } else {
            $this->Session->setFlash(!empty($payload['error']) ? $payload['error'] : 'Falha na operação.', 'default', [], 'error');
        }

        if ($redirect) return $this->redirect($redirect);
        return $this->redirect($this->referer());
    }
}
