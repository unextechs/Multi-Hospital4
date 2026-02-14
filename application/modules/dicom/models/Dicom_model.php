<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dicom_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // ==================== DICOM FILES ====================

    function insertDicom($data)
    {
        $this->db->insert('dicom_files', $data);
        return $this->db->insert_id();
    }

    function getDicomFiles($limit = 100)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get('dicom_files');
        return $query->result();
    }

    function getDicomById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('dicom_files');
        return $query->row();
    }

    function deleteDicom($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('dicom_files');
    }

    // ==================== DICOM NODES ====================

    function insertNode($data)
    {
        $this->db->insert('dicom_nodes', $data);
        return $this->db->insert_id();
    }

    function getNodes()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('name', 'asc');
        $query = $this->db->get('dicom_nodes');
        return $query->result();
    }

    function getNodeById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('dicom_nodes');
        return $query->row();
    }

    function getDefaultNode()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('is_default', 1);
        $query = $this->db->get('dicom_nodes');
        return $query->row();
    }

    function updateNode($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('dicom_nodes', $data);
    }

    function deleteNode($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('dicom_nodes');
    }

    function clearDefaultNodes()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->update('dicom_nodes', array('is_default' => 0));
    }

    function testNode($node)
    {
        // Basic TCP connectivity test
        $socket = @fsockopen($node->host, $node->port, $errno, $errstr, 5);

        if (!$socket) {
            // Update last echo status
            $this->db->where('id', $node->id);
            $this->db->update('dicom_nodes', array(
                'last_echo' => date('Y-m-d H:i:s'),
                'last_echo_status' => 'failed'
            ));

            return array(
                'success' => false,
                'message' => "Connection failed: $errstr ($errno)"
            );
        }

        // Connection successful
        fclose($socket);

        // Update last echo status
        $this->db->where('id', $node->id);
        $this->db->update('dicom_nodes', array(
            'last_echo' => date('Y-m-d H:i:s'),
            'last_echo_status' => 'success'
        ));

        return array(
            'success' => true,
            'message' => 'Connection to ' . $node->ae_title . ' successful. Note: Full DICOM C-ECHO requires additional DICOM toolkit.'
        );
    }

    // ==================== WORKLIST ====================

    function insertWorklist($data)
    {
        $this->db->insert('dicom_worklist', $data);
        return $this->db->insert_id();
    }

    function getWorklist($status = null, $limit = 100)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        if ($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('scheduled_start_date', 'asc');
        $this->db->limit($limit);
        $query = $this->db->get('dicom_worklist');
        return $query->result();
    }

    function getWorklistById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('dicom_worklist');
        return $query->row();
    }

    function getWorklistByAccession($accession_number)
    {
        $this->db->where('accession_number', $accession_number);
        $query = $this->db->get('dicom_worklist');
        return $query->row();
    }

    function updateWorklist($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('dicom_worklist', $data);
    }

    function deleteWorklist($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('dicom_worklist');
    }

    function getTodaysWorklist($modality = null)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('DATE(scheduled_start_date)', date('Y-m-d'));
        if ($modality) {
            $this->db->where('modality', $modality);
        }
        $this->db->order_by('scheduled_start_date', 'asc');
        $query = $this->db->get('dicom_worklist');
        return $query->result();
    }

    // ==================== STUDIES ====================

    function insertStudy($data)
    {
        $this->db->insert('dicom_studies', $data);
        return $this->db->insert_id();
    }

    function getStudies($limit = 100)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('study_date', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get('dicom_studies');
        return $query->result();
    }

    function getStudyById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('dicom_studies');
        return $query->row();
    }

    function getStudyByUid($study_instance_uid)
    {
        $this->db->where('study_instance_uid', $study_instance_uid);
        $query = $this->db->get('dicom_studies');
        return $query->row();
    }

    function getStudiesByPatient($patient_id)
    {
        $this->db->where('patient_id', $patient_id);
        $this->db->order_by('study_date', 'desc');
        $query = $this->db->get('dicom_studies');
        return $query->result();
    }

    function updateStudy($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('dicom_studies', $data);
    }

    // ==================== STATISTICS ====================

    function getStats()
    {
        $hospital_id = $this->session->userdata('hospital_id');

        // Total files
        $this->db->where('hospital_id', $hospital_id);
        $total_files = $this->db->count_all_results('dicom_files');

        // Active nodes
        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('status', 'active');
        $active_nodes = $this->db->count_all_results('dicom_nodes');

        // Today's worklist
        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('DATE(scheduled_start_date)', date('Y-m-d'));
        $today_worklist = $this->db->count_all_results('dicom_worklist');

        // Pending worklist
        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('status', 'scheduled');
        $pending_worklist = $this->db->count_all_results('dicom_worklist');

        // Total studies
        $this->db->where('hospital_id', $hospital_id);
        $total_studies = $this->db->count_all_results('dicom_studies');

        return array(
            'total_files' => $total_files,
            'active_nodes' => $active_nodes,
            'today_worklist' => $today_worklist,
            'pending_worklist' => $pending_worklist,
            'total_studies' => $total_studies
        );
    }

    // ==================== MODALITY HELPERS ====================

    function getModalityList()
    {
        return array(
            'CR' => 'Computed Radiography',
            'CT' => 'Computed Tomography',
            'DX' => 'Digital Radiography',
            'ES' => 'Endoscopy',
            'MG' => 'Mammography',
            'MR' => 'Magnetic Resonance',
            'NM' => 'Nuclear Medicine',
            'OT' => 'Other',
            'PT' => 'PET',
            'RF' => 'Radio Fluoroscopy',
            'RG' => 'Radiographic Imaging',
            'US' => 'Ultrasound',
            'XA' => 'X-Ray Angiography',
            'XR' => 'X-Ray'
        );
    }
}
