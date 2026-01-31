<?php
class FormasPagamento extends AppModel {

    public function afterSave($created, $options = Array()) {
        //limpa o cache
        Cache::clear(false,'FormasPagamentos');
	}
}