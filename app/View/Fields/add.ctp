<div class="card">
    <?php
    echo $this->Form->create('Field');
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'question',
                    array(
                        'label' => 'Pergunta',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'type',
                    array(
                        'label' => 'Tipo do Campo',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'options' => Configure::read('Fields.type'),
                        'required' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'size',
                    array(
                        'label' => 'Tamanho do Campo',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'options' => Configure::read('Fields.size'),
                        'required' => true,
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'options',
                    array(
                        'label' => 'Opções na lista (uma em cada linha)',
                        'class' => 'form-control',
                        'div' => 'form-group div-options'
                        
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'mandatory',
                    array(
                        'label' => 'Resposta obrigatória',
                        'type' => 'checkbox',
                        'class' => 'form-check-input',
                        'div' => 'form-check'
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

<?php
$typeQuestion = isset($this->data['Field']['type']) ? $this->data['Field']['type'] : null;
echo $this->Html->scriptBlock(
    "$(document).ready(function(){
        showHideOptions();
        $('#FieldType').change(function(){
            var _value = $(this).val();
            showHideOptions(_value);
        });
    });
    function showHideOptions(_value = ''){
        if(_value == 'list' || '$typeQuestion' == 'list'){
            $('.div-options').show();
        }else{
            $('.div-options').hide();
        }
    }
    ",
    array('block' => 'scriptBottom')
);
