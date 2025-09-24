<style>
    #containerScanner {
        background: url('/img/qrcode-scanner.jpg');
        background-repeat: no-repeat;
        background-size: 250px;
        background-position: center;
        max-width: 500px;
        min-height: 250px;
        margin: 0 auto;
    }

    #divReaderCam {
        /* max-width: 300px; */
        /* margin: 0 auto; */
    }

    #imgScanner {
        width: 50%;
    }

    .div-cameras {
        margin: 10px 0;
    }
</style>

<?php
//Verifica se tem HTTP ou está no Localhost
$localhost = strpos(FULL_BASE_URL, 'localhost');
if (!$isSecure && !$localhost) {
    echo '<div class="alert alert-danger" role="alert">A câmera não pode ser usada sem o protocolo HTTPS! <a href="https://kinderpark.com.br' . $this->here . '">Clique Aqui</a></div>';
}
?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div id="containerScanner" class="border">
                    <div id="divReaderCam"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center div-cameras">
                <select id="selectCam" style="display: none;">
                    <option value="">Nenhuma camera</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center div-button">
                <button onclick="getCameras()" id="btnCameras" class="btn btn-default">Preparar Câmeras</button>
                <button onclick="startScanning()" id="btnStart" style="display: none;" class="btn btn-success text-white">Iniciar Leitura</button>
                <button id="btnStop" style="display: none;" class="btn btn-danger">Parar Leitura</button>
            </div>
        </div>
    </div>
</div>


<hr />
<div id="divList">
    <div class="loading-message">
        <div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>Aguarde... Carregando dados...
    </div>
</div>

<!-- Checkin Modal -->
<div class="modal fade" id="checkinModal" tabindex="-1" role="dialog" aria-labelledby="checkinModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkinModalLabel"><i class="fas fa-check"></i> Checkin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="formCheckin" class="p-5">

            </div>
        </div>
    </div>
</div>

<?php
$eventId = $this->params['pass'][0];

echo $this->Html->script('html5-qrcode.min', array('inline' => false));
echo $this->Html->script('checkin', array('block' => 'scriptBottom'));
echo $this->Html->scriptBlock(
    "getCheckins($eventId);",
    array('block' => 'scriptBottom')
);
