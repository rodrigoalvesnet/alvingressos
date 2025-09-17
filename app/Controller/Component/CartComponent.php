<?php
App::uses('Component', 'Controller');

class CartComponent extends Component
{

    public $components = array('Session');

    /**
     * Checa se o carrinho existe e se está válido.
     * Retorna:
     * - false se não existir ou expirou
     * - array com dados do carrinho + tempo restante se válido
     */
    public function checkCart()
    {
        $cart = $this->Session->read('Cart');
        if (!$cart) {
            return false;
        }

        $now = time();
        $remaining = $cart['expires_at'] - $now;

        if ($remaining <= 0) {
            $this->Session->delete('Cart');
            return false;
        }

        $cart['remaining'] = $remaining;
        return $cart;
    }

    /**
     * Cria ou atualiza o carrinho com 15 min de validade
     */
    public function saveCart($newItems)
    {
        //pega a sessão atual
        $actualCartSession = $this->Session->read('Cart');
        if (!empty($actualCartSession)) {
            foreach ($newItems['cart'] as $event_id => $newItem) {
                $actualCartSession['cart'][$event_id] = $newItem;
            }
        } else {
            $actualCartSession = $newItems;
        }
        $duracaoSession = Configure::read('Site.sessionCart');
        $now = time();
        $actualCartSession['expires_at'] = time() + ($duracaoSession * 60);
        $actualCartSession['remaining'] = ($actualCartSession['expires_at'] - $now);
        //Guarda a sessão
        $this->Session->write(
            'Cart',
            $actualCartSession
        );
    }
}
