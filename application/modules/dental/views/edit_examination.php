<?php
// This view is similar to create_examination.php but with pre-filled data
// We'll extend the create examination view with edit functionality
?>

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
                        Edit Dental Examination
                    </h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-right bg-transparent">
                            <li class="breadcrumb-item"><a href="home" class="text-primary">Home</a></li>
                            <li class="breadcrumb-item"><a href="dental" class="text-primary">Dental Examinations</a></li>
                            <li class="breadcrumb-item active font-weight-bold">Edit Examination</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="content py-5">
        <div class="container-fluid">
            <form role="form" action="dental/update_examination/<?php echo $examination->id; ?>" method="post" id="dentalExaminationForm">
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
                                                    <option value="<?php echo $patient->id; ?>" <?php echo ($patient->id == $examination->patient_id) ? 'selected' : ''; ?>>
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
                                                <option value="">Select Doctor</option>
                                                <?php foreach ($doctors as $doctor): ?>
                                                    <option value="<?php echo $doctor->id; ?>" <?php echo ($doctor->id == $examination->doctor_id) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($doctor->name . ' - ' . $doctor->email); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Examination Date <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control form-control-lg" name="examination_date" 
                                                   value="<?php echo date('Y-m-d\TH:i', strtotime($examination->examination_date)); ?>" required>
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
                                              placeholder="Patient's main complaint or reason for visit..."><?php echo htmlspecialchars($examination->chief_complaint); ?></textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Medical History</label>
                                    <textarea class="form-control" name="medical_history" rows="3" 
                                              placeholder="Relevant medical history, medications, allergies..."><?php echo htmlspecialchars($examination->medical_history); ?></textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Dental History</label>
                                    <textarea class="form-control" name="dental_history" rows="3" 
                                              placeholder="Previous dental treatments, oral hygiene habits..."><?php echo htmlspecialchars($examination->dental_history); ?></textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Extra-oral Examination</label>
                                    <textarea class="form-control" name="extraoral_examination" rows="2" 
                                              placeholder="Facial symmetry, lymph nodes, TMJ..."><?php echo htmlspecialchars($examination->extraoral_examination); ?></textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold">Intra-oral Examination</label>
                                    <textarea class="form-control" name="intraoral_examination" rows="2" 
                                              placeholder="Soft tissues, gingiva, tongue, palate..."><?php echo htmlspecialchars($examination->intraoral_examination); ?></textarea>
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
                                                      placeholder="Primary and secondary diagnosis..."><?php echo htmlspecialchars($examination->diagnosis); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Treatment Plan</label>
                                            <textarea class="form-control" name="treatment_plan" rows="4" 
                                                      placeholder="Recommended treatments and procedures..."><?php echo htmlspecialchars($examination->treatment_plan); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Additional Notes</label>
                                            <textarea class="form-control" name="notes" rows="3" 
                                                      placeholder="Any additional observations or notes..."><?php echo htmlspecialchars($examination->notes); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Next Appointment</label>
                                            <input type="datetime-local" class="form-control" name="next_appointment" 
                                                   value="<?php echo $examination->next_appointment ? date('Y-m-d\TH:i', strtotime($examination->next_appointment)) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Status</label>
                                            <select class="form-control" name="status">
                                                <option value="draft" <?php echo ($examination->status === 'draft') ? 'selected' : ''; ?>>Draft</option>
                                                <option value="completed" <?php echo ($examination->status === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                                <option value="follow_up_required" <?php echo ($examination->status === 'follow_up_required') ? 'selected' : ''; ?>>Follow-up Required</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Treatments Display -->
                    <?php if (!empty($treatments)): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-gradient-secondary py-3">
                                <h3 class="card-title text-white mb-0">
                                    <i class="fas fa-tools mr-2"></i>Existing Treatments
                                </h3>
                            </div>
                            <div class="card-body p-3">
                                <?php foreach ($treatments as $treatment): ?>
                                    <div class="treatment-item border rounded p-3 mb-3 bg-light">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="font-weight-bold mb-0"><?php echo htmlspecialchars($treatment->treatment_type); ?></h6>
                                            <span class="badge badge-<?php echo ($treatment->status === 'completed') ? 'success' : (($treatment->status === 'in_progress') ? 'warning' : 'secondary'); ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $treatment->status)); ?>
                                            </span>
                                        </div>
                                        <?php if ($treatment->tooth_number): ?>
                                            <p class="text-muted mb-1"><strong>Tooth:</strong> <?php echo $treatment->tooth_number; ?></p>
                                        <?php endif; ?>
                                        <?php if ($treatment->treatment_description): ?>
                                            <p class="text-muted mb-1"><?php echo htmlspecialchars($treatment->treatment_description); ?></p>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between text-sm">
                                            <span><?php echo $treatment->treatment_date ? date('M d, Y', strtotime($treatment->treatment_date)) : 'Date TBD'; ?></span>
                                            <?php if ($treatment->cost): ?>
                                                <span class="font-weight-bold">$<?php echo number_format($treatment->cost, 2); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Existing Prescriptions Display -->
                    <?php if (!empty($prescriptions)): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-gradient-dark py-3">
                                <h3 class="card-title text-white mb-0">
                                    <i class="fas fa-pills mr-2"></i>Existing Prescriptions
                                </h3>
                            </div>
                            <div class="card-body p-3">
                                <?php foreach ($prescriptions as $prescription): ?>
                                    <div class="prescription-item border rounded p-3 mb-3 bg-light">
                                        <h6 class="font-weight-bold mb-1"><?php echo htmlspecialchars($prescription->medication_name); ?></h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <strong>Dosage:</strong> <?php echo htmlspecialchars($prescription->dosage ?: 'As directed'); ?>
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <strong>Frequency:</strong> <?php echo htmlspecialchars($prescription->frequency ?: 'As needed'); ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <strong>Duration:</strong> <?php echo htmlspecialchars($prescription->duration ?: 'As needed'); ?>
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <strong>Quantity:</strong> <?php echo $prescription->quantity ?: 'As needed'; ?>
                                                </small>
                                            </div>
                                        </div>
                                        <?php if ($prescription->instructions): ?>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Instructions:</strong> <?php echo htmlspecialchars($prescription->instructions); ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Form Actions -->
                    <div class="col-md-12">
                        <div class="card shadow-lg border-0">
                            <div class="card-body p-4 text-center">
                                <button type="submit" class="btn btn-success btn-lg px-5 mr-3">
                                    <i class="fas fa-save mr-2"></i>Update Examination
                                </button>
                                <a href="<?php echo base_url(); ?>dental/view_examination/<?php echo $examination->id; ?>" class="btn btn-info btn-lg px-5 mr-3">
                                    <i class="fas fa-eye mr-2"></i>View Examination
                                </a>
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
                                <select class="form-control" id="toothCondition" name="condition">
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

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Initialize odontogram
    initializeOdontogram();
    
    // Load existing odontogram data
    const existingOdontogramData = <?php echo json_encode($odontogram); ?>;
    console.log('Raw odontogram data from server:', existingOdontogramData);
    
    if (existingOdontogramData && Object.keys(existingOdontogramData).length > 0) {
        const formattedData = {};
        Object.keys(existingOdontogramData).forEach(key => {
            const tooth = existingOdontogramData[key];
            formattedData[tooth.tooth_number] = {
                tooth_type: tooth.tooth_type,
                condition: tooth.condition,
                surface_affected: tooth.surface_affected,
                severity: tooth.severity,
                notes: tooth.notes,
                color_code: tooth.color_code
            };
        });
        console.log('Formatted odontogram data:', formattedData);
        loadOdontogramData(formattedData);
    } else {
        console.log('No existing odontogram data found');
    }
    
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
});
</script>
