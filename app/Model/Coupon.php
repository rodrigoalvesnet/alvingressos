<?php
class Coupon extends AppModel
{
    public $belongsTo = array(
        'Event'
    );

    public function afterSave($created, $options = array())
    {
        //limpa o cache
        Cache::clear(false, 'Coupons');
    }
}
