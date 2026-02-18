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
                    <!-- Hospital Information: Centered Logo -->
                    <div class="document-section mb-2 text-center">
                        <?php if (!empty($settings->logo)): ?>
                            <img src="<?php echo site_url($this->settings_model->getSettings()->logo); ?>"
                                alt="Hospital Logo" style="max-height: 80px;">
                        <?php else: ?>
                            <h4 class="m-0"><?php echo $settings->title; ?></h4>
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
                                        Not specified
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
    /* Document Wrapper */
    .document-wrapper {
        margin: 0 1rem;
        padding: 1rem;
        background: #fff;
        border: 1px solid #e1e5e9;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Document Header */
    .document-header {
        border-bottom: 2px solid #000;
        padding-bottom: 0.3rem;
        margin-bottom: 0.5rem;
    }

    .document-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #000;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .document-subtitle {
        font-size: 0.8rem;
        color: #666;
        margin: 0;
    }

    /* Section Styling */
    .document-section {
        border-bottom: 1px solid #ddd;
        padding-bottom: 0.4rem;
        margin-bottom: 0.4rem;
    }

    .section-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #000;
        text-transform: uppercase;
        border-bottom: 1px solid #ccc;
        padding-bottom: 0.15rem;
        margin-bottom: 0.3rem;
    }

    /* Information Display */
    .info-row {
        display: flex;
        margin-bottom: 0.15rem;
        font-size: 0.8rem;
        line-height: 1.2;
    }

    .info-label {
        font-weight: 600;
        color: #333;
        min-width: 100px;
        margin-right: 0.4rem;
        flex-shrink: 0;
    }

    .info-value {
        color: #000;
        flex: 1;
        word-wrap: break-word;
    }

    /* Report Content */
    .report-content {
        font-size: 0.85rem;
        line-height: 1.3;
        color: #000;
    }

    /* Print Button */
    .print-btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }

    /* Hospital Logo */
    .hospital-logo {
        max-width: 100%;
        height: auto;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .document-wrapper {
            margin: 0 0.5rem;
            padding: 0.5rem;
        }

        .document-title {
            font-size: 1rem;
        }

        .info-label {
            min-width: 80px;
        }
    }

    /* Print Styles */
    @media print {
        body {
            font-size: 11px;
        }

        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
        }

        .document-wrapper {
            margin: 0;
            padding: 5mm;
            border: none;
            box-shadow: none;
        }

        .document-actions {
            display: none;
        }

        .document-header {
            border-bottom: 2px solid #000;
            padding-bottom: 2px;
            margin-bottom: 5px;
        }

        .document-section {
            page-break-inside: avoid;
            padding-bottom: 3px;
            margin-bottom: 3px;
        }

        .section-title {
            border-bottom: 1px solid #000;
            font-size: 0.75rem;
        }

        .info-row {
            font-size: 0.75rem;
            margin-bottom: 1px;
        }

        .report-content {
            font-size: 0.8rem;
        }

        @page {
            size: A4;
            margin: 8mm;
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