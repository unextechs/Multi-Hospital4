<div class="row">
    <!-- Personal Information - More Compact -->
    <div class="col-lg-4">
        <div class="info-group mb-3">
            <h6 class="text-primary mb-2 font-weight-bold">
                <?php echo lang('personaladjustment_information'); ?>
            </h6>
            <div class="row">
                <div class="col-6 mb-2">
                    <label class="text-muted mb-0 small"><?php echo lang('patient_name'); ?></label>
                    <div class="font-weight-bold text-dark"><?php echo $patient->name; ?></div>
                </div>
                <div class="col-12 mb-2">
                    <label class="text-muted mb-0 small">Patient ID</label>
                    <div class="font-weight-medium">
                        <span class="badge badge-primary">
                            <i class="fas fa-id-card mr-1"></i>
                            <?php echo (!empty($settings->patient_id_prefix) ? $settings->patient_id_prefix : 'P') . $patient->hospital_patient_id; ?>
                        </span>
                    </div>
                </div>

                <div class="col-6 mb-2">
                    <label class="text-muted mb-0 small"><?php echo lang('age'); ?></label>
                    <div class="font-weight-medium small">
                        <?php
                        if (!empty($patient->birthdate)) {
                            $birthdate = new DateTime($patient->birthdate);
                            $today = new DateTime('today');
                            $age = $birthdate->diff($today);
                            echo $age->y . 'y ' . $age->m . 'm';
                        }
                        ?>
                    </div>
                </div>
                <div class="col-6 mb-2">
                    <label class="text-muted mb-0 small"><?php echo lang('gender'); ?></label>
                    <div class="font-weight-medium small"><?php echo $patient->sex; ?></div>
                </div>
                <div class="col-6 mb-2">
                    <label class="text-muted mb-0 small"><?php echo lang('phone'); ?></label>
                    <div class="font-weight-medium small"><?php echo $patient->phone; ?></div>
                </div>
                <div class="col-6 mb-2">
                    <label class="text-muted mb-0 small"><?php echo lang('blood_group'); ?></label>
                    <div class="font-weight-medium small"><?php echo $patient->bloodgroup; ?></div>
                </div>
            </div>
        </div>

        <!-- Vital Signs Section -->
        <div class="info-group mb-3">
            <h6 class="text-success mb-2 font-weight-bold">
                <i class="fas fa-heartbeat"></i> Vital Signs
            </h6>
            <form id="vital_signs_form">
                <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">
                <div class="row">
                    <div class="col-6 mb-2">
                        <label class="text-muted mb-0 small">BP (mmHg)</label>
                        <input type="text" class="form-control form-control-sm" name="blood_pressure"
                            value="<?php echo !empty($patient->blood_pressure) ? $patient->blood_pressure : ''; ?>"
                            placeholder="120/80" readonly>
                    </div>
                    <div class="col-6 mb-2">
                        <label class="text-muted mb-0 small">Temp (°C)</label>
                        <input type="text" class="form-control form-control-sm" name="temperature"
                            value="<?php echo !empty($patient->temperature) ? $patient->temperature : ''; ?>"
                            placeholder="37" readonly>
                    </div>
                    <div class="col-6 mb-2">
                        <label class="text-muted mb-0 small">Pulse (bpm)</label>
                        <input type="text" class="form-control form-control-sm" name="pulse"
                            value="<?php echo !empty($patient->pulse) ? $patient->pulse : ''; ?>" placeholder="72"
                            readonly>
                    </div>
                    <div class="col-6 mb-2">
                        <label class="text-muted mb-0 small">Resp. Rate</label>
                        <input type="text" class="form-control form-control-sm" name="resp_rate"
                            value="<?php echo !empty($patient->resp_rate) ? $patient->resp_rate : ''; ?>"
                            placeholder="16" readonly>
                    </div>
                </div>
                <?php if ($this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))): ?>
                    <button type="button" class="btn btn-success btn-sm btn-block mt-2" id="editVitalSignsBtn">
                        <i class="fa fa-edit"></i> <?php echo lang('edit'); ?>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm btn-block mt-2" id="saveVitalSignsBtn"
                        style="display:none;">
                        <i class="fa fa-save"></i> <?php echo lang('save'); ?>
                    </button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Medical Information - Compact -->
    <div class="col-lg-8">
        <div class="info-group mb-3">
            <h6 class="text-primary mb-2 font-weight-bold">
                <?php echo lang('medical_information'); ?>
            </h6>

            <form id="medical_info_form">
                <input type="hidden" name="patient_id" value="<?php echo $patient->id; ?>">

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="text-muted mb-0 small"><?php echo lang('known_allergies'); ?></label>
                        <textarea class="form-control form-control-sm" name="known_allergies" rows="2"
                            readonly><?php echo !empty($patient->known_allergies) ? $patient->known_allergies : ''; ?></textarea>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="text-muted mb-0 small"><?php echo lang('medical_history'); ?></label>
                        <textarea class="form-control form-control-sm" name="medical_history" rows="2"
                            readonly><?php echo !empty($patient->medical_history) ? $patient->medical_history : ''; ?></textarea>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="text-muted mb-0 small">
                            <?php echo lang('symptoms'); ?>
                            <?php if ($this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))): ?>
                                <button type="button" class="btn btn-xs btn-outline-primary ml-1 px-2" data-toggle="modal"
                                    data-target="#addSymptomModal" title="Add New Symptom">
                                    <i class="fa fa-plus"></i>
                                </button>
                            <?php endif; ?>
                        </label>
                        <select class="form-control form-control-sm" id="workspace_symptom_select" name="symptom_id[]"
                            multiple="multiple" style="width: 100%;">
                        </select>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="text-muted mb-0 small">
                            <?php echo lang('diagnosis'); ?>
                            <?php if ($this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))): ?>
                                <button type="button" class="btn btn-xs btn-outline-primary ml-1 px-2" data-toggle="modal"
                                    data-target="#addDiagnosisModal" title="Add New Diagnosis">
                                    <i class="fa fa-plus"></i>
                                </button>
                            <?php endif; ?>
                        </label>
                        <select class="form-control form-control-sm" id="workspace_diagnosis_select"
                            name="diagnosis_id[]" multiple="multiple" style="width: 100%;">
                        </select>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label class="text-muted mb-0 small"><?php echo lang('note'); ?></label>
                        <textarea class="form-control form-control-sm" name="notes" rows="2" readonly
                            placeholder="<?php echo lang('note'); ?>"><?php echo !empty($patient->notes) ? $patient->notes : ''; ?></textarea>
                    </div>
                </div>

                <?php if ($this->ion_auth->in_group(array('admin', 'superadmin', 'Doctor'))): ?>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary btn-sm btn-block" id="editMedicalInfoBtn">
                                <i class="fa fa-edit"></i> <?php echo lang('edit'); ?>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-success btn-sm btn-block" id="saveMedicalInfoBtn"
                                style="display:none;">
                                <i class="fa fa-save"></i> <?php echo lang('save'); ?>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Case History -->
        <?php $this->load->view('doctor/workspace/case_history', array('patient' => $patient)); ?>
    </div>
