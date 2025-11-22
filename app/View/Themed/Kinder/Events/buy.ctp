<section id="buy_ticket" class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Comprar Ingresso!</h2>
            <p class="section-subtitle">Preencha os dados abaixo selecionando as melhores opções para vocês!</p>
        </div>

        <div class="ticket-buy">

            <div class="row">
                <div class="col-md-4">
                    <div class="div-thumb-event position-relative">
                        <?php
                        $tagImg = '/img/faixa-comprar.png';
                        if ($event['Event']['status'] == 'canceled') $tagImg = '/img/faixa-cancelado.png';
                        if ($event['Event']['status'] == 'closed') $tagImg = '/img/faixa-concluido.png';
                        if ($event['Event']['status'] == 'soldoff') $tagImg = '/img/faixa-esgotado.png';
                        $tag = '<img src="' . $tagImg . '" class="tag-thumb-event" />';
                        echo $this->Html->image(
                            '/uploads/event-' . $event['Event']['id'] . '/medium/' . $event['Event']['banner_mobile'],
                            array('class' => 'img-thumbnail img-fluid', 'alt' => $event['Event']['title'], 'title' => $event['Event']['title'])
                        ) . $tag;
                        ?>
                    </div>

                </div>

                <div class="col-md-8">
                    <h2><?php echo $event['Event']['title']; ?></h2>
                    <h5 class="mt-2">Informações Importantes</h5>
                    <?php echo $event['Event']['description'] ?>
                </div>
            </div>

            <?php if ($event['Event']['status'] == 'scheduled' || $event['Event']['status'] == 'oculto'): ?>
                <hr class="my-3" />
                <?php
                $eventId = $event['Event']['id'];
                echo $this->Form->create(
                    'Cart',
                    [
                        'class' => 'form-loading'
                    ]
                );
                ?>
                <input type="hidden" value="cart[<?php echo $eventId; ?>][event_id]">
                <h5>Selecione a data do ingresso</h5>
                <input type="hidden" id="dataIngresso" readonly>
                <button type="button" id="abrirCalendario" class="btn btn-secondary btn-loading"><i class="bi bi-calendar"></i> Abrir calendário</button>

                <div id="listaIngressos">
                    <?php
                    $actualCart = $cart;
                    $cart = isset($actualCart['cart'][$eventId]) ? $actualCart['cart'][$eventId] : [];
                    //Se tem alguam coisa na sessão
                    if (!empty($cart)) {
                        foreach ($cart['ingressos'] as $date => $item) {
                    ?>
                            <div class="grupo-data pricing-table" data-data="<?php echo $date; ?>">
                                <div class="row">
                                    <div class="col-md-12 pt-2"><strong>Data: <?php echo $date; ?></strong></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 pt-2">
                                        <input type="number" class="qtd form-control" placeholder="Qtde" value="<?php echo count($item); ?>" min="1">
                                    </div>
                                    <div class="linhas col-md-10">
                                        <?php
                                        //Percorre as pessoas
                                        foreach ($item as $i => $pessoa) { ?>

                                            <div class="linha border-bottom pt-2">
                                                <div class="row">
                                                    <div class="col-md-5 ">
                                                        <input
                                                            type="text"
                                                            value="<?php echo $pessoa['nome']; ?>"
                                                            name="cart[<?php echo $eventId; ?>][ingressos][<?php echo $date; ?>][<?php echo $i; ?>][nome]"
                                                            placeholder="Nome da pessoa"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-4 div-select-modalidade" data-modalidade-id="<?php echo $pessoa['modalidade']; ?>" data-date="<?php echo $date; ?>">
                                                        <!-- aqui vai ser preenchido por jquery, pela função atualizaModalidades() -->
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group mb-3">
                                                            <input type="text" disabled="" value="0,00" class="total-pessoa form-control">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn-remover-nome btn btn-danger">X <span class="d-inline d-sm-none">Remover</span></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>

                <h5 class="mt-3">Resumo dos ingressos:</h5>
                <table id="resumoIngressos" class="table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Nomes</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3">Nenhum ingresso adicionado.</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>Total geral</strong></td>
                            <td id="totalGeral">R$ 0,00</td>
                        </tr>
                    </tfoot>
                </table>

                <br>
                <?php
                echo $this->Form->input(
                    'Ir para o pagamento',
                    array(
                        'type'    => 'submit',
                        'class' => 'btn btn-primary btn-lg btn-block btn-loading',
                        'id' => 'btnSubmit',
                        'div'    => array('class' => 'form-group'),
                        'label' => false
                    )
                );

                echo $this->Form->end(); ?>

                <script>
                    $(document).ready(function() {
                        atualizaModalidades();
                        //Atualiza os totais
                        atualizarResumo(modalidades);
                    });

                    var modalidades = <?php echo json_encode(isset($modalidades) ? $modalidades : []); ?>;
                    var regras = <?php echo json_encode(isset($regras) ? $regras : []); ?>;

                    function gerarSelectModalidade(data, k) {
                        let _opcoesDisponiveis = filtrarOpcoesPorData(modalidades, regras, data);
                        let select = `<select name="cart[<?php echo $eventId; ?>][ingressos][${data}][${k}][modalidade]" class="form-control modalidade" required>`;
                        select += `<option value=""></option>`;
                        for (let key in _opcoesDisponiveis) {
                            select += `<option value="${key}">${_opcoesDisponiveis[key].name}</option>`;
                        }
                        select += `</select>`;
                        return select;
                    }

                    function atualizaModalidades() {
                        $('.div-select-modalidade').each(function(i, item) {
                            let _date = $(this).data('date');
                            let _selectModalidadesHtml = gerarSelectModalidade(_date, i);
                            //Gera o html do select
                            $(this).html(_selectModalidadesHtml);
                            let _modalidadeId = $(this).data('modalidade-id');
                            //seta a modalidade selecionada
                            $(this).find('select').val(_modalidadeId);
                        })
                    }

                    $(function() {

                        // Seleção de data
                        $("#dataIngresso").on("change", function() {

                            let data = $(this).val();
                            let _eventId = $('#EventId').val();

                            if ($('[data-data="' + data + '"]').length > 0) {
                                alert("Essa data já foi adicionada!");
                                $(this).val("");
                                return;
                            }

                            let bloco = `
                                <div class="grupo-data pricing-table" data-data="${data}">
                                    <div class="row">
                                        <div class="col-md-12 pt-2"><strong>Data: ${data}</strong></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 pt-2">
                                            <input type="number" class="qtd form-control" placeholder="Qtde" value="1" min="1">
                                        </div>
                                        <div class="linhas col-md-10">
                                            <div class="linha pt-2">
                                                <div class="row">
                                                    <div class="col-md-5 ">
                                                        <input type="text" name="cart[<?php echo $eventId; ?>][ingressos][${data}][][nome]" placeholder="Nome da pessoa" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        ${gerarSelectModalidade(data, 0)}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group mb-3">
                                                            <input type="text" disabled value="0,00" class="total-pessoa form-control">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn-remover-nome btn btn-danger">X <span class="d-inline d-sm-none">Remover</span></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $("#listaIngressos").append(bloco);
                            $(this).val("");
                            atualizarResumo(modalidades);
                        });

                        // Alterar quantidade
                        $(document).on("input", ".qtd", function() {
                            let qtd = parseInt($(this).val());
                            console.log(qtd);
                            if (qtd < 1) qtd = 1;
                            let bloco = $(this).closest(".grupo-data");
                            let linhas = bloco.find(".linha");
                            let atual = linhas.length;

                            if (qtd > atual) {
                                for (let i = atual; i < qtd; i++) {
                                    bloco.find(".linhas").append(`
                                        <div class="linha border-top pt-2">
                                            <div class="row">
                                                <div class="col-md-5 ">
                                                    <input type="text" name="cart[<?php echo $eventId; ?>][ingressos][${bloco.data("data")}][${i}][nome]" placeholder="Nome da pessoa" class="form-control" required>
                                                </div>
                                                <div class="col-md-4 ">
                                                    ${gerarSelectModalidade(bloco.data("data"), i)}
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group mb-3">
                                                        <input type="text" disabled value="0,00" class="total-pessoa form-control">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn-remover-nome btn btn-danger">X <span class="d-inline d-sm-none">Remover</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `);
                                }
                            } else if (qtd < atual) {
                                linhas.slice(qtd).remove();
                            }

                            atualizarResumo(modalidades);
                        });

                        $(document).on('change', 'select.modalidade', function() {
                            atualizarResumo(modalidades)
                        });
                        // Atualiza totais em tempo real ao mudar modalidade ou nome
                        $(document).on("input", ".linha input[type=text]", function() {
                            atualizarResumo(modalidades)
                        });

                        // Remover linha individual
                        $(document).on("click", ".btn-remover-nome", function() {
                            let bloco = $(this).closest(".grupo-data");
                            $(this).closest(".linha").remove();
                            if (bloco.find(".linha").length === 0) {
                                bloco.remove();
                            }
                            let _linhas = $('.linha').length;
                            $('.qtd').val(_linhas);
                            atualizarResumo(modalidades);
                        });

                    });
                </script>

            <?php else: ?>
                <hr class="my-3" />
                <h4><i class="fas fa-info"></i> Informações do Evento</h4>
                <div class="row">
                    <div class="col-lg-12"><?php echo $event['Event']['description']; ?></div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<script>
$(document).ready(function () {
    var blockedDates = <?php echo !empty($blockedDates) ? $blockedDates : '[]'; ?>;

    $("#dataIngresso").datepicker({
        dateFormat: 'dd/mm/yy',
        //minDate: '<?php echo $this->Alv->tratarData($event['Event']['start_date'], 'pt');?>',
        maxDate: '<?php echo $this->Alv->tratarData($event['Event']['end_date'], 'pt');?>',
        minDate: 0,
        beforeShowDay: function (date) {
            var day = date.getDay();
            var dataFormatada = $.datepicker.formatDate("dd/mm/yy", date);
            var hoje = $.datepicker.formatDate("dd/mm/yy", new Date());

            // Bloqueia segunda-feira
            if (day === 1) {
                // return [false, "", "Segunda-feira bloqueada"];
            }

            // Bloqueia o dia atual
            if (dataFormatada === hoje) {
                return [false, "", "Hoje não disponível"];
            }

            // Bloqueia datas específicas vindas do CakePHP
            if ($.inArray(dataFormatada, blockedDates) !== -1) {
                return [false, "", "Data indisponível"];
            }

            return [true, ""];
        },
        dayNames: ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado"],
        dayNamesMin: ["D", "S", "T", "Q", "Q", "S", "S"],
        monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        nextText: "Próximo",
        prevText: "Anterior"
    });

    $("#abrirCalendario").on("click", function () {
        $("#dataIngresso").datepicker("show");
    });
});
</script>


<?php
echo $this->Html->script('buy', array('block' => 'scriptBottom'));
