<!DOCTYPE html>
<html>

<head>
    <base href="<?php echo base_url(); ?>">
    <meta charset="UTF-8">
    <title><?php echo lang('queue_token'); ?> - <?php echo $settings->system_vendor; ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, sans-serif;
            background: #f5f5f5;
            padding: 10px;
        }

        .token-container {
            max-width: 350px;
            margin: 0 auto;
            background: #ffffff;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .token-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 15px;
            text-align: center;
            color: white;
        }

        .hospital-logo {
            max-width: 120px;
            max-height: 60px;
            margin-bottom: 8px;
            background: white;
            padding: 5px;
            border-radius: 6px;
        }

        .token-title {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.95;
        }

        /* Queue Number Section */
        .queue-section {
            background: #1e3a8a;
            padding: 25px 15px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .queue-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .queue-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 8px;
        }

        .queue-number {
            font-size: 64px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 3px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        /* Patient Info Section */
        .patient-section {
            background: #f8fafc;
            border-bottom: 2px dashed #cbd5e1;
            padding: 15px;
        }

        .section-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .patient-name {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .patient-meta {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: #475569;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .meta-label {
            font-weight: 600;
            color: #64748b;
        }

        .meta-value {
            font-weight: 700;
            color: #1e293b;
        }

        /* Doctor Info Section */
        .doctor-section {
            background: #ffffff;
            border-bottom: 2px dashed #cbd5e1;
            padding: 15px;
        }

        .doctor-name {
            font-size: 15px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 4px;
        }

        .doctor-specialty {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .doctor-meta {
            font-size: 11px;
            color: #94a3b8;
        }

        /* Appointment Details */
        .appointment-section {
            background: #f8fafc;
            padding: 15px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .detail-item {
            background: white;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .detail-label {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Footer */
        .token-footer {
            background: white;
            padding: 12px;
            text-align: center;
            border-top: 2px dashed #cbd5e1;
        }

        .footer-message {
            font-size: 11px;
            color: #64748b;
            font-style: italic;
            margin-bottom: 6px;
        }

        .print-info {
            font-size: 9px;
            color: #94a3b8;
        }

        /* Thermal Print Support */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .token-container {
                max-width: 100%;
                border: none;
                box-shadow: none;
                border-radius: 0;
            }

            @page {
                size: 80mm auto;
                margin: 3mm;
            }

            .queue-section::before {
                animation: none;
            }
        }

        /* For very small thermal printers (58mm) */
        @media print and (max-width: 58mm) {
            .token-header {
                padding: 10px;
            }

            .hospital-logo {
                max-width: 80px;
                max-height: 40px;
            }

            .token-title {
                font-size: 11px;
            }

            .queue-number {
                font-size: 48px;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body onload="window.print();">
    <div class="token-container">

        <!-- Header -->
        <div class="token-header">
            <?php if (!empty($settings->logo)): ?>
                <img src="<?php echo $settings->logo; ?>" alt="<?php echo $settings->system_vendor; ?>"
                    class="hospital-logo">
            <?php else: ?>
                <div style="font-size: 18px; font-weight: 700; margin-bottom: 8px;"><?php echo $settings->system_vendor; ?>
                </div>
            <?php endif; ?>
            <div class="token-title"><?php echo lang('appointment'); ?> <?php echo lang('queue_token'); ?></div>
        </div>

        <!-- Queue Number -->
        <div class="queue-section">
            <div class="queue-label"><?php echo lang('your'); ?> <?php echo lang('queue'); ?>
                <?php echo lang('number'); ?></div>
            <div class="queue-number">#<?php echo str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT); ?></div>
        </div>

        <!-- Patient Information -->
        <div class="patient-section">
            <div class="section-title"><?php echo lang('patient'); ?> <?php echo lang('information'); ?></div>
            <div class="patient-name"><?php echo $patient->name; ?></div>
            <div class="patient-meta">
                <?php
                // Calculate age
                $age = 'N/A';
                if (!empty($patient->birthdate)) {
                    $birthDate = strtotime($patient->birthdate);
                    $birthDate = date('m/d/Y', $birthDate);
                    $birthDate = explode("/", $birthDate);
                    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
                        ? ((date("Y") - $birthDate[2]) - 1)
                        : (date("Y") - $birthDate[2]));
                    $age .= ' ' . lang('years');
                } elseif (!empty($patient->age)) {
                    $age_parts = explode('-', $patient->age);
                    $age = $age_parts[0] . 'Y';
                }
                ?>
                <div class="meta-item">
                    <span class="meta-label"><?php echo lang('age'); ?>:</span>
                    <span class="meta-value"><?php echo $age; ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label"><?php echo lang('gender'); ?>:</span>
                    <span class="meta-value"><?php echo !empty($patient->sex) ? $patient->sex : 'N/A'; ?></span>
                </div>
                <?php if (!empty($patient->phone)): ?>
                    <div class="meta-item">
                        <span class="meta-label"><?php echo lang('phone'); ?>:</span>
                        <span class="meta-value"><?php echo $patient->phone; ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Doctor Information -->
        <div class="doctor-section">
            <div class="section-title"><?php echo lang('doctor'); ?> <?php echo lang('information'); ?></div>
            <div class="doctor-name">
                <?php echo !empty($doctor->title) ? $doctor->title . ' ' : ''; ?><?php echo $doctor->name; ?></div>
            <?php if (!empty($doctor->profile)): ?>
                <div class="doctor-specialty"><?php echo $doctor->profile; ?></div>
            <?php endif; ?>
            <?php if (!empty($doctor->department)): ?>
                <div class="doctor-meta"><?php echo lang('department'); ?>: <?php echo $doctor->department; ?></div>
            <?php endif; ?>
        </div>

        <!-- Appointment Details -->
        <div class="appointment-section">
            <div class="section-title"><?php echo lang('appointment'); ?> <?php echo lang('details'); ?></div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label"><?php echo lang('date'); ?></div>
                    <div class="detail-value"><?php echo date('d M Y', $appointment->date); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label"><?php echo lang('time'); ?></div>
                    <div class="detail-value">
                        <?php
                        if (!empty($appointment->s_time)) {
                            echo $appointment->s_time;
                        } else {
                            echo date('h:i A', $appointment->date);
                        }
                        ?>
                    </div>
                </div>
                <?php if (!empty($appointment->room_id) && strpos($appointment->room_id, 'hms-meeting') === false): ?>
                    <div class="detail-item" style="grid-column: 1 / -1;">
                        <div class="detail-label"><?php echo lang('room'); ?></div>
                        <div class="detail-value"><?php echo $appointment->room_id; ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="token-footer">
            <div class="footer-message"><?php echo lang('thank_you_for_choosing_us'); ?></div>
            <div class="print-info"><?php echo lang('printed'); ?>: <?php echo date('d/m/Y h:i A'); ?></div>
        </div>

    </div>
</body>

</html>