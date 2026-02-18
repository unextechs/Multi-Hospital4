<!DOCTYPE html>
<html>

<head>
    <title>Prescription -
        <?php echo $patient->name; ?>
    </title>
    <link href="<?php echo base_url(); ?>common/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>common/css/bootstrap-reset.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>common/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <style>
        body {
            background: #fff;
            font-family: 'Inter', 'Open Sans', sans-serif;
            color: #333;
        }

        .prescription-box {
            max-width: 800px;
            margin: auto;
            padding: 10px;
            border: 1px solid #eee;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            font-size: 11px;
            line-height: 1.2;
        }

        .header-main {
            border-bottom: 2px solid #3182ce;
            margin-bottom: 10px;
            padding-bottom: 5px;
            text-align: center;
        }

        .logo-container {
            margin-bottom: 5px;
        }

        .logo-img {
            max-height: 60px;
            max-width: 150px;
            object-fit: contain;
        }

        .hospital-info h3 {
            font-weight: 800;
            color: #2d3748;
            font-size: 18px;
            margin-bottom: 0px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        .info-card {
            flex: 1;
            padding: 12px;
            border-radius: 8px;
            position: relative;
        }

        .patient-card {
            background: #f8fafc;
            border-left: 6px solid #3182ce;
        }

        .physician-card {
            background: #f8fafc;
            border-left: 6px solid #2d3748;
        }

        .card-title {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            color: #718096;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .card-title i {
            margin-right: 8px;
        }

        .prescription-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        .prescription-table th {
            background: #edf2f7;
            color: #4a5568;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            padding: 8px 10px;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }

        .prescription-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #edf2f7;
            vertical-align: middle;
        }

        .medicine-name {
            font-weight: 700;
            color: #2d3748;
            font-size: 14px;
        }

        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .signature-box {
            text-align: center;
            width: 250px;
        }

        .signature-line {
            border-top: 2px solid #2d3748;
            margin-bottom: 8px;
        }

        @media print {
            .no-print {
                display: none;
            }

            .prescription-box {
                box-shadow: none;
                border: none;
                padding: 0;
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
</head>

<body onload="window.print();">

    <div class="prescription-box mt-5">
        <div class="header-main d-flex justify-content-between align-items-center">
            <div class="logo-container mb-0" style="text-align: left;">
                <img src="<?php echo base_url() . $settings->logo; ?>" class="logo-img">
            </div>
            <div class="hospital-info text-right" style="flex: 1;">
                <h3><?php echo $settings->title; ?></h3>
                <p class="mb-0 text-muted small" style="font-size: 10px;">
                    <i class="fa fa-map-marker mr-1"></i> <?php echo $settings->address; ?> |
                    <i class="fa fa-phone mr-1"></i> <?php echo $settings->phone; ?>
                </p>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
            <h2 class="text-uppercase font-weight-bold mb-0"
                style="letter-spacing: 2px; color: #2d3748; font-size: 16px;">
                <?php echo lang('prescription'); ?>
            </h2>
            <div class="text-right small">
                <p class="mb-0 font-weight-bold">Date: <?php echo date('d-M-Y', $prescription->date); ?></p>
                <p class="small text-muted mb-0">Ref: RX-<?php echo str_pad($prescription->id, 5, '0', STR_PAD_LEFT); ?>
                </p>
            </div>
        </div>

        <div class="info-section">
            <div class="info-card patient-card">
                <div class="card-title"><i class="fa fa-user"></i> Patient Details</div>
                <h5 class="mb-2 font-weight-bold"><?php echo $patient->name; ?></h5>
                <div class="row small">
                    <div class="col-xs-6">
                        <p class="mb-1 text-muted">ID: <span
                                class="text-dark font-weight-bold"><?php echo (!empty($settings->patient_id_prefix) ? $settings->patient_id_prefix : 'P') . $patient->hospital_patient_id; ?></span>
                        </p>
                        <p class="mb-1 text-muted">Gender: <span class="text-dark"><?php echo $patient->sex; ?></span>
                        </p>
                    </div>
                    <div class="col-xs-6">
                        <p class="mb-1 text-muted">Age: <span
                                class="text-dark"><?php echo !empty($age) ? $age . ' Years' : 'N/A'; ?></span></p>
                        <p class="mb-1 text-muted">Contact: <span
                                class="text-dark"><?php echo $patient->phone; ?></span></p>
                    </div>
                </div>
            </div>

            <div class="info-card physician-card">
                <div class="card-title"><i class="fa fa-user-md"></i> Physician</div>
                <h5 class="mb-2 font-weight-bold">
                    <?php echo !empty($doctor->title) ? $doctor->title . ' ' : ''; ?><?php echo $doctor->name; ?>
                </h5>
                <p class="mb-1 text-muted">Specialization:</p>
                <p class="mb-0 text-dark font-weight-bold">
                    <?php echo !empty($doctor->specialization) ? $doctor->specialization : (!empty($doctor->department_name) ? $doctor->department_name : $doctor->department); ?>
                </p>
            </div>
        </div>

        <?php if (!empty($prescription->symptom)): ?>
            <div class="alert alert-light border-0 py-2 px-3 mb-4"
                style="background: #fdf2f2; border-left: 4px solid #f87171 !important;">
                <h6 class="font-weight-bold mb-1 text-danger small"><i class="fa fa-stethoscope mr-1"></i> Symptoms &
                    Clinical Findings:</h6>
                <p class="mb-0 small text-dark"><?php echo nl2br($prescription->symptom); ?></p>
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <h5 class="font-weight-bold mb-3 d-flex align-items-center">
                <i class="fa fa-medkit mr-2 text-primary"></i> Prescribed Medications
            </h5>
            <table class="prescription-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Medicine</th>
                        <th style="width: 10%;">Route</th>
                        <th style="width: 12%;">Dosage</th>
                        <th style="width: 15%;">Frequency</th>
                        <th style="width: 8%;">Duration</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 15%;">Instructions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($prescription->medicine)) {
                        $medicines = explode('###', $prescription->medicine);
                        $i = 1;
                        foreach ($medicines as $med) {
                            $parts = explode('***', $med);
                            if (count($parts) >= 5) {
                                $med_id = $parts[0];
                                $dosage = $parts[1];
                                $frequency = $parts[2];
                                $days = $parts[3];
                                $instruction = $parts[4];
                                $quantity = isset($parts[5]) ? $parts[5] : '';
                                $route = isset($parts[6]) ? $parts[6] : '';

                                $medicine_info = $this->db->get_where('medicine', array('id' => $med_id))->row();
                                $medicine_name = $medicine_info ? $medicine_info->name : 'Unknown Medicine';
                                ?>
                                <tr>
                                    <td class="text-muted"><?php echo $i++; ?></td>
                                    <td><span class="medicine-name"><?php echo $medicine_name; ?></span></td>
                                    <td><span class="badge badge-light px-2"
                                            style="font-weight: 500; font-size: 11px;"><?php echo $route; ?></span></td>
                                    <td><?php echo $dosage; ?></td>
                                    <td><?php echo $frequency; ?></td>
                                    <td><?php echo $days; ?></td>
                                    <td><span class="font-weight-bold"><?php echo $quantity; ?></span></td>
                                    <td class="small italic text-muted"><?php echo $instruction; ?></td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <?php if (!empty($prescription->advice)): ?>
                <div class="col-xs-6">
                    <div class="p-3 rounded" style="background: #fffaf0; border: 1px dashed #f6ad55;">
                        <h6 class="font-weight-bold text-warning mb-2 small"><i class="fa fa-lightbulb-o mr-1"></i>
                            Physician's Advice:</h6>
                        <p class="mb-0 small"><?php echo nl2br($prescription->advice); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($prescription->note)): ?>
                <div class="col-xs-6">
                    <div class="p-3 rounded" style="background: #f0fff4; border: 1px dashed #68d391;">
                        <h6 class="font-weight-bold text-success mb-2 small"><i class="fa fa-sticky-note mr-1"></i>
                            Important Notes:</h6>
                        <p class="mb-0 small"><?php echo nl2br($prescription->note); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <div class="small text-muted italic">
                <p class="mb-0">Note: Please take medicines exactly as prescribed.</p>
                <p class="mb-0">System Generated | Licensed to <?php echo $settings->title; ?></p>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <p class="small font-weight-bold mb-0">Physician's Signature & Stamp</p>
                <p class="small text-muted" style="font-size: 10px;">ID: <?php echo $doctor->id; ?></p>
            </div>
        </div>
    </div>

    <div class="text-center mt-4 mb-5 no-print">
        <button onclick="window.print();" class="btn btn-primary btn-lg px-5 shadow"><i class="fa fa-print mr-2"></i>
            Print Prescription</button>
    </div>

</body>

</html>