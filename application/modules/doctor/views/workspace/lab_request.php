<div class="col-md-12">
    <div class="alert alert-info py-2 mb-3">
        <i class="fas fa-info-circle"></i> <strong>Patient:</strong> <?php echo $patient->name; ?> |
        <strong>P ID:</strong> <span
            class="badge badge-light"><?php echo (!empty($settings->patient_id_prefix) ? $settings->patient_id_prefix : 'P') . $patient->hospital_patient_id; ?></span>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="font-weight-bold text-muted">
            <?php echo lang('date'); ?>
        </label>
        <input type="text" class="form-control default-date-picker" name="date" value='<?php echo date('d-m-Y'); ?>'
            readonly>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label class="font-weight-bold text-muted">
            <?php echo lang('test_name'); ?> <span class="text-danger">*</span>
        </label>
        <select class="form-control shadow-sm select2-multiple" id="workspace_lab_category" name="category_id[]"
            required style="width: 100%;" multiple="multiple">
            <option value="">
                <?php echo lang('select'); ?>
            </option>
            <?php foreach ($payment_categories as $payment_category) { ?>
                <option value="<?php echo $payment_category->id; ?>">
                    <?php echo $payment_category->category; ?>
                </option>
            <?php } ?>
        </select>
    </div>
</div>
</div>

<!-- Hidden Fields -->
<input type="hidden" name="patient" value="<?php echo $patient->id; ?>">
<input type="hidden" name="doctor" value="<?php echo $doctor_id; ?>">
<input type="hidden" id="lab_submission_type" name="submission_type" value="submit">

<div class="row mt-3 border-top pt-3">
    <div class="col-md-12 text-right">
        <button type="button" class="btn btn-info mr-2" id="btn_print_lab_request"><i class="fas fa-print"></i>
            <?php echo lang('print'); ?> & <?php echo lang('submit'); ?>
        </button>
        <button type="submit" class="btn btn-primary" id="btn_save_lab_request"><i class="fas fa-flask"></i>
            <?php echo lang('submit'); ?>
        </button>
    </div>
</div>
</form>
</div>
</div>

<script>
    $(document).ready(function () {
        $("#workspace_lab_category").select2({
            placeholder: "<?php echo lang('select_test'); ?>",
            allowClear: true,
            dropdownParent: $('#workspace_lab_request_form').parent()
        });

        $('#btn_print_lab_request').on('click', function () {
            $('#lab_submission_type').val('print');
            $('#workspace_lab_request_form').submit();
        });

        $('#btn_save_lab_request').on('click', function () {
            $('#lab_submission_type').val('submit');
        });
    });
</script>