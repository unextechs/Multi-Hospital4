<!DOCTYPE html>
<html>

<head>
    <base href="<?php echo base_url(); ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo lang('lab_report'); ?> - <?php echo $settings->title; ?></title>

    <!-- Core Styles -->
    <link href="common/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="common/assets/fontawesome5pro/css/all.min.css" rel="stylesheet" type="text/css" media="screen">

    <!-- Invoice Components -->
    <link href="common/css/invoice_components.css" rel="stylesheet" type="text/css" media="screen">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            max-width: 100%;
            margin: 0;
            padding: 20px 30px;
        }

        .lab-report-content {
            padding: 15px 0;
        }

        .lab-report-content p {
            margin-bottom: 10px;
        }

        .lab-report-content table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .lab-report-content table td,
        .lab-report-content table th {
            padding: 8px 12px;
            border: 1px solid #dee2e6;
        }

        .lab-report-content table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        @media print {
            @page {
                size: A4;
                margin: 8mm;
            }

            body {
                font-size: 11px;
            }
        }
    </style>
</head>

<body>
    <!-- Invoice Container -->
    <div class="invoice-container" id="lab">

        <!-- Watermark Background -->
        <?php $this->load->view('partials/invoice_watermark', array('settings' => $settings)); ?>

        <!-- Invoice Content -->
        <div class="invoice-content">

            <!-- Custom Header: Centered Logo, Date Right -->
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #ddd; padding-bottom: 20px;">
                <div style="flex: 1;"></div> <!-- Left Spacer -->

                <div style="flex: 1; text-align: center;">
                    <?php if (!empty($settings->logo)): ?>
                        <img src="<?php echo $settings->logo; ?>" alt="<?php echo $settings->title; ?>"
                            style="max-height: 100px; max-width: 100%;">
                    <?php else: ?>
                        <h3 style="margin: 0; font-weight: bold;"><?php echo $settings->title; ?></h3>
                    <?php endif; ?>
                </div>

                <div style="flex: 1; text-align: right;">
                    <div style="font-size: 14px; color: #555;">
                        <strong><?php echo lang('date'); ?>:</strong> <?php echo date('d-m-Y', $lab->date); ?>
                    </div>
                </div>
            </div>

            <!-- Invoice Title -->
            <div class="invoice-title-section">
                <h2 class="invoice-title"><?php echo lang('lab_report'); ?></h2>
                <p class="invoice-subtitle">#<?php echo str_pad($lab->id, 6, '0', STR_PAD_LEFT); ?></p>
            </div>

            <?php $patient_info = $this->db->get_where('patient', array('id' => $lab->patient))->row(); ?>

            <!-- Information Grid -->
            <div class="invoice-info-grid">

                <!-- Patient Details -->
                <div class="invoice-info-box">
                    <h4 class="invoice-info-box-title"><?php echo lang('patient'); ?> <?php echo lang('details'); ?>
                    </h4>
                    <?php if (!empty($patient_info)): ?>
                        <div class="invoice-info-row">
                            <span class="invoice-info-label"><?php echo lang('name'); ?>:</span>
                            <span class="invoice-info-value"><?php echo $patient_info->name; ?></span>
                        </div>
                        <div class="invoice-info-row">
                            <span class="invoice-info-label"><?php echo lang('patient_id'); ?>:</span>
                            <span
                                class="invoice-info-value"><?php echo (!empty($settings->patient_id_prefix) ? $settings->patient_id_prefix : 'P') . (!empty($patient_info->hospital_patient_id) ? $patient_info->hospital_patient_id : $patient_info->id); ?></span>
                        </div>
                        <div class="invoice-info-row">
                            <span class="invoice-info-label"><?php echo lang('age'); ?> /
                                <?php echo lang('gender'); ?>:</span>
                            <span class="invoice-info-value">
                                <?php
                                $age = explode('-', $patient_info->age);
                                if (count($age) == 3) {
                                    echo $age[0] . " Y " . $age[1] . " M " . $age[2] . " D";
                                } else {
                                    echo $patient_info->age;
                                }
                                ?> / <?php echo $patient_info->sex; ?>
                            </span>
                        </div>
                        <?php if (!empty($patient_info->phone)): ?>
                            <div class="invoice-info-row">
                                <span class="invoice-info-label"><?php echo lang('phone'); ?>:</span>
                                <span class="invoice-info-value"><?php echo $patient_info->phone; ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Lab Report Details -->
                <div class="invoice-info-box">
                    <h4 class="invoice-info-box-title"><?php echo lang('report'); ?> <?php echo lang('details'); ?></h4>
                    <div class="invoice-info-row">
                        <span class="invoice-info-label"><?php echo lang('report'); ?> #:</span>
                        <span class="invoice-info-value"><?php echo str_pad($lab->id, 6, '0', STR_PAD_LEFT); ?></span>
                    </div>
                    <div class="invoice-info-row">
                        <span class="invoice-info-label"><?php echo lang('date'); ?>:</span>
                        <span class="invoice-info-value"><?php echo date('d-m-Y', $lab->date); ?></span>
                    </div>
                </div>

                <!-- Doctor Details -->
                <?php if (!empty($lab->doctor)): ?>
                    <div class="invoice-info-box">
                        <h4 class="invoice-info-box-title"><?php echo lang('ordering'); ?>     <?php echo lang('physician'); ?>
                        </h4>
                        <?php $doctor_details = $this->doctor_model->getDoctorById($lab->doctor); ?>
                        <?php if (!empty($doctor_details)): ?>
                            <div class="invoice-info-row">
                                <span class="invoice-info-label"><?php echo lang('name'); ?>:</span>
                                <span class="invoice-info-value">
                                    <?php echo (!empty($doctor_details->title) ? $doctor_details->title . ' ' : '') . $doctor_details->name; ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Lab Report Content -->
            <!-- Lab Report Content -->
            <?php
            if (!empty($labs)) {
                foreach ($labs as $lab_item) {
                    if (empty($lab_item->report))
                        continue;

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
                    ?>
                    <div class="invoice-info-box" style="margin-bottom: 25px;">
                        <h4 class="invoice-info-box-title">
                            <?php
                            echo $category_name . ' ' . lang('results');
                            ?>
                            (ID: <?php echo $lab_item->id; ?>)
                        </h4>
                        <div class="lab-report-content">
                            <?php echo $lab_item->report; ?>
                        </div>
                    </div>
                    <?php
                }
            } else if (!empty($lab->report)) {
                ?>
                    <div class="invoice-info-box" style="margin-bottom: 25px;">
                        <h4 class="invoice-info-box-title"><?php echo lang('test'); ?>     <?php echo lang('results'); ?></h4>
                        <div class="lab-report-content">
                        <?php echo $lab->report; ?>
                        </div>
                    </div>
            <?php } ?>

            <!-- Footer with Signature -->
            <div class="invoice-footer" style="margin-top: 50px;">
                <?php if (!empty($settings->footer_invoice_message)): ?>
                    <p class="invoice-footer-message"
                        style="text-align: center; color: #666; font-style: italic; margin-bottom: 30px;">
                        <?php echo $settings->footer_invoice_message; ?>
                    </p>
                <?php endif; ?>
                <div class="invoice-footer-signature">
                    <div class="invoice-signature-box">
                        <div class="invoice-signature-line"></div>
                        <span class="invoice-signature-label"><?php echo lang('laboratory'); ?>
                            <?php echo lang('technician'); ?></span>
                    </div>
                    <div class="invoice-signature-box">
                        <div class="invoice-signature-line"></div>
                        <span class="invoice-signature-label"><?php echo lang('authorized'); ?>
                            <?php echo lang('signature'); ?></span>
                    </div>
                </div>
            </div>

        </div><!-- /.invoice-content -->
    </div><!-- /.invoice-container -->

    <!-- Scripts for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
    <script type="text/javascript">var lab_id = "<?php echo $lab->id; ?>";</script>
    <script src="common/extranal/js/lab/invoice.js"></script>
</body>

</html>