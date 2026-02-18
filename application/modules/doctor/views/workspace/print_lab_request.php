<!DOCTYPE html>
<html>

<head>
    <title>Lab Request -
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

        .invoice-box {
            max-width: 900px;
            margin: auto;
            padding: 10px;
            border: 1px solid #eee;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            font-size: 13px;
            line-height: 1.4;
        }

        .header-main {
            border-bottom: 3px solid #3182ce;
            margin-bottom: 10px;
            padding-bottom: 10px;
            text-align: center;
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .logo-img {
            max-height: 60px;
            max-width: 150px;
            object-fit: contain;
        }

        .hospital-info h3 {
            font-weight: 800;
            color: #2d3748;
            font-size: 20px;
            margin-bottom: 2px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 15px;
        }

        .info-card {
            flex: 1;
            padding: 10px;
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
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .card-title i {
            margin-right: 8px;
        }

        .test-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        .test-table th {
            background: #edf2f7;
            color: #4a5568;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            padding: 8px 10px;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }

        .test-table td {
            padding: 8px;
            border-bottom: 1px solid #edf2f7;
            vertical-align: middle;
        }

        .test-name {
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

            .invoice-box {
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

    <div class="invoice-box mt-5">
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
                style="letter-spacing: 2px; color: #3182ce; font-size: 16px;">
                <?php echo lang('lab_request'); ?>
            </h2>
            <div class="text-right small">
                <p class="mb-0 font-weight-bold">Date: <?php echo date('d-M-Y', $labs[0]->date); ?></p>
                <p class="small text-muted mb-0">Ref: LAB-<?php echo str_pad($labs[0]->id, 5, '0', STR_PAD_LEFT); ?></p>
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

        <div class="mt-4">
            <h5 class="font-weight-bold mb-3 d-flex align-items-center">
                <i class="fa fa-flask mr-2 text-primary"></i> Requested Investigations
            </h5>
            <table class="test-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">#</th>
                        <th>Investigation / Test Name</th>
                        <th class="text-right">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($labs as $lab) {
                        $category = $this->db->get_where('payment_category', array('id' => $lab->category_id))->row();
                        ?>
                        <tr>
                            <td class="text-muted"><?php echo $i++; ?></td>
                            <td><span
                                    class="test-name"><?php echo $category ? $category->category : 'Unknown Test'; ?></span>
                            </td>
                            <td class="text-right">
                                <span class="badge badge-warning px-3 py-1"
                                    style="font-weight: 600; font-size: 11px; text-transform: uppercase;">
                                    <?php echo lang('pending_at_cashier'); ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div class="small text-muted italic">
                <p class="mb-0">Note: Please proceed to cashier for payment before sample collection.</p>
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
            Print Lab Request</button>
    </div>

</body>

</html>