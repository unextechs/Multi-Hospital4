<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pharmacy_queue_model extends CI_Model
{

    /**
     * Get pending queue items for a specific hospital
     */
    public function getPendingQueue($hospital_id = null)
    {
        $this->db->where('status', 'pending');
        if ($hospital_id) {
            $this->db->where('hospital_id', $hospital_id);
        }
        $this->db->order_by('queue_number', 'ASC');
        return $this->db->get('pharmacy_queue')->result();
    }

    /**
     * Get all queue items with optional status filter
     */
    public function getQueueItems($hospital_id = null, $status = null)
    {
        if ($hospital_id) {
            $this->db->where('hospital_id', $hospital_id);
        }
        if ($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('pharmacy_queue')->result();
    }

    /**
     * Get queue item by ID
     */
    public function getQueueById($id)
    {
        return $this->db->get_where('pharmacy_queue', ['id' => $id])->row();
    }

    /**
     * Get queue items by prescription ID
     */
    public function getQueueByPrescriptionId($prescription_id)
    {
        return $this->db->get_where('pharmacy_queue', ['prescription_id' => $prescription_id])->row();
    }

    /**
     * Update queue status and related data
     */
    public function updateQueueStatus($id, $status, $additional_data = [])
    {
        $update_data = array_merge($additional_data, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->where('id', $id);
        return $this->db->update('pharmacy_queue', $update_data);
    }

    /**
     * Get today's total queue count
     */
    public function getTodayQueueCount($hospital_id = null)
    {
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        if ($hospital_id) {
            $this->db->where('hospital_id', $hospital_id);
        }
        return $this->db->count_all_results('pharmacy_queue');
    }

    /**
     * Get pending queue count
     */
    public function getPendingQueueCount($hospital_id = null)
    {
        $this->db->where('status', 'pending');
        if ($hospital_id) {
            $this->db->where('hospital_id', $hospital_id);
        }
        return $this->db->count_all_results('pharmacy_queue');
    }

    /**
     * Get next queue number for today
     */
    public function getNextQueueNumber($hospital_id)
    {
        $this->db->select_max('queue_number');
        $this->db->where('DATE(created_at)', date('Y-m-d'));
        $this->db->where('hospital_id', $hospital_id);
        $result = $this->db->get('pharmacy_queue')->row();

        return ($result && $result->queue_number) ? $result->queue_number + 1 : 1;
    }

    /**
     * Insert new queue item
     */
    public function insertQueue($data)
    {
        $this->db->insert('pharmacy_queue', $data);
        return $this->db->insert_id();
    }

    /**
     * Delete queue item (use with caution)
     */
    public function deleteQueue($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('pharmacy_queue');
    }

    /**
     * Get completed queue items for reporting
     */
    public function getCompletedQueue($hospital_id = null, $start_date = null, $end_date = null)
    {
        $this->db->where('status', 'completed');

        if ($hospital_id) {
            $this->db->where('hospital_id', $hospital_id);
        }

        if ($start_date) {
            $this->db->where('DATE(processed_at) >=', $start_date);
        }

        if ($end_date) {
            $this->db->where('DATE(processed_at) <=', $end_date);
        }

        $this->db->order_by('processed_at', 'DESC');
        return $this->db->get('pharmacy_queue')->result();
    }

    /**
     * Get average processing time in minutes
     */
    public function getAverageProcessingTime($hospital_id = null)
    {
        $this->db->select('AVG(TIMESTAMPDIFF(MINUTE, created_at, processed_at)) as avg_time');
        $this->db->where('status', 'completed');
        $this->db->where('processed_at IS NOT NULL');

        if ($hospital_id) {
            $this->db->where('hospital_id', $hospital_id);
        }

        $result = $this->db->get('pharmacy_queue')->row();
        return $result ? round($result->avg_time, 1) : 0;
    }
}

/* End of file Pharmacy_queue_model.php */
/* Location: ./application/modules/finance/models/Pharmacy_queue_model.php */
