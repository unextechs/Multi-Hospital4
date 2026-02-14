<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Role_permission extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('hospital_model');
        $this->load->library('ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('ion_auth_model');
        $this->load->model('settings/settings_model');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        if (!$this->ion_auth->in_group(array('admin'))) {
            redirect('home/permission');
        }
    }

    public function index()
    {
        $data['groups'] = $this->db->where_not_in('name', array('superadmin', 'admin', 'Patient'))->get('groups')->result();
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard');
        $this->load->view('role_permission_list', $data);
        $this->load->view('home/footer');
    }

    public function editPermission()
    {
        $group_id = $this->input->get('id');
        $data['group'] = $this->db->where('id', $group_id)->get('groups')->row();

        $hospital_id = $this->session->userdata('hospital_id');

        // Get only the modules enabled for this hospital's package
        $data['hospital_modules'] = $this->hospital_model->modules();

        $group_modules_row = $this->hospital_model->getGroupModules($hospital_id, $group_id);
        if (!empty($group_modules_row)) {
            $data['group_modules'] = explode(',', $group_modules_row->modules);
        } else {
            $data['group_modules'] = array();
        }

        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard');
        $this->load->view('role_permission', $data);
        $this->load->view('home/footer');
    }

    public function updatePermission()
    {
        $group_id = $this->input->post('group_id');
        $modules = $this->input->post('module');
        $hospital_id = $this->session->userdata('hospital_id');

        if (!empty($modules)) {
            $modules_str = implode(',', $modules);
        } else {
            $modules_str = '';
        }

        $this->hospital_model->updateGroupModules($hospital_id, $group_id, $modules_str);
        $this->session->set_flashdata('feedback', lang('updated'));
        redirect('hospital/role_permission');
    }
}

/* End of file Role_permission.php */
/* Location: ./application/modules/hospital/controllers/Role_permission.php */
