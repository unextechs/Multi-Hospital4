<?php if ($redirect == 'download') { ?>
    <!DOCTYPE html>
    <html lang="en" <?php if ($this->db->get('settings')->row()->language == 'arabic') { ?> dir="rtl" <?php } ?>>
    <link href="common/css/bootstrap.min.css" rel="stylesheet">
    <link href="common/css/bootstrap-reset.css" rel="stylesheet">
    <link href="common/assets/fontawesome5pro/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="common/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
    </style>
    <link href="common/assets/DataTables/datatables.css" rel="stylesheet" />
    <link href="common/extranal/css/finance/downloadInvoice.css" rel="stylesheet" />
<?php } ?>

<link href="common/extranal/css/finance/invoice-all.css" rel="stylesheet" />
<?php if ($redirect != 'download') { ?>
    <link href="common/extranal/css/finance/invoice_logical.css" rel="stylesheet" />
    <section id="main-content">
        <section class="wrapper site-min-height">
        <?php } ?>
        <!-- invoice start-->
        <?php if ($redirect != 'download') { ?>
            <section class="col-md-8">
            <?php } else { ?>
                <section class="col-md-12">
                <?php } ?>
                <div class="document-wrapper">
                    <!-- Document Header -->
                    <div class="document-header mb-2">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="document-title mb-1">INVOICE #<?php echo $payment->id; ?></h3>
                                <p class="document-subtitle mb-0"><?php echo lang('payment') ?>
                                    <?php echo lang('invoice') ?> | <?php echo date('M d, Y H:i', $payment->date); ?>
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="document-actions">
                                    <button type="button" class="btn btn-outline-primary btn-sm print-btn"
                                        onclick="printDocument()">
                                        <i class="fas fa-print mr-1"></i>
                                        Print Invoice
                                    </button>
                                    <?php if ($redirect != 'download') { ?>
                                        <a href="finance/download?id=<?php echo $payment->id; ?>"
                                            class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-download mr-1"></i>
                                            Download PDF
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Content -->
                    <div class="document-content">
                        <!-- Watermark Background -->
                        <?php $this->load->view('partials/invoice_watermark', array('settings' => $settings)); ?>

                        <div class="document-section-inner">
                            <!-- Hospital Information -->
                            <div class="document-section mb-2">
                                <h5 class="section-title">HOSPITAL INFORMATION</h5>
                                <div class="section-content">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="info-row">
                                                <span class="info-label">Hospital:</span>
                                                <span class="info-value"><?php echo $settings->title; ?></span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label">Address:</span>
                                                <span class="info-value"><?php echo $settings->address; ?></span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label">Phone:</span>
                                                <span class="info-value"><?php echo $settings->phone; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <img src="<?php echo $settings->logo; ?>" alt="Hospital Logo"
                                                class="hospital-logo">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Patient Information -->
                            <div class="document-section mb-2">
                                <h5 class="section-title">PATIENT INFORMATION</h5>
                                <div class="section-content">
                                    <?php $patient_info = $this->db->get_where('patient', array('id' => $payment->patient))->row(); ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <span class="info-label">Name:</span>
                                                <span class="info-value">
                                                    <?php
                                                    if (!empty($patient_info)) {
                                                        echo $patient_info->name;
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label">Patient ID:</span>
                                                <span class="info-value">
                                                    <?php
                                                    if (!empty($patient_info)) {
                                                        $prefix = (!empty($settings->patient_id_prefix)) ? $settings->patient_id_prefix : 'P';
                                                        echo $prefix . (!empty($patient_info->hospital_patient_id) ? $patient_info->hospital_patient_id : $patient_info->id);
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label">Phone:</span>
                                                <span class="info-value">
                                                    <?php
                                                    if (!empty($patient_info)) {
                                                        echo $patient_info->phone;
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <span class="info-label">Address:</span>
                                                <span class="info-value">
                                                    <?php
                                                    if (!empty($patient_info)) {
                                                        echo $patient_info->address;
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label">Age:</span>
                                                <span class="info-value">
                                                    <?php
                                                    if (!empty($patient_info)) {
                                                        $age = explode('-', $patient_info->age);
                                                        if (count($age) == 3) {
                                                            echo $age[0] . " Y " . $age[1] . " M " . $age[2] . " D";
                                                        } else {
                                                            echo $patient_info->age;
                                                        }
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Information -->
                        <div class="document-section mb-2">
                            <h5 class="section-title">REFERRING PHYSICIAN</h5>
                            <div class="section-content">
                                <div class="info-row">
                                    <span class="info-label">Doctor:</span>
                                    <span class="info-value">
                                        <?php
                                        $doc_details = !empty($payment->doctor) ? $this->doctor_model->getDoctorById($payment->doctor) : null;
                                        if (!empty($doc_details)) {
                                            echo (!empty($doc_details->title) ? $doc_details->title . ' ' : '') . $doc_details->name;
                                        } elseif (!empty($payment->doctor_name)) {
                                            echo $payment->doctor_name;
                                        } else {
                                            echo lang('not_specified') ? lang('not_specified') : 'Not specified';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Services/Items Table -->
                        <div class="document-section mb-2">
                            <h5 class="section-title">SERVICES & CHARGES</h5>
                            <div class="section-content">
                                <div class="table-responsive">
                                    <table class="table table-bordered invoice-table">
                                        <thead>
                                            <tr>
                                                <?php if ($payment->payment_from == 'admitted_patient_bed_service') { ?>
                                                    <th>#</th>
                                                    <th><?php echo lang('service'); ?>     <?php echo lang('name'); ?></th>
                                                    <th><?php echo lang('unit'); ?>     <?php echo lang('price') ?></th>
                                                    <th><?php echo lang('quantity'); ?></th>
                                                    <th><?php echo lang('amount'); ?></th>
                                                <?php } elseif ($payment->payment_from == 'admitted_patient_bed_medicine') { ?>
                                                    <th>#</th>
                                                    <th><?php echo lang('medicine'); ?>     <?php echo lang('name'); ?></th>
                                                    <th><?php echo lang('unit'); ?>     <?php echo lang('price') ?></th>
                                                    <th><?php echo lang('quantity'); ?></th>
                                                    <th><?php echo lang('amount'); ?></th>
                                                <?php } elseif ($payment->payment_from == 'payment') { ?>
                                                    <th>#</th>
                                                    <th><?php echo lang('description'); ?></th>
                                                    <th><?php echo lang('room_no'); ?></th>
                                                    <th><?php echo lang('unit_price'); ?></th>
                                                    <th><?php echo lang('qty'); ?></th>
                                                    <th><?php echo lang('amount'); ?></th>
                                                <?php } else { ?>
                                                    <th>#</th>
                                                    <th><?php echo lang('code'); ?></th>
                                                    <th><?php echo lang('description'); ?></th>
                                                    <th><?php echo lang('unit_price'); ?></th>
                                                    <th><?php echo lang('qty'); ?></th>
                                                    <th><?php echo lang('amount'); ?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($payment->payment_from == 'admitted_patient_bed_medicine') {
                                                if (!empty($payment->category_name)) {
                                                    $category = explode('#', $payment->category_name);
                                                    $i = 0;
                                                    foreach ($category as $cat) {
                                                        $i = $i + 1;
                                                        $cat_new = array();
                                                        $cat_new = explode('*', $cat);
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $i; ?> </td>
                                                            <td><?php echo $cat_new[1]; ?> </td>
                                                            <td class="text-right"><?php echo $settings->currency; ?>
                                                                <?php echo $cat_new[2]; ?>
                                                            </td>
                                                            <td class="text-center"> <?php echo $cat_new[3]; ?> </td>
                                                            <td class="text-right"><?php echo $settings->currency; ?>
                                                                <?php echo $cat_new[4]; ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            } elseif ($payment->payment_from == 'admitted_patient_bed_service') {
                                                if (!empty($payment->category_name)) {
                                                    $category = explode('#', $payment->category_name);
                                                    $i = 0;
                                                    foreach ($category as $cat) {
                                                        $i = $i + 1;
                                                        $cat_new = array();
                                                        $cat_new = explode('*', $cat);
                                                        $service = $this->db->get_where('pservice', array('id' => $cat_new[0]))->row();
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $i; ?> </td>
                                                            <td> <?php echo $service->name; ?> </td>
                                                            <td class="text-right"><?php echo $settings->currency; ?>
                                                                <?php echo $cat_new[1]; ?>
                                                            </td>
                                                            <td class="text-center"> <?php echo '1'; ?> </td>
                                                            <td class="text-right"><?php echo $settings->currency; ?>
                                                                <?php echo $cat_new[1]; ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            } else {
                                                if (!empty($payment->category_name)) {
                                                    $category_name = $payment->category_name;
                                                    $category_name1 = explode(',', $category_name);
                                                    $i = 0;
                                                    foreach ($category_name1 as $category_name2) {
                                                        $i = $i + 1;
                                                        $category_name3 = explode('*', $category_name2);
                                                        if ($category_name3[3] > 0) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?> </td>
                                                                <td>
                                                                    <?php
                                                                    $cat_details = $this->finance_model->getPaymentcategoryById($category_name3[0]);
                                                                    $cat_name = $cat_details->category;
                                                                    echo $cat_name;
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if (strtolower($cat_name) == 'daycare' || strtolower($cat_name) == 'day care') {
                                                                        echo "Daycare Room";
                                                                    } elseif (strtolower($cat_name) == 'emergency') {
                                                                        echo "Emergency Room";
                                                                    } else {
                                                                        echo $cat_details->description;
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td class="text-right"><?php echo $settings->currency; ?>
                                                                    <?php echo $category_name3[1]; ?>
                                                                </td>
                                                                <td class="text-center"> <?php echo $category_name3[3]; ?> </td>
                                                                <td class="text-right"><?php echo $settings->currency; ?>
                                                                    <?php echo $category_name3[1] * $category_name3[3]; ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="document-section mb-2">
                            <h5 class="section-title">PAYMENT SUMMARY</h5>
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="info-row">
                                            <span class="info-label">Sub Total:</span>
                                            <span class="info-value"><?php echo $settings->currency; ?>
                                                <?php echo $payment->amount; ?></span>
                                        </div>
                                        <?php if (!empty($payment->discount)) { ?>
                                            <div class="info-row">
                                                <span class="info-label">Discount
                                                    (<?php echo $payment->percent_discount; ?>%):</span>
                                                <span class="info-value">
                                                    <?php
                                                    $discount = explode('*', $payment->discount);
                                                    if (!empty($discount[1])) {
                                                        echo $settings->currency . ' ' . $discount[1];
                                                    } else {
                                                        echo $discount[0];
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        <?php } ?>
                                        <div class="info-row">
                                            <span class="info-label"><strong>Total:</strong></span>
                                            <span class="info-value"><strong><?php echo $settings->currency; ?>
                                                    <?php echo $payment->gross_total; ?></strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Footer -->
                        <div class="document-section mb-2">
                            <h5 class="section-title">INVOICE DETAILS</h5>
                            <div class="section-content">
                                <div class="info-row">
                                    <span class="info-label">Generated By:</span>
                                    <span
                                        class="info-value"><?php echo $this->ion_auth->user($payment->user)->row()->username; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Generated On:</span>
                                    <span class="info-value"><?php echo date('M d, Y H:i', $payment->date); ?></span>
                                </div>
                            </div>
                        </div>
                    </div><!-- watermark wrapper -->
                </div>
                </div>
            </section>

            <?php if ($redirect != 'download') { ?>
                <section class="col-md-4">
                    <div class="col-md-5 no-print option_button">
                        <?php if ($payment->payment_from == 'payment' || empty($payment->payment_from)) { ?>
                            <a href="finance/payment" class="btn btn-info btn-sm info float-left"><i
                                    class="fa fa-arrow-circle-left"></i> <?php echo lang('back_to_payment_modules'); ?> </a>
                        <?php } ?>
                        <div class="text-center col-md-12 row">
                            <a class="btn btn-info btn-sm invoice_button float-left" onclick="javascript:window.print();"><i
                                    class="fa fa-print"></i> <?php echo lang('print'); ?> </a>
                            <?php if ($this->ion_auth->in_group(array('admin', 'Accountant'))) { ?>
                                <?php if ($payment->payment_from == 'payment' || empty($payment->payment_from)) {
                                    $lab_pending = array();
                                    $lab_reports_previous = $this->lab_model->getLabByInvoice($payment->id);
                                    if (!empty($lab_reports_previous)) {
                                        foreach ($lab_reports_previous as $lab) {
                                            if ($lab->test_status == 'not_done' || empty($lab->test_status)) {
                                                $lab_pending[] = 'no';
                                            }
                                        }
                                    }
                                    if (count($lab_reports_previous) == count($lab_pending) || empty($lab_reports_previous)) {
                                        ?>
                                        <a href="finance/editPayment?id=<?php echo $payment->id; ?>"
                                            class="btn btn-info btn-sm editbutton float-left"><i class="fa fa-edit"></i>
                                            <?php echo lang('edit'); ?>                 <?php echo lang('invoice'); ?> </a>
                                    <?php }
                                } ?>
                                <a href="finance/download?id=<?php echo $payment->id; ?>"
                                    class="btn btn-info btn-sm detailsbutton float-left download"><i class="fa fa-download"></i>
                                    <?php echo lang('download'); ?> </a>
                            <?php } ?>
                        </div>

                        <div class="no-print">
                            <?php if ($payment->payment_from == 'payment' || empty($payment->payment_from)) { ?>
                                <a href="finance/addPaymentView" class="float-left">
                                    <div class="btn-group">
                                        <button id="" class="btn btn-info green btn-sm">
                                            <i class="fa fa-plus-circle"></i> <?php echo lang('add_another_payment'); ?>
                                        </button>
                                    </div>
                                </a>
                            <?php } ?>
                            <?php if ($this->ion_auth->in_group(array('admin', 'Accountant'))) { ?>
                                <a href="finance/sendInvoice?id=<?php echo $payment->id; ?>"
                                    class="btn  btn-sm float-left send"> <i class="fa fa-paper-plane"></i>
                                    <?php echo lang('send_invoice'); ?> </a>
                            <?php } ?>
                        </div>

                        <div class="panel_button">
                            <div class="text-center invoice-btn no-print float-left ">
                                <a href="finance/previousInvoice?id=<?php echo $payment->id ?>"
                                    class="btn btn-info btn-sm green previousone1"><i
                                        class="glyphicon glyphicon-chevron-left"></i> </a>
                                <a href="finance/nextInvoice?id=<?php echo $payment->id ?>"
                                    class="btn btn-info btn-sm green nextone1 "><i
                                        class="glyphicon glyphicon-chevron-right"></i> </a>
                            </div>
                        </div>
                    </div>
                </section>
            <?php } ?>

            <link rel="stylesheet" href="common/extranal/css/finance/invoiceAll.css" />

            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
        </section>
        <?php if ($redirect == 'download') { ?>

    </html>
<?php } ?>
<!-- invoice end-->
<?php if ($redirect != 'download') { ?>
    </section>
    </section>
    <link href="common/extranal/css/finance/print.css" rel="stylesheet" />

    <script src="common/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="common/assets/DataTables/datatables.min.js"></script>
<?php } ?>
<script src="common/extranal/js/finance/invoice.js"></script>

<?php if ($redirectlink == 'print') { ?>
    <script src="common/extranal/js/finance/printInvoice.js"></script>
<?php } ?>

<style>
    /* Premium Design System */
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
        --success-color: #198754;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #0dcaf0;
        --dark-color: #212529;
        --light-color: #f8f9fa;
        --white: #ffffff;
        --border-color: #dee2e6;
        --font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    body {
        font-family: var(--font-family);
        background-color: #f4f7f6;
        color: var(--dark-color);
    }

    .document-wrapper {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 2.5rem;
        margin: 2rem auto;
        max-width: 1000px;
        position: relative;
        overflow: hidden;
    }

    .document-header {
        border-bottom: 2px solid var(--dark-color);
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
    }

    .document-title {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: var(--dark-color);
        margin: 0;
    }

    .document-subtitle {
        font-size: 0.95rem;
        color: var(--secondary-color);
        font-weight: 500;
    }

    .document-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--secondary-color);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    /* Information Display */
    .info-row {
        display: flex;
        align-items: baseline;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .info-label {
        font-weight: 700;
        color: #555;
        min-width: 130px;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .info-value {
        color: var(--dark-color);
        font-weight: 500;
    }

    /* Table Styles */
    .invoice-table {
        width: 100%;
        margin-top: 1rem;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
    }

    .invoice-table th {
        background-color: #f8f9fa;
        color: var(--dark-color);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 1rem;
        border-top: 2px solid var(--dark-color);
        border-bottom: 1px solid var(--border-color);
    }

    .invoice-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .invoice-table tr:last-child td {
        border-bottom: none;
    }

    /* Payment Summary */
    .payment-summary {
        background-color: var(--light-color);
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .summary-row {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .summary-label {
        font-weight: 600;
        color: var(--secondary-color);
        margin-right: 2rem;
    }

    .summary-value {
        font-weight: 700;
        color: var(--dark-color);
        min-width: 100px;
        text-align: right;
    }

    .total-row {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid var(--border-color);
    }

    .total-label {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--dark-color);
    }

    .total-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--primary-color);
    }

    /* Badges */
    .payment-status {
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        margin-top: 1rem;
    }

    .status-paid {
        background-color: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
    }

    .status-due {
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
    }

    /* Action Buttons */
    .document-actions .btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .document-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Watermark */
    .invoice-watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.05;
        z-index: 0;
        width: 60%;
        pointer-events: none;
    }

    .invoice-watermark img {
        width: 100%;
        height: auto;
    }

    .document-section-inner {
        position: relative;
        z-index: 1;
    }

    /* Hospital Logo */
    .hospital-logo {
        max-width: 100%;
        height: auto;
        object-fit: contain;
    }

    /* Print Customization */
    @media print {
        body {
            background: transparent;
        }

        .document-wrapper {
            margin: 0;
            padding: 0;
            border: none;
            box-shadow: none;
            width: 100%;
            max-width: none;
        }

        .document-actions,
        .no-print {
            display: none !important;
        }

        .invoice-table th {
            background-color: #eee !important;
            -webkit-print-color-adjust: exact;
        }
    }

    @media (max-width: 768px) {
        .document-wrapper {
            padding: 1.5rem;
            margin: 1rem;
        }

        .document-title {
            font-size: 1.5rem;
        }

        .info-label {
            min-width: 100px;
        }
    }
</style>

<script>
    function printDocument() {
        // Hide non-printable elements
        const elementsToHide = document.querySelectorAll('.document-actions, .no-print');
        elementsToHide.forEach(el => el.style.display = 'none');

        // Print the document
        window.print();

        // Restore elements after printing
        elementsToHide.forEach(el => el.style.display = '');
    }
</script>