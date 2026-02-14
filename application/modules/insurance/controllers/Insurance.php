<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Insurance extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('insurance_model');

        // Allow admin and Accountant roles to manage insurance
        if (!$this->ion_auth->in_group(array('admin', 'superadmin', 'Accountant'))) {
            redirect('home/permission');
        }
    }

    public function index()
    {
        $data['insurance_companys'] = $this->insurance_model->getInsurance();
        $this->load->view('home/dashboard');
        $this->load->view('insurance_company', $data);
        $this->load->view('home/footer');
    }

    public function addNewView()
    {
        $this->load->view('home/dashboard');
        $this->load->view('add_insurance_company');
        $this->load->view('home/footer');
    }

    public function addNew()
    {
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $description = $this->input->post('description');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        // Validating Name Field
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[100]|xss_clean');
        // Validating Password Field    
        // Validating Email Field
        $this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[2]|max_length[1000]|xss_clean');
        // Validating Address Field   
        if ($this->form_validation->run() == FALSE) {
            if (!empty($id)) {
                $data = array();
                $data['insurance'] = $this->insurance_model->getInsuranceById($id);
                $this->load->view('home/dashboard');
                $this->load->view('add_insurance_company', $data);
                $this->load->view('home/footer');
            } else {
                $data['setval'] = 'setval';
                $this->load->view('home/dashboard');
                $this->load->view('add_insurance_company', $data);
                $this->load->view('home/footer');
            }
        } else {

            $data = array();
            $data = array(
                'name' => $name,
                'description' => $description
            );
            if (empty($id)) {     // Adding New insurance
                $this->insurance_model->insertInsurance($data);
                $this->session->set_flashdata('feedback', lang('added'));
            } else { // Updating insurance
                $this->insurance_model->updateInsurance($id, $data);
                $this->session->set_flashdata('feedback', lang('updated'));
            }
            // Loading View
            redirect('insurance');
        }
    }

    function getInsurance()
    {
        $data['insurances'] = $this->insurance_model->getInsurance();
        $this->load->view('insurance', $data);
    }

    function editInsurance()
    {
        $data = array();
        $id = $this->input->get('id');
        $data['insurance'] = $this->insurance_model->getInsuranceById($id);
        $this->load->view('home/dashboard');
        $this->load->view('add_insurance_company', $data);
        $this->load->view('home/footer');
    }

    function editInsuranceByJason()
    {
        $id = $this->input->get('id');
        $data['insurance'] = $this->insurance_model->getInsuranceById($id);
        echo json_encode($data);
    }

    function delete()
    {
        $id = $this->input->get('id');
        $this->insurance_model->delete($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('insurance');
    }




    function getInsuranceCompany()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];

        $order = $this->input->post("order");
        $columns_valid = array(
            "0" => "id",
            "1" => "name",
            "2" => "description",

        );
        $values = $this->settings_model->getColumnOrder($order, $columns_valid);
        $dir = $values[0];
        $order = $values[1];

        if ($limit == -1) {
            if (!empty($search)) {
                $data['insurance_companys'] = $this->insurance_model->getInsuranceCompanyBySearch($search, $order, $dir);
            } else {
                $data['insurance_companys'] = $this->insurance_model->getInsuranceCompanyWithoutSearch($order, $dir);
            }
        } else {
            if (!empty($search)) {
                $data['insurance_companys'] = $this->insurance_model->getInsuranceCompanyByLimitBySearch($limit, $start, $search, $order, $dir);
            } else {
                $data['insurance_companys'] = $this->insurance_model->getInsuranceCompanyByLimit($limit, $start, $order, $dir);
            }
        }


        $i = 0;
        foreach ($data['insurance_companys'] as $insurance_company) {
            $i = $i + 1;

            $options1 = '<a type="button" class="btn btn-primary btn-sm editbutton" title="' . lang('edit') . '" data-toggle="modal" data-id="' . $insurance_company->id . '"><i class="fa fa-edit"> </i> ' . lang('') . '</a>';


            $options3 = '<a class="btn btn-danger btn-sm" title="' . lang('delete') . '" href="insurance/delete?id=' . $insurance_company->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i> ' . lang('') . '</a>';


            $info[] = array(

                $insurance_company->name,
                $insurance_company->description,


                $options1 . ' ' . $options3,
            );
        }

        if (!empty($data['insurance_companys'])) {
            $output = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => count($this->insurance_model->getInsurance()),
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

    // ==================== CLAIMS MANAGEMENT ====================

    public function claims()
    {
        $this->load->model('settings/settings_model');
        $data['settings'] = $this->settings_model->getSettings();
        $data['claims'] = $this->insurance_model->getClaims();
        $data['insurance_companys'] = $this->insurance_model->getInsurance();
        $data['stats'] = $this->insurance_model->getClaimsStats();
        $this->load->view('home/dashboard');
        $this->load->view('insurance_claims', $data);
        $this->load->view('home/footer');
    }

    public function addClaimView()
    {
        $this->load->model('patient/patient_model');
        $this->load->model('settings/settings_model');
        $this->load->model('finance/finance_model');

        $data['settings'] = $this->settings_model->getSettings();
        $data['patients'] = $this->patient_model->getPatient();
        $data['insurance_companys'] = $this->insurance_model->getInsurance();

        // Get invoice if passed
        $invoice_id = $this->input->get('invoice_id');
        if ($invoice_id) {
            $data['invoice'] = $this->finance_model->getPaymentById($invoice_id);
        }

        $this->load->view('home/dashboard');
        $this->load->view('add_insurance_claim', $data);
        $this->load->view('home/footer');
    }

    public function addClaim()
    {
        $claim_number = $this->insurance_model->generateClaimNumber();

        $data = array(
            'hospital_id' => $this->session->userdata('hospital_id'),
            'claim_number' => $claim_number,
            'patient_id' => $this->input->post('patient_id'),
            'patient_name' => $this->input->post('patient_name'),
            'insurance_company_id' => $this->input->post('insurance_company_id'),
            'insurance_company_name' => $this->input->post('insurance_company_name'),
            'policy_number' => $this->input->post('policy_number'),
            'invoice_id' => $this->input->post('invoice_id'),
            'claim_amount' => $this->input->post('claim_amount'),
            'service_date' => $this->input->post('service_date'),
            'diagnosis_codes' => $this->input->post('diagnosis_codes'),
            'procedure_codes' => $this->input->post('procedure_codes'),
            'claim_items' => $this->input->post('claim_items'),
            'notes' => $this->input->post('notes'),
            'status' => 'draft',
            'submitted_by' => $this->ion_auth->get_user_id()
        );

        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->insurance_model->updateClaim($id, $data);
            $this->session->set_flashdata('feedback', 'Claim updated');
        } else {
            $this->insurance_model->insertClaim($data);
            $this->session->set_flashdata('feedback', 'Claim created');
        }

        redirect('insurance/claims');
    }

    public function editClaim()
    {
        $id = $this->input->get('id');
        $this->load->model('patient/patient_model');
        $this->load->model('settings/settings_model');

        $data['claim'] = $this->insurance_model->getClaimById($id);
        $data['settings'] = $this->settings_model->getSettings();
        $data['patients'] = $this->patient_model->getPatient();
        $data['insurance_companys'] = $this->insurance_model->getInsurance();

        $this->load->view('home/dashboard');
        $this->load->view('add_insurance_claim', $data);
        $this->load->view('home/footer');
    }

    public function deleteClaim()
    {
        $id = $this->input->get('id');
        $this->insurance_model->deleteClaim($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('insurance/claims');
    }

    public function submitClaim()
    {
        $id = $this->input->get('id');
        $this->insurance_model->updateClaim($id, array(
            'status' => 'submitted',
            'submission_date' => date('Y-m-d H:i:s')
        ));
        $this->session->set_flashdata('feedback', 'Claim submitted');
        redirect('insurance/claims');
    }

    public function processClaim()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $approved_amount = $this->input->post('approved_amount');
        $rejection_reason = $this->input->post('rejection_reason');

        $data = array(
            'status' => $status,
            'approved_by' => $this->ion_auth->get_user_id()
        );

        if ($status == 'approved') {
            $data['approved_amount'] = $approved_amount;
            $data['approval_date'] = date('Y-m-d H:i:s');
        } elseif ($status == 'rejected') {
            $data['rejection_reason'] = $rejection_reason;
        } elseif ($status == 'paid') {
            $data['payment_date'] = date('Y-m-d H:i:s');
        }

        $this->insurance_model->updateClaim($id, $data);
        $this->session->set_flashdata('feedback', 'Claim ' . $status);
        redirect('insurance/claims');
    }

    public function viewClaim()
    {
        $id = $this->input->get('id');
        $this->load->model('settings/settings_model');

        $data['claim'] = $this->insurance_model->getClaimById($id);
        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard');
        $this->load->view('insurance_claim_detail', $data);
        $this->load->view('home/footer');
    }

    // ==================== PATIENT INSURANCE ====================

    public function patientInsurance()
    {
        $this->load->model('settings/settings_model');
        $data['settings'] = $this->settings_model->getSettings();
        $data['patient_insurances'] = $this->insurance_model->getPatientInsurances();
        $data['insurance_companys'] = $this->insurance_model->getInsurance();
        $this->load->view('home/dashboard');
        $this->load->view('patient_insurance', $data);
        $this->load->view('home/footer');
    }

    public function patient_insurance()
    {
        $this->patientInsurance();
    }

    public function addPatientInsuranceView()
    {
        $this->load->model('patient/patient_model');
        $this->load->model('settings/settings_model');

        $data['settings'] = $this->settings_model->getSettings();
        $data['patients'] = $this->patient_model->getPatient();
        $data['insurance_companys'] = $this->insurance_model->getInsurance();

        $this->load->view('home/dashboard');
        $this->load->view('add_patient_insurance', $data);
        $this->load->view('home/footer');
    }

    public function addPatientInsurance()
    {
        $data = array(
            'hospital_id' => $this->session->userdata('hospital_id'),
            'patient_id' => $this->input->post('patient_id'),
            'insurance_company_id' => $this->input->post('insurance_company_id'),
            'policy_number' => $this->input->post('policy_number'),
            'group_number' => $this->input->post('group_number'),
            'subscriber_id' => $this->input->post('subscriber_id'),
            'subscriber_name' => $this->input->post('subscriber_name'),
            'subscriber_relationship' => $this->input->post('subscriber_relationship'),
            'coverage_type' => $this->input->post('coverage_type'),
            'coverage_percent' => $this->input->post('coverage_percent'),
            'copay_amount' => $this->input->post('copay_amount'),
            'deductible_amount' => $this->input->post('deductible_amount'),
            'effective_date' => $this->input->post('effective_date'),
            'expiry_date' => $this->input->post('expiry_date'),
            'notes' => $this->input->post('notes'),
            'status' => 'active'
        );

        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->insurance_model->updatePatientInsurance($id, $data);
            $this->session->set_flashdata('feedback', lang('updated'));
        } else {
            $this->insurance_model->insertPatientInsurance($data);
            $this->session->set_flashdata('feedback', lang('added'));
        }

        redirect('insurance/patientInsurance');
    }

    public function editPatientInsurance()
    {
        $id = $this->input->get('id');
        $this->load->model('patient/patient_model');
        $this->load->model('settings/settings_model');

        $data['patient_insurance'] = $this->insurance_model->getPatientInsuranceById($id);
        $data['settings'] = $this->settings_model->getSettings();
        $data['patients'] = $this->patient_model->getPatient();
        $data['insurance_companys'] = $this->insurance_model->getInsurance();

        $this->load->view('home/dashboard');
        $this->load->view('add_patient_insurance', $data);
        $this->load->view('home/footer');
    }

    public function deletePatientInsurance()
    {
        $id = $this->input->get('id');
        $this->insurance_model->deletePatientInsurance($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('insurance/patientInsurance');
    }

    public function verifyCoverage()
    {
        $patient_id = $this->input->get('patient_id');
        $insurances = $this->insurance_model->getPatientInsurancesByPatient($patient_id);

        $result = array();
        foreach ($insurances as $ins) {
            $is_valid = ($ins->status == 'active' &&
                ($ins->expiry_date == null || strtotime($ins->expiry_date) >= time()));

            $result[] = array(
                'id' => $ins->id,
                'company_name' => $ins->insurance_company_name,
                'policy_number' => $ins->policy_number,
                'coverage_percent' => $ins->coverage_percent,
                'is_valid' => $is_valid,
                'expiry_date' => $ins->expiry_date
            );
        }

        echo json_encode(array('success' => true, 'insurances' => $result));
    }

    public function verifyPatientInsurance()
    {
        $id = $this->input->get('id');
        $this->insurance_model->updatePatientInsurance($id, array(
            'is_verified' => 1,
            'verification_date' => date('Y-m-d H:i:s'),
            'verified_by' => $this->ion_auth->get_user_id()
        ));
        $this->session->set_flashdata('feedback', 'Insurance verified');
        redirect('insurance/patientInsurance');
    }
}

/* End of file insurance.php */
/* Location: ./application/modules/insurance/controllers/insurance.php */

