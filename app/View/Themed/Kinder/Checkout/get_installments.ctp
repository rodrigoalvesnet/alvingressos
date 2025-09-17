<?php
$type = $this->data['Order']['payment_type'];
if (isset($paymentsType[$type])) {

    $taxValue = 0;
    $installments = $paymentsType[$type]['installments'];
    $taxType = $paymentsType[$type]['tax_type'];
    //Se tem juros
    if (!empty($taxType)) {
        $taxValue = $paymentsType[$type]['tax_value'];
        $priceWithTax = ($price + $taxValue);
    }
    $arrayParcelas = array();
    $i = 1;
    while ($i <= $installments) {
        //Se não tem acréscimo
        if ($taxType == 0) {
            $value = ($price / $i);
        }
        //Se o tipo de acrescimo é SEMPRE:
        if ($taxType == 1) {
            $value = ($priceWithTax / $i);
        }
        //Se o tipo de acrescimo é somente se for parcelado:
        if ($taxType == 2) {
            $value = ($price / $i);
            //Se a for acima da parcela 2:
            if ($i > 1) {
                $value = ($priceWithTax / $i);
            }
        }
        $value = number_format($value, 2, '.', '');
        $arrayParcelas[$i] = '(' . $i . 'x) R$' . $this->Alv->tratarValor($value, 'pt');
        $i++;
    }
    echo $this->Form->input(
        'Order.installments',
        array(
            'label' => 'Parcelas',
            'options' => $arrayParcelas,
            'class' => 'form-control',
            'empty' => 'Selecione a parcela',
            'div' => 'form-group',
            'required' => true
        )
    );
    echo $this->Form->hidden('Order.value',array(
        'value' => $price
    ));
}
