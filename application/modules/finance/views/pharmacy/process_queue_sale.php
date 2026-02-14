<!--sidebar end-->
<!--main content start-->
<?php
// Fetch full patient details since queue_item might only have name
$patient_details = $this->patient_model->getPatientById($queue_item->patient_id);
?>
<div class="content-wrapper bg-light">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fa fa-clipboard-list mr-2 text-primary"></i> Process Pharmacy Queue <span
                            class="badge badge-warning label-lg align-middle">#<?php echo $queue_item->queue_number; ?></span>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <a href="finance/pharmacy/queue" class="float-right btn btn-outline-secondary">
                        <i class="fa fa-arrow-left"></i> Back to Queue
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content py-3">
        <div class="container-fluid">
            <!-- page start-->

            <div class="row">
                <!-- Patient Info Section -->
                <div class="col-lg-4 col-md-5 col-sm-12 mb-4">
                    <div class="card card-info card-outline h-100">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-user-circle mr-2"></i>
                                <?php echo lang('patient'); ?> Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-group mb-3">
                                <label class="text-muted small text-uppercase font-weight-bold">Patient Name</label>
                                <h4 class="mt-0 text-primary font-weight-bold">
                                    <?php echo $queue_item->patient_name; ?>
                                </h4>
                                <?php if (!empty($patient_details)) { ?>
                                    <p class="text-muted mb-0"><i class="fa fa-id-card mr-1"></i> ID:
                                        <?php echo $patient_details->id; ?></p>
                                    <p class="text-muted mb-0"><i class="fa fa-phone mr-1"></i>
                                        <?php echo $patient_details->phone; ?></p>
                                    <p class="text-muted mb-0"><i class="fa fa-venus-mars mr-1"></i>
                                        <?php echo ucfirst($patient_details->sex); ?> (<?php echo $patient_details->age; ?>)
                                    </p>
                                <?php } ?>
                            </div>
                            <div class="info-group mb-3">
                                <label class="text-muted small text-uppercase font-weight-bold">Prescribed
                                    By</label>
                                <p class="h5 mt-0"><?php echo $queue_item->doctor_name; ?></p>
                            </div>
                            <div class="info-group">
                                <label class="text-muted small text-uppercase font-weight-bold">Prescribed
                                    Date</label>
                                <p class="h5 mt-0">
                                    <?php echo date('d M Y, h:i A', strtotime($queue_item->created_at)); ?>
                                </p>
                            </div>
                            <div class="alert alert-warning mt-4">
                                <i class="fa fa-info-circle"></i> Please verify medicine stock before completing
                                sale.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sale Form Section -->
                <div class="col-lg-8 col-md-7 col-sm-12">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h3 class="card-title"><i class="fa fa-pills mr-2"></i> Medicine List & Payment</h3>
                        </div>
                        <div class="card-body">
                            <form role="form" id="pharmacyForm" action="finance/pharmacy/completeQueueSale"
                                method="post" enctype="multipart/form-data">
                                <input type="hidden" name="queue_id" value="<?php echo $queue_item->id; ?>">
                                <input type="hidden" name="patient" value="<?php echo $queue_item->patient_name; ?>">

                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="min-width: 150px;">Medicine</th>
                                                <th style="min-width: 200px;">Instructions</th>
                                                <th style="width: 100px;">Qty</th>
                                                <th style="width: 120px;">Price</th>
                                                <th style="width: 120px;">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $grand_total = 0;
                                            foreach ($medicines as $key => $medicine) {
                                                $grand_total += $medicine['subtotal'];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo $medicine['medicine_name']; ?></strong>
                                                        <input type="hidden" name="medicine_id[]"
                                                            value="<?php echo $medicine['medicine_id']; ?>">
                                                    </td>
                                                    <td>
                                                        <small class="text-muted d-block" style="line-height: 1.4;">
                                                            <?php if (!empty($medicine['dosage']))
                                                                echo '<span class="badge badge-light border">Dosage: ' . $medicine['dosage'] . '</span> '; ?>
                                                            <?php if (!empty($medicine['frequency']))
                                                                echo '<span class="badge badge-light border">Freq: ' . $medicine['frequency'] . '</span> '; ?>
                                                            <?php if (!empty($medicine['days']))
                                                                echo '<span class="badge badge-light border">Days: ' . $medicine['days'] . '</span>'; ?>
                                                            <?php if (!empty($medicine['instruction']))
                                                                echo '<div class="mt-1 text-info"><i class="fa fa-info-circle text-xs"></i> ' . $medicine['instruction'] . '</div>'; ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="quantity[]"
                                                            value="<?php echo $medicine['quantity']; ?>"
                                                            class="form-control form-control-sm text-center font-weight-bold"
                                                            min="1" required readonly>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php echo $settings->currency; ?>
                                                        <?php echo number_format($medicine['unit_price'], 2); ?>
                                                    </td>
                                                    <td class="text-right font-weight-bold text-success">
                                                        <?php echo $settings->currency; ?>
                                                        <?php echo number_format($medicine['subtotal'], 2); ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr class="bg-light">
                                                <td colspan="4"
                                                    class="text-right text-uppercase font-weight-bold h5 mb-0">Total
                                                    Amount</td>
                                                <td class="text-right h5 mb-0 font-weight-bold text-primary">
                                                    <?php echo $settings->currency; ?><?php echo number_format($grand_total, 2); ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold"><?php echo lang('discount'); ?></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span
                                                        class="input-group-text"><?php echo $settings->currency; ?></span>
                                                </div>
                                                <input type="number" class="form-control form-control-lg"
                                                    name="discount" id="discount_input" value="0" min="0" step="0.01"
                                                    placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                class="font-weight-bold"><?php echo lang('amount_received'); ?></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span
                                                        class="input-group-text"><?php echo $settings->currency; ?></span>
                                                </div>
                                                <input type="number" class="form-control form-control-lg border-success"
                                                    name="amount_received" id="amount_received_input"
                                                    value="<?php echo $grand_total; ?>" min="0" step="0.01" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" name="submit"
                                    class="btn btn-primary btn-lg btn-block mt-4 shadow-sm hover-lift">
                                    <i class="fa fa-check-circle mr-2"></i> Complete Sale & Print Invoice
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- page end-->
</div>
</section>
</div>
<!--main content end-->