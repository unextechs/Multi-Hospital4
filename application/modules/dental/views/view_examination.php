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
                        Dental Examination Details
                    </h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb float-sm-right bg-transparent">
                            <li class="breadcrumb-item"><a href="home" class="text-primary">Home</a></li>
                            <li class="breadcrumb-item"><a href="dental" class="text-primary">Dental Examinations</a></li>
                            <li class="breadcrumb-item active font-weight-bold">View Examination</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="content py-5">
        <div class="container-fluid">
            <!-- Action Buttons -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-3">
                            <div class="btn-group" role="group">
                                <a href="<?php echo base_url(); ?>dental/edit_examination/<?php echo $examination->id; ?>" 
                                   class="btn btn-primary btn-lg">
                                    <i class="fas fa-edit mr-2"></i>Edit Examination
                                </a>
                                <a href="<?php echo base_url(); ?>dental/print_examination/<?php echo $examination->id; ?>" 
                                   class="btn btn-secondary btn-lg" target="_blank">
                                    <i class="fas fa-print mr-2"></i>Print Report
                                </a>
                                <a href="<?php echo base_url(); ?>dental/patient_history/<?php echo $examination->patient_id; ?>" 
                                   class="btn btn-info btn-lg">
                                    <i class="fas fa-history mr-2"></i>Patient History
                                </a>
                                <a href="<?php echo base_url(); ?>dental" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Patient Information -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-header bg-gradient-primary py-3">
                            <h3 class="card-title text-white mb-0">
                                <i class="fas fa-user mr-2"></i>Patient Information
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <div class="avatar-lg bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                                <h4 class="font-weight-bold"><?php echo htmlspecialchars($examination->patient_name); ?></h4>
                                <p class="text-muted mb-0">Patient ID: <?php echo $examination->patient_id; ?></p>
                            </div>
                            
                            <div class="info-list">
                                <div class="info-item mb-3">
                                    <i class="fas fa-envelope text-primary mr-2"></i>
                                    <strong>Email:</strong>
                                    <span class="text-muted"><?php echo htmlspecialchars($examination->patient_email ?: 'Not provided'); ?></span>
                                </div>
                                <div class="info-item mb-3">
                                    <i class="fas fa-phone text-primary mr-2"></i>
                                    <strong>Phone:</strong>
                                    <span class="text-muted"><?php echo htmlspecialchars($examination->patient_phone ?: 'Not provided'); ?></span>
                                </div>
                                <div class="info-item mb-3">
                                    <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                    <strong>Address:</strong>
                                    <span class="text-muted"><?php echo htmlspecialchars($examination->patient_address ?: 'Not provided'); ?></span>
                                </div>
                                <div class="info-item mb-3">
                                    <i class="fas fa-birthday-cake text-primary mr-2"></i>
                                    <strong>Birth Date:</strong>
                                    <span class="text-muted">
                                        <?php 
                                        if ($examination->patient_birth_date) {
                                            echo date('M d, Y', strtotime($examination->patient_birth_date));
                                            $age = date_diff(date_create($examination->patient_birth_date), date_create('today'))->y;
                                            echo " (Age: $age)";
                                        } else {
                                            echo 'Not provided';
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="info-item mb-3">
                                    <i class="fas fa-venus-mars text-primary mr-2"></i>
                                    <strong>Gender:</strong>
                                    <span class="text-muted"><?php echo htmlspecialchars($examination->patient_sex ?: 'Not specified'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Examination Details -->
                <div class="col-md-8 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-header bg-gradient-info py-3">
                            <h3 class="card-title text-white mb-0">
                                <i class="fas fa-clipboard-list mr-2"></i>Examination Details
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="info-box p-3 bg-light rounded">
                                        <h6 class="font-weight-bold text-primary">Doctor</h6>
                                        <p class="mb-0"><?php echo htmlspecialchars($examination->doctor_name); ?></p>
                                        <small class="text-muted"><?php echo htmlspecialchars($examination->doctor_email ?: ''); ?></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box p-3 bg-light rounded">
                                        <h6 class="font-weight-bold text-primary">Examination Date</h6>
                                        <p class="mb-0"><?php echo date('F d, Y', strtotime($examination->examination_date)); ?></p>
                                        <small class="text-muted"><?php echo date('h:i A', strtotime($examination->examination_date)); ?></small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="info-box p-3 bg-light rounded">
                                        <h6 class="font-weight-bold text-primary">Status</h6>
                                        <?php
                                        $status_class = '';
                                        switch ($examination->status) {
                                            case 'completed':
                                                $status_class = 'badge-success';
                                                break;
                                            case 'follow_up_required':
                                                $status_class = 'badge-warning';
                                                break;
                                            case 'draft':
                                                $status_class = 'badge-secondary';
                                                break;
                                            default:
                                                $status_class = 'badge-light';
                                        }
                                        ?>
                                        <span class="badge <?php echo $status_class; ?> badge-lg">
                                            <?php echo ucfirst(str_replace('_', ' ', $examination->status)); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box p-3 bg-light rounded">
                                        <h6 class="font-weight-bold text-primary">Next Appointment</h6>
                                        <?php if ($examination->next_appointment): ?>
                                            <p class="mb-0"><?php echo date('F d, Y', strtotime($examination->next_appointment)); ?></p>
                                            <small class="text-muted"><?php echo date('h:i A', strtotime($examination->next_appointment)); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">Not scheduled</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="clinical-info">
                                <div class="info-section mb-4">
                                    <h6 class="font-weight-bold text-primary border-bottom pb-2">Chief Complaint</h6>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($examination->chief_complaint ?: 'Not specified')); ?></p>
                                </div>
                                
                                <div class="info-section mb-4">
                                    <h6 class="font-weight-bold text-primary border-bottom pb-2">Medical History</h6>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($examination->medical_history ?: 'No significant medical history')); ?></p>
                                </div>
                                
                                <div class="info-section mb-4">
                                    <h6 class="font-weight-bold text-primary border-bottom pb-2">Dental History</h6>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($examination->dental_history ?: 'No previous dental treatments recorded')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Odontogram -->
                <div class="col-md-12 mb-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-success py-3">
                            <h3 class="card-title text-white mb-0">
                                <i class="fas fa-teeth mr-2"></i>Dental Odontogram
                            </h3>
                        </div>
                        <div class="card-body p-3">
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

                            <!-- Affected Teeth Details -->
                            <?php if (!empty($odontogram)): ?>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h6 class="font-weight-bold mb-3">Affected Teeth Details:</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Tooth #</th>
                                                        <th>Type</th>
                                                        <th>Condition</th>
                                                        <th>Surface</th>
                                                        <th>Severity</th>
                                                        <th>Notes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($odontogram as $tooth): ?>
                                                        <tr>
                                                            <td><strong><?php echo $tooth->tooth_number; ?></strong></td>
                                                            <td><?php echo ucfirst($tooth->tooth_type); ?></td>
                                                            <td>
                                                                <span class="badge" style="background-color: <?php echo $tooth->color_code; ?>; color: <?php echo ($tooth->color_code === '#FFFFFF' || $tooth->color_code === '#FFD700' || $tooth->color_code === '#00FF00' || $tooth->color_code === '#00FFFF') ? '#000' : '#fff'; ?>;">
                                                                    <?php echo ucfirst(str_replace('_', ' ', $tooth->condition)); ?>
                                                                </span>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($tooth->surface_affected ?: '-'); ?></td>
                                                            <td><?php echo $tooth->severity ? ucfirst($tooth->severity) : '-'; ?></td>
                                                            <td><?php echo htmlspecialchars($tooth->notes ?: '-'); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Clinical Examination -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-header bg-gradient-warning py-3">
                            <h3 class="card-title text-dark mb-0">
                                <i class="fas fa-stethoscope mr-2"></i>Clinical Examination
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="info-section mb-4">
                                <h6 class="font-weight-bold text-primary border-bottom pb-2">Extra-oral Examination</h6>
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($examination->extraoral_examination ?: 'No abnormalities noted')); ?></p>
                            </div>
                            
                            <div class="info-section mb-4">
                                <h6 class="font-weight-bold text-primary border-bottom pb-2">Intra-oral Examination</h6>
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($examination->intraoral_examination ?: 'No abnormalities noted')); ?></p>
                            </div>
                            
                            <div class="info-section mb-4">
                                <h6 class="font-weight-bold text-primary border-bottom pb-2">Diagnosis</h6>
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($examination->diagnosis ?: 'Diagnosis pending')); ?></p>
                            </div>
                            
                            <div class="info-section">
                                <h6 class="font-weight-bold text-primary border-bottom pb-2">Treatment Plan</h6>
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($examination->treatment_plan ?: 'Treatment plan to be determined')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Treatments & Prescriptions -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-header bg-gradient-secondary py-3">
                            <h3 class="card-title text-white mb-0">
                                <i class="fas fa-tools mr-2"></i>Treatments & Prescriptions
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <!-- Treatments -->
                            <?php if (!empty($treatments)): ?>
                                <div class="treatments-section mb-4">
                                    <h6 class="font-weight-bold text-primary border-bottom pb-2">Planned Treatments</h6>
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
                            <?php endif; ?>

                            <!-- Prescriptions -->
                            <?php if (!empty($prescriptions)): ?>
                                <div class="prescriptions-section">
                                    <h6 class="font-weight-bold text-primary border-bottom pb-2">Prescriptions</h6>
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
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-pills fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No treatments or prescriptions recorded</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes & Images -->
                <?php if ($examination->notes || !empty($images)): ?>
                <div class="col-md-12 mb-4">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-dark py-3">
                            <h3 class="card-title text-white mb-0">
                                <i class="fas fa-sticky-note mr-2"></i>Additional Information
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <?php if ($examination->notes): ?>
                                <div class="notes-section mb-4">
                                    <h6 class="font-weight-bold text-primary border-bottom pb-2">Additional Notes</h6>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($examination->notes)); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($images)): ?>
                                <div class="images-section">
                                    <h6 class="font-weight-bold text-primary border-bottom pb-2">Examination Images</h6>
                                    <div class="row">
                                        <?php foreach ($images as $image): ?>
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <img src="<?php echo base_url($image->image_path); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($image->description); ?>" style="height: 200px; object-fit: cover;">
                                                    <div class="card-body p-2">
                                                        <h6 class="card-title text-sm mb-1"><?php echo ucfirst(str_replace('_', ' ', $image->image_type)); ?></h6>
                                                        <p class="card-text text-xs text-muted"><?php echo htmlspecialchars($image->description ?: 'No description'); ?></p>
                                                        <?php if ($image->tooth_number): ?>
                                                            <small class="text-muted">Tooth: <?php echo $image->tooth_number; ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<!-- Load the interactive odontogram component -->
