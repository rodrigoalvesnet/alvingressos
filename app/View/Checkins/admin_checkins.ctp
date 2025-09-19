<?php if (!empty($presentes)) { ?>
    <div class="card">

        <div class="alert alert-primary" role="alert">
            <div class="row">
                <div class="col-lg-6">
                    <span style="font-size: 18px;line-height: 36px;">Total: <?php echo count($presentes); ?></span>                    
                </div>
                <div class="col-lg-6">
                    <input class="form-control" type="text" id="FiltroLocalizar" placeholder="Digite aqui par localizar...">
                </div>
            </div>

        </div>

        <div class="table-responsive">
            <table class="table table-filter">
                <thead>
                    <tr>
                        <th scope="col">Ticket</th>
                        <th scope="col">Nome</th>
                        <th scope="col">CPF</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Modalidade</th>
                        <th scope="col">Check-In</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($presentes as $presente) { ?>
                        <tr>
                            <td scope="row"><?php echo $presente['Ticket']['id']; ?></td>
                            <td scope="row"><?php echo $presente['Ticket']['nome']; ?></td>
                            <td scope="row"><?php echo $presente['Ticket']['cpf']; ?></td>
                            <td scope="row"><?php echo $presente['Ticket']['telefone']; ?></td>
                            <td scope="row"><?php echo $presente['Ticket']['modalidade_nome']; ?></td>
                            <td scope="row"><?php echo date('d/m/Y H:i', strtotime($presente['Checkin']['created'])); ?></td>
                            <td>
                                <?php
                                echo $this->Form->button(
                                    '<i class="fas fa-check"></i>',
                                    array(
                                        'onclick' => 'deleteCheckin(' . $presente['Checkin']['id'] . ', ' . $presente['Ticket']['event_id'] . ')',
                                        'type'    => 'button',
                                        'class' => 'btn btn-danger',
                                        'title' => 'EXCLUIR CHECKIN',
                                        'escape' => false,
                                        'div'    => false,
                                        'label' => false
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } else { ?>
    <div class="alert alert-primary" role="alert">Nenhum registro encontrado</div>
<?php }
echo $this->Html->script('alv', array('inline' => true));
?>