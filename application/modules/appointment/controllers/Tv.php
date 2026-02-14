<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tv extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('appointment/appointment_model');
        $this->load->model('appointment/queue_model');
        $this->load->model('doctor/doctor_model');
        $this->load->model('settings/settings_model');
    }

    /**
     * TV Display Index - Requires token authentication
     */
    public function index()
    {
        $token = $this->input->get('token');
        $hospital_id = $this->input->get('hospital_id');

        // Validate token
        if (!$this->validateToken($token, $hospital_id)) {
            $this->showUnauthorizedPage();
            return;
        }

        // Load hospital settings for branding
        $data['settings'] = $this->getHospitalSettings($hospital_id);
        $data['hospital_id'] = $hospital_id;
        $data['token'] = $token;

        $this->load->view('tv_display', $data);
    }

    /**
     * Get queue updates via AJAX - Requires token authentication
     */
    public function get_updates()
    {
        $token = $this->input->get('token');
        $hospital_id = $this->input->get('hospital_id');

        // Validate token
        if (!$this->validateToken($token, $hospital_id)) {
            echo json_encode(['error' => 'Unauthorized access']);
            return;
        }

        if (empty($hospital_id)) {
            echo json_encode(['error' => 'No Hospital ID provided']);
            return;
        }

        // Fetch Now Serving from queue_entries
        $serving = $this->queue_model->getQueueByStatus($hospital_id, 'Doctor', 'Serving');

        // Fetch Next (Waiting) from queue_entries
        $waiting = $this->queue_model->getQueueByStatus($hospital_id, 'Doctor', 'Waiting');

        // Ensure arrays are valid (prevent null/undefined issues)
        if (!is_array($serving)) {
            $serving = [];
        }
        if (!is_array($waiting)) {
            $waiting = [];
        }

        // Limit waiting list to top 10
        if (count($waiting) > 10) {
            $waiting = array_slice($waiting, 0, 10);
        }

        $response = [
            'serving' => $serving,
            'waiting' => $waiting
        ];

        echo json_encode($response);
    }

    /**
     * Validate TV access token
     */
    private function validateToken($token, $hospital_id)
    {
        if (empty($token) || empty($hospital_id)) {
            return false;
        }

        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('tv_access_token', $token);
        $this->db->where('tv_display_enabled', 1);
        $settings = $this->db->get('settings')->row();

        return !empty($settings);
    }

    /**
     * Get hospital settings for branding
     */
    private function getHospitalSettings($hospital_id)
    {
        $this->db->where('hospital_id', $hospital_id);
        $settings = $this->db->get('settings')->row();

        return $settings ? $settings : (object) ['system_vendor' => 'Hospital Management System'];
    }

    /**
     * Show unauthorized access page
     */
    private function showUnauthorizedPage()
    {
        $data['title'] = 'Unauthorized Access';
        $data['message'] = 'Invalid or expired TV display token. Please contact your system administrator.';
        $this->load->view('tv_unauthorized', $data);
    }

    /**
     * Generate new TV access token (called from settings)
     */
    public function generate_token()
    {
        if (!$this->ion_auth->in_group(array('admin'))) {
            show_error('Unauthorized', 403);
            return;
        }

        $hospital_id = $this->session->userdata('hospital_id');
        $token = $this->generateSecureToken($hospital_id);

        // Update settings with new token
        $data = [
            'tv_access_token' => $token,
            'tv_display_enabled' => 1
        ];

        $this->db->where('hospital_id', $hospital_id);
        $this->db->update('settings', $data);

        echo json_encode([
            'success' => true,
            'token' => $token,
            'url' => base_url("appointment/tv?token=$token&hospital_id=$hospital_id")
        ]);
    }

    /**
     * Generate secure token
     */
    private function generateSecureToken($hospital_id)
    {
        return hash('sha256', $hospital_id . time() . bin2hex(random_bytes(16)));
    }
}
