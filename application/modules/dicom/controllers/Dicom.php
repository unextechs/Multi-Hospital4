<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dicom extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('dicom_model');
        $this->load->model('settings/settings_model');
        $this->load->model('patient/patient_model');

        // Restricted to superadmin and admin
        if (!$this->ion_auth->in_group(array('superadmin', 'admin', 'Radiologist'))) {
            redirect('home/permission');
        }
    }

    public function index()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['files'] = $this->dicom_model->getDicomFiles();
        $data['nodes'] = $this->dicom_model->getNodes();
        $data['stats'] = $this->dicom_model->getStats();
        $this->load->view('home/dashboard');
        $this->load->view('dicom_view', $data);
        $this->load->view('home/footer');
    }

    public function upload()
    {
        $patient_id = $this->input->post('patient_id');
        $study_date = $this->input->post('study_date');
        $modality = $this->input->post('modality');
        $description = $this->input->post('description');

        $file_name = $_FILES['img_url']['name'];
        $config['upload_path'] = 'uploads/dicom/';
        $config['allowed_types'] = 'dcm|dcm30|dicom|zip';
        $config['max_size'] = 500000; // 500MB
        $config['file_name'] = date('YmdHis') . '_' . str_replace(' ', '_', $file_name);

        // Create upload directory if not exists
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('Upload', $config);

        if (!$this->upload->do_upload('img_url')) {
            $this->session->set_flashdata('feedback', $this->upload->display_errors());
        } else {
            $upload_data = $this->upload->data();

            $data = array(
                'hospital_id' => $this->session->userdata('hospital_id'),
                'patient_id' => $patient_id,
                'file_path' => $config['upload_path'] . $upload_data['file_name'],
                'file_name' => $upload_data['file_name'],
                'file_size' => $upload_data['file_size'],
                'modality' => $modality,
                'study_date' => $study_date,
                'description' => $description,
                'status' => 'uploaded'
            );

            $this->dicom_model->insertDicom($data);
            $this->session->set_flashdata('feedback', lang('added'));
        }

        redirect('dicom');
    }

    public function delete()
    {
        $id = $this->input->get('id');
        $file = $this->dicom_model->getDicomById($id);

        if ($file && file_exists($file->file_path)) {
            unlink($file->file_path);
        }

        $this->dicom_model->deleteDicom($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('dicom');
    }

    // ==================== DICOM NODES ====================

    public function nodes()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['nodes'] = $this->dicom_model->getNodes();
        $this->load->view('home/dashboard');
        $this->load->view('dicom_nodes', $data);
        $this->load->view('home/footer');
    }

    public function addNodeView()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard');
        $this->load->view('add_dicom_node', $data);
        $this->load->view('home/footer');
    }

    public function addNode()
    {
        $data = array(
            'hospital_id' => $this->session->userdata('hospital_id'),
            'name' => $this->input->post('name'),
            'ae_title' => strtoupper($this->input->post('ae_title')),
            'host' => $this->input->post('host'),
            'port' => $this->input->post('port') ? $this->input->post('port') : 104,
            'node_type' => $this->input->post('node_type') ? $this->input->post('node_type') : 'SCU',
            'is_default' => $this->input->post('is_default') ? 1 : 0,
            'status' => 'active'
        );

        // If setting as default, clear other defaults
        if ($data['is_default']) {
            $this->dicom_model->clearDefaultNodes();
        }

        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->dicom_model->updateNode($id, $data);
            $this->session->set_flashdata('feedback', lang('updated'));
        } else {
            $this->dicom_model->insertNode($data);
            $this->session->set_flashdata('feedback', lang('added'));
        }

        redirect('dicom/nodes');
    }

    public function editNode()
    {
        $id = $this->input->get('id');
        $data['node'] = $this->dicom_model->getNodeById($id);
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard');
        $this->load->view('add_dicom_node', $data);
        $this->load->view('home/footer');
    }

    public function deleteNode()
    {
        $id = $this->input->get('id');
        $this->dicom_model->deleteNode($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('dicom/nodes');
    }

    public function echoTest()
    {
        $id = $this->input->get('id');
        $node = $this->dicom_model->getNodeById($id);

        if (!$node) {
            echo json_encode(array('success' => false, 'message' => 'Node not found'));
            return;
        }

        // Test the connection (basic TCP test since actual DICOM C-ECHO requires DCMTK/pynetdicom)
        $result = $this->dicom_model->testNode($node);

        echo json_encode($result);
    }

    // ==================== WORKLIST ====================

    public function worklist()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['worklist'] = $this->dicom_model->getWorklist();
        $data['nodes'] = $this->dicom_model->getNodes();
        $this->load->view('home/dashboard');
        $this->load->view('dicom_worklist', $data);
        $this->load->view('home/footer');
    }

    public function addToWorklistView()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['patients'] = $this->patient_model->getPatient();
        $data['nodes'] = $this->dicom_model->getNodes();
        $this->load->view('home/dashboard');
        $this->load->view('add_worklist', $data);
        $this->load->view('home/footer');
    }

    public function addToWorklist()
    {
        // Generate unique identifiers
        $study_uid = $this->generateStudyInstanceUid();
        $accession_number = $this->generateAccessionNumber();

        $data = array(
            'hospital_id' => $this->session->userdata('hospital_id'),
            'patient_id' => $this->input->post('patient_id'),
            'patient_name' => $this->input->post('patient_name'),
            'patient_birth_date' => $this->input->post('patient_birth_date'),
            'patient_sex' => $this->input->post('patient_sex'),
            'study_instance_uid' => $study_uid,
            'accession_number' => $accession_number,
            'requested_procedure_id' => $this->input->post('procedure_id') ?: $accession_number,
            'modality' => $this->input->post('modality'),
            'scheduled_start_date' => $this->input->post('scheduled_date'),
            'scheduled_station_ae' => $this->input->post('station_ae'),
            'scheduled_station_name' => $this->input->post('station_name'),
            'referring_physician' => $this->input->post('referring_physician'),
            'procedure_description' => $this->input->post('procedure_description'),
            'study_description' => $this->input->post('study_description'),
            'status' => 'scheduled',
            'radiology_order_id' => $this->input->post('radiology_order_id')
        );

        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->dicom_model->updateWorklist($id, $data);
            $this->session->set_flashdata('feedback', lang('updated'));
        } else {
            $this->dicom_model->insertWorklist($data);
            $this->session->set_flashdata('feedback', 'Study added to worklist');
        }

        redirect('dicom/worklist');
    }

    public function updateWorklistStatus()
    {
        $id = $this->input->get('id');
        $status = $this->input->get('status');

        $this->dicom_model->updateWorklist($id, array('status' => $status));
        $this->session->set_flashdata('feedback', 'Status updated');
        redirect('dicom/worklist');
    }

    public function deleteWorklist()
    {
        $id = $this->input->get('id');
        $this->dicom_model->deleteWorklist($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('dicom/worklist');
    }

    // ==================== STUDIES ====================

    public function studies()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['studies'] = $this->dicom_model->getStudies();
        $this->load->view('home/dashboard');
        $this->load->view('dicom_studies', $data);
        $this->load->view('home/footer');
    }

    public function viewStudy()
    {
        $id = $this->input->get('id');
        $data['study'] = $this->dicom_model->getStudyById($id);
        $data['settings'] = $this->settings_model->getSettings();

        if (!$data['study']) {
            $this->session->set_flashdata('feedback', 'Study not found');
            redirect('dicom/studies');
        }

        $this->load->view('home/dashboard');
        $this->load->view('dicom_study_detail', $data);
        $this->load->view('home/footer');
    }

    // ==================== HELPERS ====================

    private function generateStudyInstanceUid()
    {
        // DICOM UID format: root.timestamp.random
        // Using a fictional root for this hospital system
        $root = '1.2.826.0.1.3680043.8.1055.1';
        $timestamp = date('YmdHis');
        $random = substr(str_replace('.', '', microtime(true)), -6);

        return $root . '.' . $timestamp . '.' . $random;
    }

    private function generateAccessionNumber()
    {
        $hospital_id = $this->session->userdata('hospital_id');
        $prefix = 'ACC';
        $date = date('ymd');
        $random = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

        return $prefix . $hospital_id . $date . $random;
    }
}
