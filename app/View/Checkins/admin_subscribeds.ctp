<?php if (!empty($subscribeds)) { ?>
    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Pedido</th>
                        <th scope="col">Nome</th>
                        <th scope="col">CPF</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Igreja</th>
                        <th scope="col">Data/Hora</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($subscribeds as $subscribed) { ?>
                        <tr>
                            <th scope="row"><?php echo $subscribed['Order']['id']; ?></th>
                            <th scope="row"><?php echo $subscribed['Order']['name']; ?></th>
                            <th scope="row"><?php echo $subscribed['Order']['cpf']; ?></th>
                            <th scope="row"><?php echo $subscribed['Order']['phone']; ?></th>
                            <th scope="row"><?php echo $subscribed['Order']['Unidade']['name']; ?></th>
                            <th scope="row"><?php echo date('d/m/Y H:i', strtotime($subscribed['Checkin']['created'])); ?></th>
                            <td>
                                <?php
                                echo $this->Form->button(
                                    '<i class="fas fa-check"></i>',
                                    array(
                                        'onclick' => 'checkinManual(' . $subscribed['Order']['id'] . ')',
                                        'type'    => 'button',
                                        'class' => 'btn btn-success btn-no-padding',
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
<?php } ?>