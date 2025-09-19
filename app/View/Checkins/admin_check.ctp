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
echo $this->Form->hidden('Checkin.user_id', array('value' => AuthComponent::user('id')));
echo $this->Form->hidden('Checkin.event_id', array('value' => $this->data['Order']['event_id']));
echo $this->Form->hidden('Checkin.order_id', array('value' => $this->data['Order']['id']));
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
                if ($this->data['Order']['status'] == 'approved') {
                    $icon = '<i class="fas fa-check text-success"></i>';
                    $title = 'Aprovado';
                    $reason = '';
                }
                if ($this->data['Order']['status'] == 'rejected') {
                    $icon = '<i class="fas fa-ban text-danger"></i>';
                    $title = 'Recusado';
                    $reason = $this->data['Order']['reason'];
                }
                if ($this->data['Order']['status'] == 'canceled') {
                    $icon = '<i class="fas fa-exclamation text-danger"></i>';
                    $title = 'Cancelado';
                    $reason = '';
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
                <div class="checkin-name"><?php echo $this->data['Ticket']['nome']; ?></div>
                <div class="checkin-cpf"><?php echo $this->data['Ticket']['cpf']; ?></div>
                <div class="checkin-church"><?php echo $this->data['Ticket']['modalidade_nome']; ?></div>
                <div class="checkin-church">Número: <?php echo $this->data['Ticket']['id']; ?></div>
                <div class="checkin-church">Data: <?php echo $this->Alv->tratarData($this->data['Ticket']['modalidade_data'], 'pt'); ?></div>
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
    if ($this->data['Order']['status'] == 'approved') {
        //Se ainda não foi feito
        if (!$checkinExists) {
            $eventId = $this->data['Order']['event_id'];
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