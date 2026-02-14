<div class="info-group mb-3">
    <h6 class="text-info mb-2 font-weight-bold">
        <i class="fas fa-history"></i> Patient History
    </h6>

    <!-- Previous Prescriptions -->
    <div class="mb-3">
        <label class="text-dark mb-1 small font-weight-bold">Recent Prescriptions</label>
        <?php
        $prescriptions = $this->db->select('prescription.*, doctor.name as doctor_name')
            ->from('prescription')
            ->join('doctor', 'doctor.id = prescription.doctor', 'left')
            ->where('prescription.patient', $patient->id)
            ->order_by('prescription.date', 'DESC')
            ->limit(5)
            ->get()->result();

        if (!empty($prescriptions)): ?>
            <div class="list-group">
                <?php foreach ($prescriptions as $presc): ?>
                    <div class="list-group-item list-group-item-action p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-dark"><strong><?php echo date('d M Y', $presc->date); ?></strong> - Dr.
                                <?php echo $presc->doctor_name; ?></small>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-xs btn-outline-primary mr-1 edit-prescription-workspace"
                                    data-id="<?php echo $presc->id; ?>" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <span class="badge badge-info">Prescription</span>
                            </div>
                        </div>
                        <?php if (!empty($presc->symptom)): ?>
                            <small class="text-secondary">Symptoms: <?php echo substr($presc->symptom, 0, 50); ?>...</small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-secondary small mb-0">No previous prescriptions</p>
        <?php endif; ?>
    </div>

    <!-- Previous Lab Tests -->
    <div class="mb-3">
        <label class="text-dark mb-1 small font-weight-bold">Recent Lab Tests</label>
        <?php
        $labs = $this->db->select('lab.*, payment_category.category as test_name, doctor.name as doctor_name')
            ->from('lab')
            ->join('payment_category', 'payment_category.id = lab.category_id', 'left')
            ->join('doctor', 'doctor.id = lab.doctor', 'left')
            ->where('lab.patient', $patient->id)
            ->order_by('lab.date', 'DESC')
            ->limit(5)
            ->get()->result();

        if (!empty($labs)): ?>
            <div class="list-group">
                <?php foreach ($labs as $lab): ?>
                    <div class="list-group-item list-group-item-action p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-dark"><strong><?php echo date('d M Y', $lab->date); ?></strong> -
                                <?php echo $lab->test_name; ?></small>
                            <span class="badge badge-<?php echo $lab->status == 'Completed' ? 'success' : 'warning'; ?>">
                                <?php echo $lab->status; ?>
                            </span>
                        </div>
                        <?php if (!empty($lab->doctor_name)): ?>
                            <small class="text-secondary">Requested by: Dr. <?php echo $lab->doctor_name; ?></small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-secondary small mb-0">No previous lab tests</p>
        <?php endif; ?>
    </div>
</div>