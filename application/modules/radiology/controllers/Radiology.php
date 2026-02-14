<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';

use \Mpdf\Mpdf;

class Radiology extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('radiology_model');
        $this->load->model('patient/patient_model');
        $this->load->model('doctor/doctor_model');
        $this->load->model('settings/settings_model');
        $this->load->model('insurance/insurance_model');

        if (!$this->ion_auth->in_group(array('admin', 'superadmin', 'Accountant', 'Doctor', 'Receptionist', 'Nurse', 'Laboratorist', 'Radiologist'))) {
            redirect('home/permission');
        }
    }

    public function index()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['stats'] = $this->radiology_model->getRadiologyStats();
        $data['recent_orders'] = $this->radiology_model->getRadiologyOrders(10);
        $data['recent_reports'] = $this->radiology_model->getRadiologyReports(10);
        $this->load->view('home/dashboard');
        $this->load->view('dashboard', $data);
        $this->load->view('home/footer');
    }

    public function orders()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['orders'] = $this->radiology_model->getRadiologyOrders();
        $this->load->view('home/dashboard');
        $this->load->view('orders', $data);
        $this->load->view('home/footer');
    }

    public function tests()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['tests'] = $this->radiology_model->getRadiologyTests();
        $data['categories'] = $this->radiology_model->getRadiologyCategories();
        $this->load->view('home/dashboard');
        $this->load->view('tests', $data);
        $this->load->view('home/footer');
    }

    public function categories()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['categories'] = $this->radiology_model->getRadiologyCategories();
        $this->load->view('home/dashboard');
        $this->load->view('categories', $data);
        $this->load->view('home/footer');
    }

    public function addOrderView()
    {
        $patient_id = $this->input->get('patient');
        $doctor_id = $this->input->get('doctor');

        if (!empty($patient_id)) {
            $data['patient_single'] = $this->patient_model->getPatientById($patient_id);
        }
        if (!empty($doctor_id)) {
            $data['doctor_single'] = $this->doctor_model->getDoctorById($doctor_id);
        }

        $data['settings'] = $this->settings_model->getSettings();
        $data['patients'] = $this->patient_model->getPatient();
        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['tests'] = $this->radiology_model->getRadiologyTestsWithCategory();
        $data['categories'] = $this->radiology_model->getRadiologyCategories();
        $data['insurance_companys'] = $this->insurance_model->getInsurance();
        $data['discount_limit'] = $this->getDiscountLimit();

        $this->load->view('home/dashboard');
        $this->load->view('add_order', $data);
        $this->load->view('home/footer');
    }

    public function addOrder()
    {
        $patient_id = $this->input->post('patient_id');
        $doctor_id = $this->input->post('doctor_id');
        $test_ids = $this->input->post('test_ids');
        $quantities = $this->input->post('quantities');

        // Calculate totals
        $subtotal = 0;
        $test_details = array();

        if (!empty($test_ids) && is_array($test_ids)) {
            foreach ($test_ids as $key => $test_id) {
                $test = $this->radiology_model->getRadiologyTestById($test_id);
                if ($test) {
                    $qty = isset($quantities[$key]) ? (int) $quantities[$key] : 1;
                    $line_total = $test->price * $qty;
                    $subtotal += $line_total;

                    $test_details[] = array(
                        'test_id' => $test_id,
                        'test_name' => $test->name,
                        'price' => $test->price,
                        'quantity' => $qty,
                        'line_total' => $line_total
                    );
                }
            }
        }

        // Get discount values
        $discount_percent = $this->input->post('discount_percent') ? floatval($this->input->post('discount_percent')) : 0;
        $flat_discount = $this->input->post('flat_discount') ? floatval($this->input->post('flat_discount')) : 0;

        // Validate discount against role limit
        $discount_limit = $this->getDiscountLimit();
        if ($discount_percent > $discount_limit) {
            $discount_percent = $discount_limit;
        }

        // Calculate discount amount
        $discount_amount = ($subtotal * $discount_percent / 100) + $flat_discount;

        // Get VAT
        $settings = $this->settings_model->getSettings();
        $vat_percent = $this->input->post('vat_percent') ? floatval($this->input->post('vat_percent')) : $settings->vat;
        $after_discount = $subtotal - $discount_amount;
        $vat_amount = $after_discount * ($vat_percent / 100);

        // Calculate gross total
        $gross_total = $after_discount + $vat_amount;

        // Amount received
        $amount_received = $this->input->post('amount_received') ? floatval($this->input->post('amount_received')) : 0;
        $due_amount = $gross_total - $amount_received;

        // Payment status
        $payment_status = 'unpaid';
        if ($amount_received >= $gross_total) {
            $payment_status = 'paid';
        } elseif ($amount_received > 0) {
            $payment_status = 'partial';
        }

        // Generate barcode
        $barcode = $this->radiology_model->generateBarcode();

        // Get order number
        $order_number = $this->radiology_model->generateOrderNumber();

        // Prepare data
        $data = array(
            'hospital_id' => $this->session->userdata('hospital_id'),
            'order_number' => $order_number,
            'barcode' => $barcode,
            'patient_id' => $patient_id,
            'patient_name' => $this->input->post('patient_name'),
            'patient_phone' => $this->input->post('patient_phone'),
            'patient_age' => $this->input->post('patient_age'),
            'patient_gender' => $this->input->post('patient_gender'),
            'patient_address' => $this->input->post('patient_address'),
            'doctor_id' => $doctor_id,
            'doctor_name' => $this->input->post('doctor_name'),
            'test_ids' => implode(',', $test_ids),
            'test_details' => json_encode($test_details),
            'subtotal' => $subtotal,
            'discount_percent' => $discount_percent,
            'discount_amount' => $discount_amount,
            'flat_discount' => $flat_discount,
            'vat_percent' => $vat_percent,
            'vat_amount' => $vat_amount,
            'gross_total' => $gross_total,
            'amount_received' => $amount_received,
            'due_amount' => $due_amount,
            'payment_status' => $payment_status,
            'payment_type' => $this->input->post('payment_type') ? $this->input->post('payment_type') : 'Cash',
            'insurance_company_id' => $this->input->post('insurance_company_id'),
            'insurance_details' => $this->input->post('insurance_details'),
            'order_date' => date('Y-m-d H:i:s'),
            'appointment_date' => $this->input->post('appointment_date') ? $this->input->post('appointment_date') : null,
            'urgency' => $this->input->post('urgency') ? $this->input->post('urgency') : 'routine',
            'clinical_history' => $this->input->post('clinical_history'),
            'clinical_notes' => $this->input->post('clinical_notes'),
            'status' => 'pending',
            'created_by' => $this->ion_auth->get_user_id()
        );

        $order_id = $this->radiology_model->insertOrder($data);

        if ($order_id) {
            $this->session->set_flashdata('feedback', lang('added'));

            // Check if print is requested
            if ($this->input->post('form_submit') == 'saveandprint') {
                redirect('radiology/printLabel?id=' . $order_id);
            }
        } else {
            $this->session->set_flashdata('feedback', lang('error'));
        }

        redirect('radiology/orders');
    }

    public function editOrder()
    {
        $id = $this->input->get('id');
        $data['order'] = $this->radiology_model->getOrderById($id);
        $data['settings'] = $this->settings_model->getSettings();
        $data['patients'] = $this->patient_model->getPatient();
        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['tests'] = $this->radiology_model->getRadiologyTestsWithCategory();
        $data['categories'] = $this->radiology_model->getRadiologyCategories();
        $data['insurance_companys'] = $this->insurance_model->getInsurance();
        $data['discount_limit'] = $this->getDiscountLimit();

        $this->load->view('home/dashboard');
        $this->load->view('add_order', $data);
        $this->load->view('home/footer');
    }

    public function deleteOrder()
    {
        $id = $this->input->get('id');
        $this->radiology_model->deleteOrder($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('radiology/orders');
    }

    public function printLabel()
    {
        $id = $this->input->get('id');
        $order = $this->radiology_model->getOrderById($id);
        $settings = $this->settings_model->getSettings();

        if (!$order) {
            $this->session->set_flashdata('feedback', 'Order not found');
            redirect('radiology/orders');
        }

        $data['order'] = $order;
        $data['settings'] = $settings;

        $this->load->view('print_barcode', $data);
    }

    public function generateBarcodePdf()
    {
        $id = $this->input->get('id');
        $order = $this->radiology_model->getOrderById($id);
        $settings = $this->settings_model->getSettings();

        if (!$order) {
            $this->session->set_flashdata('feedback', 'Order not found');
            redirect('radiology/orders');
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [50, 30], // Label size in mm
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 2,
            'margin_bottom' => 2
        ]);

        $html = '<div style="text-align: center; font-family: Arial, sans-serif;">';
        $html .= '<div style="font-size: 8px; font-weight: bold;">' . htmlspecialchars($settings->title) . '</div>';
        $html .= '<div style="font-size: 7px;">' . htmlspecialchars($order->patient_name) . '</div>';
        $html .= '<barcode code="' . $order->barcode . '" type="C128A" height="0.8" text="1" size="0.8" />';
        $html .= '<div style="font-size: 6px;">' . date('d/m/Y H:i', strtotime($order->order_date)) . '</div>';
        $html .= '</div>';

        $mpdf->WriteHTML($html);
        $mpdf->Output('radiology_label_' . $order->order_number . '.pdf', 'I');
    }

    public function getOrderByBarcode()
    {
        $barcode = $this->input->get('barcode');
        $order = $this->radiology_model->getOrderByBarcode($barcode);

        if ($order) {
            echo json_encode(array('success' => true, 'order' => $order));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Order not found'));
        }
    }

    public function searchByBarcode()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard');
        $this->load->view('search_barcode', $data);
        $this->load->view('home/footer');
    }

    // Tests CRUD
    public function addTestView()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['categories'] = $this->radiology_model->getRadiologyCategories();
        $this->load->view('home/dashboard');
        $this->load->view('add_test', $data);
        $this->load->view('home/footer');
    }

    public function addTest()
    {
        $data = array(
            'hospital_id' => $this->session->userdata('hospital_id'),
            'category_id' => $this->input->post('category_id'),
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'duration_minutes' => $this->input->post('duration_minutes'),
            'preparation_instructions' => $this->input->post('preparation_instructions'),
            'status' => 1
        );

        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->radiology_model->updateTest($id, $data);
            $this->session->set_flashdata('feedback', lang('updated'));
        } else {
            $this->radiology_model->insertTest($data);
            $this->session->set_flashdata('feedback', lang('added'));
        }

        redirect('radiology/tests');
    }

    public function editTest()
    {
        $id = $this->input->get('id');
        $data['test'] = $this->radiology_model->getRadiologyTestById($id);
        $data['settings'] = $this->settings_model->getSettings();
        $data['categories'] = $this->radiology_model->getRadiologyCategories();
        $this->load->view('home/dashboard');
        $this->load->view('add_test', $data);
        $this->load->view('home/footer');
    }

    public function deleteTest()
    {
        $id = $this->input->get('id');
        $this->radiology_model->deleteTest($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('radiology/tests');
    }

    // Categories CRUD
    public function addCategoryView()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard');
        $this->load->view('add_category', $data);
        $this->load->view('home/footer');
    }

    public function addCategory()
    {
        $data = array(
            'hospital_id' => $this->session->userdata('hospital_id'),
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'status' => 1
        );

        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->radiology_model->updateCategory($id, $data);
            $this->session->set_flashdata('feedback', lang('updated'));
        } else {
            $this->radiology_model->insertCategory($data);
            $this->session->set_flashdata('feedback', lang('added'));
        }

        redirect('radiology/categories');
    }

    public function editCategory()
    {
        $id = $this->input->get('id');
        $data['category'] = $this->radiology_model->getRadiologyCategoryById($id);
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard');
        $this->load->view('add_category', $data);
        $this->load->view('home/footer');
    }

    public function deleteCategory()
    {
        $id = $this->input->get('id');
        $this->radiology_model->deleteCategory($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('radiology/categories');
    }

    // Reports
    public function reports()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['reports'] = $this->radiology_model->getRadiologyReports();
        $this->load->view('home/dashboard');
        $this->load->view('reports', $data);
        $this->load->view('home/footer');
    }

    // Helper function to get discount limit based on user role
    private function getDiscountLimit()
    {
        $settings = $this->settings_model->getSettings();

        if ($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('superadmin')) {
            return isset($settings->radiology_discount_limit_admin) ? $settings->radiology_discount_limit_admin : 100;
        } elseif ($this->ion_auth->in_group('Doctor')) {
            return isset($settings->radiology_discount_limit_doctor) ? $settings->radiology_discount_limit_doctor : 15;
        } elseif ($this->ion_auth->in_group('Receptionist')) {
            return isset($settings->radiology_discount_limit_receptionist) ? $settings->radiology_discount_limit_receptionist : 10;
        }

        return 0; // Default no discount
    }

    // AJAX endpoint for getting orders data (for DataTables)
    public function getOrders()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];

        $order = $this->input->post("order");
        $columns_valid = array(
            "0" => "id",
            "1" => "order_number",
            "2" => "patient_name",
            "3" => "doctor_name",
            "4" => "gross_total",
            "5" => "status",
            "6" => "order_date"
        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);
        $dir = $values[0];
        $order_col = $values[1];

        $settings = $this->settings_model->getSettings();

        if ($limit == -1) {
            if (!empty($search)) {
                $orders = $this->radiology_model->getOrdersBySearch($search, $order_col, $dir);
            } else {
                $orders = $this->radiology_model->getOrdersWithoutSearch($order_col, $dir);
            }
        } else {
            if (!empty($search)) {
                $orders = $this->radiology_model->getOrdersByLimitBySearch($limit, $start, $search, $order_col, $dir);
            } else {
                $orders = $this->radiology_model->getOrdersByLimit($limit, $start, $order_col, $dir);
            }
        }

        $i = 0;
        $info = array();

        foreach ($orders as $ord) {
            $i++;

            $status_class = $ord->status == 'completed' ? 'success' : ($ord->status == 'pending' ? 'warning' : 'info');
            $status_label = '<span class="label label-' . $status_class . '">' . ucfirst($ord->status) . '</span>';

            $options = '<a class="btn btn-info btn-sm" href="radiology/editOrder?id=' . $ord->id . '" title="Edit"><i class="fa fa-edit"></i></a> ';
            $options .= '<a class="btn btn-primary btn-sm" href="radiology/printLabel?id=' . $ord->id . '" title="Print Label"><i class="fa fa-barcode"></i></a> ';
            $options .= '<a class="btn btn-danger btn-sm" href="radiology/deleteOrder?id=' . $ord->id . '" onclick="return confirm(\'Are you sure?\');" title="Delete"><i class="fa fa-trash"></i></a>';

            $info[] = array(
                $ord->order_number,
                $ord->patient_name,
                $ord->doctor_name,
                $settings->currency . number_format($ord->gross_total, 2),
                $status_label,
                date('d M Y', strtotime($ord->order_date)),
                $options
            );
        }

        if (!empty($orders)) {
            $output = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => $this->radiology_model->countOrders(),
                "recordsFiltered" => $i,
                "data" => $info
            );
        } else {
            $output = array(
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }

        echo json_encode($output);
    }
}
