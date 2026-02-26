<style>
    .img-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        margin-right: 10px;
    }
</style>
<div class="card">
    <?php
    echo $this->Form->create('Filtro');
    ?>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'crianca_nome',
                    array(
                        'label' => 'Nome da Criança',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'responsavel_nome',
                    array(
                        'label' => 'Nome do Responsável',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>

            <div class="col-lg-2">
                <label>Data inicial </label>
                <?php
                echo $this->Form->text(
                    'start_date',
                    array(
                        'type' => 'date',
                        'label' => 'Data',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <label>Data final </label>
                <?php
                echo $this->Form->text(
                    'end_date',
                    array(
                        'type' => 'date',
                        'label' => 'Data',
                        'class' => 'form-control',
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'status',
                    array(
                        'label' => 'Status',
                        'options' => $status,
                        'class' => 'form-control',
                        'empty' => 'Qualquer'
                    )
                );
                ?>
            </div>
            <div class="col-lg-2">
                <?php
                echo $this->Form->input(
                    'atracao_id',
                    array(
                        'label' => 'Atração/Brinquedo',
                        'options' => $atracoes,
                        'class' => 'form-control',
                        'empty' => 'Qualquer'
                    )
                );
                ?>
            </div>
        </div>
    </div>
    <div class="card-footer border-top">
        <?php
        echo $this->Form->submit(
            'Pesquisar',
            array(
                'type'      => 'submit',
                'class'     => 'btn btn-primary mx-1',
                'div'       => false,
                'label'     => false
            )
        );
        echo $this->Html->link(
            'Limpar',
            array(
                'admin' => true,
                'controller' => 'estadias',
                'action' => 'index',
                'limpar' => 1
            ),
            array(
                'class' => 'btn btn-outline-secondary mx-1',
                'escape' => false
            )
        );
        ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>


<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="<?php echo $this->Html->url(['action' => 'iniciar']); ?>"
                    class="btn btn-success mb-3 text-white">
                    <i class="mdi mdi-plus"></i> Nova Estadia
                </a>
            </div>
            <div>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="mdi mdi-filter"></i>
                    </span>
                    <input type="search" class="form-control" id="inputSearch" placeholder="Localizar...">
                </div>

            </div>
        </div>

        <?php if (empty($registros)) : ?>
            <div class="alert alert-info">Nenhum registro encontrado.</div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-striped" id="tableEstadias">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col"><?php echo $this->Paginator->sort('Estadia.sexo', '#'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Estadia.created', 'Data/Hora'); ?></th>
                            <th class="text-center" scope="col"><?php echo $this->Paginator->sort('Estadia.pulseira_numero', 'Pulseira'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Estadia.crianca_nome', 'Criança'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Estadia.responsavel_nome', 'Responsável'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Atracao.nome', 'Atração/Brinquedo'); ?></th>
                            <th class="text-center" scope="col"><?php echo $this->Paginator->sort('Estadia.status', 'Situação'); ?></th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $registro) {
                            $id = $registro['Estadia']['id'];
                            switch ($registro['Estadia']['status']) {
                                case 'aberta':
                                    $classBadge = 'bg-primary';
                                    break;
                                case 'pausada':
                                    $classBadge = 'bg-warning';
                                    break;
                                case 'encerrada':
                                    $classBadge = 'bg-success';
                                    break;
                                case 'cancelada':
                                    $classBadge = 'bg-danger';
                                    break;
                            }
                        ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $registro['Estadia']['id']; ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($registro['Estadia']['created'])); ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-primary">
                                        <?php echo $registro['Estadia']['pulseira_numero']; ?>
                                    </span>
                                </td>
                                <td><img src="<?= $registro['Estadia']['sexo'] === 'feminino' ? '/img/icon-girl.jpg' : '/img/icon-boy.jpg' ?>" class="img-avatar thumbnail" /><?php echo $registro['Estadia']['crianca_nome']; ?></td>
                                <td><?php echo $registro['Estadia']['responsavel_nome']; ?></td>
                                <td><?php echo $registro['Atracao']['nome']; ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill <?php echo $classBadge; ?>">
                                        <?php echo $status[$registro['Estadia']['status']]; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if ($registro['Estadia']['status'] === 'aberta'): ?>
                                        <?php
                                        // echo $this->Form->postLink(
                                        //     '<i class="mdi mdi-pause"></i>',
                                        //     ['action' => 'pausar', $id],
                                        //     [
                                        //         'title' => 'Pausar',
                                        //         'class' => 'btn btn-warning btn-sm',
                                        //         'confirm' => 'Tem certeza que deseja PAUSAR a estadia de ' . $registro['Estadia']['crianca_nome'] . '?',
                                        //         'escape' => false
                                        //     ]
                                        // );
                                        ?>
                                        <button
                                            type="button"
                                            class="btn btn-success btn-sm text-white btn-encerrar"
                                            data-id="<?php echo (int)$id; ?>"
                                            title="Encerrar">
                                            <i class="mdi mdi-check"></i>
                                        </button>
                                    <?php elseif ($registro['Estadia']['status'] === 'pausada'): ?>
                                        <?php
                                        echo $this->Form->postLink(
                                            '<i class="mdi mdi-play"></i>',
                                            ['action' => 'retomar', $id],
                                            [
                                                'title' => 'Retomar',
                                                'class' => 'btn btn-info btn-sm',
                                                'confirm' => 'Tem certeza que deseja RETOMAR a estadia de ' . $registro['Estadia']['crianca_nome'] . '?',
                                                'escape' => false
                                            ]
                                        );
                                        ?>
                                    <?php endif; ?>

                                    <?php if (in_array($registro['Estadia']['status'], ['aberta', 'pausada'])): ?>
                                        <?php
                                        echo $this->Form->postLink(
                                            '<i class="mdi mdi-close"></i>',
                                            ['action' => 'cancelar', $id],
                                            [
                                                'title' => 'Cancelar',
                                                'class' => 'btn btn-danger btn-sm',
                                                'confirm' => 'Tem certeza que deseja CANCELAR a estadia de ' . $registro['Estadia']['crianca_nome'] . '?',
                                                'escape' => false
                                            ]
                                        );
                                        echo $this->Html->link(
                                            '<i class="mdi mdi-pencil"></i>',
                                            array(
                                                'action' => 'editar',
                                                $registro['Estadia']['id']
                                            ),
                                            array(
                                                'class' => 'btn btn-warning btn-sm',
                                                'escape' => false,
                                                'style' => 'margin-left: 4px;'
                                            )
                                        );
                                        ?>
                                    <?php endif; ?>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary btn-visualizar"
                                        data-id="<?php echo (int)$id; ?>"
                                        title="Visualizar">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
            <?php echo $this->element('paginador'); ?>

        <?php endif; ?>
    </div>
</div>

<?php echo $this->element('estadias/resumo'); ?>

<?php $this->start('scriptBottom'); ?>
<script>
    (function() {


        $('#inputSearch').on('keyup', function() {
            var value = $(this).val().toLowerCase();

            $('#tableEstadias tbody tr').filter(function() {
                $(this).toggle(
                    $(this).text().toLowerCase().indexOf(value) > -1
                );
            });
        });


        function moneyBR(v) {
            v = Number(v || 0);
            return 'R$ ' + v.toFixed(2).replace('.', ',');
        }

        var prevTotalTempo = document.getElementById('prev-total-tempo');
        var prevTotalAdicionals = document.getElementById('prev-total-adicionals');

        var adicionalIdEl = document.getElementById('adicional-id');
        var adicionalQtdEl = document.getElementById('adicional-qtd');
        var btnAddAdicional = document.getElementById('btnAddAdicional');
        var tableAdicionalsBody = document.querySelector('#tableAdicionals tbody');


        var modalEl = document.getElementById('modalEncerrar');
        var loadingEl = document.getElementById('encerrar-loading');
        var contentEl = document.getElementById('encerrar-content');
        var errorEl = document.getElementById('encerrar-error');

        var inputId = document.getElementById('encerrar-id');

        var prevPulseira = document.getElementById('prev-pulseira');
        var prevTempo = document.getElementById('prev-tempo');
        var prevPausado = document.getElementById('prev-pausado');
        var prevStatus = document.getElementById('prev-status');
        var prevCrianca = document.getElementById('prev-crianca');
        var prevResponsavel = document.getElementById('prev-responsavel');
        var prevTotal = document.getElementById('prev-total');
        var prevEntrada = document.getElementById('prev-entrada');
        // var prevBase = document.getElementById('prev-base');
        // var prevAdd = document.getElementById('prev-adicional');

        var formEncerrar = document.getElementById('formEncerrar');

        var isBusy = false;
        var currentId = null;

        function setLoading() {
            loadingEl.style.display = 'block';
            contentEl.style.display = 'none';
            errorEl.style.display = 'none';
        }

        function setContent() {
            loadingEl.style.display = 'none';
            contentEl.style.display = 'block';
            errorEl.style.display = 'none';
        }

        function setError(msg) {
            errorEl.innerText = msg || 'Erro ao calcular.';
            errorEl.style.display = 'block';
            loadingEl.style.display = 'none';
            contentEl.style.display = 'none';
        }

        function clearPreview() {
            prevPulseira.value = '';
            prevPausado.value = '';
            prevTempo.value = '';
            prevStatus.value = '';
            prevTotal.innerText = '';
            prevCrianca.value = '';
            prevResponsavel.value = '';
            prevEntrada.value = '';
            prevTotalTempo.value = '';
            prevTotalAdicionals.value = '';
            tableAdicionalsBody.innerHTML = '';
            // if (adicionalIdEl) adicionalIdEl.disabled = true;
            // if (adicionalQtdEl) adicionalQtdEl.disabled = true;
            // if (btnAddAdicional) btnAddAdicional.disabled = true;

            // prevBase.value = '';
            // prevAdd.value = '';
        }

        function ucfirst(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function renderAdicionals(res) {
            tableAdicionalsBody.innerHTML = '';
            (res.itens || []).forEach(function(it) {
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td>' + (it.descricao || '') + '</td>' +
                    '<td class="text-center">' + (it.qtd || 0) + '</td>' +
                    '<td class="text-end">' + moneyBR(it.valor_unit) + '</td>' +
                    '<td class="text-end">' + moneyBR(it.valor_total) + '</td>' +
                    '<td class="text-center">' +
                    '<button class="btn btn-danger btn-sm btn-remover-item" data-item-id="' + it.id + '"><i class="mdi mdi-delete"></i></button>' +
                    '</td>';
                tableAdicionalsBody.appendChild(tr);
            });
        }

        function loadAdicionals(estadiaId) {
            return fetch('/admin/estadia_itens/listar/' + encodeURIComponent(estadiaId) + '.json', {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(function(res) {
                    if (!res || !res.ok) throw new Error(res && res.error ? res.error : 'Erro ao listar adicionals');

                    renderAdicionals(res);

                    // ✅ mantém o “Total Adicionals” alinhado com a lista
                    if (typeof res.subtotal_adicionals !== 'undefined') {
                        prevTotalAdicionals.value = moneyBR(res.subtotal_adicionals || 0);
                    }
                });
        }

        // 1) Um único listener para todos os botões (não duplica mesmo se o HTML for re-renderizado)
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-encerrar, .btn-visualizar, .btn-remover-item');
            if (!btn) return; // ✅ primeiro!

            // Se clicou no remover-item, deixa o outro listener tratar
            if (btn.classList.contains('btn-remover-item')) return;

            var btnEncerrar = btn.classList.contains('btn-encerrar');
            var btnVisualizar = btn.classList.contains('btn-visualizar');

            e.preventDefault();

            if (isBusy) return;

            var id = btn.getAttribute('data-id');
            if (!id) {
                setError('ID não informado no botão.');
                return;
            }

            isBusy = true;
            currentId = id;
            inputId.value = id;

            clearPreview();
            setLoading();
            $('#modalEncerrar').modal('show');

            fetch('/admin/estadias/preview_encerrar/' + encodeURIComponent(id) + '.json', {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(function(r) {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(function(res) {
                    // Se o usuário clicou em outro id antes da resposta, ignora resposta velha
                    if (currentId !== id) return;

                    if (!res || !res.ok) {
                        setError((res && res.error) ? res.error : 'Erro ao calcular.');
                        return;
                    }

                    prevTotalTempo.value = moneyBR(res.valor_tempo || 0);
                    prevTotalAdicionals.value = moneyBR(res.subtotal_adicionals || 0);
                    loadAdicionals(id);

                    prevPulseira.value = res.pulseira || '';
                    prevTempo.value = res.duracao_cobrada_hms || '';
                    prevPausado.value = res.tempo_pausado_hms || '00:00:00';
                    prevStatus.value = ucfirst(res.status) || '';
                    prevTotal.innerText = moneyBR(res.valor_total);
                    prevCrianca.value = res.crianca_nome || '';
                    prevResponsavel.value = res.responsavel_nome || '';
                    prevEntrada.value = res.entrada || '';
                    // prevBase.value = moneyBR(res.valor_base);
                    // prevAdd.value = moneyBR(res.valor_adicional);
                    console.log(res.status);

                    if (btnEncerrar) {
                        if (res.status == 'aberta' || res.status == 'pausada') {
                            $('#modalEncerrarLabel').text('Confirmar Encerramento - #' + res.id);
                            $('#btnEncerrar').show();
                        }
                    } else {
                        $('#modalEncerrarLabel').text('Estadia #' + res.id);
                        $('#btnEncerrar').hide();
                        if (res.status == 'aberta' || res.status == 'pausada') {
                            $('#containerTotal').removeClass('alert-primary')
                            $('#containerTotal').addClass('alert-success')
                            $('#labelTotal').text('Total a Pagar');
                        } else {
                            $('#containerTotal').removeClass('alert-success')
                            $('#containerTotal').addClass('alert-primary')
                            $('#labelTotal').text('Total Pago');
                        }
                    }

                    var podeEditarAdicionals = (res.status === 'aberta' || res.status === 'pausada');

                    // if (adicionalIdEl) adicionalIdEl.disabled = !podeEditarAdicionals;
                    // if (adicionalQtdEl) adicionalQtdEl.disabled = !podeEditarAdicionals;
                    // if (btnAddAdicional) btnAddAdicional.disabled = !podeEditarAdicionals;

                    setContent();
                })
                .catch(function(err) {
                    if (currentId !== id) return;
                    setError('Falha ao consultar preview. (' + (err && err.message ? err.message : 'erro') + ')');
                })
                .finally(function() {
                    if (currentId === id) isBusy = false;
                });
        });

        // 2) Ao fechar o modal, reseta estados
        $('#modalEncerrar').on('hidden.bs.modal', function() {
            isBusy = false;
            currentId = null;
            clearPreview();
            errorEl.style.display = 'none';
            loadingEl.style.display = 'none';
            contentEl.style.display = 'none';
        });

        // 3) (Opcional) impede submit se ainda estiver calculando/sem preview
        if (formEncerrar) {
            formEncerrar.addEventListener('submit', function(e) {
                // se estiver carregando, bloqueia
                if (loadingEl.style.display === 'block') {
                    e.preventDefault();
                    return;
                }
                // se não tem total preenchido, bloqueia
                if (!prevTotal.innerText) {
                    e.preventDefault();
                    return;
                }
            });
        }

        if (btnAddAdicional) {
            btnAddAdicional.addEventListener('click', function() {
                if (!currentId) return;

                fetch('/admin/estadia_itens/adicionar.json', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                            'Accept': 'application/json'
                        },
                        body: 'estadia_id=' + encodeURIComponent(currentId) +
                            '&adicional_id=' + encodeURIComponent(adicionalIdEl.value) +
                            '&qtd=' + encodeURIComponent(adicionalQtdEl.value || 1) +
                            '&valor_unit=' + encodeURIComponent(adicionalValorUnitEl.value || 0)

                    })
                    .then(async function(r) {
                        const text = await r.text();
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Resposta não-JSON. HTTP:', r.status, 'Body:', text.substring(0, 500));
                            throw new Error('Servidor retornou HTML (veja console/network).');
                        }
                    })

                    .then(function(res) {
                        if (!res || !res.ok) throw new Error(res && res.error ? res.error : 'Falha ao adicionar');
                        adicionalQtdEl.value = 1;
                        // recarrega preview (para atualizar total) + lista itens
                        return fetch('/admin/estadias/preview_encerrar/' + encodeURIComponent(currentId) + '.json', {
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json'
                            }
                        }).then(r => r.json()).then(function(p) {
                            if (p && p.ok) {
                                prevTotalTempo.value = moneyBR(p.valor_tempo || 0);
                                prevTotalAdicionals.value = moneyBR(p.subtotal_adicionals || 0);
                                prevTotal.innerText = moneyBR(p.valor_total || 0);
                            }
                            return loadAdicionals(currentId);
                        });
                    })
                    .catch(function(err) {
                        setError('Falha ao adicionar adicional. (' + (err && err.message ? err.message : 'erro') + ')');
                    });
            });
        }

        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-remover-item');
            if (!btn) return;

            if (!currentId) return;
            if (!$('#modalEncerrar').hasClass('show')) return;

            var itemId = btn.getAttribute('data-item-id');
            if (!itemId || !currentId) return;

            fetch('/admin/estadia_itens/remover/' + encodeURIComponent(itemId) + '.json', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(function(res) {
                    if (!res || !res.ok) throw new Error(res && res.error ? res.error : 'Falha ao remover');

                    return fetch('/admin/estadias/preview_encerrar/' + encodeURIComponent(currentId) + '.json', {
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json()).then(function(p) {
                        if (p && p.ok) {
                            prevTotalTempo.value = moneyBR(p.valor_tempo || 0);
                            prevTotalAdicionals.value = moneyBR(p.subtotal_adicionals || 0);
                            prevTotal.innerText = moneyBR(p.valor_total || 0);
                        }
                        return loadAdicionals(currentId);
                    });
                })
                .catch(function(err) {
                    setError('Falha ao remover adicional. (' + (err && err.message ? err.message : 'erro') + ')');
                });
        });

        var adicionalValorUnitEl = document.getElementById('adicional-valor-unit');

        function setValorUnitFromSelected() {
            if (!adicionalIdEl || !adicionalValorUnitEl) return;
            var opt = adicionalIdEl.options[adicionalIdEl.selectedIndex];
            var preco = opt ? Number(opt.getAttribute('data-preco') || 0) : 0;
            adicionalValorUnitEl.value = (preco || 0).toFixed(2);
        }

        if (adicionalIdEl) {
            adicionalIdEl.addEventListener('change', setValorUnitFromSelected);
        }

        // quando abrir o modal também já preenche
        setValorUnitFromSelected();


    })();
</script>
<?php $this->end(); ?>