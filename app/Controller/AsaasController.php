<?php
class AsaasController extends AppController
{
    var $components = array('Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('webhook');
    }

    public function webhook()
    {
        $this->autoRender = false;
        //Pega os dados que chegou pelo post
        $data = file_get_contents('php://input');
        //Se não está vazio
        if (!empty($data)) {
            //Decodifica, transforma em objeto
            $data = json_decode($data);
            //Se é recebimento
            if ($data->event == 'PAYMENT_RECEIVED') {
                $this->payment_received($data);
            }
            //Se venceu
            if ($data->event == 'PAYMENT_OVERDUE') {
                $this->payment_overdue($data);
            }
        }
    }

    private function payment_received($data)
    {
        $this->autoRender = false;
        $this->loadModel('Order');
        //Procura
        $order = $this->Order->find(
            'first',
            array(
                'conditions' => array(
                    'Order.invoice_id' => $data->payment->id
                ),
                'fields' => array(
                    'id',
                    'invoice_id'
                )
            )
        );
        //Se encontrou a fatura
        if (!empty($order)) {
            //Atualiza o status
            $this->Order->updateAll(
                array(
                    'Order.status' => "'approved'",
                    'Order.invoice_receipt' => "'" . $data->payment->transactionReceiptUrl . "'",
                    'Order.modified' => "'" . date('Y-m-d H:i:d') . "'"
                ),
                array(
                    'Order.invoice_id' => $data->payment->id
                )
            );

            //Tenta enviar o voucher, mas sem quebrar o webhook
            try {
                $this->Order->sendVoucher($order['Order']['id']);
            } catch (Exception $e) {
                //Registra log para investigar depois
                CakeLog::write('error', 'Falha ao enviar voucher do pedido ' . $order['Order']['id'] . ': ' . $e->getMessage());
            }
        }
        //Retorna 200 para o provedor saber que processamos
        $this->response->statusCode(200);
        return $this->response;
    }

    private function payment_overdue($data)
    {
        $this->autoRender = false;
    }
}
