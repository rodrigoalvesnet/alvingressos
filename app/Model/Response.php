<?php
class Response extends AppModel
{
    public $name = 'Response';

    public $belongsTo = array(
        'Field'
    );

    public function getTotalByResponse($fieldId)
    {
        $arrayResult = array();
        // $sql = 'SELECT COUNT(*) AS total, response FROM responses AS r  field_id = ' . $fieldId . ' GROUP BY response;';

        $sql = 'SELECT COUNT(r.id) AS total, r.response FROM responses AS r 
        LEFT JOIN orders AS o ON(o.id = r.order_id)
        LEFT JOIN `events` AS e ON(e.id = o.event_id)
        WHERE  r.field_id = 8 AND o.status = "approved" 
        GROUP BY r.response;';

        $query = $this->query($sql);
        if (!empty($query)) {
            foreach ($query as $responses) {
                $response = trim($responses['r']['response']);
                if (isset($arrayResult[$response])) {
                    $arrayResult[$response] += $responses[0]['total'];
                } else {
                    $arrayResult[$response] = $responses[0]['total'];
                }
            }
        }
        return $arrayResult;
    }

    public function checkAvailableFields($reponsesTotal, $listOptions)
    {
        $arrayResult = array(
            'available' => array(),
            'unavailable' => array()
        );
        foreach ($reponsesTotal as $option => $total) {
            if (
                ($option == 'Células' && $total >= 60)
                || $option == 'Comunicação' && $total >= 40
                || $option == 'Dança' && $total >= 45
                || $option == 'Diaconato' && $total >= 50
                || $option == 'Influe/TDxC' && $total >= 70
                || $option == 'Intercessão' && $total >= 60
                || $option == 'Louvor' && $total >= 120
                || $option == 'Pastores' && $total >= 130
                || $option == 'Projeto Alma' && $total >= 50
                || $option == 'Start' && $total >= 50
                || $option == 'Som e luz' && $total >= 30
                || $option == 'Super Kids' && $total >= 70
                || $option == 'Teatro' && $total >= 25
            ) {
                $arrayResult['unavailable'][] = $option;
                $listOptions[trim($option)] = trim($option) . ' (esgotado)';
                unset($listOptions[trim($option)]);
            }
        }
        $arrayResult['available'] = $listOptions;
        return $arrayResult;
    }
}
