<?php echo $this->element('print_cabecalho'); ?>
<?php if (!empty($registros)) { ?>
    <table>
        <thead>
            <tr>
                <th class="text-center">Pulseira</th>
                <th>Data/Hora</th>
                <th>Criança</th>
                <th>Responsável</th>
                <th>Telefone</th>
                <th>Atração</th>
                <th class="text-center">Situação</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>


            <?php
            $valorTotal = 0;
            foreach ($registros as $registro) {
                $valorTotal += $registro['Estadia']['valor_total'];
            ?>
                <tr>
                    <td class="text-center">
                        <?php echo $registro['Estadia']['pulseira_numero']; ?>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($registro['Estadia']['created'])); ?></td>
                    <td><?php echo $registro['Estadia']['crianca_nome']; ?></td>
                    <td><?php echo $registro['Estadia']['responsavel_nome']; ?></td>
                    <td><?php echo $registro['Estadia']['telefone']; ?></td>
                    <td><?php echo $registro['Atracao']['nome']; ?> (<?php echo $registro['Tarifa']['nome']; ?>)</td>
                    <td class="text-center"><?php echo $status[$registro['Estadia']['status']]; ?></td>
                    <td class="text-right"><?php echo $this->Alv->tratarValor($registro['Estadia']['valor_total'], 'pt'); ?></td>
                </tr>
            <?php } ?>


        </tbody>
    </table>
    <br ?>
    <div class="text-right border-top w-100">Total: <?php echo $this->Alv->tratarValor($valorTotal, 'pt'); ?></div>
<?php } ?>