</div>

<!-- Add Symptom Modal -->
<div class="modal fade" id="addSymptomModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo lang('add_new_symptom'); ?></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label><?php echo lang('name'); ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="new_symptom_name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal"><?php echo lang('cancel'); ?></button>
                <button type="button" class="btn btn-primary" id="saveSymptomBtn">
                    <span class="spinner-border spinner-border-sm" style="display:none;"></span>
                    <span class="button-text"><?php echo lang('save'); ?></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Diagnosis Modal -->
<div class="modal fade" id="addDiagnosisModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo lang('add_new_diagnosis'); ?></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label><?php echo lang('disease'); ?> <?php echo lang('name'); ?> <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="new_diagnosis_name" required>
                </div>
                <div class="form-group">
                    <label><?php echo lang('icd 10'); ?> <?php echo lang('code'); ?>
                        < /label>
                            <input type="text" class="form-control" id="new_diagnosis_code">
                </div>
                <div class="form-group">
                    <label><?php echo lang('description'); ?></label>
                    <textarea class="form-control" id="new_diagnosis_description" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal"><?php echo lang('cancel'); ?></button>
                <button type="button" class="btn btn-primary" id="saveDiagnosisBtn">
                    <span class="spinner-border spinner-border-sm" style="display:none;"></span>
                    <span class="button-text"><?php echo lang('save'); ?></span>
                </button>
            </div>
        </div>
    </div>
</div>