<?php
App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer
{

    protected function _getController($exception)
    {
        $controller = parent::_getController($exception);

        // força o uso do tema Kinder
        $controller->theme = Configure::read('Site.tema');
        $controller->layout = 'site'; // ou outro layout do tema

        return $controller;
    }
}