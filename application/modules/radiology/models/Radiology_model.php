<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Radiology_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // ==================== ORDERS ====================

    function insertOrder($data)
    {
        $this->db->insert('radiology_orders', $data);
        return $this->db->insert_id();
    }

    function getRadiologyOrders($limit = null)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('id', 'desc');
        if ($limit) {
            $this->db->limit($limit);
        }
        $query = $this->db->get('radiology_orders');
        return $query->result();
    }

    function getOrderById($id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('id', $id);
        $query = $this->db->get('radiology_orders');
        return $query->row();
    }

    function getOrderByBarcode($barcode)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('barcode', $barcode);
        $query = $this->db->get('radiology_orders');
        return $query->row();
    }

    function updateOrder($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->update('radiology_orders', $data);
    }

    function deleteOrder($id)
    {
        $this->db->where('id', $id);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->delete('radiology_orders');
    }

    function countOrders()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        return $this->db->count_all_results('radiology_orders');
    }

    // Order search methods for DataTables
    function getOrdersBySearch($search, $order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $query = $this->db->select('*')
            ->from('radiology_orders')
            ->where('hospital_id', $this->session->userdata('hospital_id'))
            ->where("(order_number LIKE '%" . $search . "%' OR patient_name LIKE '%" . $search . "%' OR doctor_name LIKE '%" . $search . "%' OR barcode LIKE '%" . $search . "%')", NULL, FALSE)
            ->get();
        return $query->result();
    }

    function getOrdersWithoutSearch($order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('radiology_orders');
        return $query->result();
    }

    function getOrdersByLimitBySearch($limit, $start, $search, $order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->limit($limit, $start);
        $query = $this->db->select('*')
            ->from('radiology_orders')
            ->where('hospital_id', $this->session->userdata('hospital_id'))
            ->where("(order_number LIKE '%" . $search . "%' OR patient_name LIKE '%" . $search . "%' OR doctor_name LIKE '%" . $search . "%' OR barcode LIKE '%" . $search . "%')", NULL, FALSE)
            ->get();
        return $query->result();
    }

    function getOrdersByLimit($limit, $start, $order, $dir)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get('radiology_orders');
        return $query->result();
    }

    // Generate unique barcode
    function generateBarcode()
    {
        $hospital_id = $this->session->userdata('hospital_id');
        $prefix = 'RAD';
        $timestamp = date('ymd');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        $barcode = $prefix . $hospital_id . $timestamp . $random;

        // Check if barcode exists
        $this->db->where('barcode', $barcode);
        $exists = $this->db->count_all_results('radiology_orders');

        if ($exists > 0) {
            return $this->generateBarcode(); // Recursively generate new one
        }

        return $barcode;
    }

    // Generate order number
    function generateOrderNumber()
    {
        $hospital_id = $this->session->userdata('hospital_id');
        $this->db->where('hospital_id', $hospital_id);
        $this->db->select_max('id');
        $query = $this->db->get('radiology_orders');
        $row = $query->row();

        $next_id = ($row && $row->id) ? ($row->id + 1) : 1;
        return 'RAD-' . str_pad($next_id, 6, '0', STR_PAD_LEFT);
    }

    // ==================== TESTS ====================

    function insertTest($data)
    {
        $this->db->insert('radiology_tests', $data);
        return $this->db->insert_id();
    }

    function getRadiologyTests()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('name', 'asc');
        $query = $this->db->get('radiology_tests');
        return $query->result();
    }

    function getRadiologyTestsWithCategory()
    {
        $this->db->select('radiology_tests.*, radiology_categories.name as category_name');
        $this->db->from('radiology_tests');
        $this->db->join('radiology_categories', 'radiology_tests.category_id = radiology_categories.id', 'left');
        $this->db->where('radiology_tests.hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('radiology_tests.status', 1);
        $this->db->order_by('radiology_tests.name', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    function getRadiologyTestById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('radiology_tests');
        return $query->row();
    }

    function updateTest($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('radiology_tests', $data);
    }

    function deleteTest($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('radiology_tests');
    }

    // Legacy method for compatibility
    function getRadiology()
    {
        return $this->getRadiologyTests();
    }

    function getRadiologyById($id)
    {
        return $this->getRadiologyTestById($id);
    }

    function insertRadiology($data)
    {
        return $this->insertTest($data);
    }

    function updateRadiology($id, $data)
    {
        return $this->updateTest($id, $data);
    }

    function delete($id)
    {
        return $this->deleteTest($id);
    }

    // ==================== CATEGORIES ====================

    function insertCategory($data)
    {
        $this->db->insert('radiology_categories', $data);
        return $this->db->insert_id();
    }

    function getRadiologyCategories()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('name', 'asc');
        $query = $this->db->get('radiology_categories');
        return $query->result();
    }

    function getRadiologyCategoryById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('radiology_categories');
        return $query->row();
    }

    function updateCategory($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('radiology_categories', $data);
    }

    function deleteCategory($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('radiology_categories');
    }

    // ==================== REPORTS ====================

    function insertReport($data)
    {
        $this->db->insert('radiology_reports', $data);
        return $this->db->insert_id();
    }

    function getRadiologyReports($limit = null)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('id', 'desc');
        if ($limit) {
            $this->db->limit($limit);
        }
        $query = $this->db->get('radiology_reports');
        return $query->result();
    }

    function getReportById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('radiology_reports');
        return $query->row();
    }

    function getReportsByOrderId($order_id)
    {
        $this->db->where('order_id', $order_id);
        $query = $this->db->get('radiology_reports');
        return $query->result();
    }

    function updateReport($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('radiology_reports', $data);
    }

    function deleteReport($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('radiology_reports');
    }

    // ==================== STATISTICS ====================

    function getRadiologyStats()
    {
        $hospital_id = $this->session->userdata('hospital_id');

        // Total orders
        $this->db->where('hospital_id', $hospital_id);
        $total_orders = $this->db->count_all_results('radiology_orders');

        // Pending orders
        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('status', 'pending');
        $pending_orders = $this->db->count_all_results('radiology_orders');

        // Completed orders
        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('status', 'completed');
        $completed_orders = $this->db->count_all_results('radiology_orders');

        // Total reports
        $this->db->where('hospital_id', $hospital_id);
        $total_reports = $this->db->count_all_results('radiology_reports');

        // Today's orders
        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('DATE(order_date)', date('Y-m-d'));
        $today_orders = $this->db->count_all_results('radiology_orders');

        // Today's revenue
        $this->db->select_sum('gross_total');
        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('DATE(order_date)', date('Y-m-d'));
        $query = $this->db->get('radiology_orders');
        $today_revenue = $query->row()->gross_total ?: 0;

        return array(
            'total_orders' => $total_orders,
            'pending_orders' => $pending_orders,
            'completed_orders' => $completed_orders,
            'total_reports' => $total_reports,
            'today_orders' => $today_orders,
            'today_revenue' => $today_revenue
        );
    }
}
