<style>
    * {
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
        padding: 0;
    }

    table {
        width: 100%;
        margin-top: 56pt;
    }

    td {
        text-align: center;
        height: 96pt;
        width: 50%;
    }

    .name {
        font-size: 18px;
        font-weight: bold;
    }

    .church {
        font-size: 14px;
    }

    .page-break {
        page-break-before: always;
    }
</style>
<?php
if (!empty($pessoas)) {
    
    $e = 1;
    $counttotal = 0;
    $total = count($pessoas);
    foreach ($pessoas as $id => $pessoa) {
        $counttotal++;
        //Nova página
        if ($e == 1) {
            echo '<table border="0">';
        }
        if (($e % 2) !== 0) {
            echo '<tr>';
        }
        echo '<td>
                <div class="name">' . $pessoa['name'] . '</div>
                <div class="church">' . $pessoa['church'] . '</div>
            </td>';
        if (($e % 2) == 0 || $e == 14) {
            echo '</tr>';
            if ($total == $counttotal) {
                echo '<td></td>';
            }
        } else {
            if ($total == $counttotal) {
                echo '<td></td>';
            }
        }

        //Nova página
        if ($e == 14) {
            echo '</table>';
            echo '<div class="page-break"></div>';
            $e = 1;
        } else {
            $e++;
        }
        
    }
}
?>