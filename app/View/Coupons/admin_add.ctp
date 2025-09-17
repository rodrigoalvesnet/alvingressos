<div class="card">
    <?php
    echo $this->Form->create(
        'Coupon',
        array(
            'type' => 'file'
        )
    );
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <?php
                echo $this->Form->input(
                    'code',
                    array(
                        'label' => 'Código do Cupom',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'type',
                    array(
                        'label' => 'Tipo do Desconto',
                        'options' => array(
                            'percent' => 'Percentual',
                            'money' => 'Em dinheiro'
                        ),
                        'class' => 'form-control',
                        'div' => 'form-group',
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'value',
                    array(
                        'label' => 'Valor do Desconto',
                        'type' => 'text',
                        'class' => 'form-control money',
                        'div' => 'form-group div-money-value',
                    )
                );
                echo $this->Form->input(
                    'percent',
                    array(
                        'label' => 'Percentual do Desconto',
                        'type' => 'number',
                        'max' => 100,
                        'min' => 1,
                        'class' => 'form-control',
                        'div' => 'form-group div-percent-value',
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'description',
                    array(
                        'label' => 'Descrição do cupom de desconto',
                        'class' => 'form-control',
                        'div' => 'form-group',
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'unique_by_user',
                    array(
                        'label' => 'Permitir o uso desde cupom somente uma vez por usuário',
                        'type' => 'checkbox',
                        'class' => 'form-check-input',
                        'div' => 'form-check'
                    )
                );
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'active',
                    array(
                        'label' => 'Cupom ativo',
                        'type' => 'checkbox',
                        'class' => 'form-check-input',
                        'div' => 'form-check',
                        'default' => 1
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
echo $this->Html->scriptBlock(
    '$( document ).ready(function() {
        changeType(); 
        $("#CouponType").change(function(){
            changeType();                   
        })
    });
    function changeType(){
        var _type = $("#CouponType").val();
        console.log(_type);
        if(_type == "percent"){                
            $(".div-money-value").hide();
            $(".div-percent-value").show();
        }else{
            $(".div-percent-value").hide();
            $(".div-money-value").show();
        }   
    }
    ',
    array('block' => 'scriptBottom')
);
