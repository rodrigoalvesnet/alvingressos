<div class="card">
    <?php
    echo $this->Form->create(
        'Galeria',
        array(
            'type' => 'file'
        )
    );
    echo $this->Form->hidden('id');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-10">
                <?php
                echo $this->Form->input(
                    'title',
                    array(
                        'label' => 'Titulo',
                        'class' => 'form-control',
                        'div' => 'form-group',
                        'required' => true,
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'active',
                    array(
                        'label' => 'Ativo',
                        'options' => array(
                            '1' => 'Sim',
                            '0' => 'Não'
                        ),
                        'class' => 'form-control',
                        'required' => true,
                        'div' => 'form-group'
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
                        'rows' => 2,
                        'label' => 'Descrição',
                        'class' => 'form-control',
                        'div' => 'form-group',
                    )
                );
                ?>
            </div>
        </div>

        <?php
        if (isset($this->request->data['GaleriasFoto']) && !empty($this->request->data['GaleriasFoto'])) { ?>
            <hr class="my-3" />
            <h4>Fotos Existentes</h4>
            <?php
            foreach ($this->request->data['GaleriasFoto'] as $k => $foto) { ?>
                <div class="photo">
                    <div class="row align-items-center border-bottom py-2 <?php echo $k % 2 ? '' : 'bg-grey'; ?>">
                        <div class="col-md-1">
                            <img src="<?php echo $this->webroot . 'uploads/' . $foto['image']; ?>" width="150" class="img-thumbnail" />
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Título</label>
                                <input type="text" class="form-control" name="data[GaleriasFoto][<?php echo $foto['id']; ?>][title]" value="<?php echo h($foto['title']); ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Descrição</label>
                                <input type="text" class="form-control" name="data[GaleriasFoto][<?php echo $foto['id']; ?>][description]" value="<?php echo h($foto['description']); ?>" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-danger" href="<?php echo $this->Html->url(array('action' => 'deleteFoto', $foto['id'])); ?>" onclick="return confirm('Deseja realmente remover esta foto?');">Remover</a>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>

        <hr class="my-3" />
        <h4>Adicionar novas fotos</h4>
        <div id="new-photos">
            <div class="photo">
                <div class="row align-items-center border-bottom py-2">
                    <div class="col-lg-2">
                        <label>Imagem</label>
                        <input type="file" class="form-control" name="data[GaleriasFoto][new][0][image]" />
                    </div>
                    <div class="col-lg-4">
                        <label>Título</label>
                        <input type="text" class="form-control" name="data[GaleriasFoto][new][0][title]" />
                    </div>
                    <div class="col-lg-6">
                        <label>Descrição</label>
                        <input type="text" class="form-control" name="data[GaleriasFoto][new][0][description]" />
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-secondary mt-2" id="add-new-photo">Adicionar outra foto</button>

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

<script>
    let newIndex = 1;
    document.getElementById('add-new-photo').addEventListener('click', function() {
        let container = document.getElementById('new-photos');
        let newDiv = document.createElement('div');
        newDiv.classList.add('photo');
        newDiv.innerHTML = `
        <div class="row align-items-center border-bottom py-2">
            <div class="col-lg-2">
                <label>Imagem</label>
                <input type="file" class="form-control" name="data[GaleriasFoto][new][${newIndex}][image]">
            </div>
            <div class="col-lg-4">
                <label>Título</label>
                <input type="text" class="form-control" name="data[GaleriasFoto][new][${newIndex}][title]">
            </div>
            <div class="col-lg-6">
                <label>Descrição</label>
                <input type="text" class="form-control" name="data[GaleriasFoto][new][${newIndex}][description]">
            </div>
        </div>

    `;
        container.appendChild(newDiv);
        newIndex++;
    });
</script>