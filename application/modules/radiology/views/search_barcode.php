<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fas fa-barcode"></i> Barcode Scanner
            <small>Search orders by barcode</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fas fa-tachometer-alt"></i> Home</a></li>
            <li><a href="radiology">Radiology</a></li>
            <li class="active">Barcode Scanner</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fas fa-search"></i> Search by Barcode</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="barcode_input">Scan or Enter Barcode</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon"><i class="fas fa-barcode"></i></span>
                                <input type="text" class="form-control" id="barcode_input" name="barcode"
                                    placeholder="Scan barcode or enter manually..." autofocus>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="searchBtn">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </span>
                            </div>
                            <p class="help-block">Position your cursor here and scan the barcode. The search will
                                trigger automatically.</p>
                        </div>

                        <div id="searchResult" style="display: none;">
                            <hr>
                            <div id="orderDetails">
                                <!-- Order details will be loaded here -->
                            </div>
                        </div>

                        <div id="notFoundMessage" class="alert alert-warning" style="display: none;">
                            <i class="fas fa-exclamation-triangle"></i> No order found with this barcode.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        var searchTimeout;

        // Auto-search as user types (with debounce for barcode scanner)
        $('#barcode_input').on('input', function () {
            clearTimeout(searchTimeout);
            var barcode = $(this).val().trim();

            if (barcode.length >= 10) { // Minimum barcode length
                searchTimeout = setTimeout(function () {
                    searchByBarcode(barcode);
                }, 300);
            }
        });

        // Search on Enter key
        $('#barcode_input').on('keypress', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                searchByBarcode($(this).val().trim());
            }
        });

        // Search button click
        $('#searchBtn').click(function () {
            searchByBarcode($('#barcode_input').val().trim());
        });

        function searchByBarcode(barcode) {
            if (!barcode) {
                return;
            }

            $('#searchResult').hide();
            $('#notFoundMessage').hide();

            $.ajax({
                url: '<?php echo site_url("radiology/getOrderByBarcode"); ?>',
                type: 'GET',
                data: { barcode: barcode },
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.order) {
                        displayOrder(response.order);
                        $('#searchResult').show();
                    } else {
                        $('#notFoundMessage').show();
                    }
                },
                error: function () {
                    $('#notFoundMessage').show();
                }
            });
        }

        function displayOrder(order) {
            var statusClass = 'default';
            switch (order.status) {
                case 'pending': statusClass = 'warning'; break;
                case 'in_progress': statusClass = 'info'; break;
                case 'completed': statusClass = 'success'; break;
                case 'cancelled': statusClass = 'danger'; break;
            }

            var paymentClass = 'danger';
            if (order.payment_status == 'paid') paymentClass = 'success';
            else if (order.payment_status == 'partial') paymentClass = 'warning';

            var html = '<div class="row">' +
                '<div class="col-md-6">' +
                '<h4><i class="fas fa-user"></i> Patient Information</h4>' +
                '<table class="table table-condensed">' +
                '<tr><th>Name:</th><td>' + order.patient_name + '</td></tr>' +
                '<tr><th>Phone:</th><td>' + (order.patient_phone || '-') + '</td></tr>' +
                '<tr><th>Age:</th><td>' + (order.patient_age || '-') + '</td></tr>' +
                '<tr><th>Gender:</th><td>' + (order.patient_gender || '-') + '</td></tr>' +
                '</table>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<h4><i class="fas fa-file-medical"></i> Order Information</h4>' +
                '<table class="table table-condensed">' +
                '<tr><th>Order #:</th><td><strong>' + order.order_number + '</strong></td></tr>' +
                '<tr><th>Date:</th><td>' + order.order_date + '</td></tr>' +
                '<tr><th>Doctor:</th><td>' + order.doctor_name + '</td></tr>' +
                '<tr><th>Status:</th><td><span class="label label-' + statusClass + '">' + order.status.toUpperCase() + '</span></td></tr>' +
                '<tr><th>Payment:</th><td><span class="label label-' + paymentClass + '">' + order.payment_status.toUpperCase() + '</span></td></tr>' +
                '</table>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-md-12">' +
                '<h4><i class="fas fa-money-bill"></i> Financial Summary</h4>' +
                '<table class="table table-bordered">' +
                '<tr><th>Subtotal:</th><td>' + order.subtotal + '</td>' +
                '<th>Discount:</th><td>' + order.discount_amount + '</td></tr>' +
                '<tr><th>VAT:</th><td>' + order.vat_amount + '</td>' +
                '<th>Gross Total:</th><td><strong>' + order.gross_total + '</strong></td></tr>' +
                '<tr><th>Received:</th><td>' + order.amount_received + '</td>' +
                '<th>Due:</th><td><strong class="text-danger">' + order.due_amount + '</strong></td></tr>' +
                '</table>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-md-12 text-center">' +
                '<a href="radiology/editOrder?id=' + order.id + '" class="btn btn-info btn-lg"><i class="fas fa-edit"></i> Edit Order</a> ' +
                '<a href="radiology/printLabel?id=' + order.id + '" class="btn btn-primary btn-lg"><i class="fas fa-barcode"></i> Print Label</a> ' +
                '<button type="button" class="btn btn-default btn-lg" onclick="$(\'#barcode_input\').val(\'\').focus(); $(\'#searchResult\').hide();"><i class="fas fa-redo"></i> New Search</button>' +
                '</div>' +
                '</div>';

            $('#orderDetails').html(html);
        }
    });
</script>