<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Queue_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Generate next number and create a queue entry
    function addToQueue($type, $type_id = 0, $patient_id = null)
    {
        $hospital_id = $this->session->userdata('hospital_id');
        $date = date('Y-m-d');
        $timestamp = time();

        // 1. Get next number from daily_queue
        $this->db->where('type', $type);
        $this->db->where('type_id', $type_id);
        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('date', $date);
        $query = $this->db->get('daily_queue');

        $current_number = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $current_number = $row->current_number;
            $new_number = $current_number + 1;

            $this->db->where('id', $row->id);
            $this->db->update('daily_queue', array('current_number' => $new_number));
        } else {
            $new_number = 1;
            $data = array(
                'type' => $type,
                'type_id' => $type_id,
                'hospital_id' => $hospital_id,
                'date' => $date,
                'current_number' => 1
            );
            $this->db->insert('daily_queue', $data);
        }

        // 2. Insert into queue_entries
        $entry = array(
            'hospital_id' => $hospital_id,
            'type' => $type,
            'type_id' => $type_id,
            'date' => $timestamp,
            'queue_number' => $new_number,
            'patient_id' => $patient_id,
            'status' => 'Waiting',
            'created_at' => $timestamp
        );
        $this->db->insert('queue_entries', $entry);
        return $new_number;
    }

    function getQueue($type, $type_id = 0, $hospital_id = null)
    {
        if (empty($hospital_id)) {
            $hospital_id = $this->session->userdata('hospital_id');
        }
        $date_search = date('Y-m-d');
        // We'll search by timestamp range covering today
        $start_day = strtotime($date_search . ' 00:00:00');
        $end_day = strtotime($date_search . ' 23:59:59');

        $this->db->select('queue_entries.*, patient.name as patient_name');
        $this->db->from('queue_entries');
        $this->db->join('patient', 'patient.id = queue_entries.patient_id', 'left');
        $this->db->where('queue_entries.hospital_id', $hospital_id);
        $this->db->where('queue_entries.type', $type);
        if ($type == 'Doctor' && $type_id > 0) {
            $this->db->where('queue_entries.type_id', $type_id);
        }
        $this->db->where('queue_entries.date >=', $start_day);
        $this->db->where('queue_entries.date <=', $end_day);
        $this->db->order_by('queue_entries.queue_number', 'asc');
        return $this->db->get()->result();
    }

    function getQueueByStatus($hospital_id, $type, $status)
    {
        $date_search = date('Y-m-d');
        $start_day = strtotime($date_search . ' 00:00:00');
        $end_day = strtotime($date_search . ' 23:59:59');

        $this->db->select('queue_entries.*, patient.name as patientname, doctor.name as doctorname');
        // doctor join might fail for Pharmacy/Lab if type_id is 0
        // We'll do conditional join or just left join
        $this->db->from('queue_entries');
        $this->db->join('patient', 'patient.id = queue_entries.patient_id', 'left');
        $this->db->join('doctor', 'doctor.id = queue_entries.type_id', 'left'); // Only valid for Doctor type mainly

        $this->db->where('queue_entries.hospital_id', $hospital_id);
        $this->db->where('queue_entries.type', $type);
        $this->db->where('queue_entries.status', $status);
        $this->db->where('queue_entries.date >=', $start_day);
        $this->db->where('queue_entries.date <=', $end_day);
        $this->db->order_by('queue_entries.queue_number', 'asc');

        return $this->db->get()->result();
    }

    function updateStatus($id, $status)
    {
        $data = array('status' => $status);
        $this->db->where('id', $id);
        $this->db->update('queue_entries', $data);
    }
}
