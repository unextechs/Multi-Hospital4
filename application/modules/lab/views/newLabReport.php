<?php
$patient = $this->db->get_where('patient', array('id' => $lab->patient))->row();
$invoice_details = "";
$invoice_details = $this->db->get_where('payment', array('id' => $lab->invoice_id))->row();
?>

<div class="content-wrapper">
    <section class="content py-2">
        <div class="container-fluid">
            <div class="document-wrapper">
                <!-- Document Header -->
                <div class="document-header mb-2">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="document-title mb-1">LABORATORY REPORT #<?php echo $lab->id; ?></h3>
                            <p class="document-subtitle mb-0"><?php echo $patient->name; ?> | ID:
                                <?php echo (!empty($settings->patient_id_prefix) ? $settings->patient_id_prefix : 'P') . (!empty($patient->hospital_patient_id) ? $patient->hospital_patient_id : $patient->id); ?>
                                | Invoice: <?php echo $invoice_details->id; ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="document-actions">
                                <button type="button" class="btn btn-outline-primary btn-sm print-btn"
                                    onclick="printDocument()">
                                    <i class="fas fa-print mr-1"></i>
                                    Print Report
                                </button>
                                <?php if ($redirect != 'download1') { ?>
                                    <a href="<?php echo site_url('lab/testPdf?id=' . $lab->id); ?>"
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
                    <!-- Hospital Information -->
                    <div class="document-header mb-4 text-center border-0">
                        <?php if (!empty($settings->logo)): ?>
                            <img src="<?php echo $settings->logo; ?>" alt="Hospital Logo" class="hospital-logo">
                        <?php else: ?>
                            <h2 class="m-0 font-weight-bold text-primary"><?php echo $settings->title; ?></h2>
                        <?php endif; ?>
                    </div>

                    <!-- Patient Information -->
                    <div class="document-section mb-2">
                        <h5 class="section-title">PATIENT INFORMATION</h5>
                        <div class="section-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <span class="info-label">Name:</span>
                                        <span class="info-value"><?php echo $patient->name; ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Patient ID:</span>
                                        <span
                                            class="info-value"><?php echo (!empty($settings->patient_id_prefix) ? $settings->patient_id_prefix : 'P') . (!empty($patient->hospital_patient_id) ? $patient->hospital_patient_id : $patient->id); ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Phone:</span>
                                        <span class="info-value"><?php echo $patient->phone; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <span class="info-label">Age:</span>
                                        <span class="info-value">
                                            <?php
                                            $age = explode('-', $patient->age);
                                            if (count($age) == 3) {
                                                echo $age[0] . " Y " . $age[1] . " M " . $age[2] . " D";
                                            } else {
                                                echo $patient->age;
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Gender:</span>
                                        <span class="info-value"><?php echo $patient->sex; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Doctor Information -->
                    <div class="document-section mb-2">
                        <h5 class="section-title">REFERRING PHYSICIAN</h5>
                        <div class="section-content">
                            <?php
                            $doctor_details = "";
                            if ($invoice_details) {
                                if ($invoice_details->doctor) {
                                    $doctor_details = $this->db->get_where('doctor', array('id' => $invoice_details->doctor))->row();
                                }
                            }
                            ?>
                            <div class="info-row">
                                <span class="info-label">Doctor:</span>
                                <span class="info-value">
                                    <?php if ($doctor_details) { ?>
                                        <?php echo (!empty($doctor_details->title) ? $doctor_details->title . ' ' : '') . $doctor_details->name; ?>
                                    <?php } else { ?>
                                        <?php echo !empty($lab->doctor_name) ? $lab->doctor_name : 'Not specified'; ?>
                                    <?php } ?>
                                </span>
                            </div>
                            <?php if ($doctor_details && $doctor_details->profile) { ?>
                                <div class="info-row">
                                    <span class="info-label">Specialization:</span>
                                    <span class="info-value"><?php echo $doctor_details->profile; ?></span>
                                </div>
                            <?php } ?>
                            <?php if ($invoice_details): ?>
                                <div class="info-row">
                                    <span class="info-label">Visit Date:</span>
                                    <span class="info-value"><?php echo date('d-m-Y', $invoice_details->date); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Lab Report Content -->
                    <div class="document-section mb-2">
                        <h5 class="section-title">LABORATORY RESULTS</h5>
                        <div class="section-content">
                            <div class="report-content">
                                <?php
                                if (!empty($labs)) {
                                    $count = 0;
                                    foreach ($labs as $lab_item) {
                                        if (empty($lab_item->report))
                                            continue;

                                        // Get Category Name for Title and Check for CBC
                                        $category_name = lang('test');
                                        if (!empty($lab_item->category_id)) {
                                            $category = $this->finance_model->getPaymentCategoryById($lab_item->category_id);
                                            if (!empty($category->category)) {
                                                $category_name = $category->category;
                                            }
                                        }

                                        // Skip CBC tests
                                        if (stripos($category_name, 'cbc') !== false) {
                                            continue;
                                        }

                                        if ($count > 0) {
                                            echo '<br><hr style="border-top: 1px dashed #ccc;"><br>';
                                        }
                                        $count++;
                                        ?>
                                        <div class="lab-item-report mb-4">
                                            <h6 class="font-weight-bold" style="text-decoration: underline;">
                                                <?php
                                                echo $category_name . ' ' . lang('results');
                                                ?>
                                                <span style="float: right; font-size: 0.8em; font-weight: normal;">
                                                    ID: <?php echo $lab_item->id; ?>
                                                </span>
                                            </h6>
                                            <div class="lab-report-body mt-2">
                                                <?php echo $lab_item->report; ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else if (!empty($lab->report)) {
                                    echo $lab->report;
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($settings->footer_invoice_message)): ?>
                        <div class="document-section mb-2 text-center">
                            <p style="color: #666; font-style: italic;">
                                <?php echo $settings->footer_invoice_message; ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Report Footer -->
                    <div class="document-section mb-2">
                        <div class="section-content">
                            <div class="row">
                                <?php if (!empty($lab->done_by)): ?>
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <span class="info-label">Done By:</span>
                                            <span class="info-value"><?php echo $lab->done_by; ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="col-md-6">
                                    <div class="info-row">
                                        <span class="info-label">Date:</span>
                                        <span class="info-value"><?php echo date('d-m-Y', $lab->date); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    :root {
        --primary-color: #007bff;
        --secondary-color: #6c757d;
        --dark-color: #212529;
        --border-color: #dee2e6;
        --font-family: 'Outfit', sans-serif;
    }

    body {
        font-family: var(--font-family);
    }

    .document-wrapper {
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .document-header {
        border-bottom: 2px solid var(--dark-color);
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .document-title {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: var(--dark-color);
        margin: 0;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--secondary-color);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border-bottom: 1.5px solid var(--primary-color);
        padding-bottom: 8px;
        margin-bottom: 15px;
    }

    .info-row {
        display: flex;
        align-items: baseline;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .info-label {
        font-weight: 700;
        color: #666;
        min-width: 130px;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .info-value {
        color: var(--dark-color);
        font-weight: 500;
        flex: 1;
    }

    .report-content {
        line-height: 1.6;
        color: var(--dark-color);
    }

    .hospital-logo {
        max-height: 100px;
        max-width: 300px;
        object-fit: contain;
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

    .document-content {
        position: relative;
        z-index: 1;
    }

    @media print {
        .content-wrapper {
            background: none !important;
            padding: 0 !important;
        }

        .document-wrapper {
            box-shadow: none;
            border: none;
            padding: 0;
        }

        .no-print,
        .document-actions {
            display: none !important;
        }

        .document-header {
            border-bottom-width: 2px;
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





<!--main content end-->
<!--footer start-->




<script type="text/javascript">
    var select_doctor = "<?php echo lang('select_doctor'); ?>";
</script>
<script type="text/javascript">
    var select_email = "<?php echo lang('select_email'); ?>";
</script>
<script src="common/extranal/js/lab/lab.js"></script>

<script>
    $(document).ready(function () {
        var prevRowHeight = 0;
        $("p, tr, img").each(function () {
            console.log(prevRowHeight);
            var maxHeight = 750;
            var eachRowHeight = $(this).height();
            if ((prevRowHeight + eachRowHeight) > maxHeight) {
                prevRowHeight = 0;
                $(this).before('<div class="page_breaker"></div>');
                console.log("add page break before");
            }
            prevRowHeight = prevRowHeight + $(this).height();
        });

        <?php if ($this->input->get('print') == '1') { ?>
            window.print();
        <?php } ?>

    });
</script>