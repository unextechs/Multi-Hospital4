<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pharmacy_queue_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insertQueue($data)
    {
        $this->db->insert('pharmacy_queue', $data);
    }

    public function getQueueByPrescriptionId($prescription_id)
    {
        $this->db->where('prescription_id', $prescription_id);
        $query = $this->db->get('pharmacy_queue');
        return $query->row();
    }

    public function updateQueueStatus($id, $status, $data = [])
    {
        $this->db->where('id', $id);
        $this->db->update('pharmacy_queue', $data);
    }

    public function getNextQueueNumber($hospital_id)
    {
        // Simple logic for queue number: Count today's entries + 1
        $today = date('Y-m-d');
        $this->db->where('hospital_id', $hospital_id);
        $this->db->like('created_at', $today);
        $count = $this->db->count_all_results('pharmacy_queue');
        return $count + 1;
    }
}
