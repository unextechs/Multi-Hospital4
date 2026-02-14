<!--sidebar end-->
<!--main content start-->
<link href="common/extranal/css/dental/dental_examination.css" rel="stylesheet">

<div class="content-wrapper bg-light">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fas fa-tooth mr-3 text-primary"></i>
                        New Dental Examination
                    </h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-right bg-transparent">
                            <li class="breadcrumb-item"><a href="home" class="text-primary">Home</a></li>
                            <li class="breadcrumb-item"><a href="dental" class="text-primary">Dental Examinations</a></li>
                            <li class="breadcrumb-item active font-weight-bold">New Examination</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="content py-5">
        <div class="container-fluid">
            <form role="form" action="dental/save_examination" method="post" id="dentalExaminationForm">
                <div class="row">
                    <!-- Patient & Doctor Information -->
                    <div class="col-md-12 mb-4">
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-gradient-primary py-3">
                                <h3 class="card-title text-white mb-0">
                                    <i class="fas fa-user-md mr-2"></i>Patient & Doctor Information
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Patient <span class="text-danger">*</span></label>
                                            <select class="form-control form-control-lg select2" name="patient_id" required>
                                                <option value="">Select Patient</option>
                                                <?php foreach ($patients as $patient): ?>
                                                    <option value="<?php echo $patient->id; ?>">
                                                        <?php echo htmlspecialchars($patient->name . ' - ' . $patient->phone); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Doctor <span class="text-danger">*</span></label>
                                            <select class="form-control form-control-lg select2" name="doctor_id" required>
                                                <?php if (isset($selected_doctor_id) && $selected_doctor_id): ?>
                                                    <option value="<?php echo $selected_doctor_id; ?>" selected>
                                                        <?php echo htmlspecialchars($doctors[0]->name); ?>
                                                    </option>
                                                <?php else: ?>
                                                    <option value="">Select Doctor</option>
                                                    <?php foreach ($doctors as $doctor): ?>
                                                        <option value="<?php echo $doctor->id; ?>">
                                                            <?php echo htmlspecialchars($doctor->name . ' - ' . $doctor->email); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Examination Date <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control form-control-lg" name="examination_date" 
                                                   value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Clinical Information -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-header bg-gradient-info py-3">
                                <h3 class="card-title text-white mb-0">
                                    <i class="fas fa-clipboard-list mr-2"></i>Clinical Information
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Chief Complaint</label>
                                    <textarea class="form-control" name="chief_complaint" rows="3" 
                                              placeholder="Patient's main complaint or reason for visit..."></textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Medical History</label>
                                    <textarea class="form-control" name="medical_history" rows="3" 
                                              placeholder="Relevant medical history, medications, allergies..."></textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Dental History</label>
                                    <textarea class="form-control" name="dental_history" rows="3" 
                                              placeholder="Previous dental treatments, oral hygiene habits..."></textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Extra-oral Examination</label>
                                    <textarea class="form-control" name="extraoral_examination" rows="2" 
                                              placeholder="Facial symmetry, lymph nodes, TMJ..."></textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Intra-oral Examination</label>
                                    <textarea class="form-control" name="intraoral_examination" rows="2" 
                                              placeholder="Soft tissues, gingiva, tongue, palate..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Interactive Odontogram -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-lg border-0 h-100">
                            <div class="card-header bg-gradient-success py-3">
                                <h3 class="card-title text-white mb-0">
                                    <i class="fas fa-teeth mr-2"></i>Interactive Odontogram
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-sm btn-info mr-2" id="toggleMultiSelect" title="Toggle multi-select mode (Ctrl+Click)">
                                        <i class="fas fa-mouse-pointer mr-1"></i>Multi-Select
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light" id="resetOdontogram">
                                        <i class="fas fa-undo mr-1"></i>Reset
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-2">
                                <div id="odontogramContainer" class="text-center">
                                    <!-- Odontogram will be loaded here -->
                                </div>
                                
                                <!-- Condition Legend -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6 class="font-weight-bold mb-2">Condition Legend:</h6>
                                        <div class="d-flex flex-wrap">
                                            <span class="badge badge-light mr-2 mb-1" style="background-color: #FFFFFF; color: #000; border: 1px solid #ccc;">Healthy</span>
                                            <span class="badge mr-2 mb-1" style="background-color: #FF0000; color: #fff;">Caries</span>
                                            <span class="badge mr-2 mb-1" style="background-color: #0000FF; color: #fff;">Filled</span>
                                            <span class="badge mr-2 mb-1" style="background-color: #FFD700; color: #000;">Crowned</span>
                                            <span class="badge mr-2 mb-1" style="background-color: #000000; color: #fff;">Extracted</span>
                                            <span class="badge mr-2 mb-1" style="background-color: #800080; color: #fff;">Impacted</span>
                                            <span class="badge mr-2 mb-1" style="background-color: #FF6600; color: #fff;">Fractured</span>
                                            <span class="badge mr-2 mb-1" style="background-color: #FF69B4; color: #fff;">Root Canal</span>
                                            <span class="badge mr-2 mb-1" style="background-color: #00FF00; color: #000;">Implant</span>
                                            <span class="badge mr-2 mb-1" style="background-color: #00FFFF; color: #000;">Bridge</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Diagnosis & Treatment Plan -->
                    <div class="col-md-12 mb-4">
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-gradient-warning py-3">
                                <h3 class="card-title text-dark mb-0">
                                    <i class="fas fa-stethoscope mr-2"></i>Diagnosis & Treatment Plan
                                </h3>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Diagnosis</label>
                                            <textarea class="form-control" name="diagnosis" rows="4" 
                                                      placeholder="Primary and secondary diagnosis..."></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Treatment Plan</label>
                                            <textarea class="form-control" name="treatment_plan" rows="4" 
                                                      placeholder="Recommended treatments and procedures..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Additional Notes</label>
                                            <textarea class="form-control" name="notes" rows="3" 
                                                      placeholder="Any additional observations or notes..."></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Next Appointment</label>
                                            <input type="datetime-local" class="form-control" name="next_appointment">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Status</label>
                                            <select class="form-control" name="status">
                                                <option value="draft">Draft</option>
                                                <option value="completed">Completed</option>
                                                <option value="follow_up_required">Follow-up Required</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Treatment Planning Section -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-gradient-secondary py-3">
                                <h3 class="card-title text-white mb-0">
                                    <i class="fas fa-tools mr-2"></i>Treatment Planning
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-sm btn-light" id="addTreatment">
                                        <i class="fas fa-plus mr-1"></i>Add Treatment
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div id="treatmentsContainer">
                                    <!-- Treatments will be added here dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prescription Section -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-gradient-dark py-3">
                                <h3 class="card-title text-white mb-0">
                                    <i class="fas fa-pills mr-2"></i>Prescription
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-sm btn-light" id="addPrescription">
                                        <i class="fas fa-plus mr-1"></i>Add Medication
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div id="prescriptionsContainer">
                                    <!-- Prescriptions will be added here dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="col-md-12">
                        <div class="card shadow-lg border-0">
                            <div class="card-body p-4 text-center">
                                <button type="submit" class="btn btn-success btn-lg px-5 mr-3">
                                    <i class="fas fa-save mr-2"></i>Save Examination
                                </button>
                                <button type="button" class="btn btn-info btn-lg px-5 mr-3" id="saveDraft">
                                    <i class="fas fa-file-alt mr-2"></i>Save as Draft
                                </button>
                                <a href="<?php echo base_url(); ?>dental" class="btn btn-secondary btn-lg px-5">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<!-- Tooth Selection Modal -->
