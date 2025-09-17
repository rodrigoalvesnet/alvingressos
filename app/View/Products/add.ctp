<div class="card">
    <?php
    echo $this->Form->create(
        'Product',
        array(
            'type' => 'file'
        )
    );
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'name',
                    array(
                        'label' => 'Nome',
                        'class' => 'form-control',
                        'div' => 'form-group',
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
                    'description',
                    array(
                        'label' => 'Descrição',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'price',
                    array(
                        'label' => 'Valor de Venda',
                        'type' => 'text',
                        'class' => 'form-control money',
                        'div' => 'form-group',
                        'required' => true
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'manage_inventory',
                    array(
                        'label' => 'Deseja gerenciar o estoque?',
                        'options' => array(
                            0 => 'Não, o estoque é ilimitado',
                            1 => 'Sim, o estoque é limitado'
                        ),
                        'default' => 0,
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'stock',
                    array(
                        'label' => 'Quantidade em Estoque',
                        'type' => 'number',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'min' => 0,
                        'required' => true
                    )
                );
                ?>
            </div>
            <div class="col-lg-3">
                <?php
                echo $this->Form->input(
                    'active',
                    array(
                        'label' => 'Qual é a situação deste produto?',
                        'options' => array(
                            0 => 'Indisponível',
                            1 => 'Disponível'
                        ),
                        'default' => 1,
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true
                    )
                );
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <?php
                echo $this->Form->input(
                    'new_photo',
                    array(
                        'type' => 'file',
                        'label' => 'Fotos do Produto (máximo 3 fotos)',
                        'class' => 'form-control input-file',
                        'div' => 'form-group'
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
                'type' => 'submit',
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

echo $this->Html->css(array(
    'fileuploader/jquery.fileuploader.min',
    'fileuploader/fonts/font-fileuploader',
    'fileuploader/custom'
));
echo $this->Html->script('fileuploader/jquery.fileuploader.min', array('block' => 'scriptBottom'));
//Se tem arquivos

$uploadedFiles = array();
if (isset($this->data['ProductsImage']) && !empty($this->data['ProductsImage'])) {
    foreach ($this->data['ProductsImage'] as $k => $file) {
        $uploadedFiles[] = array(
            'name' => $file['name'],
            'size' => $file['size'],
            'type' => $file['type'],
            'file' => '/uploads/medium/' . $file['filename'],            
            'data' => array(
                'thumbnail' => '/uploads/small/' . $file['filename'],
                'image_id' => $file['id']
            ),
        );
    }
}
$uploadedFiles = json_encode($uploadedFiles);
echo $this->Html->scriptBlock(
    "$('.input-file').fileuploader({
        limit: 3,
        //maxSize: 5,
        addMore: true,
        onRemove: function(item) {
            $.ajax({
                type: 'GET',
                url: '/products/remove_image/' + item.data.image_id,
                success: function (result) {
                  return true;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    return false;
                },
              });
        },
        files: $uploadedFiles
    });",
    array('block' => 'scriptBottom')
);
