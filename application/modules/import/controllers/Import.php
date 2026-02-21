<?php

class Import extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('Excel');
        $this->load->model('import_model');
        $this->load->model('lab/lab_model');
        $this->load->model('finance/finance_model');
        $this->load->helper('file');
        $this->load->helper('toastr');

        if (!$this->ion_auth->in_group(array('admin'))) {
            redirect('home/permission');
        }
    }

    function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } else {
            $this->load->view('home/dashboard');
            $this->load->view('import');
            $this->load->view('home/footer');
        }
    }



    function importPatientInfo()
    {
        if (isset($_FILES["filename"]["name"])) {
            $path = $_FILES["filename"]["tmp_name"];
            $tablename = $this->input->post('tablename');
            $this->importPatient($path, $tablename);
        }
    }

    function importDoctorInfo()
    {
        if (isset($_FILES["filename"]["name"])) {
            $path = $_FILES["filename"]["tmp_name"];
            $tablename = $this->input->post('tablename');

            $this->importDoctor($path, $tablename);
        }
    }

    function importMedicineInfo()
    {
        if (isset($_FILES["filename"]["name"])) {
            $path = $_FILES["filename"]["tmp_name"];
            $tablename = $this->input->post('tablename');

            $this->importMedicine($path, $tablename);
        }
    }

    function importPatient($file, $tablename)
    {
        $object = PHPExcel_IOFactory::load($file);
        foreach ($object->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow();    //get Highest Row
            $highestColumnLetter = $worksheet->getHighestColumn(); //get column highest as  letter
            $highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumnLetter); // convert letter to column index in number
            for ($column1 = 0; $column1 < $highestColumn; $column1++) {
                $rowData1[] = $worksheet->getCellByColumnAndRow($column1, 1)->getValue();
            }


            $headerexist = $this->import_model->headerExist($rowData1, $tablename); // get boolean header exist or not


            if ($headerexist) {
                for ($row = 2; $row <= $highestRow; $row++) {
                    $rowData = [];
                    $rowData2 = [];

                    for ($column = 0; $column < $highestColumn; $column++) {
                        if (strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue()) === 'password') {
                            $rowData3[] = $worksheet->getCellByColumnAndRow($column, 1)->getValue();
                        } else {
                            $rowData2[] = $worksheet->getCellByColumnAndRow($column, 1)->getValue();
                        }

                        if (strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue()) != 'password') {
                            $rowData[] = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                        }
                        if (strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue()) === 'name') {
                            $name = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                        }
                        if (strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue()) === 'phone') {
                            $phone = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                        }
                        if (strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue()) === 'password') {

                            $password = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                        }
                        if (strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue()) === 'email') {

                            $email = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                        }
                    }



                    if ($this->ion_auth->email_check($email)) {
                        $exist_email[] = $row;
                        $exist_rows = implode(',', $exist_email);
                        $message = 'Rows number ' . $exist_rows . ' contain the emails which already exist!';
                    } else {
                        $dfg = 5;
                        $username = $name;

                        $this->ion_auth->register($username, $password, $email, $dfg);

                        $ionid = $this->db->get_where('users', array('email' => $email))->row()->id;
                        $hospital_id = $this->session->userdata('hospital_id');

                        // Generate Local Hospital Patient Number
                        $this->db->select_max('hospital_patient_id');
                        $this->db->where('hospital_id', $hospital_id);
                        $query = $this->db->get('patient');
                        $max_id_row = $query->row();
                        $max_id = $max_id_row ? $max_id_row->hospital_patient_id : 0;
                        $next_local_id = (int) $max_id + 1;

                        // Generate Global Medical Record Number (MRN)
                        $prefix = 'MRN-' . date('Ymd') . '-';
                        $this->db->select_max('medical_record_number');
                        $this->db->like('medical_record_number', $prefix, 'after');
                        $query = $this->db->get('patient');
                        $max_mrn_row = $query->row();
                        $max_mrn = $max_mrn_row ? $max_mrn_row->medical_record_number : null;

                        if (!empty($max_mrn)) {
                            $parts = explode('-', $max_mrn);
                            $last_number = (int) end($parts);
                            $next_number = $last_number + 1;
                        } else {
                            $next_number = 1;
                        }
                        $mrn = $prefix . str_pad($next_number, 5, '0', STR_PAD_LEFT);

                        array_push($rowData, $ionid);
                        array_push($rowData2, 'ion_user_id');
                        array_push($rowData, date('d/m/y'));
                        array_push($rowData2, 'add_date');
                        array_push($rowData, time());
                        array_push($rowData2, 'registration_time');
                        array_push($rowData, rand(10000, 1000000));
                        array_push($rowData2, 'patient_id');
                        array_push($rowData, $hospital_id);
                        array_push($rowData2, 'hospital_id');
                        array_push($rowData, $next_local_id);
                        array_push($rowData2, 'hospital_patient_id');
                        array_push($rowData, $mrn);
                        array_push($rowData2, 'medical_record_number');
                        $data = array_combine($rowData2, $rowData);
                        $this->import_model->dataEntry($data, $tablename);
                    }
                }
                show_swal(lang('successful_data_import'), 'success');
                if ($message != '') {
                    show_swal($message, 'warning');
                }
            } else {
                show_swal(lang('wrong_file_format'), 'error');
            }
        }


        redirect('import');
    }

    function importDoctor($file, $tablename)
    {
        $object = PHPExcel_IOFactory::load($file);
        foreach ($object->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow();    //get Highest Row
            $highestColumnLetter = $worksheet->getHighestColumn(); //get column highest as  letter
            $highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumnLetter); // convert letter to column index in number
            for ($column1 = 0; $column1 < $highestColumn; $column1++) {
                $rowData1[] = $worksheet->getCellByColumnAndRow($column1, 1)->getValue();
            }


            $headerexist = $this->import_model->headerExist($rowData1, $tablename); // get boolean header exist or not


            if ($headerexist) {
                for ($row = 2; $row <= $highestRow; $row++) {
                    $rowData = [];
                    $rowData2 = [];

                    for ($column = 0; $column < $highestColumn; $column++) {
                        if (strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue()) === 'password') {
                            $rowData3[] = $worksheet->getCellByColumnAndRow($column, 1)->getValue();
                        } else {
                            $rowData2[] = $worksheet->getCellByColumnAndRow($column, 1)->getValue();
                        }

                        if (strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue()) != 'password') {
                            $rowData[] = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                        }
                        if (strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue()) === 'name') {
                            $name = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                        }
                        if (strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue()) === 'phone') {
                            $phone = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                        }
                        if (strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue()) === 'password') {

                            $password = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                        }
                        if (strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue()) === 'email') {

                            $email = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                        }
                    }



                    if ($this->ion_auth->email_check($email)) {
                        $exist_email[] = $row;
                        $exist_rows = implode(',', $exist_email);
                        $message1 = 'Rows number ' . $exist_rows . ' contain the emails which already exist!';
                    } else {
                        $dfg = 4;
                        $username = $name;

                        $this->ion_auth->register($username, $password, $email, $dfg);

                        $ionid = $this->db->get_where('users', array('email' => $email))->row()->id;

                        array_push($rowData, $ionid);
                        array_push($rowData2, 'ion_user_id');
                        array_push($rowData, $this->session->userdata('hospital_id'));
                        array_push($rowData2, 'hospital_id');
                        $data = array_combine($rowData2, $rowData);
                        $this->import_model->dataEntry($data, $tablename);
                    }
                }

                show_swal(lang('successful_data_import'), 'success');
                if ($message1 != '') {
                    show_swal($message1, 'warning');
                }
            } else {
                show_swal(lang('wrong_file_format'), 'error');
            }
        }


        redirect('import');
    }

    function importMedicine($file, $tablename)
    {
        $this->load->model('medicine/medicine_model');
        $object = PHPExcel_IOFactory::load($file);
        $message2 = '';
        foreach ($object->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow();    //get Highest Row
            $highestColumnLetter = $worksheet->getHighestColumn(); //get column highest as  letter
            $highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumnLetter); // convert letter to column index in number
            for ($column1 = 0; $column1 < $highestColumn; $column1++) {
                $rowData1[] = $worksheet->getCellByColumnAndRow($column1, 1)->getValue();
            }


            $headerexist = $this->import_model->headerExist($rowData1, $tablename); // get boolean header exist or not


            if ($headerexist) {
                for ($row = 2; $row <= $highestRow; $row++) {
                    $rowData = [];
                    $rowData2 = [];

                    for ($column = 0; $column < $highestColumn; $column++) {

                        $header_col = strtolower($worksheet->getCellByColumnAndRow($column, 1)->getValue());
                        $rowData2[] = $worksheet->getCellByColumnAndRow($column, 1)->getValue();

                        $cell = $worksheet->getCellByColumnAndRow($column, $row);
                        $val = $cell->getValue();

                        if ($header_col === 'e_date' || $header_col === 'expiry_date') {
                            if (PHPExcel_Shared_Date::isDateTime($cell)) {
                                $val = date('d/m/Y', PHPExcel_Shared_Date::ExcelToPHP($val));
                            }
                        }

                        $rowData[] = $val;

                        if ($header_col === 'name') {
                            $name = $val;
                        }
                    }

                    $medicinename = $this->db->get_where('medicine', array('name =' => $name))->row();

                    if (!empty($medicinename)) {
                        $exist_name[] = $row;
                        $exist_rows = implode(',', $exist_name);
                        $message2 .= 'Rows number ' . $exist_rows . ' contain the medicine which already exist!';
                    } else {
                        array_push($rowData, date('d/m/y'));
                        array_push($rowData2, 'add_date');
                        array_push($rowData, $this->session->userdata('hospital_id'));
                        array_push($rowData2, 'hospital_id');

                        $data = array_combine($rowData2, $rowData);

                        $this->medicine_model->insertMedicine($data);
                    }
                }
                show_swal(lang('successful_data_import'), 'success');
                if ($message2 != '') {
                    show_swal($message2, 'warning');
                }
            } else {
                show_swal(lang('wrong_file_format'), 'error');
            }
        }


        redirect('import');
    }

    function importLabTemplateInfo()
    {
        if (isset($_FILES["filename"]["name"])) {
            $path = $_FILES["filename"]["tmp_name"];
            $tablename = $this->input->post('tablename');

            $this->importLabTemplate($path, $tablename);
        }
    }

    function importLabTemplate($file, $tablename)
    {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
        ini_set('display_errors', 1);
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $debug_log = APPPATH . 'logs/import_debug.log';
        file_put_contents($debug_log, "Starting Lab Template Import at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

        try {
            $this->load->library('Excel');
            file_put_contents($debug_log, "Excel library loaded\n", FILE_APPEND);

            // Force CSV reader to avoid misidentification as HTML which triggers deprecation errors
            $reader = PHPExcel_IOFactory::createReader('CSV');
            $object = $reader->load($file);
            file_put_contents($debug_log, "File loaded via PHPExcel\n", FILE_APPEND);

            $user = $this->ion_auth->get_user_id();
            $hospital_id = $this->session->userdata('hospital_id');
            file_put_contents($debug_log, "User: $user, Hospital: $hospital_id\n", FILE_APPEND);

            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumnLetter = $worksheet->getHighestColumn();
                $highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumnLetter);

                $headers = [];
                for ($column = 0; $column < $highestColumn; $column++) {
                    $header_val = $worksheet->getCellByColumnAndRow($column, 1)->getValue();
                    $headers[] = strtolower(trim($header_val));
                }

                for ($row = 2; $row <= $highestRow; $row++) {
                    $name = '';
                    $category_name = '';
                    $template_content = '';

                    for ($column = 0; $column < $highestColumn; $column++) {
                        $val = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                        if (isset($headers[$column])) {
                            if ($headers[$column] == 'name') {
                                $name = $val;
                            } elseif ($headers[$column] == 'category') {
                                $category_name = $val;
                            } elseif ($headers[$column] == 'template') {
                                $template_content = $val;
                            }
                        }
                    }

                    if (!empty($name) && !empty($category_name)) {
                        $category = $this->db->get_where('lab_category', array('category' => $category_name, 'hospital_id' => $hospital_id))->row();
                        if (empty($category)) {
                            $cat_data = array(
                                'category' => $category_name,
                                'hospital_id' => $hospital_id,
                                'description' => $category_name,
                                'reference_value' => ''
                            );
                            $this->db->insert('lab_category', $cat_data);
                            $category_id = $this->db->insert_id();
                        } else {
                            $category_id = $category->id;
                        }

                        $data = array(
                            'name' => $name,
                            'template' => $template_content,
                            'user' => $user,
                            'category_id' => $category_id,
                            'hospital_id' => $hospital_id
                        );
                        $this->import_model->dataEntry($data, $tablename);
                    }
                }
            }
            $this->session->set_flashdata('feedback', lang('successful_data_import'));
            file_put_contents($debug_log, "Import completed successfully\n", FILE_APPEND);
            redirect('import');
        } catch (Exception $e) {
            file_put_contents($debug_log, "FATAL ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
            echo "A fatal error occurred during import: " . $e->getMessage();
            exit;
        }
    }

    public function importLabTestInfo()
    {
        $tablename = $this->input->post('tablename');
        if (!empty($_FILES['filename']['name'])) {
            $this->importLabTest($_FILES['filename']['tmp_name'], $tablename);
        }
        redirect('import');
    }

    function importLabTest($file, $tablename)
    {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
        $this->load->library('Excel');

        $reader = PHPExcel_IOFactory::createReader('CSV');
        $object = $reader->load($file);
        $hospital_id = $this->session->userdata('hospital_id');

        foreach ($object->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow();
            $highestColumnLetter = $worksheet->getHighestColumn();
            $highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumnLetter);

            $headers = [];
            for ($column = 0; $column < $highestColumn; $column++) {
                $header_val = $worksheet->getCellByColumnAndRow($column, 1)->getValue();
                $headers[] = strtolower(trim($header_val));
            }

            for ($row = 2; $row <= $highestRow; $row++) {
                $category = '';
                $description = '';
                $c_price = 0;
                $d_commission = 0;

                for ($column = 0; $column < $highestColumn; $column++) {
                    $val = $worksheet->getCellByColumnAndRow($column, $row)->getValue();
                    if (isset($headers[$column])) {
                        if ($headers[$column] == 'category') {
                            $category = $val;
                        } elseif ($headers[$column] == 'description') {
                            $description = $val;
                        } elseif ($headers[$column] == 'c_price') {
                            $c_price = $val;
                        } elseif ($headers[$column] == 'd_commission') {
                            $d_commission = $val;
                        }
                    }
                }

                if (!empty($category)) {
                    $data = array(
                        'category' => $category,
                        'description' => $description,
                        'type' => 'diagnostic',
                        'c_price' => !empty($c_price) ? $c_price : 0,
                        'd_commission' => !empty($d_commission) ? $d_commission : 0,
                        'hospital_id' => $hospital_id
                    );
                    $this->finance_model->insertPaymentCategory($data);
                }
            }
        }
        $this->session->set_flashdata('feedback', lang('successful_data_import'));
    }
}
