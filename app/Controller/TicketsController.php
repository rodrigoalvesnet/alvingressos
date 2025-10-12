<?php
class TicketsController extends AppController
{

    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Passaportes');
    }

    public function admin_index()
    {

        //se foi solicitadoa limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            //veriica se o cache existe
            if ($this->Session->check('Filtros.Tickets')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.Tickets');
            }
            //veriica se o cache existe
            if ($this->Session->check('Filtros.ThisData')) {
                //remove os filtros do cache
                $this->Session->delete('Filtros.ThisData');
            }
            //atualiza a pagina
            $this->redirect(array(
                'admin' => true
            ));
        }

        //condição padrão
        $arrayConditions = array();

        //se o this->data não está vazio, prepara o filtro
        if (!empty($this->request->data)) {
            if (isset($this->request->data['Filtro']['nome']) && !empty($this->request->data['Filtro']['nome'])) {
                $arrayConditions['Ticket.nome LIKE '] = '%' . $this->request->data['Filtro']['nome'] . '%';
            }
            if (isset($this->request->data['Filtro']['ticket_id']) && !empty($this->request->data['Filtro']['ticket_id'])) {
                $arrayConditions['Ticket.id'] = $this->request->data['Filtro']['ticket_id'];
            }
            if (isset($this->request->data['Filtro']['order_id']) && !empty($this->request->data['Filtro']['order_id'])) {
                $arrayConditions['Ticket.order_id'] = $this->request->data['Filtro']['order_id'];
            }
            if (isset($this->request->data['Filtro']['modalidade']) && !empty($this->request->data['Filtro']['modalidade'])) {
                $arrayConditions['Ticket.modalidade_nome LIKE '] = '%' . $this->request->data['Filtro']['modalidade'] . '%';
            }
            if (isset($this->request->data['Filtro']['date']) && !empty($this->request->data['Filtro']['date'])) {
                $arrayConditions['DATE(Ticket.modalidade_data)'] = $this->Alv->tratarData($this->request->data['Filtro']['date']);
            }
            //salva as condições na session            
            $this->Session->write('Filtros.Tickets', $arrayConditions);
            $this->Session->write('Filtros.ThisData', $this->request->data);
        } else {
            //verifica se tem condições na session
            if ($this->Session->check('Filtros.Tickets')) {
                //utiliza os filtros do cache
                $arrayConditions = $this->Session->read('Filtros.Tickets');
                $this->request->data = $this->Session->read('Filtros.ThisData');
            }
        }

        //Prepara a busca
        $this->paginate = array(
            'conditions'    => $arrayConditions,
            'limit'         => Configure::read('Sistema.limit'),
            'order'         => 'Ticket.created DESC',
            'recursive' => -1
        );
        $this->set('registros', $this->paginate('Ticket'));

    }

    public function view()
    {
        $this->theme = Configure::read('Site.tema');
        $this->layout = 'site';
    }

    public function print($id)
    {
        // $this->theme = Configure::read('Site.tema');
        $this->layout = 'pdf';
        $ticket = $this->Ticket->find(
            'first',
            array(
                'conditions' => array(
                    'Ticket.id' => $id,
                    'Ticket.status' => 'approved'
                ),
                'contain' => array(
                    'Ticket',
                    'Event' => array(
                        'Unidade' => array(
                            'name',
                            'cnpj',
                            'street',
                            'number',
                            'state',
                            'city',
                            'zipcode',
                            'email',
                            'phone',
                            'district'
                        ),
                        'fields' => array(
                            'title'
                        )
                    )
                )
            )
        );
        // pr($ticket);exit();
        $this->set('ticket', $ticket);
        $fileName = Inflector::slug($ticket['Ticket']['id'] . '-' . $ticket['Ticket']['nome'], '-');
        $this->set('fileName', $fileName);
    }

    function generate_qrcode_checkin($orderid)
    {
        $this->layout = 'image';
        App::import('Vendor', 'QrcodeGen', array('file' => 'QrcodeGen/QrcodeGen.php'));
        $qrcode = new QrcodeGen();
        $urlQrCode = Configure::read('Checkin.url') . $this->params['pass'][0];
        $image = $qrcode->link($urlQrCode);
        $this->set('imgData', $image);
        $this->set('fileName', $orderid);
    }

    function generate_qrcode_pix()
    {
        $this->layout = 'ajax';
    }
}
