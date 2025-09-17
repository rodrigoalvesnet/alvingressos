<div class="card">
    <div class="card-body">
        <?php
        echo $this->Html->link(
            'Usuários',
            array(
                'controller' => 'Migrates',
                'action' => 'users'
            ),
            array(
                'confirm' => 'Tem certeza que deseja migrar USUÁRIOS?',
                'class' => 'btn btn-primary mx-2'
            )
        );
        echo $this->Html->link(
            'Igrejas',
            array(
                'controller' => 'Migrates',
                'action' => 'churches'
            ),
            array(
                'confirm' => 'Tem certeza que deseja migrar IGREJAS?',
                'class' => 'btn btn-primary mx-2'
            )
        );
        echo $this->Html->link(
            'Eventos',
            array(
                'controller' => 'Migrates',
                'action' => 'events'
            ),
            array(
                'confirm' => 'Tem certeza que deseja migrar EVENTOS?',
                'class' => 'btn btn-primary mx-2'
            )
        );
        echo $this->Html->link(
            'Pedidos',
            array(
                'controller' => 'Migrates',
                'action' => 'orders'
            ),
            array(
                'confirm' => 'Tem certeza que deseja migrar PEDIDOS?',
                'class' => 'btn btn-primary mx-2'
            )
        );
        echo $this->Html->link(
            'Respostas',
            array(
                'controller' => 'Migrates',
                'action' => 'respostas'
            ),
            array(
                'confirm' => 'Tem certeza que deseja migrar RESPOSTAS?',
                'class' => 'btn btn-primary mx-2'
            )
        );
        echo $this->Html->link(
            'Anexos',
            array(
                'controller' => 'Migrates',
                'action' => 'anexos'
            ),
            array(
                'confirm' => 'Tem certeza que deseja migrar ANEXOS?',
                'class' => 'btn btn-primary mx-2'
            )
        );
        ?>
    </div>
</div>