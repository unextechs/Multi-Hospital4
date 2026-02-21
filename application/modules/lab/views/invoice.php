<!--sidebar end-->
<!--main content start-->
<link href="common/css/invoice_components.css" rel="stylesheet" type="text/css" media="screen">

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

    .invoice-container {
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        position: relative;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        border-bottom: 2px solid var(--dark-color);
        padding-bottom: 20px;
    }

    .hospital-logo {
        max-height: 100px;
        max-width: 300px;
        object-fit: contain;
    }

    .invoice-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .invoice-info-box {
        background: #fcfcfc;
        border: 1px solid #efefef;
        padding: 20px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .invoice-info-box:hover {
        border-color: var(--primary-color);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
    }

    .invoice-info-box-title {
        font-size: 0.9rem;
        font-weight: 800;
        color: var(--secondary-color);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border-bottom: 1.5px solid var(--primary-color);
        padding-bottom: 8px;
        margin-bottom: 15px;
    }

    .invoice-info-row {
        display: flex;
        align-items: baseline;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .invoice-info-label {
        font-weight: 700;
        color: #666;
        min-width: 120px;
        margin-right: 10px;
    }

    .invoice-info-value {
        color: var(--dark-color);
        font-weight: 500;
    }

    .lab-report-content {
        padding: 10px 0;
    }

    .lab-report-content table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 20px 0;
    }

    .lab-report-content th {
        background: #f8f9fa;
        color: var(--dark-color);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 12px 15px;
        border-top: 2px solid var(--dark-color);
        border-bottom: 1px solid var(--border-color);
    }

    .lab-report-content td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--border-color);
    }

    .invoice-status {
        padding: 4px 12px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    .invoice-status-paid {
        background: #d1e7dd;
        color: #0f5132;
    }

    .invoice-status-pending {
        background: #f8d7da;
        color: #842029;
    }

    .invoice-status-partial {
        background: #fff3cd;
        color: #664d03;
    }

    @media print {
        .content-wrapper {
            background: none !important;
            padding: 0 !important;
        }

        .invoice-container {
            box-shadow: none;
            border: none;
            padding: 0;
        }

        .no-print {
            display: none !important;
        }

        .invoice-header {
            border-bottom-width: 2px;
        }
    }
</style>

<div class="content-wrapper bg-light">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row my-2 pl-1">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold"><i class="fas fa-flask mr-2"></i><?php echo lang('lab_report'); ?> #
                        <?php echo $lab->id; ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home"><?php echo lang('home') ?></a></li>
                        <li class="breadcrumb-item"><a href="lab"><?php echo lang('lab') ?></a></li>
                        <li class="breadcrumb-item active"><?php echo lang('lab_report'); ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <!-- Invoice Actions (No Print) -->
                    <div class="invoice-actions no-print mb-3">
                        <button onclick="javascript:window.print();" class="btn btn-primary">
                            <i class="fas fa-print mr-1"></i> <?php echo lang('print'); ?>
                        </button>
                        <a href="lab/download?id=<?php echo $lab->id; ?>" class="btn btn-success">
                            <i class="fas fa-download mr-1"></i> <?php echo lang('download'); ?>
                        </a>
                        <?php if ($this->ion_auth->in_group(array('admin', 'Laboratorist'))) { ?>
                            <a href="lab/editLabReport?id=<?php echo $lab->id; ?>" class="btn btn-secondary">
                                <i class="fas fa-edit mr-1"></i> <?php echo lang('edit'); ?>
                            </a>
                        <?php } ?>
                        <a href="lab" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> <?php echo lang('back'); ?>
                        </a>
                    </div>

                    <!-- Main Invoice -->
                    <div class="invoice-container" id="lab-invoice">

                        <!-- Watermark Background -->
                        <?php $this->load->view('partials/invoice_watermark', array('settings' => $settings)); ?>

                        <!-- Invoice Content -->
                        <div class="invoice-content">

                            <!-- Invoice Header with Huge Logo -->
                            <!-- Invoice Header -->
                            <div class="invoice-header">
                                <div style="flex: 1;"></div> <!-- Left Spacer -->

                                <div style="flex: 1; text-align: center;">
                                    <?php if (!empty($settings->logo)): ?>
                                        <img src="<?php echo $settings->logo; ?>" alt="<?php echo $settings->title; ?>"
                                            class="hospital-logo">
                                    <?php else: ?>
                                        <h3 style="margin: 0; font-weight: bold;"><?php echo $settings->title; ?></h3>
                                    <?php endif; ?>
                                </div>

                                <div style="flex: 1; text-align: right;">
                                    <div class="invoice-info-value">
                                        <strong><?php echo lang('date'); ?>:</strong>
                                        <?php echo date('d-m-Y', $lab->date); ?>
                                    </div>
                                </div>
                            </div>

                            <?php // Lab Report Title section removed as per request ?>

                            <?php $patient_info = $this->db->get_where('patient', array('id' => $lab->patient))->row(); ?>

                            <!-- Information Grid -->
                            <div class="invoice-info-grid">

                                <!-- Patient Details -->
                                <div class="invoice-info-box">
                                    <h4 class="invoice-info-box-title">
                                        <?php echo lang('patient'); ?>
                                        <?php echo lang('details'); ?>
                                    </h4>
                                    <?php if (!empty($patient_info)): ?>
                                        <div class="invoice-info-row">
                                            <span class="invoice-info-label"><?php echo lang('name'); ?>:</span>
                                            <span class="invoice-info-value"
                                                style="font-weight: 700; color: #000;"><?php echo $patient_info->name; ?></span>
                                        </div>
                                        <div class="invoice-info-row">
                                            <span class="invoice-info-label"><?php echo lang('patient'); ?> ID:</span>
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
                                    <h4 class="invoice-info-box-title">
                                        <?php echo lang('report'); ?>
                                        <?php echo lang('details'); ?>
                                    </h4>
                                    <div class="invoice-info-row">
                                        <span class="invoice-info-label"><?php echo lang('report'); ?> #:</span>
                                        <span class="invoice-info-value"
                                            style="font-weight: bold; color: #007bff;"><?php echo str_pad($lab->id, 6, '0', STR_PAD_LEFT); ?></span>
                                    </div>
                                    <div class="invoice-info-row">
                                        <span class="invoice-info-label"><?php echo lang('date'); ?>:</span>
                                        <span class="invoice-info-value"><?php echo date('d-m-Y', $lab->date); ?></span>
                                    </div>
                                    <div class="invoice-info-row">
                                        <span class="invoice-info-label"><?php echo lang('status'); ?>:</span>
                                        <span class="invoice-info-value">
                                            <?php
                                            $status_class = 'invoice-status-paid';
                                            $status_text = lang('completed');
                                            if ($lab->test_status == 'not_done' || empty($lab->test_status)) {
                                                $status_class = 'invoice-status-pending';
                                                $status_text = lang('pending');
                                            } elseif ($lab->test_status == 'processing') {
                                                $status_class = 'invoice-status-partial';
                                                $status_text = lang('processing');
                                            }
                                            ?>
                                            <span
                                                class="invoice-status <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Doctor Details -->
                                <div class="invoice-info-box">
                                    <h4 class="invoice-info-box-title">
                                        <?php echo lang('ordering'); ?>
                                        <?php echo lang('physician'); ?>
                                    </h4>
                                    <?php
                                    $doctor_details = !empty($lab->doctor) ? $this->doctor_model->getDoctorById($lab->doctor) : null;
                                    ?>
                                    <div class="invoice-info-row">
                                        <span class="invoice-info-label"><?php echo lang('name'); ?>:</span>
                                        <span class="invoice-info-value" style="font-weight: 600;">
                                            <?php
                                            if (!empty($doctor_details)) {
                                                echo (!empty($doctor_details->title) ? $doctor_details->title . ' ' : '') . $doctor_details->name;
                                            } else {
                                                echo !empty($lab->doctor_name) ? $lab->doctor_name : lang('not_specified');
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($doctor_details)): ?>
                                        <?php if (!empty($doctor_details->profile)): ?>
                                            <div class="invoice-info-row">
                                                <span class="invoice-info-label"><?php echo lang('specialization'); ?>:</span>
                                                <span class="invoice-info-value"><?php echo $doctor_details->profile; ?></span>
                                            </div>
                                        <?php elseif (!empty($doctor_details->department)): ?>
                                            <div class="invoice-info-row">
                                                <span class="invoice-info-label"><?php echo lang('department'); ?>:</span>
                                                <span
                                                    class="invoice-info-value"><?php echo $doctor_details->department; ?></span>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Lab Report Content -->
                            <!-- Lab Report Content -->
                            <?php
                            if (!empty($labs)) {
                                foreach ($labs as $lab_item) {
                                    if (empty($lab_item->report))
                                        continue;
                                    ?>
                                    <div class="invoice-info-box"
                                        style="margin-bottom: 25px; border: 1px solid #eee; padding: 20px; border-radius: 8px;">
                                        <h4 class="invoice-info-box-title"
                                            style="border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">
                                            <?php
                                            // Get Category Name for Title
                                            $category_name = lang('test');
                                            if (!empty($lab_item->category_id)) {
                                                $category = $this->finance_model->getPaymentCategoryById($lab_item->category_id);
                                                if (!empty($category->category)) {
                                                    $category_name = $category->category;
                                                }
                                            }
                                            echo $category_name . ' ' . lang('results');
                                            ?>
                                            <span style="float: right; font-size: 0.8rem; text-transform: none;">
                                                ID: <?php echo $lab_item->id; ?>
                                            </span>
                                        </h4>
                                        <div class="lab-report-content">
                                            <?php echo $lab_item->report; ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else if (!empty($lab->report)) {
                                ?>
                                    <div class="invoice-info-box"
                                        style="margin-bottom: 25px; border: 1px solid #eee; padding: 20px; border-radius: 8px;">
                                        <h4 class="invoice-info-box-title"
                                            style="border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">
                                        <?php echo lang('test'); ?>
                                        <?php echo lang('results'); ?>
                                        </h4>
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

                                <div class="invoice-footer-signature"
                                    style="display: flex; justify-content: space-between; margin-top: 50px;">
                                    <div class="invoice-signature-box" style="text-align: center; width: 40%;">
                                        <div class="invoice-signature-line"
                                            style="border-top: 1px solid #000; margin-bottom: 5px;"></div>
                                        <span class="invoice-signature-label"
                                            style="font-weight: bold;"><?php echo lang('laboratory'); ?>
                                            <?php echo lang('technician'); ?></span>
                                    </div>
                                    <div class="invoice-signature-box" style="text-align: center; width: 40%;">
                                        <div class="invoice-signature-line"
                                            style="border-top: 1px solid #000; margin-bottom: 5px;"></div>
                                        <span class="invoice-signature-label"
                                            style="font-weight: bold;"><?php echo lang('authorized'); ?>
                                            <?php echo lang('signature'); ?></span>
                                    </div>
                                </div>
                            </div>

                        </div><!-- /.invoice-content -->
                    </div><!-- /.invoice-container -->

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script type="text/javascript">
    var lab_id = "<?php echo $lab->id; ?>";
</script>
<script src="common/extranal/js/lab/invoice.js"></script>