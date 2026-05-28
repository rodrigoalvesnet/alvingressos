<table border="1">
    <tr>
        <td class="header">Ticket</td>
        <td class="header">Pedido</td>
        <td class="header">Data Criação</td>
        <td class="header">Nome</td>
        <td class="header">CPF</td>
        <td class="header">E-mail</td>
        <td class="header">Telefone</td>
        <td class="header">Evento</td>
        <td class="header">Modalidade</td>
        <td class="header">Data Agendada</td>
        <td class="header"><?php echo mb_convert_encoding('Situação', 'ISO-8859-1'); ?></td>
        <td class="header">Checkin</td>
    </tr>
    <?php foreach ($registros as $registro): ?>
    <tr>
        <td><?php echo $registro['Ticket']['id']; ?></td>
        <td><?php echo $registro['Ticket']['order_id']; ?></td>
        <td><?php echo $this->Alv->tratarData($registro['Ticket']['created'], 'pt'); ?></td>
        <td><?php echo mb_convert_encoding($registro['Ticket']['nome'], 'ISO-8859-1'); ?></td>
        <td><?php echo $registro['Ticket']['cpf']; ?></td>
        <td><?php echo $registro['Ticket']['email']; ?></td>
        <td><?php echo $registro['Ticket']['telefone']; ?></td>
        <td><?php echo mb_convert_encoding(!empty($registro['Event']['title']) ? $registro['Event']['title'] : '', 'ISO-8859-1'); ?></td>
        <td><?php echo mb_convert_encoding($registro['Ticket']['modalidade_nome'], 'ISO-8859-1'); ?></td>
        <td><?php echo $this->Alv->tratarData($registro['Ticket']['modalidade_data'], 'pt'); ?></td>
        <td><?php
            $orderStatus = isset($registro['Order']['status']) ? $registro['Order']['status'] : '';
            if (!empty($registro['Checkin']['id'])) {
                $situacao = 'Utilizado';
            } elseif ($orderStatus === 'canceled') {
                $situacao = 'Cancelado';
            } elseif ($orderStatus === 'approved') {
                $situacao = 'Agendado';
            } elseif ($orderStatus === 'pending') {
                $situacao = 'Pendente';
            } elseif ($orderStatus === 'rejected') {
                $situacao = 'Rejeitado';
            } else {
                $situacao = '';
            }
            echo mb_convert_encoding($situacao, 'ISO-8859-1');
        ?></td>
        <td><?php echo !empty($registro['Checkin']['created']) ? $registro['Checkin']['created'] : ''; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
