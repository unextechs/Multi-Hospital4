<div class="row">
    <div class="col-md-12">
        <form role="form" id="workspace_prescription_form" class="clearfix" method="post" enctype="multipart/form-data">
            <div class="row">
                <!-- Left Column: History & Advice -->
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="font-weight-bold text-muted">
                            <?php echo lang('date'); ?>
                        </label>
                        <input type="text" class="form-control default-date-picker" name="date"
                            value='<?php echo date('d-m-Y'); ?>' readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-muted">
                            <?php echo lang('history'); ?>
                        </label>
                        <textarea class="form-control" name="symptom" rows="3"
                            placeholder="<?php echo lang('history'); ?>"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-muted">
                            <?php echo lang('note'); ?>
                        </label>
                        <textarea class="form-control" name="note" rows="3"
                            placeholder="<?php echo lang('note'); ?>"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-muted">
                            <?php echo lang('advice'); ?>
                        </label>
                        <textarea class="form-control" name="advice" rows="3"
                            placeholder="<?php echo lang('advice'); ?>"></textarea>
                    </div>
                </div>

                <!-- Right Column: Medicines -->
                <div class="col-md-7 border-left">
                    <h5 class="text-primary font-weight-bold mb-3"><i class="fas fa-pills mr-2"></i>
                        <?php echo lang('medicine'); ?>
                    </h5>

                    <div class="form-group medicine_sect_container">
                        <label class="text-muted small">
                            <?php echo lang('search_medicine'); ?>
                        </label>
                        <select class="form-control medicinee_workspace" id="workspace_medicine_select" name="category"
                            multiple="multiple" style="width: 100%;">
                        </select>
                    </div>

                    <div class="medicine_selected_area mt-3" style="max-height: 400px; overflow-y: auto;">
                        <!-- Selected medicines will be appended here -->
                    </div>
                </div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="patient" value="<?php echo $patient->id; ?>">
            <input type="hidden" name="doctor" value="<?php echo $doctor_id; ?>">
            <input type="hidden" name="admin" value="admin"> <!-- Required for some backend checks typically -->
            <input type="hidden" id="prescription_submission_type" name="submission_type" value="submit">

            <div class="row mt-3 border-top pt-3">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-info mr-2" id="btn_print_prescription"><i
                            class="fas fa-print"></i>
                        <?php echo lang('print'); ?> & <?php echo lang('submit'); ?>
                    </button>
                    <button type="submit" class="btn btn-primary" id="btn_save_prescription"><i class="fa fa-save"></i>
                        <?php echo lang('submit'); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>



// Handle Print Button Click
$('#btn_print_prescription').on('click', function () {
$('#prescription_submission_type').val('print');
$('#workspace_prescription_form').submit();
});

// Handle Submit Button Click
$('#btn_save_prescription').on('click', function () {
$('#prescription_submission_type').val('submit');
});
</script>