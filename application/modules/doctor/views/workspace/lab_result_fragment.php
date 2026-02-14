<div class="lab-result-view">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h5 class="mb-1 text-primary">
                <?php echo $category_name; ?>
            </h5>
            <p class="text-muted small mb-0"><i class="fas fa-calendar-alt mr-1"></i> Ordered on:
                <?php echo date('d M Y, H:i', $lab->date); ?>
            </p>
        </div>
        <div class="text-right">
            <span class="badge badge-success px-3 py-2">Result Ready</span>
        </div>
    </div>

    <div class="result-details p-3 bg-light rounded border mb-4">
        <h6 class="font-weight-bold mb-3"><i class="fas fa-microscope mr-2"></i>Investigation Report</h6>
        <div class="report-content" style="white-space: pre-wrap; min-height: 150px; line-height: 1.6;">
            <?php echo !empty($lab->report) ? nl2br($lab->report) : '<em class="text-muted">No detailed report provided. Please check the physical attachment if any.</em>'; ?>
        </div>
    </div>

    <?php if (!empty($lab->date_time_done)): ?>
        <div class="small text-muted mt-3">
            <p class="mb-1"><strong>Completed At:</strong>
                <?php echo date('d M Y, H:i', strtotime($lab->date_time_done)); ?>
            </p>
            <?php if (!empty($lab->laboratorist_name)): ?>
                <p class="mb-0"><strong>Conducted By:</strong>
                    <?php echo $lab->laboratorist_name; ?>
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>