<div class="modal fade" id="toothModal" tabindex="-1" role="dialog" aria-labelledby="toothModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="toothModalLabel">Edit Tooth: <span id="selectedToothNumber"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3" id="multiSelectInfo" style="display: none;">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Multi-select mode:</strong> You can apply the same condition to multiple teeth at once.
                </div>
                <form id="toothForm">
                    <input type="hidden" id="toothNumber" name="tooth_number">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tooth Type</label>
                                <select class="form-control" id="toothType" name="tooth_type">
                                    <option value="permanent">Permanent</option>
                                    <option value="deciduous">Deciduous</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Condition</label>
                                <select class="form-control" id="toothCondition" name="condition" required>
                                    <option value="healthy">Healthy</option>
                                    <option value="caries">Caries</option>
                                    <option value="filled">Filled</option>
                                    <option value="crowned">Crowned</option>
                                    <option value="extracted">Extracted</option>
                                    <option value="impacted">Impacted</option>
                                    <option value="fractured">Fractured</option>
                                    <option value="root_canal">Root Canal</option>
                                    <option value="implant">Implant</option>
                                    <option value="bridge">Bridge</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Surface Affected</label>
                                <input type="text" class="form-control" id="surfaceAffected" name="surface_affected" 
                                       placeholder="e.g., Mesial, Distal, Occlusal">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Severity</label>
                                <select class="form-control" id="toothSeverity" name="severity">
                                    <option value="">Not Applicable</option>
                                    <option value="mild">Mild</option>
                                    <option value="moderate">Moderate</option>
                                    <option value="severe">Severe</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Notes</label>
                        <textarea class="form-control" id="toothNotes" name="notes" rows="3" 
                                  placeholder="Additional notes about this tooth..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveToothData">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Load the interactive odontogram component -->
<script src="<?php echo base_url(); ?>common/extranal/js/dental/odontogram.js"></script>
<script src="<?php echo base_url(); ?>common/extranal/js/dental/dental_examination.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Initialize odontogram
    initializeOdontogram();
    
    // Initialize treatment and prescription sections
    initializeTreatmentSection();
    initializePrescriptionSection();
    
    // Form validation
    $('#dentalExaminationForm').on('submit', function(e) {
        // Add odontogram data to form
        const odontogramData = getOdontogramData();
        console.log('Submitting odontogram data:', odontogramData); // Debug log
        
        // Remove any existing odontogram input to prevent duplicates
        $(this).find('input[name="odontogram"]').remove();
        
        // Add fresh odontogram data to form
        $('<input>').attr({
            type: 'hidden',
            name: 'odontogram',
            value: JSON.stringify(odontogramData)
        }).appendTo(this);
    });
    
    // Save as draft
    $('#saveDraft').click(function() {
        $('select[name="status"]').val('draft');
        $('#dentalExaminationForm').submit();
    });
});
</script>
