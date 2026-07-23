<?php
class CortesiasController extends AppController
{

    var $uses = array('Ticket', 'Unidade');
    var $helpers = array('Js', 'Alv');
    var $components = array('RequestHandler', 'Alv');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('title_for_layout', 'Cortesias');
    }

    public function admin_index()
    {
        //se foi solicitada a limpeza dos filtros
        if (isset($this->params['named']['limpar'])) {
            if ($this->Session->check('Filtros.Cortesias')) {
                $this->Session->delete('Filtros.Cortesias');
            }
            $this->redirect(array(
                'admin' => true
            ));
        }

        $arrayConditions = array(
            'Ticket.origem' => 'cortesia'
        );

        if (!empty($this->request->data)) {
            if (isset($this->request->data['Filtro']['nome']) && !empty($this->request->data['Filtro']['nome'])) {
                $arrayConditions['Ticket.nome LIKE'] = '%' . $this->request->data['Filtro']['nome'] . '%';
            }
            if (isset($this->request->data['Filtro']['unidade_id']) && !empty($this->request->data['Filtro']['unidade_id'])) {
                $arrayConditions['Ticket.unidade_id'] = $this->request->data['Filtro']['unidade_id'];
            }
            $this->Session->write('Filtros.Cortesias', $arrayConditions);
        } else {
            if ($this->Session->check('Filtros.Cortesias')) {
                $arrayConditions = $this->Session->read('Filtros.Cortesias');
            }
        }

        $this->paginate = array(
            'conditions' => $arrayConditions,
            'limit'      => Configure::read('Sistema.limit'),
            'order'      => 'Ticket.created DESC',
            'contain'    => array(
                'Unidade'   => array('id', 'name'),
                'CriadoPor' => array('id', 'name'),
                'Checkin'   => array('fields' => array('id', 'created'))
            )
        );
        $this->set('registros', $this->paginate('Ticket'));

        $unidades = $this->Unidade->find('list', array(
            'recursive' => -1,
            'fields'    => array('id', 'name'),
            'order'     => array('name' => 'ASC')
        ));
        $this->set('unidades', $unidades);
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $unidadeId = isset($this->request->data['Ticket']['unidade_id']) ? (int)$this->request->data['Ticket']['unidade_id'] : 0;
            $dias      = isset($this->request->data['Ticket']['dias_validade']) ? (int)$this->request->data['Ticket']['dias_validade'] : 0;
            $nome      = isset($this->request->data['Ticket']['nome']) ? trim($this->request->data['Ticket']['nome']) : '';
            $motivo    = isset($this->request->data['Ticket']['motivo']) ? trim($this->request->data['Ticket']['motivo']) : '';

            if (empty($unidadeId)) {
                $this->Flash->error('Selecione a unidade onde a cortesia será válida.');
            } elseif ($dias <= 0) {
                $this->Flash->error('Informe a quantidade de dias de validade.');
            } else {
                $arraySave = array(
                    'id'              => null,
                    'origem'          => 'cortesia',
                    'unidade_id'      => $unidadeId,
                    'criado_por'      => AuthComponent::user('id'),
                    'nome'            => !empty($nome) ? $nome : null,
                    'motivo'          => !empty($motivo) ? $motivo : null,
                    'modalidade_nome' => 'Cortesia',
                    'valido_ate'      => date('Y-m-d', strtotime('+' . $dias . ' days'))
                );
                $this->Ticket->create();
                if ($this->Ticket->save($arraySave)) {
                    $this->Flash->success('Cortesia criada com sucesso!');
                    $this->redirect(array(
                        'action' => 'print',
                        $this->Ticket->getLastInsertId()
                    ));
                } else {
                    $this->Flash->error('Não foi possível criar a cortesia.');
                }
            }
        }

        $unidades = $this->Unidade->find('list', array(
            'recursive' => -1,
            'fields'    => array('id', 'name'),
            'order'     => array('name' => 'ASC')
        ));
        $this->set('unidades', $unidades);
        $this->set('bcLinks', array(
            'Cortesias' => '/admin/cortesias'
        ));
        $this->set('title_for_layout', 'Nova Cortesia');
    }

    public function admin_print($id)
    {
        $this->layout = 'pdf';
        $ticket = $this->Ticket->find('first', array(
            'conditions' => array(
                'Ticket.id'     => $id,
                'Ticket.origem' => 'cortesia'
            ),
            'contain' => array(
                'Unidade'
            )
        ));
        if (empty($ticket)) {
            throw new NotFoundException('Cortesia não encontrada');
        }
        $this->set('ticket', $ticket);
        $this->set('fileName', 'cortesia-' . $ticket['Ticket']['id']);
        $this->set('download', 0);
    }

    public function admin_delete($id)
    {
        $ticket = $this->Ticket->find('first', array(
            'conditions' => array(
                'Ticket.id'     => $id,
                'Ticket.origem' => 'cortesia'
            ),
            'contain' => array('Checkin' => array('fields' => array('id'))),
            'recursive' => -1
        ));

        if (empty($ticket)) {
            $this->Flash->error('Cortesia não encontrada.');
        } elseif (!empty($ticket['Checkin']['id'])) {
            $this->Flash->error('Não é possível excluir uma cortesia que já foi utilizada.');
        } elseif ($this->Ticket->delete($id)) {
            $this->Flash->success('Cortesia excluída com sucesso.');
        } else {
            $this->Flash->error('Não foi possível excluir a cortesia.');
        }
        $this->redirect(array('action' => 'index'));
    }
}
