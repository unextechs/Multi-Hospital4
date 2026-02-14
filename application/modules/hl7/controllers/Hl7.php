<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hl7 extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('hl7_model');
        $this->load->model('settings/settings_model');

        // Restricted to superadmin and admin
        if (!$this->ion_auth->in_group(array('superadmin', 'admin'))) {
            redirect('home/permission');
        }
    }

    public function index()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['messages'] = $this->hl7_model->getHl7Messages();
        $data['connections'] = $this->hl7_model->getConnections();
        $data['stats'] = $this->hl7_model->getStats();
        $this->load->view('home/dashboard');
        $this->load->view('hl7_view', $data);
        $this->load->view('home/footer');
    }

    public function chart()
    {
        $this->index();
    }

    public function messages()
    {
        $this->index();
    }

    public function add()
    {
        $message_type = $this->input->post('message_type');
        $trigger_event = $this->input->post('trigger_event');
        $payload = $this->input->post('payload');
        $connection_id = $this->input->post('connection_id');

        $data = array(
            'hospital_id' => $this->session->userdata('hospital_id'),
            'connection_id' => $connection_id,
            'message_type' => $message_type,
            'trigger_event' => $trigger_event,
            'payload' => $payload,
            'direction' => 'outbound',
            'status' => 'pending'
        );

        $this->hl7_model->insertHl7($data);
        $this->session->set_flashdata('feedback', lang('added'));
        redirect('hl7');
    }

    public function delete()
    {
        $id = $this->input->get('id');
        $this->hl7_model->deleteHl7($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('hl7');
    }

    // ==================== CONNECTIONS ====================

    public function connections()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['connections'] = $this->hl7_model->getConnections();
        $this->load->view('home/dashboard');
        $this->load->view('hl7_connections', $data);
        $this->load->view('home/footer');
    }

    public function addConnectionView()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard');
        $this->load->view('add_hl7_connection', $data);
        $this->load->view('home/footer');
    }

    public function addConnection()
    {
        $data = array(
            'hospital_id' => $this->session->userdata('hospital_id'),
            'name' => $this->input->post('name'),
            'host' => $this->input->post('host'),
            'port' => $this->input->post('port') ? $this->input->post('port') : 2575,
            'connection_type' => $this->input->post('connection_type') ? $this->input->post('connection_type') : 'MLLP',
            'sending_application' => $this->input->post('sending_application'),
            'sending_facility' => $this->input->post('sending_facility'),
            'receiving_application' => $this->input->post('receiving_application'),
            'receiving_facility' => $this->input->post('receiving_facility'),
            'status' => 'active'
        );

        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->hl7_model->updateConnection($id, $data);
            $this->session->set_flashdata('feedback', lang('updated'));
        } else {
            $this->hl7_model->insertConnection($data);
            $this->session->set_flashdata('feedback', lang('added'));
        }

        redirect('hl7/connections');
    }

    public function editConnection()
    {
        $id = $this->input->get('id');
        $data['connection'] = $this->hl7_model->getConnectionById($id);
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard');
        $this->load->view('add_hl7_connection', $data);
        $this->load->view('home/footer');
    }

    public function deleteConnection()
    {
        $id = $this->input->get('id');
        $this->hl7_model->deleteConnection($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('hl7/connections');
    }

    public function testConnection()
    {
        $id = $this->input->get('id');
        $connection = $this->hl7_model->getConnectionById($id);

        if (!$connection) {
            echo json_encode(array('success' => false, 'message' => 'Connection not found'));
            return;
        }

        // Test the connection
        $result = $this->hl7_model->testConnection($connection);

        echo json_encode($result);
    }

    // ==================== MESSAGE QUEUE ====================

    public function queue()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['queue'] = $this->hl7_model->getMessageQueue();
        $data['connections'] = $this->hl7_model->getConnections();
        $this->load->view('home/dashboard');
        $this->load->view('hl7_queue', $data);
        $this->load->view('home/footer');
    }

    public function addToQueue()
    {
        $data = array(
            'hospital_id' => $this->session->userdata('hospital_id'),
            'connection_id' => $this->input->post('connection_id'),
            'message_type' => $this->input->post('message_type'),
            'trigger_event' => $this->input->post('trigger_event'),
            'control_id' => $this->generateControlId(),
            'payload' => $this->input->post('payload'),
            'direction' => 'outbound',
            'status' => 'pending'
        );

        $this->hl7_model->addToQueue($data);
        $this->session->set_flashdata('feedback', 'Message added to queue');
        redirect('hl7/queue');
    }

    public function processQueue()
    {
        // This would typically be called via cron job
        $pending = $this->hl7_model->getPendingMessages();
        $processed = 0;
        $failed = 0;

        foreach ($pending as $message) {
            $connection = $this->hl7_model->getConnectionById($message->connection_id);
            if (!$connection) {
                $this->hl7_model->updateQueueStatus($message->id, 'failed', 'Connection not found');
                $failed++;
                continue;
            }

            // Try to send the message
            $result = $this->hl7_model->sendMessage($connection, $message->payload);

            if ($result['success']) {
                $this->hl7_model->updateQueueStatus($message->id, 'sent', null, $result['response']);
                $processed++;
            } else {
                $this->hl7_model->updateQueueStatus($message->id, 'failed', $result['error']);
                $failed++;
            }
        }

        echo json_encode(array(
            'success' => true,
            'processed' => $processed,
            'failed' => $failed
        ));
    }

    public function resendMessage()
    {
        $id = $this->input->get('id');
        $this->hl7_model->updateQueueStatus($id, 'pending');
        $this->session->set_flashdata('feedback', 'Message marked for resend');
        redirect('hl7/queue');
    }

    // ==================== MESSAGE PARSING ====================

    public function parseMessage()
    {
        $payload = $this->input->post('payload');

        if (empty($payload)) {
            echo json_encode(array('success' => false, 'message' => 'No message provided'));
            return;
        }

        $parsed = $this->hl7_model->parseMessage($payload);
        echo json_encode(array('success' => true, 'parsed' => $parsed));
    }

    public function viewMessage()
    {
        $id = $this->input->get('id');
        $message = $this->hl7_model->getHl7MessageById($id);

        if (!$message) {
            $this->session->set_flashdata('feedback', 'Message not found');
            redirect('hl7');
        }

        $data['message'] = $message;
        $data['parsed'] = $this->hl7_model->parseMessage($message->payload);
        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard');
        $this->load->view('hl7_message_detail', $data);
        $this->load->view('home/footer');
    }

    // ==================== TEMPLATES ====================

    public function templates()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['templates'] = $this->hl7_model->getTemplates();
        $this->load->view('home/dashboard');
        $this->load->view('hl7_templates', $data);
        $this->load->view('home/footer');
    }

    public function addTemplate()
    {
        $data = array(
            'hospital_id' => $this->session->userdata('hospital_id'),
            'name' => $this->input->post('name'),
            'message_type' => $this->input->post('message_type'),
            'trigger_event' => $this->input->post('trigger_event'),
            'template' => $this->input->post('template'),
            'description' => $this->input->post('description'),
            'status' => 1
        );

        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->hl7_model->updateTemplate($id, $data);
            $this->session->set_flashdata('feedback', lang('updated'));
        } else {
            $this->hl7_model->insertTemplate($data);
            $this->session->set_flashdata('feedback', lang('added'));
        }

        redirect('hl7/templates');
    }

    // ==================== MESSAGE GENERATION ====================

    public function generateAdt($patient_id, $event = 'A01')
    {
        $this->load->model('patient/patient_model');
        $patient = $this->patient_model->getPatientById($patient_id);

        if (!$patient) {
            return false;
        }

        $message = $this->hl7_model->generateAdtMessage($patient, $event);
        return $message;
    }

    public function generateOrm($order_id)
    {
        $this->load->model('radiology/radiology_model');
        $order = $this->radiology_model->getOrderById($order_id);

        if (!$order) {
            return false;
        }

        $message = $this->hl7_model->generateOrmMessage($order);
        return $message;
    }

    // ==================== HELPERS ====================

    private function generateControlId()
    {
        return date('YmdHis') . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
