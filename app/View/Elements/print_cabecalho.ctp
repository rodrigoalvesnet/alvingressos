<style>
*{
        font-family: 'Courier New', Courier, monospace;
        font-size: 9px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }

    .negrito {
        font-weight: bold;
    }

    .cabecalho {
        margin-bottom: 30px;
    }

    
    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .border-top{
        border-top: .5px solid #666;
    }
    .w-100 {
        width: 100% !important;
    }
</style>

<table border="0" class="cabecalho">
    <tr>
        <td class="text-center">
            <div class="negrito">
                <?php echo $unidadeDados['Unidade']['name']; ?>
            </div>
            <div>
                CNPJ: <?php echo $unidadeDados['Unidade']['cnpj']; ?>
            </div>
            <div>
                Endereço: <?php echo $unidadeDados['Unidade']['street']; ?>
                <?php echo !empty($unidadeDados['Unidade']['number']) ? $unidadeDados['Unidade']['number'] : 'S/N'; ?>
            </div>
            <div>
                <?php echo $unidadeDados['Unidade']['district']; ?> - <?php echo $unidadeDados['Unidade']['city']; ?> - <?php echo $unidadeDados['Unidade']['state']; ?> - CEP: <?php echo $unidadeDados['Unidade']['zipcode']; ?>
            </div>
            <div>
                Telefone: <?php echo !empty($unidadeDados['Unidade']['phone']) ? $unidadeDados['Unidade']['phone'] : 'Não informado'; ?> - E-mail: <?php echo !empty($unidadeDados['Unidade']['email']) ? $unidadeDados['Unidade']['email'] : 'Não informado'; ?>
            </div>
        </td>
    </tr>
</table>