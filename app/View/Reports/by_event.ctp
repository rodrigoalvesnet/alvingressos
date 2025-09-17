<table border="1">
    <tr>
        <td class="header">Pedido</td>
        <td class="header">Nome</td>
        <td class="header">CPF</td>
        <td class="header">E-mail</td>
        <td class="header">Telefone</td>
        <td class="header">Igreja</td>
        <td class="header">Valor</td>
        <td class="header">Pagamento</td>
        <td class="header">Parcelas</td>
        <td class="header">Cupom</td>
        <td class="header"><?php echo mb_convert_encoding('Situação', 'ISO-8859-1'); ?></td>        
        <?php
        //Percorre as perguntas
        foreach ($registros[0]['Event']['Field'] as $field) { ?>
            <td class="header"><?php echo mb_convert_encoding($field['question'], 'ISO-8859-1'); ?></td>
        <?php } ?>
        <td class="header">Checkin</td>
    </tr>
    <?php
    //Percorre os registros
    foreach ($registros as $registro) { ?>
        <tr>
            <td class="header"><?php echo $registro['Order']['id']; ?></td>
            <td class="header"><?php echo mb_convert_encoding($registro['Order']['name'], 'ISO-8859-1'); ?></td>
            <td class="header"><?php echo $registro['Order']['cpf']; ?></td>
            <td class="header"><?php echo $registro['Order']['email']; ?></td>
            <td class="header"><?php echo $registro['Order']['phone']; ?></td>
            <td class="header"><?php echo mb_convert_encoding($registro['Church']['name'], 'ISO-8859-1'); ?></td>
            <td class="header"><?php echo $this->Alv->tratarValor($registro['Order']['value'], 'pt'); ?></td>
            <td class="header"><?php echo mb_convert_encoding($paymentsTypes[$registro['Order']['payment_type']], 'ISO-8859-1'); ?></td>
            <td class="header"><?php echo $registro['Order']['installments']; ?></td>
            <td class="header"><?php echo $registro['Coupon']['code']; ?></td>
            <td class="header"><?php echo mb_convert_encoding($status[$registro['Order']['status']], 'ISO-8859-1'); ?></td>
            <?php
            //Percorre as perguntas
            foreach ($registro['Response'] as $response) { ?>
                <td class="header"><?php echo mb_convert_encoding($response['response'], 'ISO-8859-1'); ?></td>
            <?php } ?>
            <td class="header"><?php echo empty($registro['Checkin']['id']) ? mb_convert_encoding('Não', 'ISO-8859-1') : 'Sim'; ?></td>
        </tr>
    <?php } ?>
</table>