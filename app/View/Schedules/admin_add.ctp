<div class="card">
    <?php
    echo $this->Form->create('Schedule');
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'title',
                    array(
                        'label' => 'Título/Nome',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'description',
                    array(
                        'label' => 'Descrição',
                        'class' => 'form-control',
                        'div' => 'form-group',
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'date',
                    array(
                        'type' => 'text',
                        'label' => 'Data',
                        'class' => 'form-control datepicker',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'start',
                    array(
                        'type' => 'text',
                        'label' => 'Início',
                        'class' => 'form-control time',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-4">
                <?php
                echo $this->Form->input(
                    'end',
                    array(
                        'type' => 'text',
                        'label' => 'Final',
                        'class' => 'form-control time',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
        </div>
    </div>
    <div class="card-footer border-top">
        <?php
        echo $this->Form->submit(
            'Salvar',
            array(
                'type'    => 'submit',
                'class' => 'btn btn-primary',
                'div'    => false,
                'label' => false
            )
        );
        ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>