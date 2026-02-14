<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Insurance_model extends CI_model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function insertInsurance($data)
    {
        $data1 = array('hospital_id' => $this->session->userdata('hospital_id'));
        $data2 = array_merge($data, $data1);
        $this->db->insert('insurance_company', $data2);
    }

    function getInsurance()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('insurance_company');
        return $query->result();
    }

    function getInsuranceByName($name)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('name', $name);
        $query = $this->db->get('insurance_company');
        return $query->row();
    }

    function getInsuranceById($id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('id', $id);
        $query = $this->db->get('insurance_company');
        return $query->row();
    }

    function updateInsurance($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('insurance_company', $data);
    }

    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('insurance_company');
    }





    function getInsuranceCompanyBySearch($search, $order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $query = $this->db->select('*')
            ->from('insurance_company')
            ->where('hospital_id', $this->session->userdata('hospital_id'))
            ->where("(id LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR description LIKE '%" . $search . "%')", NULL, FALSE)
            ->get();
        return $query->result();
    }
    function getInsuranceCompanyWithoutSearch($order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('insurance_company');
        return $query->result();
    }
    function getInsuranceCompanyByLimitBySearch($limit, $start, $search, $order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->limit($limit, $start);
        $query = $this->db->select('*')
            ->from('insurance_company')
            ->where('hospital_id', $this->session->userdata('hospital_id'))
            ->where("(id LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR description LIKE '%" . $search . "%')", NULL, FALSE)
            ->get();

        return $query->result();
    }
    function getInsuranceCompanyByLimit($limit, $start, $order, $dir)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get('insurance_company');
        return $query->result();
    }



    // ==================== CLAIMS ====================

    function insertClaim($data)
    {
        $this->db->insert('insurance_claims', $data);
        return $this->db->insert_id();
    }

    function getClaims($limit = 100)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get('insurance_claims');
        return $query->result();
    }

    function getClaimById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('insurance_claims');
        return $query->row();
    }

    function updateClaim($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('insurance_claims', $data);
    }

    function deleteClaim($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('insurance_claims');
    }

    function generateClaimNumber()
    {
        $hospital_id = $this->session->userdata('hospital_id');
        $prefix = 'CLM';
        $timestamp = date('ymd');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        return $prefix . $hospital_id . $timestamp . $random;
    }

    function getClaimsStats()
    {
        $hospital_id = $this->session->userdata('hospital_id');

        $this->db->where('hospital_id', $hospital_id);
        $total_claims = $this->db->count_all_results('insurance_claims');

        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('status', 'pending');
        $pending_claims = $this->db->count_all_results('insurance_claims');

        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('status', 'approved');
        $approved_claims = $this->db->count_all_results('insurance_claims');

        $this->db->where('hospital_id', $hospital_id);
        $this->db->where('status', 'rejected');
        $rejected_claims = $this->db->count_all_results('insurance_claims');

        return array(
            'total_claims' => $total_claims,
            'pending_claims' => $pending_claims,
            'approved_claims' => $approved_claims,
            'rejected_claims' => $rejected_claims
        );
    }

    // ==================== PATIENT INSURANCE ====================

    function insertPatientInsurance($data)
    {
        $this->db->insert('patient_insurance', $data);
        return $this->db->insert_id();
    }

    function getPatientInsurances($limit = 100)
    {
        $this->db->select('patient_insurance.*, patient.name as patient_name, insurance_company.name as insurance_company_name');
        $this->db->from('patient_insurance');
        $this->db->join('patient', 'patient_insurance.patient_id = patient.id', 'left');
        $this->db->join('insurance_company', 'patient_insurance.insurance_company_id = insurance_company.id', 'left');
        $this->db->where('patient_insurance.hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('patient_insurance.id', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    function getPatientInsurancesByPatient($patient_id)
    {
        $this->db->select('patient_insurance.*, insurance_company.name as insurance_company_name');
        $this->db->from('patient_insurance');
        $this->db->join('insurance_company', 'patient_insurance.insurance_company_id = insurance_company.id', 'left');
        $this->db->where('patient_insurance.patient_id', $patient_id);
        $this->db->where('patient_insurance.hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get();
        return $query->result();
    }

    function getPatientInsuranceById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('patient_insurance');
        return $query->row();
    }

    function updatePatientInsurance($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('patient_insurance', $data);
    }

    function deletePatientInsurance($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('patient_insurance');
    }

}
