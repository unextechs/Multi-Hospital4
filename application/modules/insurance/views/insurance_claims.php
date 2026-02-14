<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fas fa-file-invoice-dollar"></i> Insurance Claims
            <small>Manage insurance claims and reimbursements</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fas fa-tachometer-alt"></i> Home</a></li>
            <li><a href="insurance">Insurance</a></li>
            <li class="active">Claims</li>
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
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fas fa-file-invoice"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Claims</span>
                        <span class="info-box-number"><?php echo $stats['total_claims']; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending</span>
                        <span class="info-box-number"><?php echo $stats['pending_claims']; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Approved</span>
                        <span class="info-box-number"><?php echo $stats['approved_claims']; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fas fa-times-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Rejected</span>
                        <span class="info-box-number"><?php echo $stats['rejected_claims']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Claims List</h3>
                        <div class="box-tools pull-right">
                            <a href="insurance/addClaimView" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> New Claim
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="claims-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Claim #</th>
                                        <th>Patient</th>
                                        <th>Insurance Company</th>
                                        <th>Service Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($claims)): ?>
                                        <?php foreach ($claims as $claim): ?>
                                            <tr>
                                                <td><strong><?php echo $claim->claim_number; ?></strong></td>
                                                <td><?php echo $claim->patient_name; ?></td>
                                                <td><?php echo $claim->insurance_company_name; ?></td>
                                                <td><?php echo date('d M Y', strtotime($claim->service_date)); ?></td>
                                                <td><?php echo $settings->currency; ?>
                                                    <?php echo number_format($claim->claim_amount, 2); ?></td>
                                                <td>
                                                    <?php
                                                    $status_class = 'default';
                                                    if ($claim->status == 'approved' || $claim->status == 'paid')
                                                        $status_class = 'success';
                                                    if ($claim->status == 'rejected')
                                                        $status_class = 'danger';
                                                    if ($claim->status == 'pending' || $claim->status == 'submitted')
                                                        $status_class = 'warning';
                                                    ?>
                                                    <span class="label label-<?php echo $status_class; ?>">
                                                        <?php echo ucfirst($claim->status); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="insurance/viewClaim?id=<?php echo $claim->id; ?>"
                                                        class="btn btn-info btn-xs">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($claim->status == 'draft' || $claim->status == 'pending'): ?>
                                                        <a href="insurance/editClaim?id=<?php echo $claim->id; ?>"
                                                            class="btn btn-warning btn-xs">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="insurance/submitClaim?id=<?php echo $claim->id; ?>"
                                                            class="btn btn-success btn-xs"
                                                            onclick="return confirm('Submit this claim?');">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </a>
                                                        <a href="insurance/deleteClaim?id=<?php echo $claim->id; ?>"
                                                            class="btn btn-danger btn-xs"
                                                            onclick="return confirm('Are you sure?');">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
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
        $('#claims-table').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        });
    });
</script>