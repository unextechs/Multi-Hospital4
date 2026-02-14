<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fas fa-user-shield"></i> Patient Insurance
            <small>Manage patient insurance policies</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fas fa-tachometer-alt"></i> Home</a></li>
            <li><a href="insurance">Insurance</a></li>
            <li class="active">Patient Insurance</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php if ($this->session->flashdata('feedback')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->session->flashdata('feedback'); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Patient Policies</h3>
                        <div class="box-tools pull-right">
                            <a href="insurance/addPatientInsuranceView" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Policy
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="patient-insurance-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Insurance Company</th>
                                        <th>Policy Number</th>
                                        <th>Subscriber</th>
                                        <th>Coverage</th>
                                        <th>Status</th>
                                        <th>Verified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($patient_insurances)): ?>
                                        <?php foreach ($patient_insurances as $pi): ?>
                                            <tr>
                                                <td><strong><?php echo $pi->patient_name; ?></strong></td>
                                                <td><?php echo $pi->insurance_company_name; ?></td>
                                                <td><?php echo $pi->policy_number; ?></td>
                                                <td>
                                                    <?php echo $pi->subscriber_name; ?><br>
                                                    <small
                                                        class="text-muted"><?php echo ucfirst($pi->subscriber_relationship); ?></small>
                                                </td>
                                                <td><?php echo $pi->coverage_percent; ?>%</td>
                                                <td>
                                                    <span
                                                        class="label label-<?php echo $pi->status == 'active' ? 'success' : 'danger'; ?>">
                                                        <?php echo ucfirst($pi->status); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($pi->is_verified): ?>
                                                        <span class="text-success"><i class="fas fa-check-circle"></i> Yes</span>
                                                    <?php else: ?>
                                                        <span class="text-warning"><i class="fas fa-exclamation-circle"></i>
                                                            No</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!$pi->is_verified): ?>
                                                        <a href="insurance/verifyPatientInsurance?id=<?php echo $pi->id; ?>"
                                                            class="btn btn-success btn-xs" title="Verify">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="insurance/editPatientInsurance?id=<?php echo $pi->id; ?>"
                                                        class="btn btn-warning btn-xs">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="insurance/deletePatientInsurance?id=<?php echo $pi->id; ?>"
                                                        class="btn btn-danger btn-xs"
                                                        onclick="return confirm('Are you sure?');">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        $('#patient-insurance-table').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: ['copy', 'excel', 'pdf']
        });
    });
</script>