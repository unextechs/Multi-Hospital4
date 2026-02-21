<div class="content-wrapper bg-light">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row my-2 pl-1">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold"><i class="fas fa-file-invoice mr-2"></i><?php echo lang('invoice') ?> #
                        <?php echo $payment->id; ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><?php echo lang('home') ?></a></li>
                        <li class="breadcrumb-item"><a href="finance/payment"><?php echo lang('all') ?>
                                <?php echo lang('invoices') ?></a></li>
                        <li class="breadcrumb-item active"><?php echo lang('invoice') ?> # <?php echo $payment->id; ?>
                        </li>
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
                        <a rel="noopener" onclick="javascript:window.print();" class="btn btn-secondary"><i
                                class="fas fa-print mr-1"></i>
                            <?php echo lang('print'); ?></a>
                        <a type="button" href="finance/download?id=<?php echo $payment->id; ?>" class="btn btn-primary">
                            <i class="fas fa-download mr-1"></i> <?php echo lang('generate'); ?> PDF
                        </a>
                        <?php if ($this->ion_auth->in_group(array('admin', 'Accountant'))) { ?>
                            <?php if ($payment->payment_from == 'payment' || empty($payment->payment_from)) {
                                $lab_pending = array();
                                $lab_reports_previous = $this->lab_model->getLabByInvoice($payment->id);

                                if (!empty($lab_reports_previous)) {
                                    foreach ($lab_reports_previous as $lab) {
                                        if ($lab->test_status == 'not_done' || empty($lab->test_status)) {
                                            $lab_pending[] = 'no';
                                        }
                                    }
                                }
                                if (count($lab_reports_previous) == count($lab_pending) || empty($lab_reports_previous)) { ?>
                                    <a href="finance/editPayment?id=<?php echo $payment->id; ?>" class="btn btn-secondary"><i
                                            class="fa fa-edit mr-1"></i>
                                        <?php echo lang('edit'); ?>             <?php echo lang('invoice'); ?></a>
                                <?php }
                            } ?>
                        <?php } ?>
                        <?php if ($payment->payment_from == 'payment' || empty($payment->payment_from)) { ?>
                            <a href="finance/payment" class="btn btn-secondary"><i class="fa fa-arrow-circle-left mr-1"></i>
                                <?php echo lang('back_to_payment_modules'); ?></a>
                        <?php } ?>
                        <button type="button" class="btn btn-success depositButton" data-toggle="modal"
                            data-id="<?php echo $payment->id ?>" data-from="<?php echo $payment->payment_from ?>"><i
                                class="far fa-credit-card mr-1"></i>
                            <?php echo lang('submit'); ?> <?php echo lang('payment'); ?>
                        </button>
                    </div>

                    <!-- Main Invoice -->
                    <link rel="stylesheet" href="common/css/invoice_components.css">
                    <div class="invoice-container">

                        <!-- Watermark Background -->
                        <?php
                        $this->load->view('partials/invoice_watermark', array('settings' => $settings));
                        ?>

                        <!-- Invoice Content -->
                        <div class="invoice-content">

                            <!-- Invoice Header with Huge Logo -->
                            <?php
                            $this->load->view('partials/invoice_header', array(
                                'settings' => $settings,
                                'invoice_date' => $payment->date
                            ));
                            ?>

                            <?php // Invoice Title section removed as per request ?>

                            <?php $patient_info = $this->db->get_where('patient', array('id' => $payment->patient))->row(); ?>

                            <!-- Information Grid -->
                            <div class="invoice-info-grid">

                                <!-- Patient Details -->
                                <div class="invoice-info-box">
                                    <h4 class="invoice-info-box-title"><?php echo lang('patient'); ?>
                                        <?php echo lang('details'); ?>
                                    </h4>
                                    <div class="invoice-info-row">
                                        <span class="invoice-info-label"><?php echo lang('name'); ?>:</span>
                                        <span class="invoice-info-value"><?php echo $patient_info->name; ?></span>
                                    </div>
                                    <div class="invoice-info-row">
                                        <span class="invoice-info-label"><?php echo lang('patient_id'); ?>:</span>
                                        <span
                                            class="invoice-info-value"><?php echo (!empty($settings->patient_id_prefix) ? $settings->patient_id_prefix : 'P') . (!empty($patient_info->hospital_patient_id) ? $patient_info->hospital_patient_id : $patient_info->id); ?></span>
                                    </div>
                                    <?php if (!empty($patient_info->birthdate) || !empty($patient_info->age)): ?>
                                        <div class="invoice-info-row">
                                            <span class="invoice-info-label"><?php echo lang('age'); ?>:</span>
                                            <span class="invoice-info-value">
                                                <?php
                                                if (!empty($patient_info->birthdate)) {
                                                    $birthDate = new DateTime($patient_info->birthdate);
                                                    $today = new DateTime('today');
                                                    $diff = $birthDate->diff($today);
                                                    if ($diff->y > 0) {
                                                        echo $diff->y . ' ' . lang('years');
                                                    } elseif ($diff->m > 0) {
                                                        echo $diff->m . ' ' . lang('months');
                                                    } else {
                                                        echo $diff->d . ' ' . lang('days');
                                                    }
                                                } elseif (!empty($patient_info->age)) {
                                                    $age_parts = explode('-', $patient_info->age);
                                                    if (count($age_parts) == 3) {
                                                        if ($age_parts[0] > 0) {
                                                            echo $age_parts[0] . ' ' . lang('years');
                                                        } elseif ($age_parts[1] > 0) {
                                                            echo $age_parts[1] . ' ' . lang('months');
                                                        } else {
                                                            echo $age_parts[2] . ' ' . lang('days');
                                                        }
                                                    } else {
                                                        echo $patient_info->age;
                                                    }
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($patient_info->sex)): ?>
                                        <div class="invoice-info-row">
                                            <span class="invoice-info-label"><?php echo lang('gender'); ?>:</span>
                                            <span
                                                class="invoice-info-value"><?php echo ucfirst($patient_info->sex); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($patient_info->phone)): ?>
                                        <div class="invoice-info-row">
                                            <span class="invoice-info-label"><?php echo lang('phone'); ?>:</span>
                                            <span class="invoice-info-value"><?php echo $patient_info->phone; ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Invoice Details -->
                                <div class="invoice-info-box">
                                    <h4 class="invoice-info-box-title"><?php echo lang('invoice'); ?>
                                        <?php echo lang('details'); ?>
                                    </h4>
                                    <div class="invoice-info-row">
                                        <span class="invoice-info-label"><?php echo lang('invoice'); ?> #:</span>
                                        <span
                                            class="invoice-info-value"><?php echo str_pad($payment->id, 6, '0', STR_PAD_LEFT); ?></span>
                                    </div>
                                    <div class="invoice-info-row">
                                        <span class="invoice-info-label"><?php echo lang('type'); ?>:</span>
                                        <span class="invoice-info-value">
                                            <?php
                                            $payment_type = !empty($payment->payment_from) ? ucwords(str_replace('_', ' ', $payment->payment_from)) : 'General Payment';
                                            echo $payment_type;
                                            ?>
                                        </span>
                                    </div>
                                    <div class="invoice-info-row">
                                        <span class="invoice-info-label"><?php echo lang('date'); ?>:</span>
                                        <span
                                            class="invoice-info-value"><?php echo date('d-m-Y', $payment->date); ?></span>
                                    </div>
                                    <div class="invoice-info-row">
                                        <span class="invoice-info-label"><?php echo lang('time'); ?>:</span>
                                        <span
                                            class="invoice-info-value"><?php echo date('h:i A', $payment->date); ?></span>
                                    </div>
                                </div>

                                <!-- Doctor Details (for appointments) -->
                                <?php if ($payment->payment_from == 'appointment' && !empty($payment->appointment_id)):
                                    $appointment_details = $this->db->get_where('appointment', array('id' => $payment->appointment_id))->row();
                                    ?>
                                    <div class="invoice-info-box">
                                        <h4 class="invoice-info-box-title"><?php echo lang('doctor'); ?>
                                            <?php echo lang('details'); ?>
                                        </h4>
                                        <div class="invoice-info-row">
                                            <span class="invoice-info-label"><?php echo lang('name'); ?>:</span>
                                            <span
                                                class="invoice-info-value"><?php echo $appointment_details->doctorname; ?></span>
                                        </div>
                                        <div class="invoice-info-row">
                                            <span class="invoice-info-label"><?php echo lang('appointment'); ?>:</span>
                                            <span
                                                class="invoice-info-value"><?php echo date('d-m-Y', $appointment_details->date); ?>
                                                at <?php echo $appointment_details->time_slot; ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Items Table -->
                            <div class="invoice-table-wrapper">
                                <table class="invoice-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th><?php echo lang('description'); ?></th>
                                            <?php if ($payment->payment_from == 'appointment') { ?>
                                                <th><?php echo lang('date_time'); ?></th>
                                                <th><?php echo lang('doctor'); ?></th>
                                            <?php } elseif ($payment->payment_from != 'admitted_patient_bed_service' && $payment->payment_from != 'admitted_patient_bed_medicine') { ?>
                                                <th><?php echo lang('room_no'); ?></th>
                                                <th class="text-center"><?php echo lang('qty'); ?></th>
                                                <th class="text-right"><?php echo lang('unit_price'); ?></th>
                                            <?php } else { ?>
                                                <th class="text-center"><?php echo lang('quantity'); ?></th>
                                                <th class="text-right"><?php echo lang('unit'); ?>
                                                    <?php echo lang('price'); ?>
                                                </th>
                                            <?php } ?>
                                            <th class="text-right"><?php echo lang('amount'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($payment->payment_from == 'appointment') {
                                            if (!empty($payment->category_name)) {
                                                $appointment_details = $this->db->get_where('appointment', array('id' => $payment->appointment_id))->row();
                                                ?>
                                                <tr>
                                                    <td>1</td>
                                                    <td><?php echo $payment->category_name; ?></td>
                                                    <td>
                                                        <?php echo date('d-m-Y', $appointment_details->date); ?>
                                                        <br><small><?php echo $appointment_details->time_slot; ?></small>
                                                    </td>
                                                    <td><?php echo $appointment_details->doctorname; ?></td>
                                                    <td class="text-right"><?php echo $settings->currency; ?>
                                                        <?php echo number_format($payment->gross_total, 2); ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } elseif ($payment->payment_from == 'admitted_patient_bed_medicine') {
                                            if (!empty($payment->category_name)) {
                                                $category = explode('#', $payment->category_name);
                                                $i = 0;
                                                foreach ($category as $cat) {
                                                    $i++;
                                                    $cat_new = explode('*', $cat);
                                                    if (count($cat_new) >= 5) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo $cat_new[1]; ?></td>
                                                            <td class="text-center"><?php echo $cat_new[3]; ?></td>
                                                            <td class="text-right"><?php echo $settings->currency; ?>
                                                                <?php echo number_format($cat_new[2], 2); ?>
                                                            </td>
                                                            <td class="text-right"><?php echo $settings->currency; ?>
                                                                <?php echo number_format($cat_new[4], 2); ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                        } elseif ($payment->payment_from == 'admitted_patient_bed_service') {
                                            if (!empty($payment->category_name)) {
                                                $category = explode('#', $payment->category_name);
                                                $i = 0;
                                                foreach ($category as $cat) {
                                                    $i++;
                                                    $cat_new = explode('*', $cat);
                                                    if (count($cat_new) >= 2) {
                                                        $service = $this->db->get_where('pservice', array('id' => $cat_new[0]))->row();
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo !empty($service) ? $service->name : 'Service'; ?></td>
                                                            <td class="text-center">1</td>
                                                            <td class="text-right"><?php echo $settings->currency; ?>
                                                                <?php echo number_format($cat_new[1], 2); ?>
                                                            </td>
                                                            <td class="text-right"><?php echo $settings->currency; ?>
                                                                <?php echo number_format($cat_new[1], 2); ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                        } else {
                                            if (!empty($payment->category_name)) {
                                                $category_name = $payment->category_name;
                                                $category_name1 = explode(',', $category_name);
                                                $i = 0;
                                                foreach ($category_name1 as $category_name2) {
                                                    $category_name3 = explode('*', $category_name2);
                                                    if (isset($category_name3[3]) && $category_name3[3] > 0) {
                                                        $i++;
                                                        $cat_info = $this->finance_model->getPaymentcategoryById($category_name3[0]);
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td>
                                                                <?php
                                                                $cat_name = $cat_info->category;
                                                                echo $cat_name;
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if (strtolower($cat_name) == 'daycare' || strtolower($cat_name) == 'day care') {
                                                                    echo "Daycare Room";
                                                                } elseif (strtolower($cat_name) == 'emergency') {
                                                                    echo "Emergency Room";
                                                                } else {
                                                                    echo $cat_info->description;
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="text-center"><?php echo $category_name3[3]; ?></td>
                                                            <td class="text-right"><?php echo $settings->currency; ?>
                                                                <?php echo number_format($category_name3[1], 2); ?>
                                                            </td>
                                                            <td class="text-right"><?php echo $settings->currency; ?>
                                                                <?php echo number_format($category_name3[1] * $category_name3[3], 2); ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Payment Summary -->
                            <div class="invoice-summary-section">
                                <div class="invoice-summary-box">
                                    <div class="invoice-summary">
                                        <?php if (!empty($payment->discount) && $payment->discount > 0): ?>
                                            <div class="invoice-summary-row">
                                                <span class="invoice-summary-label"><?php echo lang('subtotal'); ?>:</span>
                                                <span class="invoice-summary-value"><?php echo $settings->currency; ?>
                                                    <?php echo number_format($payment->gross_total + $payment->discount, 2); ?></span>
                                            </div>
                                            <div class="invoice-summary-row discount">
                                                <span class="invoice-summary-label"><?php echo lang('discount'); ?>
                                                    (<?php echo number_format($payment->percent_discount, 2); ?>%):</span>
                                                <span class="invoice-summary-value">- <?php echo $settings->currency; ?>
                                                    <?php echo number_format($payment->discount, 2); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="invoice-summary-row total">
                                            <span
                                                class="invoice-summary-label"><?php echo lang('grand_total'); ?>:</span>
                                            <span class="invoice-summary-value"><?php echo $settings->currency; ?>
                                                <?php echo number_format($payment->gross_total, 2); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <?php if (!empty($payment->remarks)): ?>
                                <div class="invoice-info-box" style="margin-bottom: 20px;">
                                    <h4 class="invoice-info-box-title"><?php echo lang('remarks'); ?></h4>
                                    <p style="margin: 0; color: var(--invoice-text-muted);"><?php echo $payment->remarks; ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <!-- Footer -->
                            <?php if (!empty($settings->footer_invoice_message)): ?>
                                <div class="invoice-footer">
                                    <p class="invoice-footer-message"><?php echo $settings->footer_invoice_message; ?></p>
                                </div>
                            <?php endif; ?>

                        </div><!-- /.invoice-content -->
                    </div><!-- /.invoice-container -->


                    <!-- Send Invoice Form (No Print) -->
                    <div class="col-md-7 no-print card card-body mt-3">
                        <form role="form" action="finance/sendInvoice" method="post" enctype="multipart/form-data">
                            <div class="radio radio_button">
                                <label>
                                    <input type="radio" name="radio" id="optionsRadios2" value="patient"
                                        checked="checked">
                                    <?php echo lang('send_invoice_to_patient'); ?>
                                </label>
                            </div>
                            <div class="radio radio_button">
                                <label>
                                    <input type="radio" name="radio" id="optionsRadios2" value="other">
                                    <?php echo lang('send_invoice_to_others'); ?>
                                </label>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $payment->id; ?>">
                            <div class="radio other" style="display:none;">
                                <label>
                                    <?php echo lang('email'); ?> <?php echo lang('address'); ?>
                                    <input type="email" name="other_email" value=""
                                        class="form-control form-control-lg">
                                </label>
                            </div>
                            <button type="submit" name="submit" class="btn btn-success float-left my-3"><i
                                    class="fa fa-location-arrow"></i> <?php echo lang('send'); ?></button>
                        </form>
                    </div>

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>


<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-weight-bold"> <?php echo lang('add_deposit'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form role="form" action="finance/deposit" id="deposit-form" class="clearfix" method="post"
                    enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('name'); ?>
                                &ast; </label>
                            <input type="text" class="form-control form-control-lg" name="name" id="name" value=''
                                placeholder="" readonly="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1"> <?php echo lang('invoice_no'); ?> &ast; </label>
                            <input type="text" class="form-control form-control-lg" name="invoice_no" id="invoice_no"
                                value='' placeholder="" readonly="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1"> <?php echo lang('date'); ?> &ast; </label>
                            <input type="text" class="form-control form-control-lg" name="date" id="date" value=''
                                placeholder="" readonly="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1"> <?php echo lang('due'); ?>
                                <?php echo lang('amount'); ?></label>
                            <input type="text" class="form-control form-control-lg" id="due_amount" name="due" value=''
                                placeholder="" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1"> <?php echo lang('deposit_amount'); ?></label>
                            <input type="text" class="form-control form-control-lg" name="deposited_amount"
                                id="deposited_amount" value='' placeholder="" required>
                        </div>



                        <div class="form-group col-md-12">
                            <div class="">
                                <label for="exampleInputEmail1"> <?php echo lang('deposit_type'); ?></label>
                            </div>
                            <div class="">
                                <select class="form-control form-control-lg m-bot15 js-example-basic-single selecttype"
                                    id="selecttype" name="deposit_type" value=''>
                                    <?php if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) { ?>
                                        <option value="Cash"> <?php echo lang('cash'); ?> </option>
                                        <!-- <option value="Insurance"> <?php echo lang('insurance'); ?> </option> -->
                                        <option value="Card"> <?php echo lang('card'); ?> </option>
                                    <?php } ?>


                                </select>
                            </div>

                            <div class="hidden insurance_div">

                                <div class="form-group" style="margin-top:10px;">
                                    <label for="exampleInputEmail1"> <?php echo lang('insurance_company'); ?>
                                        <?php echo lang('name'); ?></label>

                                    <div class="company_div">
                                        <select class="form-control form-control-lg m-bot15 js-example-basic-single"
                                            name="insurance_company" id="insurance_company" value=''>
                                            <option value="">Select Company</option>
                                            <?php foreach ($insurance_companys as $insurance_company) { ?>
                                                <option value="<?php echo $insurance_company->id; ?>" <?php


                                                   ?>><?php echo $insurance_company->name; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>
                                <div class="">
                                    <div class="payment_label" style="margin-top:10px;">
                                        <label for="exampleInputEmail1"> <?php echo lang('insurance_details'); ?>
                                        </label>
                                    </div>
                                    <div class="">
                                        <textarea class="form-control form-control-lg" name="insurance_details" rows="2"
                                            cols="20"></textarea>
                                    </div>

                                </div>
                            </div>

                            <?php
                            $payment_gateway = $settings->payment_gateway;
                            ?>



                            <div class="cardPayment row mt-3">

                                <div class="col-md-12 payment pad_bot">
                                    <label for="exampleInputEmail1"> <?php echo lang('accepted'); ?>
                                        <?php echo lang('cards'); ?></label>
                                    <div class="payment pad_bot">
                                        <img src="uploads/card.png" width="100%">
                                    </div>
                                </div>
                                <?php
                                if ($payment_gateway == 'PayPal') {
                                    ?>

                                    <div class="col-md-12 payment pad_bot">
                                        <label for="exampleInputEmail1"> <?php echo lang('card'); ?>
                                            <?php echo lang('type'); ?></label>
                                        <select class="form-control form-control-lg m-bot15" name="card_type" value=''>

                                            <option value="Mastercard"> <?php echo lang('mastercard'); ?> </option>
                                            <option value="Visa"> <?php echo lang('visa'); ?> </option>
                                            <option value="American Express"> <?php echo lang('american_express'); ?>
                                            </option>
                                        </select>
                                    </div>
                                <?php } ?>
                                <?php if ($payment_gateway == '2Checkout' || $payment_gateway == 'PayPal') {
                                    ?>
                                    <div class="col-md-12 payment pad_bot mt-3">
                                        <label for="exampleInputEmail1"> <?php echo lang('cardholder'); ?>
                                            <?php echo lang('name'); ?></label>
                                        <input type="text" id="cardholder" class="form-control pay_in" name="cardholder"
                                            value='' placeholder="">
                                    </div>
                                <?php } ?>
                                <?php if ($payment_gateway != 'Pay U Money' && $payment_gateway != 'Paystack' && $payment_gateway != 'SSLCOMMERZ' && $payment_gateway != 'Paytm') { ?>
                                    <div class="col-md-12 payment pad_bot mt-3">
                                        <label for="exampleInputEmail1"> <?php echo lang('card'); ?>
                                            <?php echo lang('number'); ?></label>
                                        <input type="text" class="form-control pay_in" id="card" name="card_number" value=''
                                            placeholder="">
                                    </div>



                                    <div class="col-md-8 payment pad_bot mt-3">
                                        <label for="exampleInputEmail1"> <?php echo lang('expire'); ?>
                                            <?php echo lang('date'); ?></label>
                                        <input type="text" class="form-control pay_in" id="expire" data-date=""
                                            data-date-format="MM YY" placeholder="Expiry (MM/YY)" name="expire_date"
                                            maxlength="7" aria-describedby="basic-addon1" value='' placeholder="">
                                    </div>
                                    <div class="col-md-4 payment pad_bot mt-3">
                                        <label for="exampleInputEmail1"> <?php echo lang('cvv'); ?> </label>
                                        <input type="text" class="form-control pay_in" id="cvv" maxlength="3" name="cvv"
                                            value='' placeholder="">
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>



                        </div>


                        <input type="hidden" name="redirect" value="due">
                        <input type="hidden" name="id" id="id" value=''>
                        <input type="hidden" name="patient" id="patient_id" value=''>
                        <input type="hidden" name="payment_id" id="payment_id" value=''>
                        <div class="cashsubmit payment btn-block mt-4">
                            <button type="submit" name="submit2" id="submit1"
                                class="btn btn-primary btn-block float-right"> <?php echo lang('submit'); ?></button>
                        </div>
                        <div class="cardsubmit d-none btn-block mt-4">
                            <?php $twocheckout = $this->db->get_where('paymentGateway', array('name =' => '2Checkout'))->row(); ?>
                            <button type="submit" name="pay_now" id="submit-btn"
                                class="btn btn-primary btn-block float-right" <?php if ($settings->payment_gateway == 'Stripe') {
                                    ?>onClick="stripePay(event);" <?php }
                                ?><?php if ($settings->payment_gateway == '2Checkout' && $twocheckout->status == 'live') {
                                    ?>onClick="twoCheckoutPay(event);" <?php }
                                ?>>
                                <?php echo lang('submit'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<script type="text/javascript">
    var language = "<?php echo $this->language; ?>";
</script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="common/extranal/js/finance/invoice.js"></script>
<?php if (!empty($gateway->publish)) {
    $gateway_stripe = $gateway->publish;
} else {
    $gateway_stripe = '';
} ?>




<script type="text/javascript">
    var publish = "<?php echo $gateway_stripe; ?>";
</script>
<script src="common/js/moment.min.js"></script>

<script type="text/javascript">
    var payment_gateway = "<?php echo $settings->payment_gateway; ?>";
</script>

<script src="common/extranal/js/finance/patient_deposit.js"></script>