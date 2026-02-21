<div class="content-wrapper bg-gradient-light" style="min-height: 2726.9px;">
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fas fa-boxes text-primary mr-3"></i>
                        <?php echo lang('medicine'); ?> Stock & Sales Report
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="home">
                                    <?php echo lang('home'); ?>
                                </a></li>
                            <li class="breadcrumb-item"><a href="finance/pharmacy/home">
                                    <?php echo lang('pharmacy'); ?>
                                </a></li>
                            <li class="breadcrumb-item active">Stock Report</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="content py-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header">
                            <h3 class="card-title text-black font-weight-800">
                                Current Medicines Valuation & Expected Sales
                            </h3>
                        </div>

                        <div class="card-body bg-light">
                            <table class="table table-hover" id="editable-sample-stock">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="font-weight-bold text-uppercase">
                                            <?php echo lang('id'); ?>
                                        </th>
                                        <th class="font-weight-bold text-uppercase">
                                            <?php echo lang('name'); ?>
                                        </th>
                                        <th class="font-weight-bold text-uppercase">Current Stock</th>
                                        <th class="font-weight-bold text-uppercase">
                                            <?php echo lang('p_price'); ?>
                                        </th>
                                        <th class="font-weight-bold text-uppercase">
                                            <?php echo lang('s_price'); ?>
                                        </th>
                                        <th class="font-weight-bold text-uppercase">Total Purchase Value</th>
                                        <th class="font-weight-bold text-uppercase">Total Sales Value</th>
                                        <th class="font-weight-bold text-uppercase">
                                            <?php echo lang('expiry_date'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="common/js/codearistos.min.js"></script>
<script>
    $(document).ready(function () {
        var table = $('#editable-sample-stock').DataTable({
            responsive: true,
            "processing": true,
            "serverSide": true,
            "searchable": true,
            "ajax": {
                url: "finance/pharmacy/getMedicineStockReportList",
                type: 'POST',
            },
            scroller: {
                loadingIndicator: true
            },
            dom: "<'row'<'col-sm-3'l><'col-sm-5 text-center'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: [
                { extend: 'copyHtml5', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] } },
                { extend: 'excelHtml5', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] } },
                { extend: 'csvHtml5', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] } },
                { extend: 'pdfHtml5', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] } },
                { extend: 'print', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] } },
            ],
            aLengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            iDisplayLength: 100,
            "order": [[0, "desc"]],
            "language": {
                "lengthMenu": "_MENU_",
                search: "_INPUT_",
                "url": "common/assets/DataTables/languages/" + "<?php echo $this->language; ?>" + ".json"
            }
        });
        table.buttons().container().appendTo('.custom_buttons');
    });
</script>