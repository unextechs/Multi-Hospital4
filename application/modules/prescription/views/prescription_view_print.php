<!--main content start-->
<!-- Invoice Components CSS for Watermark -->
<link href="common/css/invoice_components.css" rel="stylesheet" type="text/css" media="screen">

<?php
$doctor = $this->doctor_model->getDoctorById($prescription->doctor);
$patient = $this->patient_model->getPatientById($prescription->patient);
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 pl-1">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold"><i
                            class="fas fa-prescription mr-2"></i><?php echo lang('prescription'); ?> :
                        (<?php echo lang('id'); ?>: RX-<?php echo str_pad($prescription->id, 5, '0', STR_PAD_LEFT); ?>)
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home"><?php echo lang('prescription') ?></a></li>
                        <li class="breadcrumb-item active"><?php echo lang('print') ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content py-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9">
                    <!-- Document Header -->
                    <div class="document-header mb-2">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="document-title mb-1">MEDICAL PRESCRIPTION
                                    #RX-<?php echo str_pad($prescription->id, 5, '0', STR_PAD_LEFT); ?>
                                </h3>
                                <p class="document-subtitle mb-0">
                                    <?php // Doctor name removed from header as requested ?>
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="document-actions">
                                    <button type="button" class="btn btn-outline-primary btn-sm print-btn"
                                        onclick="printDocument()">
                                        <i class="fas fa-print mr-1"></i>
                                        Print Prescription
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Content -->
                    <div class="document-content" style="position: relative;">
                        <!-- Watermark Background -->
                        <?php $this->load->view('partials/invoice_watermark', array('settings' => $settings)); ?>

                        <!-- Hospital Header Section -->
                        <div class="document-section mb-4 text-center border-0">
                            <?php if (!empty($settings->logo)): ?>
                                <img src="<?php echo $settings->logo; ?>" class="hospital-logo mb-3" alt="Hospital Logo">
                                <br>
                                <span class="font-weight-bold"
                                    style="font-size: 1.1em;"><?php echo date('M d, Y', $prescription->date); ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- Doctor Information Section -->
                        <div class="document-section mb-2">
                            <h5 class="section-title">PHYSICIAN</h5>
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="info-row">
                                            <span class="info-label">Name:</span>
                                            <span
                                                class="info-value"><?php echo !empty($doctor) ? $doctor->name . (!empty($doctor->title) ? ' (' . $doctor->title . ')' : '') : (!empty($prescription->doctorname) ? $prescription->doctorname : $settings->title); ?></span>
                                        </div>
                                        <?php if (!empty($doctor) && !empty($doctor->profile)): ?>
                                            <div class="info-row">
                                                <span class="info-label">Specialization:</span>
                                                <span class="info-value"><?php echo $doctor->profile; ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="info-row">
                                            <span class="info-label">Hospital:</span>
                                            <span class="info-value"><?php echo $settings->title; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Patient Information Section -->
                        <div class="document-section mb-2">
                            <h5 class="section-title">PATIENT INFORMATION</h5>
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <span class="info-label">Name:</span>
                                            <span
                                                class="info-value"><?php echo !empty($patient) ? $patient->name : 'Not specified'; ?></span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Patient ID:</span>
                                            <span
                                                class="info-value"><?php echo !empty($patient) ? (!empty($settings->patient_id_prefix) ? $settings->patient_id_prefix : 'P') . $patient->hospital_patient_id : 'Not specified'; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <span class="info-label">Age:</span>
                                            <span class="info-value">
                                                <?php
                                                if (!empty($patient)) {
                                                    $age = explode('-', $patient->age);
                                                    if (count($age) == 3) {
                                                        echo $age[0] . " Y " . $age[1] . " M " . $age[2] . " D";
                                                    } else {
                                                        echo $patient->age;
                                                    }
                                                } else {
                                                    echo 'Not specified';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Gender:</span>
                                            <span
                                                class="info-value"><?php echo !empty($patient) ? $patient->sex : 'Not specified'; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Information Section -->
                        <div class="document-section mb-2">
                            <h5 class="section-title">MEDICAL INFORMATION</h5>
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Symptoms/History removed as per request -->
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <span class="info-label">Notes:</span>
                                            <span
                                                class="info-value"><?php echo !empty($prescription->note) ? $prescription->note : 'None'; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($prescription->advice)): ?>
                                    <div class="info-row">
                                        <span class="info-label">Advice:</span>
                                        <span class="info-value"><?php echo $prescription->advice; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Prescription Section -->
                        <div class="document-section mb-2">
                            <h5 class="section-title">PRESCRIPTION</h5>
                            <div class="section-content">
                                <div class="prescription-header">
                                    <span class="rx-symbol">℞</span>
                                    <span class="rx-text">PRESCRIPTION</span>
                                </div>

                                <?php if (!empty($prescription->medicine)): ?>
                                    <div class="medicine-table">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="medicine-col"><?php echo lang('medicine'); ?></th>
                                                    <th class="dosage-col"><?php echo lang('dosage'); ?></th>
                                                    <th class="frequency-col"><?php echo lang('frequency'); ?></th>
                                                    <th class="days-col"><?php echo lang('duration'); ?></th>
                                                    <th class="instruction-col"><?php echo lang('instruction'); ?></th>
                                                    <th class="quantity-col"><?php echo lang('quantity'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $medicine = $prescription->medicine;
                                                $medicine = explode('###', $medicine);
                                                foreach ($medicine as $key => $value) {
                                                    if (!empty(trim($value))) {
                                                        $single_medicine = explode('***', $value);
                                                        ?>
                                                        <tr>
                                                            <td class="medicine-cell">
                                                                <?php echo $this->medicine_model->getMedicineById($single_medicine[0])->name; ?>
                                                            </td>
                                                            <td class="dosage-cell">
                                                                <?php echo isset($single_medicine[1]) ? $single_medicine[1] : ''; ?>
                                                            </td>
                                                            <td class="frequency-cell">
                                                                <?php echo isset($single_medicine[2]) ? $single_medicine[2] : ''; ?>
                                                            </td>
                                                            <td class="days-cell">
                                                                <?php echo isset($single_medicine[3]) ? $single_medicine[3] : ''; ?>
                                                            </td>
                                                            <td class="instruction-cell">
                                                                <?php echo isset($single_medicine[4]) ? $single_medicine[4] : ''; ?>
                                                            </td>
                                                            <td class="quantity-cell">
                                                                <?php
                                                                $q = isset($single_medicine[5]) ? $single_medicine[5] : '';
                                                                if (
                                                                    empty($q) && isset($single_medicine[2]) &&
                                                                    isset($single_medicine[3])
                                                                ) {
                                                                    $freq = $single_medicine[2];
                                                                    $d = $single_medicine[3];
                                                                    $f_val = 0;
                                                                    if (strpos($freq, '+') !== false) {
                                                                        $parts = explode('+', $freq);
                                                                        foreach ($parts as $p)
                                                                            $f_val += (int) $p;
                                                                    } elseif (is_numeric($freq)) {
                                                                        $f_val = (int) $freq;
                                                                    }
                                                                    if ($f_val > 0 && is_numeric($d)) {
                                                                        $q = $f_val * (int) $d;
                                                                    }
                                                                }
                                                                echo $q;
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="no-medicine">
                                        <p class="text-muted">No medications prescribed</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Signature Section -->
                        <div class="document-section mb-2">
                            <div class="signature-section">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="signature-line">
                                            <span class="signature-label"><?php echo lang('signature'); ?>:</span>
                                            <div class="signature-space"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <div class="hospital-info">
                                            <div class="info-row">
                                                <span class="info-label">Hospital:</span>
                                                <span class="info-value"><?php echo $settings->title; ?></span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label">Date:</span>
                                                <span
                                                    class="info-value"><?php echo date('M d, Y', $prescription->date); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Message Section -->
                        <?php if (!empty($settings->footer_invoice_message)) { ?>
                            <div class="document-section mb-2 border-bottom-0">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <p class="font-italic text-muted mb-0">
                                            <?php echo $settings->footer_invoice_message; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="col-lg-3">
                    <div class="document-sidebar">
                        <div class="sidebar-section">
                            <h6 class="sidebar-title">PRESCRIPTION SUMMARY</h6>
                            <div class="summary-item">
                                <span class="summary-label">Prescription ID:</span>
                                <span
                                    class="summary-value">#RX-<?php echo str_pad($prescription->id, 5, '0', STR_PAD_LEFT); ?></span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Date:</span>
                                <span class="summary-value"><?php echo date('M d, Y', $prescription->date); ?></span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Doctor:</span>
                                <span
                                    class="summary-value"><?php echo !empty($doctor) ? $doctor->name : $settings->title; ?></span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Patient:</span>
                                <span
                                    class="summary-value"><?php echo !empty($patient) ? $patient->name : 'Not specified'; ?></span>
                            </div>
                            <?php if (!empty($prescription->medicine)): ?>
                                <div class="summary-item">
                                    <span class="summary-label">Medications:</span>
                                    <span
                                        class="summary-value"><?php echo count(explode('###', $prescription->medicine)); ?>
                                        items</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Action Buttons -->
                        <div class="sidebar-section">
                            <h6 class="sidebar-title">ACTIONS</h6>
                            <div class="action-buttons">
                                <button class="btn btn-outline-primary btn-sm btn-block mb-1" onclick="printDocument()">
                                    <i class="fas fa-print mr-1"></i>
                                    Print Prescription
                                </button>
                                <button class="btn btn-outline-success btn-sm btn-block mb-1" id="download">
                                    <i class="fa fa-download mr-1"></i>
                                    Download PDF
                                </button>
                                <?php if ($this->ion_auth->in_group(array('admin'))): ?>
                                    <a class="btn btn-outline-info btn-sm btn-block mb-1" href='prescription/all'>
                                        <i class="fa fa-medkit mr-1"></i>
                                        All Prescriptions
                                    </a>
                                <?php endif; ?>
                                <?php if ($this->ion_auth->in_group(array('Doctor'))): ?>
                                    <a class="btn btn-outline-info btn-sm btn-block mb-1" href='prescription'>
                                        <i class="fa fa-medkit mr-1"></i>
                                        My Prescriptions
                                    </a>
                                <?php endif; ?>
                                <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))): ?>
                                    <a class="btn btn-outline-success btn-sm btn-block mb-1"
                                        href="prescription/addPrescriptionView">
                                        <i class="fa fa-plus-circle mr-1"></i>
                                        Add Prescription
                                    </a>
                                <?php endif; ?>
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

    .prescription-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .rx-symbol {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-right: 15px;
    }

    .rx-text {
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .medicine-table th {
        background: #f8f9fa;
        font-weight: 700;
        color: var(--dark-color);
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 12px;
    }

    .medicine-table td {
        padding: 12px;
        vertical-align: middle;
    }

    .medicine-cell {
        font-weight: 600;
        color: var(--dark-color);
    }

    .quantity-cell {
        font-weight: 700;
        color: var(--primary-color);
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
            width: 100% !important;
            margin: 0 !important;
        }

        .no-print,
        .document-actions,
        .col-lg-3 {
            display: none !important;
        }

        .col-lg-9 {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        .document-header {
            border-bottom-width: 2px;
        }
    }
</style>

<script>
    function printDocument() {
        // Hide the print button and sidebar during printing
        const printBtn = document.querySelector('.print-btn');
        const documentActions = document.querySelector('.document-actions');
        const sidebar = document.querySelector('.document-sidebar');

        if (printBtn) printBtn.style.display = 'none';
        if (documentActions) documentActions.style.display = 'none';
        if (sidebar) sidebar.style.display = 'none';

        // Print the document
        window.print();

        // Restore elements after printing
        setTimeout(() => {
            if (printBtn) printBtn.style.display = 'inline-block';
            if (documentActions) documentActions.style.display = 'flex';
            if (sidebar) sidebar.style.display = 'block';
        }, 1000);
    }

    // Add keyboard shortcut for printing (Ctrl+P)
    document.addEventListener('keydown', function (e) {
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            printDocument();
        }
    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script type="text/javascript">
    var id_pres = "<?php echo $prescription->id; ?>";
</script>
<script src="common/extranal/js/prescription/prescription_print.js"></script>

<!--main content end-->