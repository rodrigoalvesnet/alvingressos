<table border="1">
    <tr>
        <td class="header">Pedido</td>
        <td class="header">Data</td>
        <td class="header">Nome</td>
        <td class="header">CPF</td>
        <td class="header">E-mail</td>
        <td class="header">Telefone</td>
        <td class="header">Unidade</td>
        <td class="header">Valor</td>
        <td class="header">Pagamento</td>
        <td class="header">Parcelas</td>
        <td class="header">Cupom</td>
        <td class="header"><?php echo mb_convert_encoding('Situação', 'ISO-8859-1'); ?></td>        
        <td class="header">Checkin</td>
    </tr>
    <?php
    //Percorre os registros
    foreach ($registros as $registro) { ?>
        <tr>
            <td class="header"><?php echo $registro['Order']['id']; ?></td>
            <td class="header"><?php echo $this->Alv->tratarData($registro['Order']['created'], 'pt'); ?></td>
            <td class="header"><?php echo mb_convert_encoding($registro['Order']['name'], 'ISO-8859-1'); ?></td>
            <td class="header"><?php echo $registro['Order']['cpf']; ?></td>
            <td class="header"><?php echo $registro['Order']['email']; ?></td>
            <td class="header"><?php echo $registro['Order']['phone']; ?></td>
            <td class="header"><?php echo mb_convert_encoding($registro['Unidade']['name'], 'ISO-8859-1'); ?></td>
            <td class="header"><?php echo $this->Alv->tratarValor($registro['Order']['value'], 'pt'); ?></td>
            <td class="header"><?php echo mb_convert_encoding($paymentsTypes[$registro['Order']['payment_type']], 'ISO-8859-1'); ?></td>
            <td class="header"><?php echo $registro['Order']['installments']; ?></td>
            <td class="header"><?php echo $registro['Coupon']['code']; ?></td>
            <td class="header"><?php echo mb_convert_encoding($status[$registro['Order']['status']], 'ISO-8859-1'); ?></td>
            <td class="header"><?php echo !empty($registro['Checkin']['created']) ? $registro['Checkin']['created'] : null; ?></td>
        </tr>
    <?php } ?>
</table>