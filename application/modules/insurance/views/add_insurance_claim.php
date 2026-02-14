<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fas fa-file-medical"></i> <?php echo isset($claim) ? 'Edit' : 'New'; ?> Claim
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fas fa-tachometer-alt"></i> Home</a></li>
            <li><a href="insurance">Insurance</a></li>
            <li><a href="insurance/claims">Claims</a></li>
            <li class="active"><?php echo isset($claim) ? 'Edit' : 'New'; ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Claim Details</h3>
                    </div>
                    <form action="insurance/addClaim" method="post">
                        <?php if (isset($claim)): ?>
                        <input type="hidden" name="id" value="<?php echo $claim->id; ?>">
                        <?php endif; ?>
                        
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="patient">Patient *</label>
                                        <select class="form-control select2" id="patient_id" name="patient_id" required>
                                            <option value="">Select Patient</option>
                                            <?php foreach ($patients as $patient): ?>
                                            <option value="<?php echo $patient->id; ?>" 
                                                <?php echo (isset($claim) && $claim->patient_id == $patient->id) ? 'selected' : ''; ?>
                                                data-name="<?php echo $patient->name; ?>">
                                                <?php echo $patient->name; ?> (ID: <?php echo $patient->id; ?>)
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" name="patient_name" id="patient_name" value="<?php echo isset($claim) ? $claim->patient_name : ''; ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="insurance_company">Insurance Company *</label>
                                        <select class="form-control select2" id="insurance_company_id" name="insurance_company_id" required>
                                            <option value="">Select Company</option>
                                            <?php foreach ($insurance_companys as $company): ?>
                                            <option value="<?php echo $company->id; ?>" 
                                                <?php echo (isset($claim) && $claim->insurance_company_id == $company->id) ? 'selected' : ''; ?>
                                                data-name="<?php echo $company->name; ?>">
                                                <?php echo $company->name; ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" name="insurance_company_name" id="insurance_company_name" value="<?php echo isset($claim) ? $claim->insurance_company_name : ''; ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="policy_number">Policy Number *</label>
                                        <input type="text" class="form-control" name="policy_number" id="policy_number" 
                                               value="<?php echo isset($claim) ? $claim->policy_number : ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service_date">Service Date *</label>
                                        <input type="text" class="form-control default-date-picker" name="service_date" id="service_date" 
                                               value="<?php echo isset($claim) ? date('d-m-Y', strtotime($claim->service_date)) : date('d-m-Y'); ?>" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="claim_amount">Claim Amount *</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $settings->currency; ?></span>
                                            <input type="number" step="0.01" class="form-control" name="claim_amount" id="claim_amount" 
                                                   value="<?php echo isset($claim) ? $claim->claim_amount : ''; ?>" required>
                                        </div>
                                    </div>

                                    <?php if(isset($invoice)): ?>
                                    <div class="form-group">
                                        <label>Linked Invoice</label>
                                        <p class="form-control-static">
                                            #<?php echo $invoice->id; ?> - <?php echo $settings->currency . ' ' . $invoice->amount; ?>
                                            <input type="hidden" name="invoice_id" value="<?php echo $invoice->id; ?>">
                                        </p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="diagnosis_codes">Diagnosis Codes (ICD-10)</label>
                                        <textarea class="form-control" name="diagnosis_codes" rows="3" placeholder="Enter codes separated by comma"><?php echo isset($claim) ? $claim->diagnosis_codes : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="procedure_codes">Procedure Codes (CPT)</label>
                                        <textarea class="form-control" name="procedure_codes" rows="3" placeholder="Enter codes separated by comma"><?php echo isset($claim) ? $claim->procedure_codes : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" name="notes" rows="3"><?php echo isset($claim) ? $claim->notes : ''; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Claim
                            </button>
                            <a href="insurance/claims" class="btn btn-default">Cancel</a>
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
    $('.default-date-picker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true
    });
    
    $('#patient_id').change(function() {
        var name = $(this).find(':selected').data('name');
        $('#patient_name').val(name);
        
        // Auto-fetch insurance if available
        var id = $(this).val();
        if(id) {
            $.ajax({
                url: 'insurance/verifyCoverage?patient_id=' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if(response.success && response.insurances.length > 0) {
                        // Pre-fill first active insurance
                        var ins = response.insurances[0];
                        $('#policy_number').val(ins.policy_number);
                        // Trigger change for company if present in list
                        // This part might need logic to match company name to ID
                    }
                }
            });
        }
    });
    
    $('#insurance_company_id').change(function() {
        var name = $(this).find(':selected').data('name');
        $('#insurance_company_name').val(name);
    });
});
</script>
