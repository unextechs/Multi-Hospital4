<!DOCTYPE html>
<html>

<head>
    <base href="<?php echo base_url(); ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo lang('invoice'); ?> - <?php echo $settings->title; ?></title>

    <!-- Core Styles -->
    <link href="common/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="common/assets/fontawesome5pro/css/all.min.css" rel="stylesheet" type="text/css" media="screen">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .invoice-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .invoice-logo {
            max-width: 200px;
            max-height: 100px;
            margin-bottom: 15px;
        }

        .clinic-name {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .clinic-info {
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }

        /* Title and Meta */
        .invoice-title {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .invoice-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }

        .invoice-number {
            font-size: 16px;
            color: #666;
        }

        .invoice-date {
            font-size: 14px;
            color: #666;
        }

        /* Patient Details - Simple inline */
        .patient-details {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #2c3e50;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .detail-line {
            display: flex;
            padding: 6px 0;
            font-size: 14px;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
            min-width: 120px;
        }

        .detail-value {
            color: #333;
        }

        /* Medicines Table */
        .medicines-table {
            width: 100%;
            margin: 25px 0;
            border-collapse: collapse;
        }

        .medicines-table thead th {
            background: #2c3e50;
            color: #fff;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .medicines-table tbody td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }

        .medicines-table tbody tr:last-child td {
            border-bottom: 2px solid #2c3e50;
        }

        .medicines-table tbody tr:hover {
            background: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Summary */
        .invoice-summary {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .summary-table {
            min-width: 300px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }

        .summary-row.total {
            border-top: 2px solid #2c3e50;
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
            padding-top: 12px;
            margin-top: 8px;
        }

        .summary-row.discount .summary-value {
            color: #e74c3c;
        }

        /* Footer */
        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #777;
            font-style: italic;
        }

        /* Action Buttons */
        .no-print {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .invoice-wrapper {
                box-shadow: none;
                padding: 20px;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 15mm;
            }
        }
    </style>
</head>

<body>

    <!-- Action Buttons (No Print) -->
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print mr-1"></i> <?php echo lang('print'); ?>
        </button>
        <button id="download" class="btn btn-success">
            <i class="fas fa-download mr-1"></i> <?php echo lang('download'); ?>
        </button>
        <?php if ($this->ion_auth->in_group(array('admin', 'Pharmacist', 'Accountant'))) { ?>
            <!--
            <a href="finance/pharmacy/editPayment?id=<?php echo $payment->id; ?>" class="btn btn-secondary">
                <i class="fas fa-edit mr-1"></i> <?php echo lang('edit'); ?>
            </a>
            -->
        <?php } ?>
        <a href="finance/pharmacy" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> <?php echo lang('back'); ?>
        </a>
    </div>

    <!-- Invoice Wrapper -->
    <div class="invoice-wrapper" id="invoice">

        <!-- Header -->
        <div class="invoice-header">
            <?php if (!empty($settings->logo)): ?>
                <img src="<?php echo $settings->logo; ?>" alt="<?php echo $settings->title; ?>" class="invoice-logo">
            <?php endif; ?>
            <!-- Hospital details removed as per request -->

            <!-- Watermark -->
            <div
                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.1; z-index: 0; pointer-events: none;">
                <?php if (!empty($settings->logo)): ?>
                    <img src="<?php echo $settings->logo; ?>" style="width: 400px; max-width: 80%;" alt="Watermark">
                <?php endif; ?>
            </div>
        </div>

        <!-- Title and Meta -->
        <div class="invoice-meta" style="position: relative; z-index: 1;">
            <div>
                <?php // Pharmacy Invoice title removed as per request ?>
                <div class="invoice-number">Invoice #<?php echo str_pad($payment->id, 6, '0', STR_PAD_LEFT); ?></div>
            </div>
            <div class="invoice-date">
                <?php echo date('d M Y, h:i A', $payment->date); ?>
            </div>
        </div>

        <!-- Patient Details -->
        <?php
        // Attempt to get patient details
        $patient_name = $payment->patient;
        $patient_details = null;

        if (!empty($patient_name)) {
            // Try to lookup by name (fallback if patient ID not available directly)
            $this->db->where('name', $patient_name);
            $this->db->where('hospital_id', $this->session->userdata('hospital_id'));
            $query = $this->db->get('patient');
            if ($query->num_rows() > 0) {
                $patient_details = $query->row();
            }
        }
        ?>

        <div class="patient-details" style="position: relative; z-index: 1;">
            <div class="section-title"><?php echo lang('patient'); ?> <?php echo lang('details'); ?></div>
            <div class="detail-line">
                <span class="detail-label"><?php echo lang('name'); ?>:</span>
                <span class="detail-value"><?php echo $patient_name; ?></span>
            </div>
            <?php if (!empty($patient_details)): ?>
                <?php if (!empty($patient_details->hospital_patient_id)): ?>
                    <div class="detail-line">
                        <span class="detail-label"><?php echo lang('patient_id'); ?>:</span>
                        <span class="detail-value">P-<?php echo $patient_details->hospital_patient_id; ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($patient_details->age)): ?>
                    <div class="detail-line">
                        <span class="detail-label"><?php echo lang('age'); ?>:</span>
                        <span class="detail-value"><?php echo $patient_details->age; ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($patient_details->phone)): ?>
                    <div class="detail-line">
                        <span class="detail-label"><?php echo lang('phone'); ?>:</span>
                        <span class="detail-value"><?php echo $patient_details->phone; ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($patient_details->address)): ?>
                    <div class="detail-line">
                        <span class="detail-label"><?php echo lang('address'); ?>:</span>
                        <span class="detail-value"><?php echo $patient_details->address; ?></span>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Medicines Table -->
        <table class="medicines-table" style="position: relative; z-index: 1;">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th><?php echo lang('medicine'); ?> <?php echo lang('name'); ?></th>
                    <th class="text-right"><?php echo lang('unit_price'); ?></th>
                    <th class="text-center"><?php echo lang('quantity'); ?></th>
                    <th class="text-right"><?php echo lang('total'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($payment_items)) {
                    $i = 0;
                    foreach ($payment_items as $item) {
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>
                                <strong><?php echo $item->medicine_name; ?></strong>
                                <?php if (!empty($item->company)): ?>
                                    <br><small style="color: #777;"><?php echo $item->company; ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-right"><?php echo $settings->currency; ?>
                                <?php echo number_format($item->price, 2); ?>
                            </td>
                            <td class="text-center"><?php echo $item->quantity; ?></td>
                            <td class="text-right"><?php echo $settings->currency; ?>
                                <?php echo number_format($item->total, 2); ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5" class="text-center"><?php echo lang('no_data_found'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Summary -->
        <div class="invoice-summary" style="position: relative; z-index: 1;">
            <div class="summary-table">
                <?php if (!empty($payment->discount) && $payment->discount > 0): ?>
                    <div class="summary-row">
                        <span><?php echo lang('subtotal'); ?>:</span>
                        <span class="summary-value"><?php echo $settings->currency; ?>
                            <?php echo number_format($payment->amount, 2); ?></span>
                    </div>
                    <div class="summary-row discount">
                        <span><?php echo lang('discount'); ?>:</span>
                        <span class="summary-value">- <?php echo $settings->currency; ?>
                            <?php echo number_format($payment->discount, 2); ?></span>
                    </div>
                <?php endif; ?>

                <div class="summary-row total">
                    <span><?php echo lang('grand_total'); ?>:</span>
                    <span><?php echo $settings->currency; ?>
                        <?php echo number_format($payment->gross_total, 2); ?></span>
                </div>

                <?php if (!empty($payment->amount_received) && $payment->amount_received > 0): ?>
                    <div class="summary-row">
                        <span><?php echo lang('amount_paid'); ?>:</span>
                        <span class="summary-value" style="color: #27ae60;"><?php echo $settings->currency; ?>
                            <?php echo number_format($payment->amount_received, 2); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Due amount removed as per request -->
            </div>
        </div>

        <!-- Footer -->
        <?php if (!empty($settings->footer_invoice_message)): ?>
            <div class="invoice-footer">
                <?php echo $settings->footer_invoice_message; ?>
            </div>
        <?php endif; ?>

    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        document.getElementById('download').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const invoiceElement = document.getElementById('invoice');

            html2canvas(invoiceElement, {
                scale: 2,
                useCORS: true,
                logging: false
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgWidth = 210;
                const imgHeight = canvas.height * imgWidth / canvas.width;

                pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                pdf.save('pharmacy_invoice_<?php echo $payment->id; ?>.pdf');
            });
        });
    </script>
</body>

</html>