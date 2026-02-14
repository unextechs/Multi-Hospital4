<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fas fa-clipboard-list"></i> Radiology Orders
            <small>Manage radiology orders</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fas fa-tachometer-alt"></i> Home</a></li>
            <li><a href="radiology">Radiology</a></li>
            <li class="active">Orders</li>
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
                        <h3 class="box-title">All Orders</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('radiology/searchByBarcode'); ?>"
                                class="btn btn-warning btn-sm">
                                <i class="fas fa-barcode"></i> Scan Barcode
                            </a>
                            <a href="<?php echo site_url('radiology/addOrderView'); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> New Order
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="ordersTable" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Barcode</th>
                                        <th>Patient</th>
                                        <th>Doctor</th>
                                        <th>Tests</th>
                                        <th>Total</th>
                                        <th>Discount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($orders)): ?>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td><strong><?php echo $order->order_number; ?></strong></td>
                                                <td>
                                                    <code style="font-size: 10px;"><?php echo $order->barcode; ?></code>
                                                </td>
                                                <td><?php echo $order->patient_name; ?></td>
                                                <td><?php echo $order->doctor_name; ?></td>
                                                <td>
                                                    <?php
                                                    $test_details = json_decode($order->test_details);
                                                    if ($test_details) {
                                                        echo count($test_details) . ' test(s)';
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo $settings->currency . number_format($order->gross_total, 2); ?></strong>
                                                    <?php if ($order->payment_status != 'paid'): ?>
                                                        <br><small class="text-danger">Due:
                                                            <?php echo $settings->currency . number_format($order->due_amount, 2); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($order->discount_amount > 0): ?>
                                                        <span class="label label-info">
                                                            <?php echo $settings->currency . number_format($order->discount_amount, 2); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $status_class = 'default';
                                                    switch ($order->status) {
                                                        case 'pending':
                                                            $status_class = 'warning';
                                                            break;
                                                        case 'in_progress':
                                                            $status_class = 'info';
                                                            break;
                                                        case 'completed':
                                                            $status_class = 'success';
                                                            break;
                                                        case 'cancelled':
                                                            $status_class = 'danger';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="label label-<?php echo $status_class; ?>">
                                                        <?php echo ucfirst($order->status); ?>
                                                    </span>
                                                    <?php if ($order->urgency != 'routine'): ?>
                                                        <br><span class="label label-danger"
                                                            style="font-size: 9px;"><?php echo strtoupper($order->urgency); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($order->order_date)); ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="radiology/printLabel?id=<?php echo $order->id; ?>"
                                                            class="btn btn-primary btn-xs" title="Print Barcode">
                                                            <i class="fas fa-barcode"></i>
                                                        </a>
                                                        <a href="radiology/editOrder?id=<?php echo $order->id; ?>"
                                                            class="btn btn-info btn-xs" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="radiology/deleteOrder?id=<?php echo $order->id; ?>"
                                                            class="btn btn-danger btn-xs" title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this order?');">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center">No orders found</td>
                                        </tr>
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
        $('#ordersTable').DataTable({
            "order": [[8, "desc"]],
            "pageLength": 25,
            "responsive": true
        });
    });
</script>