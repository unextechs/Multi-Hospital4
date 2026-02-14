<!--sidebar end-->
<!--main content start-->
<div class="content-wrapper bg-light">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">Pharmacy Queue</h1>
                </div>
                <div class="col-sm-6">
                    <a href="finance/pharmacy/addPaymentView" class="float-right btn btn-primary">
                        <i class="fa fa-plus-circle"></i> <?php echo lang('add_new'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content py-3">
        <div class="container-fluid">
            <!-- page start-->
            <section class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="adv-table editable-table ">
                        <div class="space15"></div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="editable-sample">
                                <thead>
                                    <tr>
                                        <th>Queue #</th>
                                        <th>
                                            <?php echo lang('date'); ?>
                                        </th>
                                        <th>
                                            <?php echo lang('patient'); ?>
                                        </th>
                                        <th>
                                            <?php echo lang('doctor'); ?>
                                        </th>
                                        <th>Medicines</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th class="no-print">
                                            <?php echo lang('options'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pending_queue as $item) {
                                        $medicines = json_decode($item->medicines_data);
                                        $medicine_count = count($medicines);
                                        ?>
                                        <tr class="">
                                            <td><span class="label label-primary" style="font-size: 14px;">#
                                                    <?php echo $item->queue_number; ?>
                                                </span></td>
                                            <td>
                                                <?php echo date('d-m-Y h:i A', strtotime($item->created_at)); ?>
                                            </td>
                                            <td>
                                                <?php echo $item->patient_name; ?>
                                            </td>
                                            <td>
                                                <?php echo $item->doctor_name; ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?php echo $medicine_count; ?> Items
                                                </span>
                                                <small class="text-muted d-block">
                                                    <?php
                                                    foreach ($medicines as $med) {
                                                        echo $med->medicine_name . ', ';
                                                    }
                                                    ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php echo $settings->currency; ?>
                                                <?php echo number_format($item->total_amount, 2); ?>
                                            </td>
                                            <td>
                                                <span class="label label-warning">
                                                    <?php echo ucfirst($item->status); ?>
                                                </span>
                                            </td>
                                            <td class="no-print">
                                                <a class="btn btn-success btn-xs"
                                                    href="finance/pharmacy/processQueue?id=<?php echo $item->id; ?>">
                                                    <i class="fa fa-check-circle"></i> Process Sale
                                                </a>
                                                <!-- <a class="btn btn-danger btn-xs" href="#" onclick="return confirm('Cancel this request?');">
                                            <i class="fa fa-times"></i>
                                        </a> -->
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <?php if (empty($pending_queue)) { ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No pending prescriptions in queue</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </section>
    <!-- page end-->
</div>
</section>
</div>
<!--main content end-->

<script src="common/js/codearistos.min.js"></script>
<script>
    $(document).ready(function () {
        $('#editable-sample').DataTable({
            "order": [[0, "asc"]]
        });
    });
</script>