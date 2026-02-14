<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Prescription_model extends CI_model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function insertPrescription($data)
    {
        $data1 = array('hospital_id' => $this->session->userdata('hospital_id'));
        $data2 = array_merge($data, $data1);
        $this->db->insert('prescription', $data2);
    }

    function getPrescription()
    {
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function getPrescriptionById($id)
    {
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        $this->db->where('id', $id);
        $query = $this->db->get('prescription');
        return $query->row();
    }

    function getPrescriptionByPatientId($patient_id)
    {
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        $this->db->order_by('id', 'desc');
        $this->db->where('patient', $patient_id);
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function getPrescriptionByDoctorId($doctor_id)
    {
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        $this->db->order_by('id', 'desc');
        $this->db->where('doctor', $doctor_id);
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function updatePrescription($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('prescription', $data);
    }

    function deletePrescription($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('prescription');
    }

    function getPrescriptionWithoutSearch($order, $dir)
    {
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function getPrescriptionBySearch($search, $order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->select('*')
            ->from('prescription');
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        $query = $this->db->where("(id LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)
            ->get();
        ;
        return $query->result();
    }

    function getPrescriptionByLimit($limit, $start, $order, $dir)
    {
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function getPrescriptionByLimitBySearch($limit, $start, $search, $order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->limit($limit, $start);
        $query = $this->db->select('*')
            ->from('prescription');
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        $query = $this->db->where("(id LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)
            ->get();
        ;
        return $query->result();
    }

    function getPrescriptionByDoctor($doctor_id)
    {
        $this->db->order_by('id', 'desc');
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        $this->db->where('doctor', $doctor_id);
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function getPrescriptionByDoctorWithoutSearch($doctor_id, $order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        $this->db->where('doctor', $doctor_id);
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function getPrescriptionBySearchByDoctor($doctor, $search, $order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->select('*')
            ->from('prescription');
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        $query = $this->db->where('doctor', $doctor)
            ->where("(id LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)
            ->get();
        ;
        return $query->result();
    }

    function getPrescriptionByLimitByDoctor($doctor, $limit, $start, $order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        $this->db->where('doctor', $doctor);
        $this->db->limit($limit, $start);
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function getPrescriptionByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir)
    {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->limit($limit, $start);
        $this->db->select('*')
            ->from('prescription');
        if (!$this->ion_auth->in_group('superadmin')) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        }
        $query = $this->db->where('doctor', $doctor)
            ->where("(id LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)
            ->get();
        ;
        return $query->result();
    }

}
