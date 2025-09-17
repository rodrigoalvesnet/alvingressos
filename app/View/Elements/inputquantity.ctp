<center>
    <div class="input-group" style="min-width: 100px; max-width: 150px;">
        <span class="input-group-prepend">
            <button type="button" class="btn btn-outline-secondary btn-number" disabled="disabled" data-type="minus" data-field="<?php echo $id; ?>">
                <span class="fa fa-minus"></span>
            </button>
        </span>
        <?php 
        echo $this->Form->text(
            $name,
            array(
                'readonly' => true,
                'type' => 'text',
                'class' => 'form-control input-number input-quantity',
                'value' => 0,
                'min' => 0,
                'max' => 10,
                'style' => 'background-color: white; text-align: center;',
                'div' => false,
                'id' => $id
            )
        );
        ?>
        <span class="input-group-append">
            <button type="button" class="btn btn-outline-secondary btn-number" data-type="plus" data-field="<?php echo $id; ?>">
                <span class="fa fa-plus"></span>
            </button>
        </span>
    </div>
</center>
<?php
echo $this->Html->script('inputquantity', array('block' => 'scriptBottom'));
