<?php
echo $this->Html->meta(array('http-equiv' => 'refresh', 'content' => '60'), false);
echo $message . '<br />';
echo 'Atualizado às ' . date('H:i:s');