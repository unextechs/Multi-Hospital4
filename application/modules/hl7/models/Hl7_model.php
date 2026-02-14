<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hl7_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // ==================== MESSAGES ====================

    function insertHl7($data)
    {
        $this->db->insert('hl7_messages', $data);
        return $this->db->insert_id();
    }

    function getHl7Messages($limit = 100)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get('hl7_messages');
        return $query->result();
    }

    function getHl7MessageById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('hl7_messages');
        return $query->row();
    }

    function deleteHl7($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('hl7_messages');
    }

    // ==================== CONNECTIONS ====================

    function insertConnection($data)
    {
        $this->db->insert('hl7_connections', $data);
        return $this->db->insert_id();
    }

    function getConnections()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('name', 'asc');
        $query = $this->db->get('hl7_connections');
        return $query->result();
    }

    function getConnectionById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('hl7_connections');
        return $query->row();
    }

    function updateConnection($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('hl7_connections', $data);
    }

    function deleteConnection($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('hl7_connections');
    }

    function testConnection($connection)
    {
        // Try to establish a TCP connection
        $socket = @fsockopen($connection->host, $connection->port, $errno, $errstr, 5);

        if (!$socket) {
            return array(
                'success' => false,
                'message' => "Connection failed: $errstr ($errno)"
            );
        }

        // Update last connected time
        $this->db->where('id', $connection->id);
        $this->db->update('hl7_connections', array('last_connected' => date('Y-m-d H:i:s')));

        fclose($socket);

        return array(
            'success' => true,
            'message' => 'Connection successful'
        );
    }

    // ==================== MESSAGE QUEUE ====================

    function addToQueue($data)
    {
        $this->db->insert('hl7_message_queue', $data);
        return $this->db->insert_id();
    }

    function getMessageQueue($limit = 100)
    {
        $this->db->select('hl7_message_queue.*, hl7_connections.name as connection_name');
        $this->db->from('hl7_message_queue');
        $this->db->join('hl7_connections', 'hl7_message_queue.connection_id = hl7_connections.id', 'left');
        $this->db->where('hl7_message_queue.hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('hl7_message_queue.id', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    function getPendingMessages($limit = 50)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('status', 'pending');
        $this->db->where('attempts <', 3);
        $this->db->order_by('id', 'asc');
        $this->db->limit($limit);
        $query = $this->db->get('hl7_message_queue');
        return $query->result();
    }

    function updateQueueStatus($id, $status, $error = null, $response = null)
    {
        $data = array(
            'status' => $status,
            'processed_at' => date('Y-m-d H:i:s')
        );

        if ($error) {
            $data['error_message'] = $error;
        }

        if ($response) {
            $data['response'] = $response;
        }

        // Increment attempts
        $this->db->set('attempts', 'attempts + 1', FALSE);
        $this->db->where('id', $id);
        $this->db->update('hl7_message_queue', $data);
    }

    function sendMessage($connection, $payload)
    {
        // MLLP framing
        $MLLP_START = chr(0x0B); // VT
        $MLLP_END = chr(0x1C) . chr(0x0D); // FS + CR

        $framed_message = $MLLP_START . $payload . $MLLP_END;

        $socket = @fsockopen($connection->host, $connection->port, $errno, $errstr, 10);

        if (!$socket) {
            return array(
                'success' => false,
                'error' => "Connection failed: $errstr ($errno)"
            );
        }

        // Send the message
        fwrite($socket, $framed_message);

        // Wait for response (up to 30 seconds)
        stream_set_timeout($socket, 30);
        $response = '';

        while (!feof($socket)) {
            $chunk = fread($socket, 1024);
            $response .= $chunk;

            // Check for MLLP end
            if (strpos($response, chr(0x1C)) !== false) {
                break;
            }
        }

        fclose($socket);

        // Remove MLLP framing from response
        $response = str_replace(array(chr(0x0B), chr(0x1C), chr(0x0D)), '', $response);

        // Check for ACK
        if (strpos($response, 'AA') !== false || strpos($response, 'CA') !== false) {
            return array(
                'success' => true,
                'response' => $response
            );
        } else if (strpos($response, 'AE') !== false || strpos($response, 'AR') !== false) {
            return array(
                'success' => false,
                'error' => 'Application error or rejection',
                'response' => $response
            );
        }

        // If response is empty or unknown, consider it failed
        if (empty($response)) {
            return array(
                'success' => false,
                'error' => 'No response received'
            );
        }

        return array(
            'success' => true,
            'response' => $response
        );
    }

    // ==================== MESSAGE PARSING ====================

    function parseMessage($payload)
    {
        $parsed = array(
            'segments' => array(),
            'message_type' => '',
            'trigger_event' => '',
            'control_id' => '',
            'version' => ''
        );

        // Normalize line endings
        $payload = str_replace(array("\r\n", "\n"), "\r", $payload);
        $segments = explode("\r", $payload);

        foreach ($segments as $segment_str) {
            $segment_str = trim($segment_str);
            if (empty($segment_str))
                continue;

            $fields = explode('|', $segment_str);
            $segment_name = $fields[0];

            $segment = array(
                'name' => $segment_name,
                'fields' => $fields,
                'raw' => $segment_str
            );

            // Parse MSH segment for message header info
            if ($segment_name == 'MSH') {
                if (isset($fields[8])) {
                    $msg_type = explode('^', $fields[8]);
                    $parsed['message_type'] = isset($msg_type[0]) ? $msg_type[0] : '';
                    $parsed['trigger_event'] = isset($msg_type[1]) ? $msg_type[1] : '';
                }
                $parsed['control_id'] = isset($fields[9]) ? $fields[9] : '';
                $parsed['version'] = isset($fields[11]) ? $fields[11] : '';
            }

            // Parse PID segment for patient info
            if ($segment_name == 'PID') {
                $segment['patient_id'] = isset($fields[3]) ? $fields[3] : '';
                $segment['patient_name'] = isset($fields[5]) ? $this->parseXpn($fields[5]) : '';
                $segment['dob'] = isset($fields[7]) ? $fields[7] : '';
                $segment['sex'] = isset($fields[8]) ? $fields[8] : '';
                $segment['address'] = isset($fields[11]) ? $fields[11] : '';
                $segment['phone'] = isset($fields[13]) ? $fields[13] : '';
            }

            // Parse OBR segment for order info
            if ($segment_name == 'OBR') {
                $segment['order_id'] = isset($fields[2]) ? $fields[2] : '';
                $segment['filler_order'] = isset($fields[3]) ? $fields[3] : '';
                $segment['service_id'] = isset($fields[4]) ? $fields[4] : '';
                $segment['observation_date'] = isset($fields[7]) ? $fields[7] : '';
                $segment['ordering_provider'] = isset($fields[16]) ? $fields[16] : '';
            }

            // Parse ORC segment for common order info
            if ($segment_name == 'ORC') {
                $segment['order_control'] = isset($fields[1]) ? $fields[1] : '';
                $segment['placer_order'] = isset($fields[2]) ? $fields[2] : '';
                $segment['filler_order'] = isset($fields[3]) ? $fields[3] : '';
                $segment['order_status'] = isset($fields[5]) ? $fields[5] : '';
            }

            $parsed['segments'][] = $segment;
        }

        return $parsed;
    }

    private function parseXpn($xpn_field)
    {
        // XPN format: family^given^middle^suffix^prefix
        $parts = explode('^', $xpn_field);
        $name_parts = array();

        if (isset($parts[4]) && !empty($parts[4]))
            $name_parts[] = $parts[4]; // Prefix
        if (isset($parts[1]) && !empty($parts[1]))
            $name_parts[] = $parts[1]; // Given
        if (isset($parts[2]) && !empty($parts[2]))
            $name_parts[] = $parts[2]; // Middle
        if (isset($parts[0]) && !empty($parts[0]))
            $name_parts[] = $parts[0]; // Family
        if (isset($parts[3]) && !empty($parts[3]))
            $name_parts[] = $parts[3]; // Suffix

        return implode(' ', $name_parts);
    }

    // ==================== MESSAGE GENERATION ====================

    function generateAdtMessage($patient, $event = 'A01')
    {
        $timestamp = date('YmdHis');
        $control_id = $timestamp . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Format patient name as XPN (family^given^middle)
        $name_parts = explode(' ', $patient->name);
        $family_name = array_pop($name_parts);
        $given_name = implode(' ', $name_parts);
        $patient_name = $family_name . '^' . $given_name;

        // Format date of birth
        $dob = '';
        if (!empty($patient->birthdate)) {
            $dob = date('Ymd', strtotime($patient->birthdate));
        }

        // Format sex
        $sex = strtoupper(substr($patient->sex ?? 'U', 0, 1));

        $message = "MSH|^~\\&|HOSPITAL|{$this->session->userdata('hospital_id')}|RECEIVER|FACILITY|{$timestamp}||ADT^{$event}|{$control_id}|P|2.5\r";
        $message .= "EVN|{$event}|{$timestamp}\r";
        $message .= "PID|1||{$patient->id}^^^HOSPITAL^MR||{$patient_name}||{$dob}|{$sex}|||{$patient->address}||{$patient->phone}\r";

        return $message;
    }

    function generateOrmMessage($order)
    {
        $timestamp = date('YmdHis');
        $control_id = $timestamp . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Format patient name
        $name_parts = explode(' ', $order->patient_name);
        $family_name = array_pop($name_parts);
        $given_name = implode(' ', $name_parts);
        $patient_name = $family_name . '^' . $given_name;

        $message = "MSH|^~\\&|HOSPITAL|{$this->session->userdata('hospital_id')}|RECEIVER|FACILITY|{$timestamp}||ORM^O01|{$control_id}|P|2.5\r";
        $message .= "PID|1||{$order->patient_id}^^^HOSPITAL^MR||{$patient_name}||{$order->patient_age}||||{$order->patient_address}||{$order->patient_phone}\r";
        $message .= "ORC|NW|{$order->order_number}|||CM|||{$timestamp}|||||{$order->doctor_name}\r";

        // Add OBR for each test
        $test_details = json_decode($order->test_details);
        if ($test_details) {
            $i = 1;
            foreach ($test_details as $test) {
                $message .= "OBR|{$i}|{$order->order_number}||{$test->test_id}^{$test->test_name}|||{$timestamp}||||||||{$order->doctor_name}\r";
                $i++;
            }
        }

        return $message;
    }

    // ==================== TEMPLATES ====================

    function insertTemplate($data)
    {
        $this->db->insert('hl7_templates', $data);
        return $this->db->insert_id();
    }

    function getTemplates()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('hl7_templates');
        return $query->result();
    }

    function updateTemplate($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('hl7_templates', $data);
    }

    // ==================== STATISTICS ====================

    function getStats()
    {
        $hospital_id = $this->session->userdata('hospital_id');

        // Total messages
        $this->db->where('hospital_id', $hospital_id);
        $total_messages = $this->db->count_all_results('hl7_messages');

        // Active connections
        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('status', 'active');
        $active_connections = $this->db->count_all_results('hl7_connections');

        // Pending queue
        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('status', 'pending');
        $pending_queue = $this->db->count_all_results('hl7_message_queue');

        // Failed queue
        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('status', 'failed');
        $failed_queue = $this->db->count_all_results('hl7_message_queue');

        return array(
            'total_messages' => $total_messages,
            'active_connections' => $active_connections,
            'pending_queue' => $pending_queue,
            'failed_queue' => $failed_queue
        );
    }
}
