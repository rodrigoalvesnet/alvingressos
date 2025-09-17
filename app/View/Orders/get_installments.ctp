<?php
$type = $this->data['Order']['payment_type'];
if (isset($paymentsType[$type])) {
    //Se é free ou pix customizado
    if ($type == 'free' || $type == 'pix_old') {
        echo $this->Form->hidden(
            'Order.installments',
            array(
                'value' => 1
            )
        );
        if ($type == 'pix_old') {
            echo $this->Form->input(
                'Order.price',
                array(
                    'label' => 'Valor Total',
                    'value' => 'R$ ' . $this->Alv->tratarValor($price, 'pt'),
                    'class' => 'form-control',
                    'div' => 'form-group',
                    'disabled' => true
                )
            );

            // echo $this->Form->input(
            //     'Order.chave_pix',
            //     array(
            //         'label' => 'Chave PIX (utilize a chave abaixo)',
            //         'value' => $paymentsType[$type]['pix'], //Exibe a chave PIX
            //         'class' => 'form-control',
            //         'div' => 'form-group',
            //         'disabled' => true
            //     )
            // );
?>
            <div class="form-group">
                <label for="OrderChavePix">Chave PIX (utilize a chave abaixo)</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="OrderChavePix" value="<?php echo $paymentsType[$type]['pix']; ?>" disabled />
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" onclick="copiarTexto()">Copiar</button>
                    </div>
                </div>
            </div>
            <script>
                function copiarTexto() {
                    const texto = document.getElementById("OrderChavePix").value;

                    // Cria um elemento <textarea> temporário
                    const temp = document.createElement("textarea");
                    temp.value = texto;

                    // Evita que o textarea apareça na tela
                    temp.style.position = "fixed";
                    temp.style.left = "-9999px";

                    document.body.appendChild(temp);
                    temp.focus();
                    temp.select();

                    try {
                        const sucesso = document.execCommand("copy");
                        if (sucesso) {
                            alert("Chave PIX copiada com sucesso!");
                        } else {
                            alert("Não foi possível copiar.");
                        }
                    } catch (err) {
                        alert("Erro ao copiar: " + err);
                    }

                    document.body.removeChild(temp);
                }
            </script>
<?php
        }
    } else {
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
    }
}
