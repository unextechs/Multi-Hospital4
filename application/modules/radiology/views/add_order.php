<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fas fa-plus"></i> <?php echo isset($order) ? lang('edit') : lang('add_new'); ?> Radiology Order
            <small><?php echo isset($order) ? 'Edit existing order' : 'Create a new radiology order'; ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fas fa-tachometer-alt"></i> Home</a></li>
            <li><a href="radiology">Radiology</a></li>
            <li><a href="radiology/orders">Orders</a></li>
            <li class="active"><?php echo isset($order) ? lang('edit') : 'New Order'; ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php if ($this->session->flashdata('feedback')): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $this->session->flashdata('feedback'); ?>
        </div>
        <?php endif; ?>
        
        <form role="form" action="radiology/addOrder" method="post" id="orderForm">
            <?php if (isset($order)): ?>
            <input type="hidden" name="id" value="<?php echo $order->id; ?>">
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fas fa-user"></i> Patient Information</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="patient_id">Patient *</label>
                                <select class="form-control select2" id="patient_id" name="patient_id" required>
                                    <option value="">Select Patient</option>
                                    <?php foreach ($patients as $patient): ?>
                                    <option value="<?php echo $patient->id; ?>" 
                                            data-name="<?php echo $patient->name; ?>" 
                                            data-phone="<?php echo $patient->phone; ?>" 
                                            data-address="<?php echo $patient->address; ?>" 
                                            data-age="<?php echo $patient->age; ?>" 
                                            data-gender="<?php echo $patient->sex; ?>"
                                            <?php echo ((isset($order) && $order->patient_id == $patient->id) || (isset($patient_single) && $patient_single->id == $patient->id)) ? 'selected' : ''; ?>>
                                        <?php echo $patient->name . ' (' . $patient->id . ')'; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="patient_name">Patient Name *</label>
                                <input type="text" class="form-control" id="patient_name" name="patient_name" 
                                       value="<?php echo isset($order) ? $order->patient_name : ''; ?>" required readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="patient_phone">Phone</label>
                                        <input type="text" class="form-control" id="patient_phone" name="patient_phone"
                                               value="<?php echo isset($order) ? $order->patient_phone : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="patient_age">Age</label>
                                        <input type="number" class="form-control" id="patient_age" name="patient_age"
                                               value="<?php echo isset($order) ? $order->patient_age : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="patient_gender">Gender</label>
                                <select class="form-control" id="patient_gender" name="patient_gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" <?php echo (isset($order) && $order->patient_gender == 'male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?php echo (isset($order) && $order->patient_gender == 'female') ? 'selected' : ''; ?>>Female</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="patient_address">Address</label>
                                <textarea class="form-control" id="patient_address" name="patient_address" rows="2"><?php echo isset($order) ? $order->patient_address : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fas fa-stethoscope"></i> Order Information</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="doctor_id">Doctor *</label>
                                <select class="form-control select2" id="doctor_id" name="doctor_id" required>
                                    <option value="">Select Doctor</option>
                                    <?php foreach ($doctors as $doctor): ?>
                                    <option value="<?php echo $doctor->id; ?>" 
                                            data-name="<?php echo $doctor->name; ?>"
                                            <?php echo ((isset($order) && $order->doctor_id == $doctor->id) || (isset($doctor_single) && $doctor_single->id == $doctor->id)) ? 'selected' : ''; ?>>
                                        <?php echo $doctor->name; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="doctor_name">Doctor Name *</label>
                                <input type="text" class="form-control" id="doctor_name" name="doctor_name" 
                                       value="<?php echo isset($order) ? $order->doctor_name : ''; ?>" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="order_date">Order Date *</label>
                                <input type="datetime-local" class="form-control" id="order_date" name="order_date" 
                                       value="<?php echo isset($order) ? date('Y-m-d\TH:i', strtotime($order->order_date)) : date('Y-m-d\TH:i'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="appointment_date">Appointment Date</label>
                                <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date"
                                       value="<?php echo (isset($order) && $order->appointment_date) ? date('Y-m-d\TH:i', strtotime($order->appointment_date)) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="urgency">Urgency</label>
                                <select class="form-control" id="urgency" name="urgency">
                                    <option value="routine" <?php echo (isset($order) && $order->urgency == 'routine') ? 'selected' : ''; ?>>Routine</option>
                                    <option value="urgent" <?php echo (isset($order) && $order->urgency == 'urgent') ? 'selected' : ''; ?>>Urgent</option>
                                    <option value="stat" <?php echo (isset($order) && $order->urgency == 'stat') ? 'selected' : ''; ?>>STAT</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fas fa-notes-medical"></i> Clinical Information</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="clinical_history">Clinical History</label>
                                <textarea class="form-control" id="clinical_history" name="clinical_history" rows="3" placeholder="Enter patient's clinical history"><?php echo isset($order) ? $order->clinical_history : ''; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="clinical_notes">Clinical Notes</label>
                                <textarea class="form-control" id="clinical_notes" name="clinical_notes" rows="3" placeholder="Enter additional clinical notes"><?php echo isset($order) ? $order->clinical_notes : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fas fa-x-ray"></i> Radiology Tests</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="test_search">Add Test</label>
                                <select class="form-control select2" id="test_search" name="test_search">
                                    <option value="">Select Test to Add</option>
                                    <?php foreach ($tests as $test): ?>
                                    <option value="<?php echo $test->id; ?>" 
                                            data-name="<?php echo $test->name; ?>" 
                                            data-category="<?php echo $test->category_name; ?>" 
                                            data-price="<?php echo $test->price; ?>">
                                        <?php echo $test->name . ' - ' . $test->category_name . ' (' . $settings->currency . number_format($test->price, 2) . ')'; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="testsTable">
                                    <thead>
                                        <tr>
                                            <th>Test</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="testsTableBody">
                                        <?php 
                                        if (isset($order) && !empty($order->test_details)) {
                                            $test_details = json_decode($order->test_details);
                                            if ($test_details) {
                                                foreach ($test_details as $test_detail) {
                                                    echo '<tr data-test-id="' . $test_detail->test_id . '">';
                                                    echo '<td>' . $test_detail->test_name . '<input type="hidden" name="test_ids[]" value="' . $test_detail->test_id . '"></td>';
                                                    echo '<td>-</td>';
                                                    echo '<td>' . $settings->currency . number_format($test_detail->price, 2) . '</td>';
                                                    echo '<td><input type="number" name="quantities[]" value="' . $test_detail->quantity . '" min="1" class="form-control quantity-input" style="width: 80px;"></td>';
                                                    echo '<td class="subtotal">' . $settings->currency . number_format($test_detail->line_total, 2) . '</td>';
                                                    echo '<td><button type="button" class="btn btn-danger btn-xs remove-test"><i class="fas fa-trash"></i></button></td>';
                                                    echo '</tr>';
                                                }
                                            }
                                        } else {
                                        ?>
                                        <tr id="noTestsRow">
                                            <td colspan="6" class="text-center">No tests selected</td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fas fa-calculator"></i> Payment Details</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Subtotal</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo $settings->currency; ?></span>
                                    <input type="text" class="form-control" id="subtotal" name="subtotal" 
                                           value="<?php echo isset($order) ? $order->subtotal : '0.00'; ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Discount (%) <small class="text-muted">(Max: <?php echo $discount_limit; ?>%)</small></label>
                                <input type="number" class="form-control" id="discount_percent" name="discount_percent" 
                                       value="<?php echo isset($order) ? $order->discount_percent : '0'; ?>" 
                                       min="0" max="<?php echo $discount_limit; ?>" step="0.01">
                            </div>
                            
                            <div class="form-group">
                                <label>Flat Discount</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo $settings->currency; ?></span>
                                    <input type="number" class="form-control" id="flat_discount" name="flat_discount" 
                                           value="<?php echo isset($order) ? $order->flat_discount : '0'; ?>" 
                                           min="0" step="0.01">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Discount Amount</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo $settings->currency; ?></span>
                                    <input type="text" class="form-control" id="discount_amount" name="discount_amount" 
                                           value="<?php echo isset($order) ? $order->discount_amount : '0.00'; ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>VAT (%)</label>
                                <input type="number" class="form-control" id="vat_percent" name="vat_percent" 
                                       value="<?php echo isset($order) ? $order->vat_percent : $settings->vat; ?>" 
                                       min="0" max="100" step="0.01">
                            </div>
                            
                            <div class="form-group">
                                <label>VAT Amount</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo $settings->currency; ?></span>
                                    <input type="text" class="form-control" id="vat_amount" name="vat_amount" 
                                           value="<?php echo isset($order) ? $order->vat_amount : '0.00'; ?>" readonly>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="form-group">
                                <label><strong>Gross Total</strong></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo $settings->currency; ?></span>
                                    <input type="text" class="form-control" id="gross_total" name="gross_total" 
                                           value="<?php echo isset($order) ? $order->gross_total : '0.00'; ?>" readonly 
                                           style="font-weight: bold; font-size: 18px;">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Amount Received</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo $settings->currency; ?></span>
                                    <input type="number" class="form-control" id="amount_received" name="amount_received" 
                                           value="<?php echo isset($order) ? $order->amount_received : '0'; ?>" 
                                           min="0" step="0.01">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Due Amount</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo $settings->currency; ?></span>
                                    <input type="text" class="form-control" id="due_amount" name="due_amount" 
                                           value="<?php echo isset($order) ? $order->due_amount : '0.00'; ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Payment Type</label>
                                <select class="form-control" id="payment_type" name="payment_type">
                                    <option value="Cash" <?php echo (isset($order) && $order->payment_type == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                                    <option value="Card" <?php echo (isset($order) && $order->payment_type == 'Card') ? 'selected' : ''; ?>>Card</option>
                                    <option value="Insurance" <?php echo (isset($order) && $order->payment_type == 'Insurance') ? 'selected' : ''; ?>>Insurance</option>
                                </select>
                            </div>
                            
                            <div class="insurance-details" style="display: none;">
                                <div class="form-group">
                                    <label>Insurance Company</label>
                                    <select class="form-control" id="insurance_company_id" name="insurance_company_id">
                                        <option value="">Select Company</option>
                                        <?php foreach ($insurance_companys as $insurance): ?>
                                        <option value="<?php echo $insurance->id; ?>" 
                                                <?php echo (isset($order) && $order->insurance_company_id == $insurance->id) ? 'selected' : ''; ?>>
                                            <?php echo $insurance->name; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Insurance Details</label>
                                    <textarea class="form-control" id="insurance_details" name="insurance_details" rows="2"><?php echo isset($order) ? $order->insurance_details : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="box-footer">
                        <button type="button" onclick="window.history.back();" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> <?php echo lang('back'); ?>
                        </button>
                        <button type="submit" name="form_submit" value="save" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> <?php echo isset($order) ? lang('update') : lang('save'); ?>
                        </button>
                        <button type="submit" name="form_submit" value="saveandprint" class="btn btn-success btn-lg">
                            <i class="fas fa-print"></i> Save & Print Label
                        </button>
                        <a href="radiology/orders" class="btn btn-default btn-lg">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

<script>
var currency = '<?php echo $settings->currency; ?>';
var discountLimit = <?php echo $discount_limit; ?>;

$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();
    
    // Patient selection handler
    $('#patient_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        $('#patient_name').val(selectedOption.data('name'));
        $('#patient_phone').val(selectedOption.data('phone'));
        $('#patient_address').val(selectedOption.data('address'));
        $('#patient_age').val(selectedOption.data('age'));
        $('#patient_gender').val(selectedOption.data('gender'));
    });
    
    // Doctor selection handler
    $('#doctor_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        $('#doctor_name').val(selectedOption.data('name'));
    });
    
    // Payment type handler
    $('#payment_type').change(function() {
        if ($(this).val() == 'Insurance') {
            $('.insurance-details').show();
        } else {
            $('.insurance-details').hide();
        }
    }).trigger('change');
    
    // Test selection handler
    $('#test_search').change(function() {
        var selectedOption = $(this).find('option:selected');
        if (selectedOption.val()) {
            addTestToTable(selectedOption.val(), selectedOption.data('name'), selectedOption.data('category'), selectedOption.data('price'));
            $(this).val('').trigger('change');
        }
    });
    
    function addTestToTable(testId, testName, category, price) {
        // Check if test already exists
        var existingRow = $('#testsTableBody tr[data-test-id="' + testId + '"]');
        if (existingRow.length > 0) {
            alert('This test is already added to the order.');
            return;
        }
        
        // Remove "no tests" row if it exists
        $('#noTestsRow').remove();
        
        // Add new test row
        var newRow = '<tr data-test-id="' + testId + '">' +
            '<td>' + testName + '<input type="hidden" name="test_ids[]" value="' + testId + '"></td>' +
            '<td>' + category + '</td>' +
            '<td>' + currency + parseFloat(price).toFixed(2) + '</td>' +
            '<td><input type="number" name="quantities[]" value="1" min="1" class="form-control quantity-input" style="width: 80px;"></td>' +
            '<td class="subtotal">' + currency + parseFloat(price).toFixed(2) + '</td>' +
            '<td><button type="button" class="btn btn-danger btn-xs remove-test"><i class="fas fa-trash"></i></button></td>' +
            '</tr>';
        
        $('#testsTableBody').append(newRow);
        calculateTotals();
    }
    
    // Remove test handler
    $(document).on('click', '.remove-test', function() {
        $(this).closest('tr').remove();
        if ($('#testsTableBody tr').length === 0) {
            $('#testsTableBody').append('<tr id="noTestsRow"><td colspan="6" class="text-center">No tests selected</td></tr>');
        }
        calculateTotals();
    });
    
    // Quantity change handler
    $(document).on('change', '.quantity-input', function() {
        var row = $(this).closest('tr');
        var price = parseFloat(row.find('td:eq(2)').text().replace(currency, ''));
        var quantity = parseInt($(this).val());
        var subtotal = price * quantity;
        
        row.find('.subtotal').text(currency + subtotal.toFixed(2));
        calculateTotals();
    });
    
    // Discount and VAT handlers
    $('#discount_percent, #flat_discount, #vat_percent, #amount_received').on('input change', function() {
        // Enforce discount limit
        var discountPercent = parseFloat($('#discount_percent').val()) || 0;
        if (discountPercent > discountLimit) {
            $('#discount_percent').val(discountLimit);
        }
        calculateTotals();
    });
    
    function calculateTotals() {
        var subtotal = 0;
        $('.subtotal').each(function() {
            subtotal += parseFloat($(this).text().replace(currency, '')) || 0;
        });
        
        var discountPercent = parseFloat($('#discount_percent').val()) || 0;
        var flatDiscount = parseFloat($('#flat_discount').val()) || 0;
        var discountAmount = (subtotal * discountPercent / 100) + flatDiscount;
        
        var afterDiscount = subtotal - discountAmount;
        
        var vatPercent = parseFloat($('#vat_percent').val()) || 0;
        var vatAmount = afterDiscount * (vatPercent / 100);
        
        var grossTotal = afterDiscount + vatAmount;
        
        var amountReceived = parseFloat($('#amount_received').val()) || 0;
        var dueAmount = grossTotal - amountReceived;
        
        $('#subtotal').val(subtotal.toFixed(2));
        $('#discount_amount').val(discountAmount.toFixed(2));
        $('#vat_amount').val(vatAmount.toFixed(2));
        $('#gross_total').val(grossTotal.toFixed(2));
        $('#due_amount').val(dueAmount.toFixed(2));
    }
    
    // Initialize calculations
    calculateTotals();
    
    // Form validation
    $('#orderForm').submit(function(e) {
        if ($('#testsTableBody tr[data-test-id]').length === 0) {
            e.preventDefault();
            alert('Please add at least one test to the order.');
            return false;
        }
    });
    
    // Trigger initial selections if editing
    <?php if (isset($order)): ?>
    $('#patient_id').trigger('change');
    $('#doctor_id').trigger('change');
    <?php endif; ?>
});
</script>
