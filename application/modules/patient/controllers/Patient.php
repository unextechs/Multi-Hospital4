<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Patient extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('patient_model');
        $this->load->model('department/department_model');
        $this->load->model('doctor/doctor_model');
        $this->load->model('settings/settings_model');
        $this->load->module('sms');

        if (!$this->ion_auth->in_group(array('admin', 'Accountant', 'Doctor', 'Receptionist', 'Nurse', 'Laboratorist', 'Patient'))) {
            redirect('home/permission');
        }
    }

    public function index()
    {
        $data = array();
        $data['departments'] = $this->department_model->getDepartment();
        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['groups'] = $this->db->get('blood_group')->result();
        $this->load->view('home/dashboard');
        $this->load->view('patient', $data);
        $this->load->view('home/footer');
    }

    public function addNewView()
    {
        $data = array();
        $data['departments'] = $this->department_model->getDepartment();
        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['groups'] = $this->db->get('blood_group')->result();
        $this->load->view('home/dashboard');
        $this->load->view('add_new', $data);
        $this->load->view('home/footer');
    }

    public function addNew()
    {
        $id = $this->input->post('id');

        if (empty($id)) {
            $limit = $this->patient_model->getLimit();
            if ($limit <= 0) {
                show_swal(lang('patient_limit_exceed'), 'error', lang('error'));
                redirect('patient');
            }
        }

        $name = $this->input->post('name');
        $password = $this->input->post('password');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $sex = $this->input->post('sex');
        $birthdate = $this->input->post('birthdate');
        $age = $this->input->post('age');
        $bloodgroup = $this->input->post('bloodgroup');
        $doctor = $this->input->post('doctor');
        $national_id = $this->input->post('national_id');
        $height = $this->input->post('height');
        $weight = $this->input->post('weight');
        $known_allergies = $this->input->post('known_allergies');
        $medical_history = $this->input->post('medical_history');
        $emergency_contact_name = $this->input->post('emergency_contact_name');
        $emergency_contact_number = $this->input->post('emergency_contact_number');
        $sms = $this->input->post('sms');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        // Validating Name Field
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[1]|max_length[100]|xss_clean');

        // Validating Password Field (only for new patients)
        if (empty($id)) {
            if (!empty($password)) {
                $this->form_validation->set_rules('password', 'Password', 'trim|min_length[5]|max_length[100]|xss_clean');
            }
        }

        // Validating Email Field (optional)
        if (!empty($email)) {
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|min_length[5]|max_length[100]|xss_clean');
        }

        // Validating Phone Field
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|min_length[1]|max_length[50]|xss_clean');

        // Validating Gender Field
        $this->form_validation->set_rules('sex', 'Gender', 'trim|required|xss_clean');

        // Validating Address Field
        $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');

        // Validating Birthdate Field (required only if age not provided)
        if (empty($age)) {
            $this->form_validation->set_rules('birthdate', 'Date of Birth', 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == FALSE) {
            if (!empty($id)) {
                redirect("patient/editPatient?id=" . $id);
            } else {
                redirect('patient/addNewView');
            }
        } else {
            // Handle file upload
            $img_url = '';
            if (!empty($_FILES['img_url']['name'])) {
                $file_name = $_FILES['img_url']['name'];
                $file_name_pieces = explode('_', $file_name);
                $new_file_name = '';
                $count = 1;
                foreach ($file_name_pieces as $piece) {
                    if ($count !== 1) {
                        $piece = ucfirst($piece);
                    }
                    $new_file_name .= $piece;
                    $count++;
                }

                $config = array(
                    'file_name' => $new_file_name,
                    'upload_path' => "./uploads/",
                    'allowed_types' => "gif|jpg|png|jpeg",
                    'overwrite' => False,
                    'max_size' => "20480000",
                    'max_height' => "1768",
                    'max_width' => "2024"
                );

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('img_url')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];
                } else {
                    $error = $this->upload->display_errors('', '');
                    show_swal($error, 'error', lang('error'));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            }

            // If birthdate is empty but age is provided, compute birthdate from age
            if (empty($birthdate) && !empty($age)) {
                $age = intval($age);
                $birth_year = date('Y') - $age;
                $birthdate = $birth_year . '-01-01';
            }

            // Prepare data array
            $data = array(
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'phone' => $phone,
                'sex' => $sex,
                'birthdate' => $birthdate,
                'bloodgroup' => $bloodgroup,
                'doctor' => $doctor,
                'national_id' => $national_id,
                'height' => $height,
                'weight' => $weight,
                'known_allergies' => $known_allergies,
                'medical_history' => $medical_history,
                'emergency_contact_name' => $emergency_contact_name,
                'emergency_contact_number' => $emergency_contact_number,
                'add_date' => date('Y-m-d'),
                'registration_time' => date('H:i:s')
            );

            if (!empty($img_url)) {
                $data['img_url'] = $img_url;
            }

            if (empty($id)) {
                // Adding New Patient
                if (!empty($email) && $this->ion_auth->email_check($email)) {
                    show_swal(lang('this_email_address_is_already_registered'), 'warning', lang('warning'));
                    redirect('patient/addNewView');
                } else {
                    // Create patient record
                    $this->patient_model->insertPatient($data);

                    // Get the newly created patient ID
                    $patient_id = $this->db->insert_id();

                    // Create ion auth user if email and password are provided
                    if (!empty($email) && !empty($password)) {
                        $username = $name;
                        $dfg = 8; // Patient group ID
                        $this->ion_auth->register($username, $password, $email, $dfg);
                        $ion_user_id = $this->db->get_where('users', array('email' => $email))->row()->id;
                        $id_info = array('ion_user_id' => $ion_user_id);
                        $this->patient_model->updatePatient($patient_id, $id_info);
                        $this->hospital_model->addHospitalIdToIonUser($ion_user_id, $this->hospital_id);
                    }

                    // Send SMS if requested
                    if ($sms == 'sms') {
                        $set['settings'] = $this->settings_model->getSettings();
                        $autosms = $this->sms_model->getAutoSmsByType('patient');
                        if ($autosms->status == 'Active') {
                            $message = $autosms->message;
                            $to = $phone;
                            $name1 = explode(' ', $name);
                            if (!isset($name1[1])) {
                                $name1[1] = null;
                            }
                            $patient_info = $this->patient_model->getPatientById($patient_id);
                            $data1 = array(
                                'firstname' => $name1[0],
                                'lastname' => $name1[1],
                                'name' => $name,
                                'patient_id' => $patient_info->hospital_patient_id,
                                'company' => $set['settings']->system_vendor
                            );
                            $messageprint = $this->parser->parse_string($message, $data1);
                            $data2[] = array($to => $messageprint);
                            $this->sms->sendSms($to, $message, $data2);
                        }
                    }

                    show_swal(lang('new_patient_added_successfully'), 'success', lang('added'));
                    redirect('patient');
                }
            } else {
                // Updating Patient
                $patient_details = $this->patient_model->getPatientById($id);
                if (!empty($email) && $email != $patient_details->email) {
                    if ($this->ion_auth->email_check($email)) {
                        show_swal(lang('this_email_address_is_already_registered'), 'warning', lang('warning'));
                        redirect("patient/editPatient?id=" . $id);
                    }
                }

                // Update ion user if exists
                if (!empty($patient_details->ion_user_id)) {
                    $ion_user_id = $patient_details->ion_user_id;
                    $username = $name;
                    if (empty($password)) {
                        $password = $this->db->get_where('users', array('id' => $ion_user_id))->row()->password;
                    } else {
                        $password = $this->ion_auth_model->hash_password($password);
                    }
                    if (!empty($email)) {
                        $this->patient_model->updateIonUser($username, $email, $password, $ion_user_id);
                    }
                }

                $this->patient_model->updatePatient($id, $data);
                show_swal(lang('patient_details_updated'), 'success', lang('updated'));
                redirect('patient');
            }
        }
    }

    function editPatient()
    {
        $data = array();
        $id = $this->input->get('id');
        $data['patient'] = $this->patient_model->getPatientById($id);
        $data['departments'] = $this->department_model->getDepartment();
        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['groups'] = $this->db->get('blood_group')->result();
        $this->load->view('home/dashboard');
        $this->load->view('add_new', $data);
        $this->load->view('home/footer');
    }

    function editPatientByJason()
    {
        $id = $this->input->get('id');
        $data['patient'] = $this->patient_model->getPatientById($id);
        $data['doctor'] = null;
        if (!empty($data['patient']->doctor)) {
            $data['doctor'] = $this->doctor_model->getDoctorById($data['patient']->doctor);
        }

        // Calculate age from birthdate
        if (!empty($data['patient']->birthdate)) {
            $dateOfBirth = $data['patient']->birthdate;
            if (strtotime($dateOfBirth)) {
                $today = date("Y-m-d");
                $diff = date_diff(date_create($dateOfBirth), date_create($today));
                $age = $diff->format('%y') . '-' . $diff->format('%m') . '-' . $diff->format('%d');
                $data['age'] = $age;
                $data['age_years'] = $diff->format('%y');
            }
        }

        echo json_encode($data);
    }

    function getPatientById()
    {
        $this->getPatientByJason();
    }

    function getPatientByJason()
    {
        error_reporting(0);
        $id = $this->input->get('id');
        $data['patient'] = $this->patient_model->getPatientById($id);

        if (empty($data['patient'])) {
            echo json_encode(array());
            return;
        }

        $data['doctor'] = null;
        if (!empty($data['patient']->doctor)) {
            $data['doctor'] = $this->doctor_model->getDoctorById($data['patient']->doctor);
        }

        // Calculate age from birthdate
        if (!empty($data['patient']->birthdate)) {
            $dateOfBirth = $data['patient']->birthdate;
            if (strtotime($dateOfBirth)) {
                $today = date("Y-m-d");
                $diff = date_diff(date_create($dateOfBirth), date_create($today));
                $age = $diff->format('%y years %m months %d days');
                $data['age'] = $age;
                $data['age_years'] = $diff->format('%y');
            } else {
                $data['age'] = '';
                $data['age_years'] = '';
            }
        } else {
            $data['age'] = '';
            $data['age_years'] = '';
        }

        echo json_encode($data);
    }

    function delete()
    {
        if ($this->ion_auth->in_group(array('Patient'))) {
            redirect('home/permission');
        }

        $id = $this->input->get('id');
        $user_data = $this->db->get_where('patient', array('id' => $id))->row();

        // Delete image file
        if (!empty($user_data->img_url)) {
            $path = $user_data->img_url;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // Delete ion user if exists
        if (!empty($user_data->ion_user_id)) {
            $this->db->where('id', $user_data->ion_user_id);
            $this->db->delete('users');
        }

        $this->patient_model->delete($id);
        show_swal(lang('a_patient_deleted_from_the_list'), 'warning', lang('deleted'));
        redirect('patient');
    }

    function getPatient()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];

        $order = $this->input->post("order");
        $columns_valid = array(
            "0" => "hospital_patient_id",
            "1" => "name",
            "2" => "phone"
        );
        $values = $this->settings_model->getColumnOrder($order, $columns_valid);
        $dir = $values[0];
        $order_column = $values[1];

        if ($limit == -1) {
            if (!empty($search)) {
                $data['patients'] = $this->patient_model->getPatientBySearch($search, $order_column, $dir);
            } else {
                $data['patients'] = $this->patient_model->getPatientWithoutSearch($order_column, $dir);
            }
        } else {
            if (!empty($search)) {
                $data['patients'] = $this->patient_model->getPatientByLimitBySearch($limit, $start, $search, $order_column, $dir);
            } else {
                $data['patients'] = $this->patient_model->getPatientByLimit($limit, $start, $order_column, $dir);
            }
        }

        $i = 0;
        foreach ($data['patients'] as $patient) {
            $i = $i + 1;

            // Build action buttons
            $options1 = '<a type="button" class="btn btn-primary btn-sm editbutton mr-1" title="' . lang('edit') . '" data-toggle="modal" data-id="' . $patient->id . '"><i class="fa fa-edit"></i></a>';

            if ($this->ion_auth->in_group(array('Doctor', 'admin', 'superadmin'))) {
                // Doctor workspace trigger
                $options2 = '<a type="button" class="btn btn-info btn-sm open-doctor-workspace mr-1" title="' . lang('info') . '" data-toggle="modal" data-id="' . $patient->id . '"><i class="fa fa-info"></i></a>';
            } else {
                // Standard info modal
                $options2 = '<a type="button" class="btn btn-info btn-sm inffo mr-1" title="' . lang('info') . '" data-toggle="modal" data-id="' . $patient->id . '"><i class="fa fa-info"></i></a>';
            }
            $options3 = '<a class="btn btn-danger btn-sm delete_button mr-1" title="' . lang('delete') . '" href="patient/delete?id=' . $patient->id . '" onclick="return confirm(\'Are you sure you want to delete this patient?\');"><i class="fa fa-trash"></i></a>';

            // Calculate due balance
            $due_balance = 0;
            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {
                $due_balance = $this->patient_model->getDueBalanceByPatientId($patient->id);
            }

            // Format patient number with P prefix (e.g., P1, P2, P3)
            $settings = $this->settings_model->getSettings();
            $prefix = (!empty($settings->patient_id_prefix)) ? $settings->patient_id_prefix : 'P';
            $patient_number = $prefix . $patient->hospital_patient_id;

            $info_row = array(
                $patient_number,
                $patient->name,
                $patient->phone
            );

            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {
                $info_row[] = number_format($due_balance, 2);
            }

            $info_row[] = $options2 . ' ' . $options1 . ' ' . $options3;

            $info[] = $info_row;
        }

        if (!empty($data['patients'])) {
            $output = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => count($this->patient_model->getPatient()),
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

    public function getPatientInfo()
    {
        $searchTerm = $this->input->post('searchTerm');
        $response = $this->patient_model->getPatientInfo($searchTerm);
        echo json_encode($response);
    }

    public function getPatientInfoId()
    {
        $searchTerm = $this->input->post('searchTerm');
        $response = $this->patient_model->getPatientInfoId($searchTerm);
        echo json_encode($response);
    }


    public function getPatientinfoWithAddNewOption()
    {
        $searchTerm = $this->input->post('searchTerm');
        $response = $this->patient_model->getPatientinfoWithAddNewOption($searchTerm);
        echo json_encode($response);
    }

    public function getPatientPayments()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];

        $order = $this->input->post("order");
        $columns_valid = array(
            "0" => "hospital_patient_id",
            "1" => "name",
            "2" => "phone"
        );
        $values = $this->settings_model->getColumnOrder($order, $columns_valid);
        $dir = $values[0];
        $order_column = $values[1];

        if ($limit == -1) {
            if (!empty($search)) {
                $data['patients'] = $this->patient_model->getPatientBySearch($search, $order_column, $dir);
            } else {
                $data['patients'] = $this->patient_model->getPatientWithoutSearch($order_column, $dir);
            }
        } else {
            if (!empty($search)) {
                $data['patients'] = $this->patient_model->getPatientByLimitBySearch($limit, $start, $search, $order_column, $dir);
            } else {
                $data['patients'] = $this->patient_model->getPatientByLimit($limit, $start, $order_column, $dir);
            }
        }

        $i = 0;
        foreach ($data['patients'] as $patient) {
            $i = $i + 1;

            // Build action buttons
            $options1 = '<a type="button" class="btn btn-primary btn-sm editbutton mr-1" title="' . lang('edit') . '" data-toggle="modal" data-id="' . $patient->id . '"><i class="fa fa-edit"></i></a>';
            $options2 = '<a type="button" class="btn btn-info btn-sm inffo mr-1" title="' . lang('info') . '" data-toggle="modal" data-id="' . $patient->id . '"><i class="fa fa-info"></i></a>';
            // $options3 = '<a class="btn btn-danger btn-sm delete_button mr-1" title="' . lang('delete') . '" href="patient/delete?id=' . $patient->id . '" onclick="return confirm(\'Are you sure you want to delete this patient?\');"><i class="fa fa-trash"></i></a>';

            // Payment history button for patient payments page
            $options4 = '<a class="btn btn-success btn-sm custom_button mr-1" title="' . lang('payment') . ' ' . lang('history') . '" href="finance/patientPaymentHistory?patient=' . $patient->id . '"><i class="fa fa-money-bill-alt"></i> ' . lang('history') . '</a>';


            // Calculate due balance
            $due_balance = 0;
            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist', 'Laboratorist'))) {
                $due_balance = $this->patient_model->getDueBalanceByPatientId($patient->id);
            }

            // Format patient number with P prefix (e.g., P1, P2, P3)
            $settings = $this->settings_model->getSettings();
            $prefix = (!empty($settings->patient_id_prefix)) ? $settings->patient_id_prefix : 'P';
            $patient_number = $prefix . $patient->hospital_patient_id;

            $info_row = array(
                $patient_number,
                $patient->name,
                $patient->phone
            );

            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist', 'Laboratorist'))) {
                $info_row[] = number_format($due_balance, 2);
            }

            // For Patient Payments page, we might just want History + Info
            $info_row[] = $options4 . ' ' . $options2;

            $info[] = $info_row;
        }

        if (!empty($data['patients'])) {
            $output = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => count($this->patient_model->getPatient()),
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

    public function patientPayments()
    {
        $data = array();
        $data['departments'] = $this->department_model->getDepartment();
        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['groups'] = $this->db->get('blood_group')->result();
        $this->load->view('home/dashboard');
        $this->load->view('patient_payments', $data);
        $this->load->view('home/footer');
    }

    public function caseList()
    {
        $data = array();
        $data['patients'] = $this->patient_model->getPatient();
        $this->load->model('finance/finance_model');
        $data['payment_categories'] = $this->finance_model->getPaymentCategory();
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard');
        $this->load->view('case_list', $data);
        $this->load->view('home/footer');
    }

    public function documents()
    {
        $data = array();
        $data['patients'] = $this->patient_model->getPatient();
        $this->load->view('home/dashboard');
        $this->load->view('documents', $data);
        $this->load->view('home/footer');
    }

    /**
     * Add or update medical history / case record
     */
    public function addMedicalHistory()
    {
        $id = $this->input->post('id');
        $date = $this->input->post('date');
        $patient_id = $this->input->post('patient_id');
        $doctor_id = $this->input->post('doctor_id');
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $redirect = $this->input->post('redirect');

        // Get arrays for symptoms, tests, diagnosis, treatment, advice
        $symptom_ids = $this->input->post('symptom_id');
        $test_ids = $this->input->post('test_id');
        $diagnosis_ids = $this->input->post('diagnosis_id');
        $treatment_ids = $this->input->post('treatment_id');
        $advice_ids = $this->input->post('advice_id');

        // Convert date to timestamp
        if (!empty($date)) {
            $date_parts = explode('-', $date);
            if (count($date_parts) == 3) {
                $timestamp = strtotime($date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0]);
            } else {
                $timestamp = strtotime($date);
            }
        } else {
            $timestamp = time();
        }

        // Get names for the arrays
        $symptom_names = array();
        if (!empty($symptom_ids) && is_array($symptom_ids)) {
            foreach ($symptom_ids as $symptom_id) {
                $symptom = $this->db->get_where('symptom', array('id' => $symptom_id))->row();
                if ($symptom) {
                    $symptom_names[] = $symptom->name;
                }
            }
        }

        $test_names = array();
        if (!empty($test_ids) && is_array($test_ids)) {
            $this->load->model('lab/lab_model');
            foreach ($test_ids as $test_id) {
                $test = $this->lab_model->getLabCategoryById($test_id);
                if ($test) {
                    $test_names[] = $test->category;
                }
            }
        }

        $diagnosis_names = array();
        if (!empty($diagnosis_ids) && is_array($diagnosis_ids)) {
            foreach ($diagnosis_ids as $diagnosis_id) {
                $diagnosis = $this->db->get_where('disease', array('id' => $diagnosis_id))->row();
                if ($diagnosis) {
                    $diagnosis_names[] = $diagnosis->name;
                }
            }
        }

        $treatment_names = array();
        if (!empty($treatment_ids) && is_array($treatment_ids)) {
            foreach ($treatment_ids as $treatment_id) {
                $treatment = $this->db->get_where('treatment', array('id' => $treatment_id))->row();
                if ($treatment) {
                    $treatment_names[] = $treatment->name;
                }
            }
        }

        $advice_names = array();
        if (!empty($advice_ids) && is_array($advice_ids)) {
            foreach ($advice_ids as $advice_id) {
                $advice = $this->db->get_where('advice', array('id' => $advice_id))->row();
                if ($advice) {
                    $advice_names[] = $advice->name;
                }
            }
        }

        // Prepare data array
        $data = array(
            'date' => $timestamp,
            'patient_id' => $patient_id,
            'doctor_id' => $doctor_id,
            'title' => $title,
            'description' => $description,
            'symptom_id' => !empty($symptom_ids) ? implode(',', $symptom_ids) : '',
            'test_id' => !empty($test_ids) ? implode(',', $test_ids) : '',
            'diagnosis_id' => !empty($diagnosis_ids) ? implode(',', $diagnosis_ids) : '',
            'treatment_id' => !empty($treatment_ids) ? implode(',', $treatment_ids) : '',
            'advice_id' => !empty($advice_ids) ? implode(',', $advice_ids) : '',
            'hospital_id' => $this->session->userdata('hospital_id')
        );

        if (empty($id)) {
            // Insert new medical history
            $this->patient_model->insertMedicalHistory($data);
            $medical_history_id = $this->db->insert_id();

            // Create lab requests for selected tests
            if (!empty($test_ids) && is_array($test_ids)) {
                $this->load->model('lab/lab_model');
                $this->load->model('finance/finance_model');

                foreach ($test_ids as $test_id) {
                    $test = $this->lab_model->getLabCategoryById($test_id);
                    if ($test) {
                        $lab_data = array(
                            'patient' => $patient_id,
                            'doctor' => $doctor_id,
                            'date' => time(),
                            'category_id' => $test_id,
                            'test_status' => 'pending',
                            'invoice_id' => 0,
                            'hospital_id' => $this->session->userdata('hospital_id')
                        );
                        $this->lab_model->insertLab($lab_data);
                    }
                }
            }

            show_swal(lang('case') . ' ' . lang('added_successfully'), 'success', lang('added'));
        } else {
            // Update existing medical history
            $this->patient_model->updateMedicalHistory($id, $data);
            show_swal(lang('case') . ' ' . lang('updated_successfully'), 'success', lang('updated'));
        }

        // Redirect
        if (!empty($redirect)) {
            redirect($redirect);
        } else {
            redirect('patient/caseList');
        }
    }

    public function getCaseList()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];

        if ($limit == -1) {
            if (!empty($search)) {
                $data['cases'] = $this->patient_model->getMedicalHistoryBySearch($search);
            } else {
                $data['cases'] = $this->patient_model->getMedicalHistory();
            }
        } else {
            if (!empty($search)) {
                $data['cases'] = $this->patient_model->getMedicalHistoryByLimitBySearch($limit, $start, $search);
            } else {
                $data['cases'] = $this->patient_model->getMedicalHistoryByLimit($limit, $start);
            }
        }

        //  $data['cases'] = $this->patient_model->getMedicalHistory();

        $info = array();
        $i = 0;
        foreach ($data['cases'] as $case) {
            $i = $i + 1;
            $options1 = '';
            $options2 = '';
            $options4 = '';
            $options6 = '';
            // $options1 = '<a type="button" class="btn btn-info btn-xs btn_width editbutton" title="' . lang('edit') . '" data-toggle="modal" data-id="' . $case->id . '"><i class="fa fa-edit"> </i> ' . lang('edit') . '</a>';
            // $options2 = '<a class="btn btn-info btn-xs btn_width delete_button" title="' . lang('delete') . '" href="patient/deleteMedicalHistory?id=' . $case->id . '&redirect=patient/caseList" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i> ' . lang('delete') . '</a>';

            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist', 'Laboratorist', 'Nurse', 'Doctor'))) {
                $options1 = '<a type="button" class="btn btn-info btn-xs btn_width editbutton" title="' . lang('edit') . '" data-toggle="modal" data-id="' . $case->id . '"><i class="fa fa-edit"> </i> ' . lang('edit') . '</a>';
            }
            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist', 'Laboratorist', 'Nurse', 'Doctor'))) {
                $options2 = '<a class="btn btn-info btn-xs btn_width delete_button" title="' . lang('delete') . '" href="patient/deleteMedicalHistory?id=' . $case->id . '&redirect=patient/caseList" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i> ' . lang('delete') . '</a>';
            }

            $options4 = '<a type="button" class="btn btn-info btn-xs btn_width case" title="' . lang('case') . '" data-toggle="modal" data-id="' . $case->id . '"><i class="fa fa-file"> </i> ' . lang('case') . '</a>';
            $options5 = '<a class="btn btn-info btn-xs btn_width" title="' . lang('add_lab_request') . '" href="lab/addLabView?patient=' . $case->patient_id . '&doctor=' . $case->doctor_id . '&view=request"><i class="fas fa-flask"> </i> ' . lang('add_lab_request') . '</a>';

            $patient_details = $this->patient_model->getPatientById($case->patient_id);
            if (!empty($patient_details)) {
                $patient_name = $patient_details->name;
                $settings = $this->settings_model->getSettings();
                $prefix = (!empty($settings->patient_id_prefix)) ? $settings->patient_id_prefix : 'P';
                $patient_id = $prefix . $patient_details->hospital_patient_id;
            } else {
                $patient_name = $case->patient_name;
                $patient_id = '';
            }

            $info[] = array(
                date('d-m-Y', $case->date),
                $patient_name . " - " . $patient_id,
                $case->title,
                $options1 . ' ' . $options2 . ' ' . $options4 . ' ' . $options5,
            );
        }

        if (!empty($data['cases'])) {
            $output = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => $this->db->get('medical_history')->num_rows(),
                "recordsFiltered" => $i,
                "data" => $info
            );
        } else {
            $output = array(
                // "draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }

        echo json_encode($output);
    }

    public function getDocuments()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];
        $order = $this->input->post("order");

        if ($limit == -1) {
            if (!empty($search)) {
                $data['documents'] = $this->patient_model->getDocumentBySearch($search, $order, null);
            } else {
                $data['documents'] = $this->patient_model->getPatientMaterialWithoutSearch($order, null);
            }
        } else {
            if (!empty($search)) {
                $data['documents'] = $this->patient_model->getDocumentByLimitBySearch($limit, $start, $search, $order, null);
            } else {
                $data['documents'] = $this->patient_model->getDocumentByLimit($limit, $start, $order, null);
            }
        }

        //  $data['documents'] = $this->patient_model->getPatientMaterial();

        foreach ($data['documents'] as $document) {
            $options2 = '';
            $options3 = '';
            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist', 'Laboratorist', 'Nurse', 'Doctor'))) {
                $options2 = '<a class="btn btn-info btn-xs btn_width delete_button" title="' . lang('delete') . '" href="patient/deletePatientMaterial?id=' . $document->id . '&redirect=patient/documents" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i> ' . lang('delete') . '</a>';
                $options3 = '<a class="btn btn-info btn-xs btn_width" title="' . lang('download') . '" href="' . $document->url . '" download><i class="fa fa-download"> </i> ' . lang('download') . '</a>';
            }

            $patient_details = $this->patient_model->getPatientById($document->patient);
            if (!empty($patient_details)) {
                $patient_name = $patient_details->name;
                $settings = $this->settings_model->getSettings();
                $prefix = (!empty($settings->patient_id_prefix)) ? $settings->patient_id_prefix : 'P';
                $patient_id = $prefix . $patient_details->hospital_patient_id;
            } else {
                $patient_name = $document->patient_name;
                $patient_id = $document->patient;
            }

            $info[] = array(
                date('d-m-Y', $document->date),
                $patient_name . " - " . $patient_id,
                $document->title,
                '<a href="' . $document->url . '" target="_blank">' . '<img src="' . $document->url . '" width="100px" height="100px">' . '</a>',
                $options3 . ' ' . $options2,
                //  $options2
            );
        }

        if (!empty($data['documents'])) {
            $output = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => $this->db->get('patient_material')->num_rows(),
                "recordsFiltered" => $this->db->get('patient_material')->num_rows(),
                "data" => $info
            );
        } else {
            $output = array(
                // "draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }

        echo json_encode($output);
    }

    public function editMedicalHistoryByJason()
    {
        $id = $this->input->get('id');
        $data['medical_history'] = $this->patient_model->getMedicalHistoryById($id);
        $data['patient'] = $this->patient_model->getPatientById($data['medical_history']->patient_id);

        $data['doctor'] = null;
        if (!empty($data['medical_history']->doctor_id)) {
            $data['doctor'] = $this->doctor_model->getDoctorById($data['medical_history']->doctor_id);
        }

        $data['diagnosis'] = array();
        if (!empty($data['medical_history']->diagnosis_id)) {
            $ids = explode(',', $data['medical_history']->diagnosis_id);
            foreach ($ids as $element_id) {
                $element = $this->db->get_where('disease', array('id' => $element_id))->row();
                if ($element) {
                    $data['diagnosis'][] = array('id' => $element->id, 'text' => $element->name);
                }
            }
        }

        $data['treatment'] = array();
        if (!empty($data['medical_history']->treatment_id)) {
            $ids = explode(',', $data['medical_history']->treatment_id);
            foreach ($ids as $element_id) {
                $element = $this->db->get_where('treatment', array('id' => $element_id))->row();
                if ($element) {
                    $data['treatment'][] = array('id' => $element->id, 'text' => $element->name);
                }
            }
        }

        $data['symptom'] = array();
        if (!empty($data['medical_history']->symptom_id)) {
            $ids = explode(',', $data['medical_history']->symptom_id);
            foreach ($ids as $element_id) {
                $element = $this->db->get_where('symptom', array('id' => $element_id))->row();
                if ($element) {
                    $data['symptom'][] = array('id' => $element->id, 'text' => $element->name);
                }
            }
        }

        $data['test'] = array();
        if (!empty($data['medical_history']->test_id)) {
            $ids = explode(',', $data['medical_history']->test_id);
            $this->load->model('lab/lab_model');
            foreach ($ids as $element_id) {
                $element = $this->lab_model->getLabCategoryById($element_id);
                if ($element) {
                    $data['test'][] = array('id' => $element->id, 'text' => $element->category);
                }
            }
        }

        $data['advice'] = array();
        if (!empty($data['medical_history']->advice_id)) {
            $ids = explode(',', $data['medical_history']->advice_id);
            foreach ($ids as $element_id) {
                $element = $this->db->get_where('advice', array('id' => $element_id))->row();
                if ($element) {
                    $data['advice'][] = array('id' => $element->id, 'text' => $element->name);
                }
            }
        }

        echo json_encode($data);
    }

    public function getCaseDetailsByJason()
    {
        $id = $this->input->get('id');
        $data['case'] = $this->patient_model->getMedicalHistoryById($id);
        $data['patient'] = $this->patient_model->getPatientById($data['case']->patient_id);
        $data['doctor'] = $this->doctor_model->getDoctorById($data['case']->doctor_id);

        // Fetch Full History for this patient
        $all_history = $this->patient_model->getMedicalHistoryByPatientId($data['case']->patient_id);

        // Format full history for easier JS processing
        $data['all_history'] = array();
        if (!empty($all_history)) {
            foreach ($all_history as $key => $h) {
                $h->formatted_date = date('d M, Y', $h->date);
                $data['all_history'][] = $h;
            }
        }

        $data['diagnosis'] = array();
        if (!empty($data['case']->diagnosis_id)) {
            $ids = explode(',', $data['case']->diagnosis_id);
            foreach ($ids as $element_id) {
                $element = $this->db->get_where('disease', array('id' => $element_id))->row();
                if ($element) {
                    $data['diagnosis'][] = $element->name;
                }
            }
        }

        $data['treatment'] = array();
        if (!empty($data['case']->treatment_id)) {
            $ids = explode(',', $data['case']->treatment_id);
            foreach ($ids as $element_id) {
                $element = $this->db->get_where('treatment', array('id' => $element_id))->row();
                if ($element) {
                    $data['treatment'][] = $element->name;
                }
            }
        }

        $data['symptom'] = array();
        if (!empty($data['case']->symptom_id)) {
            $ids = explode(',', $data['case']->symptom_id);
            foreach ($ids as $element_id) {
                $element = $this->db->get_where('symptom', array('id' => $element_id))->row();
                if ($element) {
                    $data['symptom'][] = $element->name;
                }
            }
        }

        $data['test'] = array();
        if (!empty($data['case']->test_id)) {
            $ids = explode(',', $data['case']->test_id);
            $this->load->model('lab/lab_model');
            foreach ($ids as $element_id) {
                $element = $this->db->get_where('payment_category', array('id' => $element_id))->row();
                if ($element) {
                    $data['test'][] = $element->category;
                }
            }
        }

        $data['advice'] = array();
        if (!empty($data['case']->advice_id)) {
            $ids = explode(',', $data['case']->advice_id);
            foreach ($ids as $element_id) {
                $element = $this->db->get_where('advice', array('id' => $element_id))->row();
                if ($element) {
                    $data['advice'][] = $element->name;
                }
            }
        }

        // Compute patient age
        $data['patient_age'] = '';
        if (!empty($data['patient']->birthdate) && strtotime($data['patient']->birthdate)) {
            $diff = date_diff(date_create($data['patient']->birthdate), date_create('today'));
            $data['patient_age'] = $diff->format('%y');
        }

        // Fetch prescriptions for this patient
        $data['prescriptions'] = array();
        $this->load->model('prescription/prescription_model');
        $prescriptions = $this->prescription_model->getPrescriptionByPatientId($data['case']->patient_id);
        if (!empty($prescriptions)) {
            foreach ($prescriptions as $rx) {
                $rx_item = new stdClass();
                $rx_item->id = $rx->id;
                $rx_item->date = date('d M, Y', strtotime($rx->date));
                // Get prescription medicines
                $medicines = $this->db->get_where('prescription_details', array('prescription_id' => $rx->id))->result();
                $rx_item->medicines = array();
                if (!empty($medicines)) {
                    foreach ($medicines as $med) {
                        $medicine = $this->db->get_where('medicine', array('id' => $med->medicine_id))->row();
                        $med_item = new stdClass();
                        $med_item->name = !empty($medicine) ? $medicine->name : 'Unknown';
                        $med_item->dosage = !empty($med->dosage) ? $med->dosage : '';
                        $med_item->frequency = !empty($med->frequency) ? $med->frequency : '';
                        $med_item->duration = !empty($med->days) ? $med->days : '';
                        $med_item->instruction = !empty($med->instruction) ? $med->instruction : '';
                        $rx_item->medicines[] = $med_item;
                    }
                }
                $data['prescriptions'][] = $rx_item;
            }
        }

        // Fetch lab reports for this patient
        $data['lab_reports'] = array();
        $this->load->model('lab/lab_model');
        $labs = $this->lab_model->getLabByPatientId($data['case']->patient_id);
        if (!empty($labs)) {
            foreach ($labs as $lab) {
                $lab_item = new stdClass();
                $lab_item->id = $lab->id;
                $lab_item->date = !empty($lab->date) ? date('d M, Y', strtotime($lab->date)) : '';
                $lab_item->report = !empty($lab->report) ? $lab->report : '';
                $lab_item->status = !empty($lab->status) ? $lab->status : '';
                // Get test name from payment_category
                $test_name = '';
                if (!empty($lab->category_id)) {
                    $cat = $this->db->get_where('payment_category', array('id' => $lab->category_id))->row();
                    $test_name = !empty($cat) ? $cat->category : '';
                }
                $lab_item->test_name = $test_name;
                $data['lab_reports'][] = $lab_item;
            }
        }

        echo json_encode($data);
    }
    public function getMedicalHistoryByjason()
    {
        $id = $this->input->get('id');
        $data['patient'] = $this->patient_model->getPatientById($id);
        $this->load->model('appointment/appointment_model');
        $this->load->model('doctor/doctor_model');
        $this->load->model('doctor/doctorvisit_model');
        $this->load->model('finance/finance_model');
        $this->load->model('lab/lab_model');
        $this->load->model('prescription/prescription_model');
        $this->load->model('bed/bed_model');

        $data['appointments'] = $this->appointment_model->getAppointmentByPatient($id);

        // Fetch Odontogram if enabled
        if ($this->settings->show_odontogram_in_history == 'yes') {
            $data['odontogram'] = $this->patient_model->getOdontogramByPatientId($id);
        }

        $data['settings'] = $this->settings_model->getSettings();
        $data['redirect_tab'] = '';

        // Load modules list for the view
        $data['modules'] = $this->modules;

        // Since the view uses $this->modules, we usually don't need to pass it if it's a property of the controller, 
        // but passing it in data ensures it's available as $modules if the view uses $modules variable.
        // However, the view uses $this->modules or in_array('...', $this->modules). 
        // CodeIgniter views have access to controller instance via $this.

        $data['is_modal'] = true;
        $view = $this->load->view('patient/medical_history', $data, true);
        echo json_encode(array('view' => $view));
    }

    public function get_followup_status_ajax()
    {
        $id = $this->input->get('id');
        $patient = $this->patient_model->getPatientById($id);
        if (!$patient) {
            echo json_encode(['success' => false, 'message' => 'Patient not found']);
            exit;
        }

        $settings = $this->settings_model->getSettings();
        $followup_period = !empty($settings->followup_period) ? $settings->followup_period : 15;

        // Get last visit date (use prescription date as it represents a consultation/visit)
        $this->db->where('patient', $id);
        $this->db->order_by('date', 'desc');
        $this->db->limit(1);
        $prescription = $this->db->get('prescription')->row();

        if ($prescription) {
            $last_visit_timestamp = $prescription->date;
            $current_timestamp = time();
            $diff_seconds = $current_timestamp - $last_visit_timestamp;
            $diff_days = floor($diff_seconds / (60 * 60 * 24));

            if ($diff_days < $followup_period) {
                echo json_encode([
                    'success' => true,
                    'status' => 'followup',
                    'days_since' => $diff_days,
                    'followup_period' => $followup_period,
                    'last_visit' => date('d M Y', $last_visit_timestamp)
                ]);
                exit;
            }
        }

        echo json_encode(['success' => true, 'status' => 'new_appointment', 'followup_period' => $followup_period]);
        exit;
    }
}

/* End of file Patient.php */
/* Location: ./application/modules/patient/controllers/Patient.php */
