<script type="text/javascript">
    window.base_url = "<?php echo base_url(); ?>";
</script>
<!-- Core Doctor Workspace CSS -->
<style>
    .doctor-workspace-modal {
        padding: 0 !important;
    }

    .doctor-workspace-modal .modal-dialog {
        width: 100%;
        max-width: 95%;
        margin: 10px auto;
        height: 95vh;
    }

    .doctor-workspace-modal .modal-content {
        height: 100%;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .workspace-header {
        background: #fff;
        border-bottom: 1px solid #edf2f7;
        padding: 12px 24px;
        flex-shrink: 0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        z-index: 10;
        height: 70px;
    }

    .brand-logo {
        height: 45px;
        margin-right: 20px;
        border-right: 1px solid #e2e8f0;
        padding-right: 20px;
    }

    .patient-identity h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #1a202c;
        letter-spacing: -0.5px;
    }

    .workspace-body {
        flex-grow: 1;
        display: flex;
        overflow: hidden;
        background: #f8fafc;
        font-family: 'Inter', sans-serif;
    }

    .workspace-nav {
        width: 260px;
        background: #1a202c;
        color: #a0aec0;
        flex-shrink: 0;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .workspace-nav-header {
        padding: 20px 24px;
        border-bottom: 1px solid #2d3748;
        margin-bottom: 8px;
    }

    .workspace-nav .nav-link {
        color: #a0aec0;
        padding: 14px 24px;
        border-radius: 0;
        transition: all 0.2s ease;
        font-weight: 500;
        display: flex;
        align-items: center;
        border-left: 4px solid transparent;
        font-size: 15px;
    }

    .workspace-nav .nav-link i {
        margin-right: 14px;
        width: 20px;
        text-align: center;
        font-size: 18px;
        opacity: 0.8;
    }

    .workspace-nav .nav-link:hover {
        background: #2d3748;
        color: #fff;
    }

    .workspace-nav .nav-link.active {
        background: #2d3748;
        color: #fff;
        border-left-color: #3182ce;
        font-weight: 600;
    }

    .workspace-content {
        flex-grow: 1;
        padding: 30px;
        overflow-y: auto;
        background: #f8fafc;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 24px;
        position: relative;
        padding-bottom: 12px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 4px;
        background: #3182ce;
        border-radius: 2px;
    }

    .history-card {
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        margin-top: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
    }

    .history-header {
        background: #f8fafc;
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
        border-radius: 10px 10px 0 0;
    }

    .history-header h6 {
        margin: 0;
        font-weight: 700;
        color: #4a5568;
    }

    .btn-circle {
        width: 38px;
        height: 38px;
        border-radius: 50% !important;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 !important;
        background: #f8fafc !important;
        transition: all 0.2s;
    }

    .btn-circle:hover {
        background: #e2e8f0 !important;
        transform: scale(1.05);
    }

    .btn-circle i {
        font-size: 16px;
    }

    .workspace-nav-footer {
        margin-top: auto;
        padding: 20px;
        border-top: 1px solid #2d3748;
    }

    .exit-btn {
        background: rgba(229, 62, 62, 0.1);
        color: #feb2b2 !important;
        border: 1px solid rgba(229, 62, 62, 0.2);
        width: 100%;
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-radius: 8px;
        transition: all 0.2s;
        text-decoration: none !important;
    }

    .exit-btn:hover {
        background: #e53e3e;
        color: #fff !important;
        transform: translateY(-2px);
    }

    .remove-med-btn {
        background: #fff5f5 !important;
        border: 1px solid #feb2b2 !important;
        color: #e53e3e !important;
        width: 32px;
        height: 32px;
        border-radius: 6px !important;
        padding: 0 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .remove-med-btn:hover {
        background: #e53e3e !important;
        color: #fff !important;
        border-color: #c53030 !important;
    }

    .table-workspace {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
    }

    .table-workspace th {
        background: #f1f5f9;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        padding: 12px 20px;
        border: none;
    }

    .table-workspace td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #334155;
    }

    .table-workspace tr:last-child td {
        border-bottom: none;
    }

    .table-workspace tr:hover td {
        background: #f8fafc;
    }

    .badge-status {
        font-weight: 500;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 6px;
    }

    .status-pending {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fef3c7;
    }

    .status-done {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #dcfce7;
    }

    .workspace-section {
        display: none;
        width: 100%;
    }

    .workspace-section.active {
        display: block;
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .workspace-body {
            flex-direction: column;
        }

        .workspace-nav {
            width: 100%;
            max-height: 220px;
            border-bottom: 2px solid #2d3748;
        }

        .workspace-content {
            padding: 20px;
            height: calc(100vh - 290px);
        }
    }
</style>

<div class="modal fade doctor-workspace-modal" id="doctorWorkspaceModal" tabindex="-1" role="dialog"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <!-- Header -->
            <div class="workspace-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <?php
                    $settings = $this->db->get('settings')->row();
                    $logo_url = !empty($settings->logo) ? $settings->logo : 'common/img/logo.png';
                    ?>
                    <img src="<?php echo $logo_url; ?>" class="brand-logo" alt="Logo">

                    <div class="patient-identity">
                        <h2>
                            <?php echo $patient->name; ?>
                        </h2>
                        <div class="d-flex align-items-center small text-muted mt-1">
                            <span class="mr-3"><i class="fas fa-id-card-alt text-primary mr-1"></i> <strong>ID:</strong>
                                P
                                <?php echo $patient->hospital_patient_id; ?>
                            </span>
                            <span class="mr-3"><i class="fas fa-venus-mars mr-1"></i>
                                <?php echo $patient->sex; ?>
                            </span>
                            <?php if (!empty($age)): ?>
                                <span><i class="fas fa-calendar-alt mr-1"></i>
                                    <?php echo $age_full; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <button type="button" class="btn btn-light btn-circle border shadow-sm" data-dismiss="modal"
                        data-bs-dismiss="modal" title="Close"><i class="fas fa-times text-dark"></i></button>
                </div>
            </div>

            <div class="workspace-body">
                <!-- Navigation -->
                <nav class="workspace-nav">
                    <div class="workspace-nav-header">
                        <span class="text-uppercase small tracking-widest text-white-50">Main Tabs</span>
                    </div>
                    <div class="nav flex-column nav-pills">
                        <a class="nav-link active" id="tab-overview" href="#section-overview"><i
                                class="fas fa-user-md"></i> Medical Profile</a>
                        <a class="nav-link" id="tab-vitals" href="#section-vitals"><i class="fas fa-heartbeat"></i>
                            Vital Signs</a>
                        <a class="nav-link" id="tab-lab" href="#section-lab-request"><i class="fas fa-flask"></i> Lab
                            Request</a>
                        <a class="nav-link" id="tab-medicine-log" href="#section-medicine-log"><i
                                class="fas fa-pills"></i> Medicine Log</a>
                        <a class="nav-link" id="tab-prescription" href="#section-prescription"><i
                                class="fas fa-file-prescription"></i> Prescription</a>
                        <a class="nav-link" id="tab-daycare" href="#section-daycare"><i class="fas fa-sun"></i> Request
                            Day Care</a>
                        <a class="nav-link" id="tab-emergency" href="#section-emergency"><i
                                class="fas fa-ambulance"></i> Request Emergency</a>
                    </div>
                    <div class="workspace-nav-footer">
                        <button type="button" class="exit-btn border-0" data-dismiss="modal" data-bs-dismiss="modal">
                            <i class="fas fa-sign-out-alt mr-2"></i> Exit Workspace
                        </button>
                    </div>
                </nav>

                <!-- Content -->
                <main class="workspace-content">

                    <!-- 1. MEDICAL PROFILE -->
                    <div id="section-overview" class="workspace-section active" style="display: block;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="section-title mb-0 text-primary">Patient Profile</h4>
                            <div class="btn-group shadow-sm">
                                <button class="btn btn-outline-primary px-4" id="editMedicalInfoBtn"><i
                                        class="fas fa-edit mr-2"></i>Edit</button>
                                <button class="btn btn-success px-4" id="saveMedicalInfoBtn" style="display: none;"><i
                                        class="fas fa-save mr-2"></i>Save Changes</button>
                            </div>
                        </div>

                        <form id="medical_info_form">
                            <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
                            <input type="hidden" name="doctor_id"
                                value="<?php echo isset($doctor_id) ? $doctor_id : ''; ?>">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
                                        <div class="card-header bg-danger text-white py-2"><i
                                                class="fas fa-exclamation-triangle mr-2"></i>Known Allergies</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 p-3" name="known_allergies" rows="4"
                                                readonly
                                                style="resize:none;"><?php echo isset($patient->known_allergies) ? $patient->known_allergies : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
                                        <div class="card-header bg-primary text-white py-2"><i
                                                class="fas fa-history mr-2"></i>Family/Medical History</div>
                                        <div class="card-body p-0">
                                            <textarea class="form-control border-0 p-3" name="medical_history" rows="4"
                                                readonly
                                                style="resize:none;"><?php echo isset($patient->medical_history) ? $patient->medical_history : ''; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm rounded-lg">
                                        <div class="card-header bg-info text-white py-2"><i
                                                class="fas fa-stethoscope mr-2"></i>Ongoing Symptoms</div>
                                        <div class="card-body">
                                            <select class="form-control" id="workspace_symptom_select" name="symptoms[]"
                                                multiple="multiple" disabled></select>
                                            <div class="mt-2 text-right"><a href="#"
                                                    class="text-info small font-weight-bold" data-toggle="modal"
                                                    data-target="#addSymptomModal"><i
                                                        class="fa fa-plus-circle mr-1"></i>Add New</a></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm rounded-lg">
                                        <div class="card-header bg-dark text-white py-2"><i
                                                class="fas fa-diagnoses mr-2"></i>Clinical Diagnosis</div>
                                        <div class="card-body">
                                            <select class="form-control" id="workspace_diagnosis_select"
                                                name="diagnosis[]" multiple="multiple" disabled></select>
                                            <div class="mt-2 text-right"><a href="#"
                                                    class="text-dark small font-weight-bold" data-toggle="modal"
                                                    data-target="#addDiagnosisModal"><i
                                                        class="fa fa-plus-circle mr-1"></i>Add New</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Medical History Audit -->
                        <div class="history-card">
                            <div class="history-header">
                                <h6><i class="fas fa-history mr-2"></i>Medical History Log</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-workspace mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($medical_histories)):
                                            foreach ($medical_histories as $mh): ?>
                                                <tr>
                                                    <td>
                                                        <?php echo date('d M Y', strtotime($mh->date)); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $mh->description; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; else: ?>
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">No records found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 2. VITAL SIGNS -->
                    <div id="section-vitals" class="workspace-section" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="section-title mb-0 text-primary">Vital Signs</h4>
                            <div class="btn-group shadow-sm">
                                <button class="btn btn-outline-primary btn-sm px-4" id="editVitalSignsBtn"><i
                                        class="fas fa-plus mr-2"></i>New Entry</button>
                                <button class="btn btn-success btn-sm px-4" id="saveVitalSignsBtn"
                                    style="display: none;"><i class="fas fa-save mr-2"></i>Save Vitals</button>
                            </div>
                        </div>

                        <form id="vital_signs_form">
                            <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
                            <?php $current_vs = !empty($vital_signs) ? $vital_signs[0] : null; ?>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group"><label class="small text-muted font-weight-bold">BP
                                            (mmHg)</label><input type="text"
                                            class="form-control form-control-lg border-0 shadow-sm"
                                            name="blood_pressure"
                                            value="<?php echo !empty($current_vs->systolic_blood_pressure) ? $current_vs->systolic_blood_pressure . '/' . $current_vs->diastolic_blood_pressure : ''; ?>"
                                            placeholder="120/80" readonly></div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <div class="form-group"><label class="small text-muted font-weight-bold">Temp
                                            (°C)</label><input type="text"
                                            class="form-control form-control-lg border-0 shadow-sm" name="temperature"
                                            value="<?php echo isset($current_vs->temperature) ? $current_vs->temperature : ''; ?>"
                                            placeholder="37.0" readonly></div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <div class="form-group"><label class="small text-muted font-weight-bold">Heart Rate
                                            (bpm)</label><input type="text"
                                            class="form-control form-control-lg border-0 shadow-sm" name="pulse_rate"
                                            value="<?php echo isset($current_vs->heart_rate) ? $current_vs->heart_rate : ''; ?>"
                                            placeholder="72" readonly></div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <div class="form-group"><label class="small text-muted font-weight-bold">Resp Rate
                                            (bpm)</label><input type="text"
                                            class="form-control form-control-lg border-0 shadow-sm"
                                            name="respiration_rate"
                                            value="<?php echo isset($current_vs->respiratory_rate) ? $current_vs->respiratory_rate : ''; ?>"
                                            placeholder="18" readonly></div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <div class="form-group"><label class="small text-muted font-weight-bold">Weight
                                            (kg)</label><input type="text"
                                            class="form-control form-control-lg border-0 shadow-sm" name="weight"
                                            value="<?php echo isset($current_vs->bmi_weight) ? $current_vs->bmi_weight : ''; ?>"
                                            placeholder="70" readonly></div>
                                </div>
                            </div>
                        </form>

                        <div class="history-card">
                            <div class="history-header">
                                <h6><i class="fas fa-chart-line mr-2 text-primary"></i>Vital Signs Records</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-workspace mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>BP</th>
                                            <th>Temp</th>
                                            <th>Pulse</th>
                                            <th>Resp</th>
                                            <th>Weight</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($vital_signs)):
                                            foreach ($vital_signs as $vs): ?>
                                                <tr>
                                                    <td>
                                                        <?php echo !empty($vs->add_date_time) ? date('d M Y, H:i', strtotime($vs->add_date_time)) : 'N/A'; ?>
                                                    </td>
                                                    <td><span class="font-weight-bold">
                                                            <?php echo !empty($vs->systolic_blood_pressure) ? $vs->systolic_blood_pressure . '/' . $vs->diastolic_blood_pressure : 'N/A'; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php echo $vs->temperature ? $vs->temperature : 'N/A'; ?>°C
                                                    </td>
                                                    <td>
                                                        <?php echo $vs->heart_rate ? $vs->heart_rate : 'N/A'; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $vs->respiratory_rate ? $vs->respiratory_rate : 'N/A'; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $vs->bmi_weight ? $vs->bmi_weight : 'N/A'; ?>kg
                                                    </td>
                                                </tr>
                                            <?php endforeach; else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No vitals recorded yet</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 3. LAB REQUEST -->
                    <div id="section-lab-request" class="workspace-section" style="display: none;">
                        <h4 class="section-title text-primary">Laboratory Requests</h4>

                        <div class="card border-0 shadow-sm mb-5">
                            <div class="card-body p-4">
                                <form id="workspace_lab_request_form">
                                    <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
                                    <input type="hidden" name="doctor_id"
                                        value="<?php echo isset($doctor_id) ? $doctor_id : ''; ?>">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-dark mb-2">Order New Investigation</label>
                                        <select class="form-control" id="workspace_lab_category" name="category_id[]"
                                            multiple="multiple" style="width: 100%;">
                                            <?php if (!empty($payment_categories)):
                                                foreach ($payment_categories as $param): ?>
                                                    <option value="<?php echo $param->id; ?>">
                                                        <?php echo $param->category; ?>
                                                    </option>
                                                <?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-outline-primary mr-2 submit-btn"
                                            data-type="save">
                                            <i class="fas fa-save mr-1"></i> Send Lab Request
                                        </button>
                                        <button type="submit" class="btn btn-primary submit-btn" data-type="print">
                                            <i class="fas fa-print mr-1"></i> Print & Send
                                        </button>
                                    </div>
                                    <input type="hidden" name="submission_type" class="submission-type" value="save">
                                </form>
                            </div>
                        </div>

                        <div class="history-card">
                            <div class="history-header">
                                <h6><i class="fas fa-flask mr-2 text-primary"></i>Recent Orders</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-workspace mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Investigation</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($lab_requests)):
                                            foreach ($lab_requests as $lab): ?>
                                                <tr>
                                                    <td>#
                                                        <?php echo $lab->id; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $lab->date_string; ?>
                                                    </td>
                                                    <td class="font-weight-bold">
                                                        <?php
                                                        $cat_name = 'Unknown';
                                                        foreach ($payment_categories as $pc) {
                                                            if ($pc->id == $lab->category_id) {
                                                                $cat_name = $pc->category;
                                                                break;
                                                            }
                                                        }
                                                        echo $cat_name;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge-status <?php echo ($lab->status == 'pending') ? 'status-pending' : 'status-done'; ?>">
                                                            <?php echo ($lab->status == 'complete') ? 'Completed' : ucfirst($lab->status); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($lab->status == 'pending'): ?>
                                                            <button class="btn btn-sm btn-outline-primary edit-lab-workspace"
                                                                data-id="<?php echo $lab->id; ?>">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ($lab->status == 'complete'): ?>
                                                            <button class="btn btn-sm btn-success view-lab-results"
                                                                data-id="<?php echo $lab->id; ?>">
                                                                <i class="fas fa-eye"></i> View Results
                                                            </button>
                                                            <button class="btn btn-sm btn-secondary mark-lab-seen ml-1"
                                                                data-id="<?php echo $lab->id; ?>">
                                                                <i class="fas fa-check"></i> Mark Seen
                                                            </button>
                                                        <?php endif; ?>
                                                        <button class="btn btn-danger btn-sm delete-lab-workspace ml-1"
                                                            data-id="<?php echo $lab->id; ?>" title="Delete"
                                                            style="width: 35px; height: 35px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                                            <i class="fas fa-trash" style="font-size: 16px;"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No lab requests found
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 4. MEDICINE LOG -->
                    <div id="section-medicine-log" class="workspace-section" style="display: none;">
                        <h4 class="section-title">Medicine Log - Recent Prescriptions</h4>

                        <div class="history-card">
                            <div class="history-header">
                                <h6><i class="fas fa-pills mr-2 text-success"></i>Recent Prescription History</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-workspace mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Patient</th>
                                            <th>Diagnosis</th>
                                            <th>Medicines Count</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($prescriptions) && count($prescriptions) > 0):
                                            // Get unique prescriptions by grouping by date and patient
                                            $grouped_prescriptions = [];
                                            foreach ($prescriptions as $presc) {
                                                $key = $presc->date . '_' . $patient->id;
                                                if (!isset($grouped_prescriptions[$key])) {
                                                    $grouped_prescriptions[$key] = [
                                                        'date' => $presc->date,
                                                        'prescription_id' => $presc->id,
                                                        'medicines' => []
                                                    ];
                                                }
                                                $grouped_prescriptions[$key]['medicines'][] = $presc;
                                            }

                                            foreach ($grouped_prescriptions as $group): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', $group['date']); ?></td>
                                                    <td><?php echo $patient->name; ?></td>
                                                    <td>
                                                        <?php
                                                        // Get diagnosis from medical info
                                                        $diagnosis_text = 'Not recorded';
                                                        if (isset($medical_info->diagnosis) && !empty($medical_info->diagnosis)) {
                                                            $diagnosis_ids = explode(',', $medical_info->diagnosis);
                                                            $diagnosis_names = [];
                                                            foreach ($diagnosis_ids as $diag_id) {
                                                                foreach ($diagnoses as $d) {
                                                                    if ($d->id == $diag_id) {
                                                                        $diagnosis_names[] = $d->name;
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                            if (!empty($diagnosis_names)) {
                                                                $diagnosis_text = implode(', ', $diagnosis_names);
                                                            }
                                                        }
                                                        echo substr($diagnosis_text, 0, 50) . (strlen($diagnosis_text) > 50 ? '...' : '');
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info"><?php echo count($group['medicines']); ?>
                                                            medicines</span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-xs btn-primary view-prescription-details"
                                                            data-date="<?php echo $group['date']; ?>">
                                                            <i class="fas fa-eye"></i> View Details
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach;
                                        else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No prescriptions found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Medicine Log:</strong> Click "View Details" to see full medical information
                            including symptoms, diagnosis, clinical notes, and all prescribed medicines.
                        </div>
                    </div>

                    <!-- 5. PRESCRIPTION -->
                    <div id="section-prescription" class="workspace-section" style="display: none;">
                        <h4 class="section-title text-primary">Electronic Prescription</h4>

                        <div class="card border-0 shadow-sm mb-5">
                            <div class="card-body p-4">
                                <form id="workspace_prescription_form">
                                    <input type="hidden" name="id" value="" class="prescription-id">
                                    <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
                                    <input type="hidden" name="doctor_id"
                                        value="<?php echo isset($doctor_id) ? $doctor_id : ''; ?>">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group"><label class="font-weight-bold">Clinical
                                                    Notes /
                                                    Advice</label><textarea class="form-control shadow-sm" name="note"
                                                    rows="3"
                                                    placeholder="Enter clinical notes or advice for the patient..."><?php echo isset($current_prescription->note) ? $current_prescription->note : ''; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Search & Add Medication</label>
                                                <select class="form-control shadow-sm" id="workspace_medicine_select"
                                                    name="medicine" style="width: 100%;"></select>
                                            </div>
                                            <div id="prescription_medicine_list" class="mt-4">
                                                <!-- Dynamic Items -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-outline-success mr-2 submit-btn"
                                            data-type="save">
                                            <i class="fas fa-save mr-1"></i> Finalize & Save
                                        </button>
                                        <button type="submit" class="btn btn-success submit-btn" data-type="print">
                                            <i class="fas fa-print mr-1"></i> Finalize, Save & Print
                                        </button>
                                    </div>
                                    <input type="hidden" name="submission_type" class="submission-type" value="save">
                                </form>
                            </div>
                        </div>

                        <div class="history-card">
                            <div class="history-header">
                                <h6><i class="fas fa-clipboard-list mr-2 text-primary"></i>Prescription History
                                </h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-workspace mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Medications</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($prescriptions)):
                                            foreach ($prescriptions as $pr): ?>
                                                <tr>
                                                    <td>
                                                        <?php echo date('d M Y', $pr->date); ?>
                                                    </td>
                                                    <td style="max-width: 400px;">
                                                        <?php
                                                        $meds = explode('###', $pr->medicine);
                                                        foreach ($meds as $m) {
                                                            $parts = explode('***', $m);
                                                            if (count($parts) > 0) {
                                                                $medicine_id = trim($parts[0]);
                                                                if (empty($medicine_id))
                                                                    continue;
                                                                $medicine_name = 'Unknown Medicine (' . $medicine_id . ')';
                                                                $medicine_route = '';
                                                                if (!empty($all_medicines)) {
                                                                    foreach ($all_medicines as $med) {
                                                                        if ($med->id == $medicine_id) {
                                                                            $medicine_name = $med->name;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                                // Extract route from parts (index 6)
                                                                if (isset($parts[6]) && !empty($parts[6])) {
                                                                    $medicine_route = ' <span class="text-muted small">(' . $parts[6] . ')</span>';
                                                                }
                                                                echo '<div class="small mb-1">• <strong>' . $medicine_name . '</strong>' . $medicine_route . '</div>';
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <a href="prescription/viewPrescription?id=<?php echo $pr->id; ?>"
                                                            target="_blank" class="btn btn-sm btn-outline-info"><i
                                                                class="fas fa-eye mr-1"></i>View</a>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary edit-prescription-workspace"
                                                            data-id="<?php echo $pr->id; ?>"><i
                                                                class="fas fa-edit mr-1"></i>Edit</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No prescriptions
                                                    found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 5. DAY CARE REQUEST -->
                    <div id="section-daycare" class="workspace-section" style="display: none;">
                        <h4 class="section-title text-primary">Request Day Care Admission</h4>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <form id="workspace_daycare_request_form">
                                    <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
                                    <input type="hidden" name="doctor_id"
                                        value="<?php echo isset($doctor_id) ? $doctor_id : ''; ?>">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Admission Date</label>
                                                <input type="date" class="form-control shadow-sm" name="admission_date"
                                                    value="<?php echo date('Y-m-d'); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Expected Duration (Hours)</label>
                                                <input type="text" class="form-control shadow-sm" name="duration"
                                                    placeholder="e.g. 4 hours">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Reason for Day Care</label>
                                                <textarea class="form-control shadow-sm" name="reason" rows="3"
                                                    placeholder="Specify reason for day care admission..."
                                                    required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Special Instructions</label>
                                                <textarea class="form-control shadow-sm" name="instructions" rows="2"
                                                    placeholder="Any special care instructions..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="fas fa-paper-plane mr-2"></i>Submit Day Care Request
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Note:</strong> Day care requests will be sent to the admissions department for
                            processing.
                        </div>
                    </div>

                    <!-- 6. EMERGENCY REQUEST -->
                    <div id="section-emergency" class="workspace-section" style="display: none;">
                        <h4 class="section-title text-danger">Request Emergency Admission</h4>

                        <div class="card border-0 shadow-sm border-danger">
                            <div class="card-body p-4">
                                <form id="workspace_emergency_request_form">
                                    <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
                                    <input type="hidden" name="doctor_id"
                                        value="<?php echo isset($doctor_id) ? $doctor_id : ''; ?>">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold text-danger">Priority Level</label>
                                                <select class="form-control shadow-sm" name="priority" required>
                                                    <option value="">Select Priority</option>
                                                    <option value="critical">Critical - Immediate</option>
                                                    <option value="high">High - Urgent</option>
                                                    <option value="medium">Medium - Soon</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Chief Complaint</label>
                                                <input type="text" class="form-control shadow-sm" name="chief_complaint"
                                                    placeholder="e.g., Chest pain, Trauma, etc." required>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Clinical Assessment</label>
                                                <textarea class="form-control shadow-sm" name="assessment" rows="3"
                                                    placeholder="Brief clinical assessment and findings..."
                                                    required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Immediate Actions Required</label>
                                                <textarea class="form-control shadow-sm" name="actions" rows="2"
                                                    placeholder="Specify any immediate interventions needed..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-danger px-4">
                                            <i class="fas fa-ambulance mr-2"></i>Submit Emergency Request
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="alert alert-danger mt-4">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Emergency Protocol:</strong> This request will immediately notify the emergency
                            department and on-call staff.
                        </div>
                    </div>

                </main>
            </div>
        </div>

    </div>
</div>
</div>

<!-- Medicine Row Template -->
<template id="medicine_row_template">
    <div class="medicine-entry-card card border-0 shadow-sm mb-3 bg-white">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="med-name text-primary font-weight-bold mb-0 flex-grow-1">Medicine Name</h6>
                <button type="button" class="remove-med-btn flex-shrink-0" title="Remove Medicine">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            <input type="hidden" name="medicine[]" class="med-id">
            <div class="row small">
                <div class="col-md-2 mb-2">
                    <label class="font-weight-bold text-muted mb-1">Dosage</label>
                    <input type="text" name="dosage[]" class="form-control form-control-sm" placeholder="e.g. 500mg">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="font-weight-bold text-muted mb-1">Frequency</label>
                    <input type="text" name="frequency[]" class="form-control form-control-sm" placeholder="1+0+1">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="font-weight-bold text-muted mb-1">Duration</label>
                    <input type="text" name="days[]" class="form-control form-control-sm" placeholder="e.g. 7 days">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="font-weight-bold text-muted mb-1">Route</label>
                    <input type="text" name="route[]" class="form-control form-control-sm" placeholder="e.g. Oral">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="font-weight-bold text-muted mb-1">Quantity</label>
                    <input type="text" name="quantity[]" class="form-control form-control-sm"
                        placeholder="e.g. 1 Vial/Strip">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="font-weight-bold text-muted mb-1">Instruction</label>
                    <input type="text" name="instruction[]" class="form-control form-control-sm"
                        placeholder="Before meal">
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Add Symptom Modal (Z-index fixed for nested modals) -->
<div class="modal fade" id="addSymptomModal" tabindex="-1" role="dialog" aria-hidden="true"
    style="z-index: 1060; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">New Symptom</h5><button type="button" class="close text-white"
                    data-dismiss="modal" data-bs-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group"><label class="font-weight-bold">Symptom Name</label><input type="text"
                        class="form-control form-control-lg border-info" id="new_symptom_name" required
                        placeholder="e.g. Fever, Headache"></div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light border px-4" data-dismiss="modal"
                    data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info px-4" id="saveSymptomBtn"><span
                        class="spinner-border spinner-border-sm mr-2" style="display:none;"></span>Save Symptom</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Diagnosis Modal -->
<div class="modal fade" id="addDiagnosisModal" tabindex="-1" role="dialog" aria-hidden="true"
    style="z-index: 1060; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">New Diagnosis</h5><button type="button" class="close text-white"
                    data-dismiss="modal" data-bs-dismiss="modal"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group mb-3"><label class="font-weight-bold">Diagnosis Name</label><input type="text"
                        class="form-control form-control-lg" id="new_diagnosis_name" required></div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label class="small font-weight-bold">ICD Code</label><input type="text"
                                class="form-control" id="new_diagnosis_code"></div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group"><label class="small font-weight-bold">Brief Description</label><textarea
                                class="form-control" id="new_diagnosis_description" rows="2"></textarea></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-light border px-4" data-dismiss="modal"
                    data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark px-4" id="saveDiagnosisBtn"><span
                        class="spinner-border spinner-border-sm mr-2" style="display:none;"></span>Add
                    Diagnosis</button>
            </div>
        </div>
    </div>
</div>

<!-- Prescription Details Modal (Medicine Log) -->
<div class="modal fade" id="prescriptionDetailsModal" tabindex="-1" role="dialog" aria-hidden="true"
    style="z-index: 1060; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-file-medical mr-2"></i>Medical Record Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal"><i
                        class="fa fa-times"></i></button>
            </div>
            <div class="modal-body p-4" id="prescription_details_content">
                <!-- Content loaded via AJAX -->
                <div class="text-center p-5">
                    <div class="spinner-border text-success" role="status"></div>
                    <p class="mt-2 text-muted">Loading medical record details...</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"
                    data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary px-4" id="printPrescriptionBtn"><i
                        class="fas fa-print mr-2"></i>Print Record</button>
            </div>
        </div>
    </div>
</div>

<!-- Lab Result View Modal -->
<div class="modal fade" id="labResultModal" tabindex="-1" role="dialog" aria-hidden="true"
    style="z-index: 1060; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-flask mr-2"></i>Lab Investigation Results</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal"><i
                        class="fa fa-times"></i></button>
            </div>
            <div class="modal-body p-4" id="lab_result_content">
                <!-- Content loaded via AJAX -->
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Fetching investigation results...</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"
                    data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success px-4" id="printLabResultBtn"><i
                        class="fas fa-print mr-2"></i>Print Result</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Lab Modal -->
<div class="modal fade" id="editLabModal" tabindex="-1" role="dialog" aria-hidden="true"
    style="z-index: 1060; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-indigo text-white">
                <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Lab Request</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal"><i
                        class="fa fa-times"></i></button>
            </div>
            <form id="edit_lab_form">
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="edit_lab_id">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Investigation Type</label>
                        <select class="form-control select2" name="category_id" id="edit_lab_category" required
                            style="width: 100%;">
                            <option value="">Select Investigation</option>
                            <?php foreach ($payment_categories as $category): ?>
                                <option value="<?php echo $category->id; ?>"><?php echo $category->category; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Clinical Notes</label>
                        <textarea class="form-control" name="report" id="edit_lab_notes" rows="3"
                            placeholder="Any specific requirements..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light border px-4" data-dismiss="modal"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-indigo px-4"><span
                            class="spinner-border spinner-border-sm mr-2" style="display:none;"></span>Update
                        Request</button>
                </div>
            </form>
        </div>
    </div>
</div>