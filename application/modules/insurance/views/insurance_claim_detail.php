<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fas fa-file-text"></i> Claim Details
            <small><?php echo $claim->claim_number; ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fas fa-tachometer-alt"></i> Home</a></li>
            <li><a href="insurance">Insurance</a></li>
            <li><a href="insurance/claims">Claims</a></li>
            <li class="active">View</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="uploads/patient-avatar.png"
                            alt="Patient profile picture">
                        <h3 class="profile-username text-center"><?php echo $claim->patient_name; ?></h3>
                        <p class="text-muted text-center">Patient ID: <?php echo $claim->patient_id; ?></p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Claim Amount</b> <a
                                    class="pull-right"><?php echo $settings->currency . ' ' . number_format($claim->claim_amount, 2); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b>
                                <a class="pull-right">
                                    <span
                                        class="label label-<?php echo ($claim->status == 'approved' || $claim->status == 'paid') ? 'success' : (($claim->status == 'rejected') ? 'danger' : 'warning'); ?>">
                                        <?php echo ucfirst($claim->status); ?>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Insurance Details</h3>
                    </div>
                    <div class="box-body">
                        <strong><i class="fas fa-building margin-r-5"></i> Company</strong>
                        <p class="text-muted"><?php echo $claim->insurance_company_name; ?></p>

                        <hr>

                        <strong><i class="fas fa-file-alt margin-r-5"></i> Policy Number</strong>
                        <p class="text-muted"><?php echo $claim->policy_number; ?></p>

                        <hr>

                        <strong><i class="far fa-calendar-alt margin-r-5"></i> Service Date</strong>
                        <p class="text-muted"><?php echo date('d M Y', strtotime($claim->service_date)); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Process Claim</h3>
                    </div>
                    <div class="box-body">
                        <form action="insurance/processClaim" method="post" class="form-horizontal">
                            <input type="hidden" name="id" value="<?php echo $claim->id; ?>">
                            <div class="form-group">
                                <label for="status" class="col-sm-2 control-label">Action</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="status" id="process_status">
                                        <option value="pending" <?php echo $claim->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="submitted" <?php echo $claim->status == 'submitted' ? 'selected' : ''; ?>>Submitted</option>
                                        <option value="approved" <?php echo $claim->status == 'approved' ? 'selected' : ''; ?>>Approve</option>
                                        <option value="rejected" <?php echo $claim->status == 'rejected' ? 'selected' : ''; ?>>Reject</option>
                                        <option value="paid" <?php echo $claim->status == 'paid' ? 'selected' : ''; ?>>
                                            Mark as Paid</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" id="approved_amount_group" style="display:none;">
                                <label for="approved_amount" class="col-sm-2 control-label">Approved Amount</label>
                                <div class="col-sm-10">
                                    <input type="number" step="0.01" class="form-control" name="approved_amount"
                                        value="<?php echo $claim->approved_amount > 0 ? $claim->approved_amount : $claim->claim_amount; ?>">
                                </div>
                            </div>

                            <div class="form-group" id="rejection_reason_group" style="display:none;">
                                <label for="rejection_reason" class="col-sm-2 control-label">Reason</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control"
                                        name="rejection_reason"><?php echo $claim->rejection_reason; ?></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Update Status</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#clinical" data-toggle="tab">Clinical Info</a></li>
                        <li><a href="#history" data-toggle="tab">History</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="clinical">
                            <strong>Diagnosis Codes (ICD-10):</strong>
                            <p><?php echo $claim->diagnosis_codes ? $claim->diagnosis_codes : 'None'; ?></p>
                            <hr>
                            <strong>Procedure Codes (CPT):</strong>
                            <p><?php echo $claim->procedure_codes ? $claim->procedure_codes : 'None'; ?></p>
                            <hr>
                            <strong>Notes:</strong>
                            <p><?php echo $claim->notes ? $claim->notes : 'None'; ?></p>
                        </div>

                        <div class="tab-pane" id="history">
                            <ul class="timeline timeline-inverse">
                                <li>
                                    <i class="fas fa-file-medical bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-clock"></i>
                                            <?php echo date('d M Y H:i', strtotime($claim->created_at)); ?></span>
                                        <h3 class="timeline-header">Claim Created</h3>
                                    </div>
                                </li>
                                <?php if ($claim->submission_date): ?>
                                    <li>
                                        <i class="fas fa-paper-plane bg-yellow"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i>
                                                <?php echo date('d M Y H:i', strtotime($claim->submission_date)); ?></span>
                                            <h3 class="timeline-header">Claim Submitted</h3>
                                        </div>
                                    </li>
                                <?php endif; ?>
                                <?php if ($claim->approval_date): ?>
                                    <li>
                                        <i class="fas fa-check bg-green"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i>
                                                <?php echo date('d M Y H:i', strtotime($claim->approval_date)); ?></span>
                                            <h3 class="timeline-header">Claim Approved</h3>
                                            <div class="timeline-body">
                                                Amount: <?php echo $settings->currency . ' ' . $claim->approved_amount; ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        function toggleFields() {
            var status = $('#process_status').val();
            if (status == 'approved') {
                $('#approved_amount_group').show();
                $('#rejection_reason_group').hide();
            } else if (status == 'rejected') {
                $('#approved_amount_group').hide();
                $('#rejection_reason_group').show();
            } else {
                $('#approved_amount_group').hide();
                $('#rejection_reason_group').hide();
            }
        }

        $('#process_status').change(toggleFields);
        toggleFields();
    });
</script>