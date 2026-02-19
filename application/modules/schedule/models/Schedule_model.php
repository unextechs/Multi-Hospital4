<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Schedule_model extends CI_model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function getSchedule()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('time_schedule');
        return $query->result();
    }

    function getAvailableDoctorByDate($date)
    {
        if (empty($date)) {
            $date = time();
        }
        if (!is_numeric($date)) {
            $date = strtotime($date);
        }
        $weekday = date("l", $date);
        $this->db->where('date', $date);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query1 = $this->db->get('holidays')->result();
        if (!empty($query1)) {
            $doctor = array();
            foreach ($query1 as $q1) {
                $doctor[] = $q1->doctor;
            }
            $this->db->where_not_in('id', $doctor);
        }
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('doctor')->result();
        foreach ($query as $availableDoctor) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $this->db->where('doctor', $availableDoctor->id);
            $this->db->where('weekday', $weekday);
            $query_slot = $this->db->get('time_slot')->result();

            if (!empty($query_slot)) {
                $doctor_avail[] = $availableDoctor->id;
            }
        }
        $this->db->where_in('id', $doctor_avail);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query_avail_doctor = $this->db->get('doctor');
        return $query_avail_doctor->result();
    }

    function getAvailableDoctorsByDateBySlot($date, $slot)
    {
        if (empty($date)) {
            $date = time();
        }
        if (!is_numeric($date)) {
            $date = strtotime($date);
        }
        $weekday = date("l", $date);
        $this->db->where('date', $date);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query1 = $this->db->get('holidays')->result();
        if (!empty($query1)) {
            $doctor = array();
            foreach ($query1 as $q1) {
                $doctor[] = $q1->doctor;
            }
            $this->db->where_not_in('id', $doctor);
        }

        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('doctor')->result();
        foreach ($query as $availableDoctor) {
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $this->db->where('doctor', $availableDoctor->id);
            $this->db->where('weekday', $weekday);
            $query_slot = $this->db->get('time_slot')->result();

            if (!empty($query_slot)) {
                $doctor_avail[] = $availableDoctor->id;
            }
        }

        foreach ($doctor_avail as $key => $value) {
            $this->db->where('doctor', $value);
            $this->db->where('date', $date);
            $this->db->where('time_slot', $slot);
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $query_appointment = $this->db->get('appointment')->result();

            if (empty($query_appointment)) {
                $most_probable_avail_doctor[] = $value;
            }
        }
        $this->db->where_in('id', $most_probable_avail_doctor);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query_avail_doctor = $this->db->get('staff');
        return $query_avail_doctor->result();
    }

    function getAvailableSlotByDoctorByDate($date, $doctor)
    {
        if (empty($date)) {
            $date = time();
        }
        if (!is_numeric($date)) {
            $date = strtotime($date);
        }
        //$newDate = date("m-d-Y", strtotime($date));
        $weekday = date("l", $date);

        $this->db->where('date', $date);
        $this->db->where('doctor', $doctor);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $holiday = $this->db->get('holidays')->result();

        if (empty($holiday)) {
            $this->db->where('date', $date);
            $this->db->where('doctor', $doctor);
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $query = $this->db->get('appointment')->result();

            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $this->db->where('doctor', $doctor);
            $this->db->where('weekday', $weekday);
            $this->db->order_by('s_time_key', 'asc');
            $query1 = $this->db->get('time_slot')->result();

            $availabletimeSlot = array();
            $bookedTimeSlot = array();

            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $this->settings = $this->db->get('settings')->row();

            $is_today = (date('Y-m-d', (int) $date) == date('Y-m-d'));
            $current_time_key = -1;
            if ($is_today) {
                $current_time = date('h:i A');
                $current_time_key = $this->get_time_key($current_time);
            }

            foreach ($query1 as $timeslot) {
                if ($is_today && $timeslot->s_time_key <= $current_time_key) {
                    continue;
                }
                if ($this->settings->time_format == 24) {
                    $timeslot->s_time = $this->settings_model->convert_to_24h($timeslot->s_time);
                    $timeslot->e_time = $this->settings_model->convert_to_24h($timeslot->e_time);
                }
                $availabletimeSlot[] = $timeslot->s_time . ' To ' . $timeslot->e_time;
            }
            foreach ($query as $bookedTime) {
                if ($bookedTime->status != 'Cancelled') {
                    if ($this->settings->time_format == 24) {
                        $time_string = explode(' To ', $bookedTime->time_slot);
                        $time_string[0] = $this->settings_model->convert_to_24h($time_string[0]);
                        $time_string[1] = $this->settings_model->convert_to_24h($time_string[1]);
                    } else {
                        $time_string = explode(' To ', $bookedTime->time_slot);
                    }

                    $bookedTimeSlot[] = $time_string[0] . ' To ' . $time_string[1];
                }
            }

            $availableSlot = array_diff($availabletimeSlot, $bookedTimeSlot);
        } else {
            $availableSlot = array();
        }

        return $availableSlot;
    }

    function getAvailableSlotByDoctorByDateByAppointmentId($date, $doctor, $appointment_id)
    {
        if (empty($date)) {
            $date = time();
        }
        if (!is_numeric($date)) {
            $date = strtotime($date);
        }
        //$newDate = date("m-d-Y", strtotime($date));
        $weekday = date("l", $date);

        $this->db->where('date', $date);
        $this->db->where('doctor', $doctor);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $holiday = $this->db->get('holidays')->result();

        if (empty($holiday)) {

            $this->db->where('date', $date);
            $this->db->where('doctor', $doctor);
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $query = $this->db->get('appointment')->result();

            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $this->db->where('doctor', $doctor);
            $this->db->where('weekday', $weekday);
            $this->db->order_by('s_time_key', 'asc');
            $query1 = $this->db->get('time_slot')->result();

            $availabletimeSlot = array();
            $bookedTimeSlot = array();

            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $this->settings = $this->db->get('settings')->row();

            $is_today = (date('Y-m-d', (int) $date) == date('Y-m-d'));
            $current_time_key = -1;
            if ($is_today) {
                $current_time = date('h:i A');
                $current_time_key = $this->get_time_key($current_time);
            }

            foreach ($query1 as $timeslot) {
                if ($is_today && $timeslot->s_time_key <= $current_time_key) {
                    continue;
                }
                if ($this->settings->time_format == 24) {
                    $timeslot->s_time = $this->settings_model->convert_to_24h($timeslot->s_time);
                    $timeslot->e_time = $this->settings_model->convert_to_24h($timeslot->e_time);
                }
                $availabletimeSlot[] = $timeslot->s_time . ' To ' . $timeslot->e_time;
            }
            foreach ($query as $bookedTime) {
                if ($bookedTime->status != 'Cancelled') {
                    if ($bookedTime->id != $appointment_id) {
                        if ($this->settings->time_format == 24) {
                            $time_string = explode(' To ', $bookedTime->time_slot);
                            $time_string[0] = $this->settings_model->convert_to_24h($time_string[0]);
                            $time_string[1] = $this->settings_model->convert_to_24h($time_string[1]);
                            $bookedTime->time_slot = $time_string[0] . ' ' . lang('to') . ' ' . $time_string[1];
                        } else {
                            $time_string = explode(' To ', $bookedTime->time_slot);
                        }
                        $bookedTimeSlot[] = $time_string[0] . ' To ' . $time_string[1];
                    }
                }
            }

            $availableSlot = array_diff($availabletimeSlot, $bookedTimeSlot);
        } else {
            $availableSlot = array();
        }

        return $availableSlot;
    }

    function updateIonUser($username, $email, $password, $ion_user_id)
    {
        $uptade_ion_user = array(
            'username' => $username,
            'email' => $email,
            'password' => $password
        );
        $this->db->where('id', $ion_user_id);
        $this->db->update('users', $uptade_ion_user);
    }

    function getDoctorByIonUserId($id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('ion_user_id', $id);
        $query = $this->db->get('doctor');
        return $query->row();
    }

    function insertTimeSlot($data)
    {
        $data1 = array('hospital_id' => $this->session->userdata('hospital_id'));
        $data2 = array_merge($data, $data1);
        $this->db->insert('time_slot', $data2);
    }

    function getTimeSlot()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('time_slot');
        return $query->result();
    }

    function getTimeSlotById($id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('id', $id);
        $query = $this->db->get('time_slot');
        return $query->row();
    }

    function getTimeSlotByDoctor($id)
    {
        $this->db->order_by('s_time_key', 'asc');
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('doctor', $id);
        $query = $this->db->get('time_slot');
        return $query->result();
    }

    function updateTimeSlot($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('time_slot', $data);
    }

    function deleteTimeSlot($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('time_slot');
    }

    function insertSchedule($data)
    {
        $data1 = array('hospital_id' => $this->session->userdata('hospital_id'));
        $data2 = array_merge($data, $data1);
        $this->db->insert('time_schedule', $data2);
    }

    function getScheduleByDoctor($doctor)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('doctor', $doctor);
        $query = $this->db->get('time_schedule');
        return $query->result();
    }

    function getScheduleById($id)
    {
        $this->db->where('id', $id);
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('time_schedule');
        return $query->row();
    }

    function getScheduleByDoctorByWeekday($doctor, $weekday)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('doctor', $doctor);
        $this->db->where('weekday', $weekday);
        $query = $this->db->get('time_schedule');
        return $query->result();
    }

    function getScheduleByDoctorByWeekdayById($doctor, $weekday, $id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where_not_in('id', $id);
        $this->db->where('doctor', $doctor);
        $this->db->where('weekday', $weekday);
        $query = $this->db->get('time_schedule');
        return $query->result();
    }

    function updateSchedule($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('time_schedule', $data);
    }

    function deleteSchedule($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('time_schedule');
    }

    function deleteTimeSlotByDoctorByWeekday($doctor, $weekday)
    {
        $this->db->where('doctor', $doctor);
        $this->db->where('weekday', $weekday);
        $this->db->delete('time_slot');
    }

    function insertHoliday($data)
    {
        $data1 = array('hospital_id' => $this->session->userdata('hospital_id'));
        $data2 = array_merge($data, $data1);
        $this->db->insert('holidays', $data2);
    }

    function getHolidays()
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $query = $this->db->get('holidays');
        return $query->result();
    }

    function getHolidayById($id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('id', $id);
        $query = $this->db->get('holidays');
        return $query->row();
    }

    function getHolidaysByDoctor($id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->order_by('id', 'asc');
        $this->db->where('doctor', $id);
        $query = $this->db->get('holidays');
        return $query->result();
    }

    function getHolidayByDoctorByDate($doctor, $date)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('doctor', $doctor);
        $this->db->where('date', $date);
        $query = $this->db->get('holidays');
        return $query->row();
    }

    function getTimeSlotByDoctorByWeekday($doctor, $weekday)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where('doctor', $doctor);
        $this->db->where('weekday', $weekday);
        $query = $this->db->get('time_slot');
        return $query->result();
    }

    function getTimeSlotByDoctorByWeekdayById($doctor, $weekday, $id)
    {
        $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
        $this->db->where_not_in('id', $id);
        $this->db->where('doctor', $doctor);
        $this->db->where('weekday', $weekday);
        $query = $this->db->get('time_slot');
        return $query->result();
    }

    function updateHoliday($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('holidays', $data);
    }

    function deleteHoliday($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('holidays');
    }

    function get_time_key($time)
    {
        $time_key = -1;
        $all_slot = array(
            '0' => '12:00 AM',
            '1' => '12:05 AM',
            '2' => '12:10 AM',
            '3' => '12:15 AM',
            '4' => '12:20 AM',
            '5' => '12:25 AM',
            '6' => '12:30 AM',
            '7' => '12:35 AM',
            '8' => '12:40 PM',
            '9' => '12:45 AM',
            '10' => '12:50 AM',
            '11' => '12:55 AM',
            '12' => '01:00 AM',
            '13' => '01:05 AM',
            '14' => '01:10 AM',
            '15' => '01:15 AM',
            '16' => '01:20 AM',
            '17' => '01:25 AM',
            '18' => '01:30 AM',
            '19' => '01:35 AM',
            '20' => '01:40 AM',
            '21' => '01:45 AM',
            '22' => '01:50 AM',
            '23' => '01:55 AM',
            '24' => '02:00 AM',
            '25' => '02:05 AM',
            '26' => '02:10 AM',
            '27' => '02:15 AM',
            '28' => '02:20 AM',
            '29' => '02:25 AM',
            '30' => '02:30 AM',
            '31' => '02:35 AM',
            '32' => '02:40 AM',
            '33' => '02:45 AM',
            '34' => '02:50 AM',
            '35' => '02:55 AM',
            '36' => '03:00 AM',
            '37' => '03:05 AM',
            '38' => '03:10 AM',
            '39' => '03:15 AM',
            '40' => '03:20 AM',
            '41' => '03:25 AM',
            '42' => '03:30 AM',
            '43' => '03:35 AM',
            '44' => '03:40 AM',
            '45' => '03:45 AM',
            '46' => '03:50 AM',
            '47' => '03:55 AM',
            '48' => '04:00 AM',
            '49' => '04:05 AM',
            '50' => '04:10 AM',
            '51' => '04:15 AM',
            '52' => '04:20 AM',
            '53' => '04:25 AM',
            '54' => '04:30 AM',
            '55' => '04:35 AM',
            '56' => '04:40 AM',
            '57' => '04:45 AM',
            '58' => '04:50 AM',
            '59' => '04:55 AM',
            '60' => '05:00 AM',
            '61' => '05:05 AM',
            '62' => '05:10 AM',
            '63' => '05:15 AM',
            '64' => '05:20 AM',
            '65' => '05:25 AM',
            '66' => '05:30 AM',
            '67' => '05:35 AM',
            '68' => '05:40 AM',
            '69' => '05:45 AM',
            '70' => '05:50 AM',
            '71' => '05:55 AM',
            '72' => '06:00 AM',
            '73' => '06:05 AM',
            '74' => '06:10 AM',
            '75' => '06:15 AM',
            '76' => '06:20 AM',
            '77' => '06:25 AM',
            '78' => '06:30 AM',
            '79' => '06:35 AM',
            '80' => '06:40 AM',
            '81' => '06:45 AM',
            '82' => '06:50 AM',
            '83' => '06:55 AM',
            '84' => '07:00 AM',
            '85' => '07:05 AM',
            '86' => '07:10 AM',
            '87' => '07:15 AM',
            '88' => '07:20 AM',
            '89' => '07:25 AM',
            '90' => '07:30 AM',
            '91' => '07:35 AM',
            '92' => '07:40 AM',
            '93' => '07:45 AM',
            '94' => '07:50 AM',
            '95' => '07:55 AM',
            '96' => '08:00 AM',
            '97' => '08:05 AM',
            '98' => '08:10 AM',
            '99' => '08:15 AM',
            '100' => '08:20 AM',
            '101' => '08:25 AM',
            '102' => '08:30 AM',
            '103' => '08:35 AM',
            '104' => '08:40 AM',
            '105' => '08:45 AM',
            '106' => '08:50 AM',
            '107' => '08:55 AM',
            '108' => '09:00 AM',
            '109' => '09:05 AM',
            '110' => '09:10 AM',
            '111' => '09:15 AM',
            '112' => '09:20 AM',
            '113' => '09:25 AM',
            '114' => '09:30 AM',
            '115' => '09:35 AM',
            '116' => '09:40 AM',
            '117' => '09:45 AM',
            '118' => '09:50 AM',
            '119' => '09:55 AM',
            '120' => '10:00 AM',
            '121' => '10:05 AM',
            '122' => '10:10 AM',
            '123' => '10:15 AM',
            '124' => '10:20 AM',
            '125' => '10:25 AM',
            '126' => '10:30 AM',
            '127' => '10:35 AM',
            '128' => '10:40 AM',
            '129' => '10:45 AM',
            '130' => '10:50 AM',
            '131' => '10:55 AM',
            '132' => '11:00 AM',
            '133' => '11:05 AM',
            '134' => '11:10 AM',
            '135' => '11:15 AM',
            '136' => '11:20 AM',
            '137' => '11:25 AM',
            '138' => '11:30 AM',
            '139' => '11:35 AM',
            '140' => '11:40 AM',
            '141' => '11:45 AM',
            '142' => '11:50 AM',
            '143' => '11:55 AM',
            '144' => '12:00 PM',
            '145' => '12:05 PM',
            '146' => '12:10 PM',
            '147' => '12:15 PM',
            '148' => '12:20 PM',
            '149' => '12:25 PM',
            '150' => '12:30 PM',
            '151' => '12:35 PM',
            '152' => '12:40 PM',
            '153' => '12:45 PM',
            '154' => '12:50 PM',
            '155' => '12:55 PM',
            '156' => '01:00 PM',
            '157' => '01:05 PM',
            '158' => '01:10 PM',
            '159' => '01:15 PM',
            '160' => '01:20 PM',
            '161' => '01:25 PM',
            '162' => '01:30 PM',
            '163' => '01:35 PM',
            '164' => '01:40 PM',
            '165' => '01:45 PM',
            '166' => '01:50 PM',
            '167' => '01:55 PM',
            '168' => '02:00 PM',
            '169' => '02:05 PM',
            '170' => '02:10 PM',
            '171' => '02:15 PM',
            '172' => '02:20 PM',
            '173' => '02:25 PM',
            '174' => '02:30 PM',
            '175' => '02:35 PM',
            '176' => '02:40 PM',
            '177' => '02:45 PM',
            '178' => '02:50 PM',
            '179' => '02:55 PM',
            '180' => '03:00 PM',
            '181' => '03:05 PM',
            '182' => '03:10 PM',
            '183' => '03:15 PM',
            '184' => '03:20 PM',
            '185' => '03:25 PM',
            '186' => '03:30 PM',
            '187' => '03:35 PM',
            '188' => '03:40 PM',
            '189' => '03:45 PM',
            '190' => '03:50 PM',
            '191' => '03:55 PM',
            '192' => '04:00 PM',
            '193' => '04:05 PM',
            '194' => '04:10 PM',
            '195' => '04:15 PM',
            '196' => '04:20 PM',
            '197' => '04:25 PM',
            '198' => '04:30 PM',
            '199' => '04:35 PM',
            '200' => '04:40 PM',
            '201' => '04:45 PM',
            '202' => '04:50 PM',
            '203' => '04:55 PM',
            '204' => '05:00 PM',
            '205' => '05:05 PM',
            '206' => '05:10 PM',
            '207' => '05:15 PM',
            '208' => '05:20 PM',
            '209' => '05:25 PM',
            '210' => '05:30 PM',
            '211' => '05:35 PM',
            '212' => '05:40 PM',
            '213' => '05:45 PM',
            '214' => '05:50 PM',
            '215' => '05:55 PM',
            '216' => '06:00 PM',
            '217' => '06:05 PM',
            '218' => '06:10 PM',
            '219' => '06:15 PM',
            '220' => '06:20 PM',
            '221' => '06:25 PM',
            '222' => '06:30 PM',
            '223' => '06:35 PM',
            '224' => '06:40 PM',
            '225' => '06:45 PM',
            '226' => '06:50 PM',
            '227' => '06:55 PM',
            '228' => '07:00 PM',
            '229' => '07:05 PM',
            '230' => '07:10 PM',
            '231' => '07:15 PM',
            '232' => '07:20 PM',
            '233' => '07:25 PM',
            '234' => '07:30 PM',
            '235' => '07:35 PM',
            '236' => '07:40 PM',
            '237' => '07:45 PM',
            '238' => '07:50 PM',
            '239' => '07:55 PM',
            '240' => '08:00 PM',
            '241' => '08:05 PM',
            '242' => '08:10 PM',
            '243' => '08:15 PM',
            '244' => '08:20 PM',
            '245' => '08:25 PM',
            '246' => '08:30 PM',
            '247' => '08:35 PM',
            '248' => '08:40 PM',
            '249' => '08:45 PM',
            '250' => '08:50 PM',
            '251' => '08:55 PM',
            '252' => '09:00 PM',
            '253' => '09:05 PM',
            '254' => '09:10 PM',
            '255' => '09:15 PM',
            '256' => '09:20 PM',
            '257' => '09:25 PM',
            '258' => '09:30 PM',
            '259' => '09:35 PM',
            '260' => '09:40 PM',
            '261' => '09:45 PM',
            '262' => '09:50 PM',
            '263' => '09:55 PM',
            '264' => '10:00 PM',
            '265' => '10:05 PM',
            '266' => '10:10 PM',
            '267' => '10:15 PM',
            '268' => '10:20 PM',
            '269' => '10:25 PM',
            '270' => '10:30 PM',
            '271' => '10:35 PM',
            '272' => '10:40 PM',
            '273' => '10:45 PM',
            '274' => '10:50 PM',
            '275' => '10:55 PM',
            '276' => '11:00 PM',
            '277' => '11:05 PM',
            '278' => '11:10 PM',
            '279' => '11:15 PM',
            '280' => '11:20 PM',
            '281' => '11:25 PM',
            '282' => '11:30 PM',
            '283' => '11:35 PM',
            '284' => '11:40 PM',
            '285' => '11:45 PM',
            '286' => '11:50 PM',
            '287' => '11:55 PM',
        );

        $minute = date('i');
        $hour = date('h');
        if (date('A') == 'PM') {
            if ($hour != 12) {
                $hour = $hour + 12;
            }
        } else {
            if ($hour == 12) {
                $hour = 0;
            }
        }

        $total_minutes = $hour * 60 + $minute;
        $time_key = floor($total_minutes / 5);

        return $time_key;
    }
}