<script src="<?php echo base_url(); ?>common/extranal/js/dental/odontogram.js"></script>

<script>
$(document).ready(function() {
    // Initialize odontogram
    initializeOdontogram();
    
    // Load existing odontogram data
    const odontogramData = <?php echo json_encode($odontogram); ?>;
    console.log('Raw odontogram data from server (view):', odontogramData);
    
    if (odontogramData && Object.keys(odontogramData).length > 0) {
        const formattedData = {};
        Object.keys(odontogramData).forEach(key => {
            const tooth = odontogramData[key];
            formattedData[tooth.tooth_number] = {
                tooth_type: tooth.tooth_type,
                condition: tooth.condition,
                surface_affected: tooth.surface_affected,
                severity: tooth.severity,
                notes: tooth.notes,
                color_code: tooth.color_code
            };
        });
        console.log('Formatted odontogram data (view):', formattedData);
        loadOdontogramData(formattedData);
    } else {
        console.log('No existing odontogram data found (view)');
    }
});
</script>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
}

.info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 10px;
}

.info-item i {
    margin-top: 2px;
    width: 20px;
}

.info-box {
    transition: all 0.3s ease;
}

.info-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.treatment-item, .prescription-item {
    transition: all 0.3s ease;
}

.treatment-item:hover, .prescription-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.badge-lg {
    font-size: 0.9rem;
    padding: 8px 16px;
}
</style>
