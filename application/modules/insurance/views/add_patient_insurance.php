<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fas fa-file-contract"></i> <?php echo isset($patient_insurance) ? 'Edit' : 'Add'; ?> Patient Insurance
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fas fa-tachometer-alt"></i> Home</a></li>
            <li><a href="insurance">Insurance</a></li>
            <li><a href="insurance/patientInsurance">Patient Insurance</a></li>
            <li class="active"><?php echo isset($patient_insurance) ? 'Edit' : 'Add'; ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Policy Details</h3>
                    </div>
                    <form action="insurance/addPatientInsurance" method="post">
                        <?php if (isset($patient_insurance)): ?>
                        <input type="hidden" name="id" value="<?php echo $patient_insurance->id; ?>">
                        <?php endif; ?>
                        
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="patient">Patient *</label>
                                        <select class="form-control select2" name="patient_id" required>
                                            <option value="">Select Patient</option>
                                            <?php foreach ($patients as $patient): ?>
                                            <option value="<?php echo $patient->id; ?>" 
                                                <?php echo (isset($patient_insurance) && $patient_insurance->patient_id == $patient->id) ? 'selected' : ''; ?>>
                                                <?php echo $patient->name; ?> (ID: <?php echo $patient->id; ?>)
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="insurance_company">Insurance Company *</label>
                                        <select class="form-control select2" name="insurance_company_id" required>
                                            <option value="">Select Company</option>
                                            <?php foreach ($insurance_companys as $company): ?>
                                            <option value="<?php echo $company->id; ?>" 
                                                <?php echo (isset($patient_insurance) && $patient_insurance->insurance_company_id == $company->id) ? 'selected' : ''; ?>>
                                                <?php echo $company->name; ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="policy_number">Policy Number *</label>
                                        <input type="text" class="form-control" name="policy_number" 
                                               value="<?php echo isset($patient_insurance) ? $patient_insurance->policy_number : ''; ?>" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="group_number">Group Number</label>
                                        <input type="text" class="form-control" name="group_number" 
                                               value="<?php echo isset($patient_insurance) ? $patient_insurance->group_number : ''; ?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subscriber_name">Subscriber Name</label>
                                        <input type="text" class="form-control" name="subscriber_name" 
                                               value="<?php echo isset($patient_insurance) ? $patient_insurance->subscriber_name : ''; ?>" placeholder="Leave empty if same as patient">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="subscriber_relationship">Relationship to Patient</label>
                                        <select class="form-control" name="subscriber_relationship">
                                            <option value="self" <?php echo (isset($patient_insurance) && $patient_insurance->subscriber_relationship == 'self') ? 'selected' : ''; ?>>Self</option>
                                            <option value="spouse" <?php echo (isset($patient_insurance) && $patient_insurance->subscriber_relationship == 'spouse') ? 'selected' : ''; ?>>Spouse</option>
                                            <option value="child" <?php echo (isset($patient_insurance) && $patient_insurance->subscriber_relationship == 'child') ? 'selected' : ''; ?>>Child</option>
                                            <option value="other" <?php echo (isset($patient_insurance) && $patient_insurance->subscriber_relationship == 'other') ? 'selected' : ''; ?>>Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="effective_date">Effective Date</label>
                                                <input type="text" class="form-control date-picker" name="effective_date" 
                                                       value="<?php echo isset($patient_insurance) ? $patient_insurance->effective_date : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="expiry_date">Expiry Date</label>
                                                <input type="text" class="form-control date-picker" name="expiry_date" 
                                                       value="<?php echo isset($patient_insurance) ? $patient_insurance->expiry_date : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="coverage_percent">Coverage Percentage (%)</label>
                                        <input type="number" class="form-control" name="coverage_percent" 
                                               value="<?php echo isset($patient_insurance) ? $patient_insurance->coverage_percent : '80'; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" name="notes" rows="3"><?php echo isset($patient_insurance) ? $patient_insurance->notes : ''; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Policy
                            </button>
                            <a href="insurance/patientInsurance" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('.select2').select2();
    $('.date-picker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });
});
</script>
