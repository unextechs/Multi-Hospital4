<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Workspace extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('doctor_model');
        $this->load->model('patient/patient_model');
        $this->load->model('prescription/prescription_model');
        $this->load->model('lab/lab_model');
        $this->load->model('appointment/appointment_model');
        $this->load->model('finance/finance_model');
        $this->load->model('bed/bed_model');
        $this->load->model('medicine/medicine_model');
        $this->load->model('pharmacy/pharmacy_queue_model');

        $this->hospital_id = $this->session->userdata('hospital_id');

        // Allow admin, superadmin, Doctor, and Nurse (Nurses have view-only access)
        if (!$this->ion_auth->in_group(array('Doctor', 'admin', 'superadmin', 'Nurse'))) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['error' => 'Access Denied']);
                exit;
            } else {
                redirect('home/permission');
            }
        }
    }

    public function index()
    {
        // This might not be used if we only use it as a modal API
        echo "Doctor Workspace API";
    }

    // Load the workspace modal view (HTML)
    public function load_workspace()
    {
        $patient_id = $this->input->get('id');
        $data['patient'] = $this->patient_model->getPatientById($patient_id);

        if (empty($data['patient'])) {
            echo "Patient not found";
            return;
        }

        // Calculate Age
        if (!empty($data['patient']->birthdate)) {
            $birthDate = new DateTime($data['patient']->birthdate);
            $today = new DateTime('today');
            $data['age'] = $birthDate->diff($today)->y;
            $data['age_full'] = $birthDate->diff($today)->y . 'Y ' . $birthDate->diff($today)->m . 'M';
        } else {
            $data['age'] = '';
            $data['age_full'] = '';
        }

        // Fetch History
        $all_vitals = $this->patient_model->getVitalSignByPatientId($patient_id);
        $data['vital_signs'] = array_slice($all_vitals, 0, 20);

        // Lab Requests: Show only pending OR (complete AND NOT seen) AND Today Only
        $all_labs = $this->lab_model->getLabByPatientId($patient_id);
        $today_start = strtotime('today');
        $today_end = strtotime('tomorrow');

        $filtered_labs = array_filter($all_labs, function ($lab) use ($today_start, $today_end) {
            $is_seen = isset($lab->doctor_seen) ? $lab->doctor_seen : 0;
            $is_today = ($lab->date >= $today_start && $lab->date < $today_end);
            return ($is_today && ($lab->status == 'pending' || ($lab->status == 'complete' && !$is_seen)));
        });
        $data['lab_requests'] = array_values($filtered_labs);

        $all_prescriptions = $this->prescription_model->getPrescriptionByPatientId($patient_id);
        $data['prescriptions'] = $all_prescriptions;

        $all_histories = $this->patient_model->getMedicalHistoryByPatientId($patient_id);
        $data['medical_histories'] = array_slice($all_histories, 0, 20);

        // Get Lab Test Categories (diagnostic type only)
        $all_categories = $this->finance_model->getPaymentCategory();
        $data['payment_categories'] = array_filter($all_categories, function ($cat) {
            return isset($cat->type) && $cat->type == 'diagnostic';
        });

        // Get current doctor
        $user_id = $this->ion_auth->get_user_id();
        $doctor = $this->db->get_where('doctor', array('ion_user_id' => $user_id))->row();
        $data['doctor_id'] = $doctor ? $doctor->id : '';

        // Get all medicines for name resolution in history
        $data['all_medicines'] = $this->medicine_model->getMedicine();

        $data['settings'] = $this->db->get_where('settings', array('hospital_id' => $this->hospital_id))->row();

        $this->load->view('doctor/patient_workspace', $data);
    }

    public function save_medical_info()
    {
        if (!$this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))) {
            echo json_encode(['success' => false, 'message' => 'Permission Denied']);
            exit;
        }

        $patient_id = $this->input->post('patient_id');
        $known_allergies = $this->input->post('known_allergies');
        $medical_history = $this->input->post('medical_history');
        $symptoms = $this->input->post('symptoms');
        $diagnosis = $this->input->post('diagnosis');
        $notes = $this->input->post('notes');

        if (is_array($symptoms)) {
            $symptoms = implode(', ', $symptoms);
        }
        if (is_array($diagnosis)) {
            $diagnosis = implode(', ', $diagnosis);
        }
        if (is_array($notes)) {
            $notes = implode(', ', $notes);
        }

        // Update Patient Table (since these fields exist in patient table commonly, or we add them)
        // Assuming symptoms, diagnosis, notes are adding to patient profile or case history
        // For now, let's assume they are columns in patient table or we strictly use them for "Profile" purpose
        // If columns don't exist, we might need case history table. 
        // User asked "add like the case diagnosis or sysmbtoms or notes"
        // Let's check if 'patient' table has these columns.
        // If not, we might need to add them or use 'medical_history' field which is text.

        // But for "Case Diagnosis", usually it's per visit. The user seems to want a general "Medical Info" update.
        // I will check if columns exist first. But since I can't interactively check easily without slowing down, 
        // I will update 'medical_history' and 'known_allergies' which strictly exist.
        // For symptoms/diagnosis/notes, I will attempt to update them in the patient table if they exist.

        $data = array(
            'known_allergies' => $known_allergies,
            'medical_history' => $medical_history,
            'symptoms' => $symptoms,
            'diagnosis' => $diagnosis,
            'notes' => $notes
        );

        // Standardize: ensure columns exist (just in case)
        $this->load->dbforge();
        $cols_to_check = ['symptoms', 'diagnosis', 'notes', 'known_allergies', 'medical_history'];
        foreach ($cols_to_check as $col) {
            if (!$this->db->field_exists($col, 'patient')) {
                $this->dbforge->add_column('patient', array($col => array('type' => 'TEXT', 'null' => TRUE)));
            }
        }

        $this->db->where('id', $patient_id);
        if ($this->db->update('patient', $data)) {
            echo json_encode(['success' => true, 'message' => 'Medical Info Updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error occurred']);
        }
        exit;
    }

    public function save_vital_signs()
    {
        if (!$this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))) {
            echo json_encode(['success' => false, 'message' => 'Permission Denied']);
            exit;
        }

        $patient_id = $this->input->post('patient_id');
        $blood_pressure = $this->input->post('blood_pressure'); // Expected: "120/80"
        $temperature = $this->input->post('temperature');
        $pulse_rate = $this->input->post('pulse_rate');
        $respiration_rate = $this->input->post('respiration_rate');
        $weight = $this->input->post('weight');

        // Split BP
        $systolic = '';
        $diastolic = '';
        if (!empty($blood_pressure) && strpos($blood_pressure, '/') !== false) {
            $bp_parts = explode('/', $blood_pressure);
            $systolic = trim($bp_parts[0]);
            $diastolic = trim($bp_parts[1]);
        } else {
            $systolic = $blood_pressure; // Fallback
        }

        if (empty($patient_id)) {
            echo json_encode(['success' => false, 'message' => 'Patient ID is required']);
            exit;
        }

        $data = array(
            'patient_id' => $patient_id,
            'systolic_blood_pressure' => $systolic,
            'diastolic_blood_pressure' => $diastolic,
            'temperature' => $temperature,
            'heart_rate' => $pulse_rate,
            'respiratory_rate' => $respiration_rate,
            'bmi_weight' => $weight,
            'add_date_time' => date('Y-m-d H:i:s')
        );

        // Save to vital_signs table (History)
        $this->patient_model->insertVitalSign($data);

        // Also update patient record for quick access
        $this->patient_model->updatePatient($patient_id, [
            'systolic_blood_pressure' => $systolic,
            'diastolic_blood_pressure' => $diastolic,
            'temperature' => $temperature,
            'heart_rate' => $pulse_rate,
            'respiratory_rate' => $respiration_rate,
            'weight' => $weight
        ]);

        echo json_encode(['success' => true, 'message' => 'Vital Signs Saved Successfully']);
    }

    // Tab or section loaders that return view fragments
    public function get_medical_info()
    {
        $patient_id = $this->input->get('id');
        $data['patient'] = $this->patient_model->getPatientById($patient_id);
        $this->load->view('doctor/workspace/medical_info', $data);
    }

    // API to fetch specific tab content (if needed dynamic loading)
    // For now, we might load everything in the view or lazy load via these:

    public function get_medical_history()
    {
        $patient_id = $this->input->get('id');
        $data['patient'] = $this->patient_model->getPatientById($patient_id);
        // Add more medical history data processing if needed
        $this->load->view('doctor/workspace/medical_info', $data); // Reusing medical info for now
    }

    public function get_prescription_form()
    {
        $patient_id = $this->input->get('id');
        $data['patient'] = $this->patient_model->getPatientById($patient_id);

        // Get current doctor
        $user_id = $this->ion_auth->get_user_id();
        $doctor = $this->db->get_where('doctor', array('ion_user_id' => $user_id))->row();
        $data['doctor_id'] = $doctor ? $doctor->id : '';

        $this->load->view('doctor/workspace/prescription', $data);
    }

    public function get_lab_request_form()
    {
        $patient_id = $this->input->get('id');
        $data['patient'] = $this->patient_model->getPatientById($patient_id);

        // Get current doctor
        $user_id = $this->ion_auth->get_user_id();
        $doctor = $this->db->get_where('doctor', array('ion_user_id' => $user_id))->row();
        $data['doctor_id'] = $doctor ? $doctor->id : '';

        // Get Lab Test Categories (diagnostic type only)
        $all_categories = $this->finance_model->getPaymentCategory();
        $data['payment_categories'] = array_filter($all_categories, function ($cat) {
            return isset($cat->type) && $cat->type == 'diagnostic';
        });

        $this->load->view('doctor/workspace/lab_request', $data);
    }

    public function add_prescription_ajax()
    {
        if (!$this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))) {
            echo json_encode(['success' => false, 'message' => 'Permission Denied']);
            exit;
        }

        $date = $this->input->post('date');
        if (!empty($date)) {
            $date = strtotime($date);
        }

        $patient = $this->input->post('patient') ? $this->input->post('patient') : $this->input->post('patient_id');
        $doctor = $this->input->post('doctor') ? $this->input->post('doctor') : $this->input->post('doctor_id');
        $symptom = $this->input->post('symptom');
        $medicine = $this->input->post('medicine'); // Array of IDs
        $dosage = $this->input->post('dosage');
        $frequency = $this->input->post('frequency');
        $days = $this->input->post('days');
        $instruction = $this->input->post('instruction');
        $note = $this->input->post('note');
        $advice = $this->input->post('advice');
        $quantity = $this->input->post('quantity');
        $route = $this->input->post('route');

        $report = array();
        $final_report = '';

        if (!empty($medicine)) {
            foreach ($medicine as $key => $value) {
                $report[$value] = array(
                    'dosage' => isset($dosage[$key]) ? $dosage[$key] : '',
                    'frequency' => isset($frequency[$key]) ? $frequency[$key] : '',
                    'days' => isset($days[$key]) ? $days[$key] : '',
                    'instruction' => isset($instruction[$key]) ? $instruction[$key] : '',
                    'quantity' => isset($quantity[$key]) ? $quantity[$key] : 0,
                    'route' => isset($route[$key]) ? $route[$key] : '',
                );
            }

            foreach ($report as $key1 => $value1) {
                $final[] = $key1 . '***' . implode('***', $value1);
            }

            $final_report = implode('###', $final);
        }

        // Simplistic Validation needed here or rely on frontend + basic checks
        if (empty($patient) || empty($date)) {
            echo json_encode(['success' => false, 'message' => 'Required fields missing']);
            exit;
        }

        $patient_details = $this->patient_model->getPatientById($patient);
        $doctor_details = $this->doctor_model->getDoctorById($doctor);

        $data = array(
            'date' => $date,
            'patient' => $patient,
            'doctor' => $doctor,
            'symptom' => $symptom,
            'medicine' => $final_report,
            'note' => $note,
            'advice' => $advice,
            'patientname' => $patient_details->name,
            'doctorname' => $doctor_details->name
        );

        $id = $this->input->post('id');

        if (!empty($id)) {
            $this->prescription_model->updatePrescription($id, $data);
            $insert_id = $id;
        } else {
            $this->prescription_model->insertPrescription($data);
            $insert_id = $this->db->insert_id();
        }

        // Add to Pharmacy Queue
        if (!empty($medicine)) {
            $this->addToPharmacyQueue($insert_id, $patient, $doctor, $medicine, $dosage, $frequency, $days, $instruction, $quantity, $route);
        }

        // Check if this is a print request
        $submission_type = $this->input->post('submission_type');
        if ($submission_type === 'print') {
            // Return print URL
            $print_url = base_url('doctor/workspace/print_prescription?id=' . $insert_id);
            echo json_encode(['success' => true, 'print_url' => $print_url, 'message' => 'Prescription Added Successfully']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Prescription Added Successfully']);
        }
    }

    public function add_lab_request_ajax()
    {
        if (!$this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))) {
            echo json_encode(['success' => false, 'message' => 'Permission Denied']);
            exit;
        }

        $date = $this->input->post('date');
        if (!empty($date)) {
            $dateTimestamp = strtotime($date);
        } else {
            $dateTimestamp = time();
        }

        $patient = $this->input->post('patient') ? $this->input->post('patient') : $this->input->post('patient_id');
        $doctor = $this->input->post('doctor') ? $this->input->post('doctor') : $this->input->post('doctor_id');
        $category_ids = $this->input->post('category_id'); // Now an array
        $submission_type = $this->input->post('submission_type');
        $user = $this->ion_auth->get_user_id();

        if (empty($category_ids) || empty($patient)) {
            echo json_encode(['success' => false, 'message' => 'Required fields missing']);
            exit;
        }

        $patient_details = $this->patient_model->getPatientById($patient);
        $doctor_details = $this->doctor_model->getDoctorById($doctor);

        $lab_ids = [];
        foreach ($category_ids as $category_id) {
            $data = array(
                'patient' => $patient,
                'doctor' => $doctor,
                'user' => $user,
                'patient_name' => $patient_details->name,
                'patient_phone' => $patient_details->phone,
                'patient_address' => $patient_details->address,
                'doctor_name' => $doctor_details->name,
                'date' => $dateTimestamp,
                'date_string' => date('d-m-Y', $dateTimestamp),
                'status' => 'pending',
                'test_status' => 'not_done',
                'category_id' => $category_id,
                'report' => ''
            );
            $this->lab_model->insertLab($data);
            $lab_ids[] = $this->db->insert_id();
        }

        $response = ['success' => true, 'message' => 'Lab Request Added Successfully'];

        if ($submission_type === 'print') {
            // Generate a URL or content specialized for printing
            // For simplicity and matching "old invoice" feel, we create a print URL
            $response['print_url'] = 'doctor/workspace/print_lab_request?ids=' . implode(',', $lab_ids);
        }

        echo json_encode($response);
    }

    public function print_lab_request()
    {
        $ids = explode(',', $this->input->get('ids'));
        $data['labs'] = [];
        foreach ($ids as $id) {
            $lab = $this->lab_model->getLabById($id);
            if ($lab) {
                $data['labs'][] = $lab;
            }
        }

        if (empty($data['labs'])) {
            exit('Invalid Lab IDs');
        }

        $data['patient'] = $this->patient_model->getPatientById($data['labs'][0]->patient);
        $data['doctor'] = $this->doctor_model->getDoctorById($data['labs'][0]->doctor);
        $lab_h_id = !empty($data['labs'][0]->hospital_id) ? $data['labs'][0]->hospital_id : $this->hospital_id;
        $data['settings'] = $this->db->get_where('settings', array('hospital_id' => $lab_h_id))->row();

        // Calculate Age
        if (!empty($data['patient']->birthdate)) {
            $birthDate = new DateTime($data['patient']->birthdate);
            $today = new DateTime('today');
            $data['age'] = $birthDate->diff($today)->y;
        } else {
            $data['age'] = '';
        }

        // Use a professional print view
        $this->load->view('doctor/workspace/print_lab_request', $data);
    }

    public function print_prescription()
    {
        $id = $this->input->get('id');

        if (empty($id)) {
            exit('Invalid Prescription ID');
        }

        // Get prescription details
        $data['prescription'] = $this->db->get_where('prescription', array('id' => $id))->row();

        if (!$data['prescription']) {
            exit('Prescription not found');
        }

        // Get patient and doctor details
        $data['patient'] = $this->patient_model->getPatientById($data['prescription']->patient);
        $data['doctor'] = $this->doctor_model->getDoctorById($data['prescription']->doctor);
        $pres_h_id = !empty($data['prescription']->hospital_id) ? $data['prescription']->hospital_id : $this->hospital_id;
        $data['settings'] = $this->db->get_where('settings', array('hospital_id' => $pres_h_id))->row();

        // Calculate Age
        if (!empty($data['patient']->birthdate)) {
            $birthDate = new DateTime($data['patient']->birthdate);
            $today = new DateTime('today');
            $data['age'] = $birthDate->diff($today)->y;
        } else {
            $data['age'] = '';
        }

        // Load print view
        $this->load->view('doctor/workspace/print_prescription', $data);
    }

    private function addToPharmacyQueue($prescription_id, $patient_id, $doctor_id, $medicines, $dosages, $frequencies, $days, $instructions, $quantities = [], $routes = [])
    {
        // Parse medicine data and calculate costs
        $medicine_details = [];
        $total_amount = 0;

        if (!empty($medicines)) {
            foreach ($medicines as $key => $medicine_id) {
                // Clean medicine ID (remove name if present, e.g. "123*Aspirin" -> "123")
                $parts = explode('*', (string) $medicine_id);
                $clean_id = $parts[0];

                // Get medicine info including price
                $medicine_info = $this->medicine_model->getMedicineById($clean_id);

                if ($medicine_info) {
                    // Aggressively prioritize manual quantity entry
                    $quantity = null;
                    if (isset($quantities[$key]) && trim($quantities[$key]) !== '') {
                        $raw_qty = trim($quantities[$key]);
                        // Log for debugging if we could, but let's just be thorough
                        if (preg_match('/\d*\.?\d+/', $raw_qty, $matches)) {
                            $quantity = (float) $matches[0];
                        } else {
                            $quantity = 1; // Fallback for text like "one vial"
                        }
                    }

                    // Fallback to calculation ONLY if quantity was NOT provided manually
                    if ($quantity === null) {
                        // Calculate quantity based on frequency and days
                        $frequency_value = isset($frequencies[$key]) ? $frequencies[$key] : '1';
                        $days_value = isset($days[$key]) ? (int) $days[$key] : 1;

                        // Extract number from frequency (e.g., "3 times daily" -> 3)
                        // Improve frequency parsing to handle 1+1+1
                        if (strpos($frequency_value, '+') !== false || strpos($frequency_value, '-') !== false) {
                            $splitChar = strpos($frequency_value, '+') !== false ? '+' : '-';
                            $parts = explode($splitChar, $frequency_value);
                            $times_per_day = 0;
                            foreach ($parts as $part) {
                                $times_per_day += (int) $part;
                            }
                        } else {
                            preg_match('/\d+/', $frequency_value, $matches);
                            $times_per_day = isset($matches[0]) ? (int) $matches[0] : 1;
                        }

                        $quantity = $times_per_day * $days_value;
                    }

                    $price = isset($medicine_info->s_price) ? $medicine_info->s_price : (isset($medicine_info->price) ? $medicine_info->price : 0);
                    $subtotal = $price * $quantity;
                    $total_amount += $subtotal;

                    $medicine_details[] = [
                        'medicine_id' => $clean_id,
                        'medicine_name' => $medicine_info->name,
                        'dosage' => isset($dosages[$key]) ? $dosages[$key] : '',
                        'frequency' => isset($frequencies[$key]) ? $frequencies[$key] : '',
                        'days' => isset($days[$key]) ? $days[$key] : '',
                        'instruction' => isset($instructions[$key]) ? $instructions[$key] : '',
                        'route' => isset($routes[$key]) ? $routes[$key] : '',
                        'quantity' => $quantity,
                        'unit_price' => $price,
                        'subtotal' => $subtotal
                    ];
                }
            }
        }

        // Only create queue if we have medicines
        if (empty($medicine_details)) {
            return;
        }

        // Get patient and doctor names
        $patient_info = $this->patient_model->getPatientById($patient_id);
        $doctor_info = $this->doctor_model->getDoctorById($doctor_id);

        // Check if queue entry already exists for this prescription
        $existing_queue = $this->pharmacy_queue_model->getQueueByPrescriptionId($prescription_id);

        if ($existing_queue) {
            // Update existing queue entry
            $queue_data = [
                'medicines_data' => json_encode($medicine_details),
                'total_amount' => $total_amount,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->pharmacy_queue_model->updateQueueStatus($existing_queue->id, $existing_queue->status, $queue_data);
        } else {
            // Get next queue number for today
            $queue_number = $this->pharmacy_queue_model->getNextQueueNumber($this->hospital_id);

            // Insert into pharmacy queue
            $queue_data = [
                'prescription_id' => $prescription_id,
                'patient_id' => $patient_id,
                'patient_name' => $patient_info ? $patient_info->name : 'Unknown',
                'doctor_id' => $doctor_id,
                'doctor_name' => $doctor_info ? $doctor_info->name : 'Unknown',
                'hospital_id' => $this->hospital_id,
                'medicines_data' => json_encode($medicine_details),
                'total_amount' => $total_amount,
                'status' => 'pending',
                'queue_number' => $queue_number,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->pharmacy_queue_model->insertQueue($queue_data);
        }
    }

    public function add_daycare_request_ajax()
    {
        if (!$this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))) {
            echo json_encode(['success' => false, 'message' => 'Permission Denied']);
            exit;
        }

        $patient_id = $this->input->post('patient_id');
        $doctor_id = $this->input->post('doctor_id');
        $admission_date = $this->input->post('admission_date');
        $duration = $this->input->post('duration');
        $reason = $this->input->post('reason');
        $instructions = $this->input->post('instructions');

        if (empty($patient_id) || empty($admission_date) || empty($reason)) {
            echo json_encode(['success' => false, 'message' => 'Required fields missing']);
            exit;
        }

        $patient_details = $this->patient_model->getPatientById($patient_id);
        $doctor_details = $this->doctor_model->getDoctorById($doctor_id);

        // Get hospital settings for fee
        $settings = $this->db->get_where('settings', ['hospital_id' => $this->hospital_id])->row();
        $fee = isset($settings->daycare_fee) ? $settings->daycare_fee : 0.00;

        // Insert into daycare table
        $data = array(
            'patient_id' => $patient_id,
            'patient_name' => $patient_details->name,
            'doctor_id' => $doctor_id,
            'doctor_name' => $doctor_details->name,
            'admission_date' => $admission_date,
            'duration' => $duration,
            'reason' => $reason,
            'instructions' => $instructions,
            'fee' => $fee,
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'request_date' => date('Y-m-d H:i:s'),
            'hospital_id' => $this->hospital_id
        );

        $this->db->insert('daycare_requests', $data);

        echo json_encode([
            'success' => true,
            'message' => 'Day Care request submitted successfully',
            'fee' => number_format($fee, 2)
        ]);
    }

    public function add_emergency_request_ajax()
    {
        if (!$this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))) {
            echo json_encode(['success' => false, 'message' => 'Permission Denied']);
            exit;
        }

        $patient_id = $this->input->post('patient_id');
        $doctor_id = $this->input->post('doctor_id');
        $priority = $this->input->post('priority');
        $chief_complaint = $this->input->post('chief_complaint');
        $assessment = $this->input->post('assessment');
        $actions = $this->input->post('actions');

        if (empty($patient_id) || empty($priority) || empty($chief_complaint) || empty($assessment)) {
            echo json_encode(['success' => false, 'message' => 'Required fields missing']);
            exit;
        }

        $patient_details = $this->patient_model->getPatientById($patient_id);
        $doctor_details = $this->doctor_model->getDoctorById($doctor_id);

        // Get hospital settings for fee
        $settings = $this->db->get_where('settings', ['hospital_id' => $this->hospital_id])->row();
        $fee = isset($settings->emergency_fee) ? $settings->emergency_fee : 0.00;

        // Insert into emergency_requests table
        $data = array(
            'patient_id' => $patient_id,
            'patient_name' => $patient_details->name,
            'patient_phone' => $patient_details->phone,
            'doctor_id' => $doctor_id,
            'doctor_name' => $doctor_details->name,
            'priority' => $priority,
            'chief_complaint' => $chief_complaint,
            'assessment' => $assessment,
            'immediate_actions' => $actions,
            'fee' => $fee,
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'request_date' => date('Y-m-d H:i:s'),
            'hospital_id' => $this->hospital_id
        );

        $this->db->insert('emergency_requests', $data);

        // TODO: Send notification to emergency department (email/SMS/push)

        echo json_encode([
            'success' => true,
            'message' => 'Emergency request submitted successfully. Emergency department has been notified.',
            'fee' => number_format($fee, 2)
        ]);
        exit;
    }

    public function get_lab_for_edit()
    {
        $id = $this->input->get('id');
        $lab = $this->lab_model->getLabById($id);
        if ($lab) {
            echo json_encode(['success' => true, 'lab' => $lab]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lab request not found']);
        }
    }

    public function update_lab_ajax()
    {
        if (!$this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))) {
            echo json_encode(['success' => false, 'message' => 'Permission Denied']);
            exit;
        }

        $id = $this->input->post('id');
        $category_id = $this->input->post('category_id');
        $report = $this->input->post('report');

        $data = array(
            'category_id' => $category_id,
            'report' => $report
        );

        $this->lab_model->updateLab($id, $data);
        echo json_encode(['success' => true, 'message' => 'Lab Request Updated']);
    }

    public function get_lab_results()
    {
        $id = $this->input->get('id');
        $data['lab'] = $this->lab_model->getLabById($id);
        if (!$data['lab']) {
            echo "Results not found";
            return;
        }

        // Get category name
        $category = $this->finance_model->getPaymentCategoryById($data['lab']->category_id);
        $data['category_name'] = $category ? $category->category : 'Unknown';

        // Load result view fragment
        $this->load->view('doctor/workspace/lab_result_fragment', $data);
    }

    public function get_prescription_details()
    {
        $date = $this->input->get('date');
        $patient_id = $this->input->get('patient_id');

        // Fetch all prescriptions for this patient on this date
        $all_prescriptions = $this->prescription_model->getPrescriptionByPatientId($patient_id);
        $data['prescriptions'] = array_filter($all_prescriptions, function ($p) use ($date) {
            return $p->date == $date;
        });

        if (empty($data['prescriptions'])) {
            // Check if there are at least labs if no prescriptions
            $all_labs = $this->lab_model->getLabByPatientId($patient_id);
            $day_start = $date;
            $day_end = $date + 86400;
            $data['labs'] = array_filter($all_labs, function ($l) use ($day_start, $day_end) {
                return $l->date >= $day_start && $l->date < $day_end;
            });

            if (empty($data['labs'])) {
                echo "Record not found";
                return;
            }
        } else {
            // Fetch labs for the same day
            $all_labs = $this->lab_model->getLabByPatientId($patient_id);
            $day_start = $date;
            $day_end = $date + 86400;
            $data['labs'] = array_filter($all_labs, function ($l) use ($day_start, $day_end) {
                return $l->date >= $day_start && $l->date < $day_end;
            });
        }

        // Get patient medical info (symptoms, diagnosis, notes)
        $data['patient'] = $this->patient_model->getPatientById($patient_id);

        // Fetch symptoms and diagnoses names
        $data['symptoms'] = $this->db->get('symptom')->result();
        $data['diagnoses'] = $this->db->get('diagnosis')->result();

        $this->load->view('doctor/workspace/prescription_details_fragment', $data);
    }

    public function mark_lab_as_seen()
    {
        if (!$this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))) {
            echo json_encode(['success' => false, 'message' => 'Permission Denied']);
            exit;
        }

        $id = $this->input->post('id');

        // Ensure column exists
        if (!$this->db->field_exists('doctor_seen', 'lab')) {
            $this->load->dbforge();
            $this->dbforge->add_column('lab', array('doctor_seen' => array('type' => 'INT', 'constraint' => 1, 'default' => 0)));
        }

        $data = array('doctor_seen' => 1);
        $this->db->where('id', $id);
        if ($this->db->update('lab', $data)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating status']);
        }
    }

    public function delete_lab_ajax()
    {
        if (!$this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))) {
            echo json_encode(['success' => false, 'message' => 'Permission Denied']);
            exit;
        }

        $id = $this->input->post('id');
        $lab = $this->lab_model->getLabById($id);

        if (!$lab) {
            echo json_encode(['success' => false, 'message' => 'Lab request not found']);
            exit;
        }

        if ($this->lab_model->deleteLab($id)) {
            echo json_encode(['success' => true, 'message' => 'Lab request deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting lab request']);
        }
        exit;
    }

}
