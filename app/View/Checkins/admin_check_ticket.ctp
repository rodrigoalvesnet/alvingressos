<?php if (!empty($ticketNotFound)): ?>
<div class="modal-body checkin">
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="checkin-icon text-center"><i class="fas fa-times-circle text-danger"></i></div>
            <div class="checkin-title">Ingresso não encontrado</div>
            <div class="checkin-reason">Verifique se o QR Code é válido.</div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Fechar</button>
</div>
<?php else: ?>
<?php
echo $this->Form->create(
    'Checkin',
    array(
        'url' => array(
            'controller' => 'Checkin',
            'action' => 'checkin'
        )
    )
);
// pr($this->data);
// Cortesia não tem Order (não veio de compra) — trata como já aprovada
$orderStatus = !empty($this->data['Order']['status']) ? $this->data['Order']['status'] : 'approved';
echo $this->Form->hidden('Checkin.user_id', array('value' => AuthComponent::user('id')));
echo $this->Form->hidden('Checkin.event_id', array('value' => isset($this->data['Order']['event_id']) ? $this->data['Order']['event_id'] : null));
echo $this->Form->hidden('Checkin.order_id', array('value' => isset($this->data['Order']['id']) ? $this->data['Order']['id'] : null));
echo $this->Form->hidden('Checkin.ticket_id', array('value' => $this->data['Ticket']['id']));
?>
<div class="modal-body checkin">
    <div class="row">
        <div class="col-lg-12 text-center">
            <div id="loadingData">
                Aguarde ...
            </div>
            <div id="dataResult">
                <?php
                $icon = '<i class="fas fa-exclamation text-warning"></i>';
                $title = 'Pendente';
                $reason = '';
                if ($orderStatus == 'approved') {
                    $icon = '<i class="fas fa-check text-success"></i>';
                    $title = !empty($isCortesia) ? 'Cortesia' : 'Aprovado';
                    $reason = '';
                }
                if ($orderStatus == 'rejected') {
                    $icon = '<i class="fas fa-ban text-danger"></i>';
                    $title = 'Recusado';
                    $reason = $this->data['Order']['reason'];
                }
                if ($orderStatus == 'canceled') {
                    $icon = '<i class="fas fa-exclamation text-danger"></i>';
                    $title = 'Cancelado';
                    $reason = '';
                }
                //Se pertence a outra unidade
                if ($bloqueiaUnidade) {
                    $icon = '<i class="fas fa-ban text-danger"></i>';
                    $title = 'Unidade Incorreta!';
                    $reason = 'Este ingresso pertence à unidade <strong>' . h($nomeUnidadeCorreta) . '</strong>. O check-in deve ser realizado nessa unidade.';
                }
                //Se está adiantado (não se aplica a cortesia, que não tem data fixa)
                if ($bloqueiaCheckinAdiantado) {
                    $icon = '<i class="fas fa-clock text-info"></i>';
                    $title = 'Data Incorreta!';
                    $reason = 'Passaporte agendado somente para <strong>' . date('d/m/Y', strtotime($this->data['Ticket']['modalidade_data'])) . '</strong>';
                }
                //Se está atrasado / expirado
                if ($bloqueiaCheckinAtrasado) {
                    $icon = '<i class="fas fa-exclamation text-danger"></i>';
                    if (!empty($isCortesia)) {
                        $title = 'Cortesia Vencida!';
                        $reason = 'Cortesia vencida em <strong>' . date('d/m/Y', strtotime($this->data['Ticket']['valido_ate'])) . '</strong>';
                    } else {
                        $title = 'Passaporte Vencido!';
                        $reason = 'Passaporte vencido em <strong>' . date('d/m/Y', strtotime($this->data['Ticket']['modalidade_data'])) . '</strong>';
                    }
                }
                //Se já foi feito
                if ($checkinExists) {
                    $icon = '<i class="fas fa-exclamation text-info"></i>';
                    $title = 'Já Realizado!';
                    $reason = 'às ' . date('d/m/Y H:i', strtotime($this->data['Checkin']['created'])) . ' - Por ' . $this->data['Checkin']['User']['name'];
                }
                ?>
                <div class="checkin-icon text-center"><?php echo $icon; ?></div>
                <div class="checkin-title"><?php echo $title; ?></div>
                <div class="checkin-reason"><?php echo $reason; ?></div>
                <hr />
                <?php if (!empty($this->data['Ticket']['nome'])) { ?>
                    <div class="checkin-name"><?php echo $this->data['Ticket']['nome']; ?></div>
                <?php } ?>
                <?php if (!empty($this->data['Ticket']['cpf'])) { ?>
                    <div class="checkin-cpf"><?php echo $this->data['Ticket']['cpf']; ?></div>
                <?php } ?>
                <div class="checkin-church"><?php echo $this->data['Ticket']['modalidade_nome']; ?></div>
                <div class="checkin-church">Número: <?php echo $this->data['Ticket']['id']; ?></div>
                <?php if (!empty($isCortesia)) { ?>
                    <div class="checkin-church">Válida até: <?php echo $this->Alv->tratarData($this->data['Ticket']['valido_ate'], 'pt'); ?></div>
                <?php } else { ?>
                    <div class="checkin-church">Data: <?php echo $this->Alv->tratarData($this->data['Ticket']['modalidade_data'], 'pt'); ?></div>
                <?php } ?>
                <?php
                if (!empty($this->data['Response'])) {
                    echo '<hr />';
                    foreach ($this->data['Response'] as $response) {
                        echo $response['Field']['question'] . '<br />';
                        echo '<strong>' . $response['response'] . '</strong><br />';
                    }
                }
                ?>
            </div>

        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Fechar</button>
    <?php
    //se pode fazer o checkin
    if ($orderStatus == 'approved') {
        //Se ainda não foi feito
        if (!$checkinExists && !$bloqueiaCheckinAdiantado && !$bloqueiaCheckinAtrasado && !$bloqueiaUnidade) {
            $eventId = isset($this->data['Order']['event_id']) ? $this->data['Order']['event_id'] : 0;
            echo $this->Form->button(
                'Confirmar Checkin',
                array(
                    'id' => 'btnDoCheckin',
                    'onclick' => 'doCheckin(' . $eventId . ')',
                    'type'    => 'button',
                    'class' => 'btn btn-success text-white',
                    'id' => 'btnCheckin',
                    'div'    => false,
                    'label' => false
                )
            );
        }
    }
    ?>
</div>
<?php echo $this->Form->end(); ?>
<?php endif; ?>