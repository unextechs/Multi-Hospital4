<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Radiology Label - <?php echo $order->order_number; ?></title>
    <style>
        @page {
            size: 50mm 30mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 2mm;
            font-family: Arial, sans-serif;
            font-size: 8pt;
        }

        .label-container {
            width: 46mm;
            height: 26mm;
            border: 1px solid #000;
            padding: 2mm;
            box-sizing: border-box;
            text-align: center;
        }

        .hospital-name {
            font-weight: bold;
            font-size: 7pt;
            margin-bottom: 1mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .patient-name {
            font-size: 8pt;
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .order-number {
            font-size: 7pt;
            margin-bottom: 2mm;
        }

        .barcode-container {
            margin: 2mm 0;
        }

        .barcode {
            width: 100%;
            height: 10mm;
        }

        .barcode-text {
            font-size: 8pt;
            font-family: monospace;
            letter-spacing: 1px;
            margin-top: 1mm;
        }

        .date-info {
            font-size: 6pt;
            color: #333;
        }

        /* For screen display */
        @media screen {
            body {
                background: #f0f0f0;
                padding: 20px;
            }

            .label-container {
                width: 200px;
                height: auto;
                padding: 10px;
                margin: 20px auto;
                background: white;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .hospital-name {
                font-size: 10pt;
            }

            .patient-name {
                font-size: 12pt;
            }

            .order-number {
                font-size: 9pt;
            }

            .barcode-text {
                font-size: 12pt;
            }

            .date-info {
                font-size: 8pt;
            }

            .actions {
                text-align: center;
                margin-top: 20px;
            }

            .actions button {
                padding: 10px 20px;
                margin: 5px;
                font-size: 14px;
                cursor: pointer;
            }

            .btn-print {
                background: #007bff;
                color: white;
                border: none;
                border-radius: 4px;
            }

            .btn-pdf {
                background: #28a745;
                color: white;
                border: none;
                border-radius: 4px;
            }

            .btn-back {
                background: #6c757d;
                color: white;
                border: none;
                border-radius: 4px;
            }
        }

        @media print {
            .actions {
                display: none;
            }

            body {
                background: white;
                padding: 0;
            }

            .label-container {
                border: none;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="label-container">
        <div class="hospital-name"><?php echo htmlspecialchars($settings->title); ?></div>
        <div class="patient-name"><?php echo htmlspecialchars($order->patient_name); ?></div>
        <div class="order-number">Order: <?php echo $order->order_number; ?></div>

        <div class="barcode-container">
            <!-- SVG Barcode (Code128) -->
            <svg class="barcode" id="barcode"></svg>
            <div class="barcode-text"><?php echo $order->barcode; ?></div>
        </div>

        <div class="date-info">
            <?php echo date('d/m/Y H:i', strtotime($order->order_date)); ?>
            <?php if ($order->urgency != 'routine'): ?>
                | <strong style="color: red;"><?php echo strtoupper($order->urgency); ?></strong>
            <?php endif; ?>
        </div>
    </div>

    <div class="actions">
        <button class="btn-print" onclick="window.print();">
            <i class="fa fa-print"></i> Print Label
        </button>
        <a href="<?php echo site_url('radiology/generateBarcodePdf?id=' . $order->id); ?>">
            <button class="btn-pdf" type="button">
                <i class="fa fa-file-pdf"></i> Download PDF
            </button>
        </a>
        <a href="<?php echo site_url('radiology/orders'); ?>">
            <button class="btn-back" type="button">
                <i class="fa fa-arrow-left"></i> Back to Orders
            </button>
        </a>
    </div>

    <!-- JsBarcode library for generating barcodes -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        // Generate barcode
        JsBarcode("#barcode", "<?php echo $order->barcode; ?>", {
            format: "CODE128",
            width: 1.5,
            height: 30,
            displayValue: false,
            margin: 0
        });
    </script>
</body>

</html>