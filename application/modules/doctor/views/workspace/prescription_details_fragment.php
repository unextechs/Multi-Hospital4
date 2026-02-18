<div class="prescription-details-view">
    <div class="patient-record-header mb-4 border-bottom pb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 text-success">Medical Record -
                    <?php echo date('M d, Y', $prescriptions[reset($prescriptions)->id]->date); ?>
                </h5>
                <p class="text-muted small">ID: P
                    <?php echo $patient->hospital_patient_id; ?> |
                    <?php echo $patient->name; ?>
                </p>
            </div>
            <div class="col-md-6 text-right">
                <span class="badge badge-info">Routine Visit</span>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm bg-white overflow-hidden">
                <div class="card-header bg-info text-white py-1 small font-weight-bold">Symptoms Reported</div>
                <div class="card-body p-3">
                    <?php
                    $p_symptoms = isset($patient->symptoms) ? explode(',', $patient->symptoms) : [];
                    if (!empty($p_symptoms)):
                        foreach ($p_symptoms as $ps):
                            $s_name = trim($ps);
                            foreach ($symptoms as $s) {
                                if ($s->id == $ps) {
                                    $s_name = $s->name;
                                    break;
                                }
                            }
                            ?>
                            <span class="badge badge-light border text-dark mr-1 mb-1">
                                <?php echo $s_name; ?>
                            </span>
                        <?php endforeach; else: ?>
                        <p class="text-muted small mb-0">No symptoms recorded</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm bg-white overflow-hidden">
                <div class="card-header bg-dark text-white py-1 small font-weight-bold">Diagnosis</div>
                <div class="card-body p-3">
                    <?php
                    $p_diagnosis = isset($patient->diagnosis) ? explode(',', $patient->diagnosis) : [];
                    if (!empty($p_diagnosis)):
                        foreach ($p_diagnosis as $pd):
                            $d_name = trim($pd);
                            foreach ($diagnoses as $d) {
                                if ($d->id == $pd) {
                                    $d_name = $d->name;
                                    break;
                                }
                            }
                            ?>
                            <div class="small font-weight-bold text-danger mb-1"><i class="fas fa-check-circle mr-1"></i>
                                <?php echo $d_name; ?>
                            </div>
                        <?php endforeach; else: ?>
                        <p class="text-muted small mb-0">No diagnosis recorded</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="medicines-section mb-4">
        <h6 class="font-weight-bold border-left border-primary pl-2 mb-3">Prescribed Medications</h6>
        <div class="table-responsive">
            <table class="table table-sm table-hover border">
                <thead class="bg-light">
                    <tr>
                        <th class="small">Medicine</th>
                        <th class="small">Dosage</th>
                        <th class="small">Freq</th>
                        <th class="small">Dur</th>
                        <th class="small">Route</th>
                        <th class="small">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prescriptions as $p):
                        if (!empty($p->medicine)) {
                            $meds = explode('###', $p->medicine);
                            foreach ($meds as $m) {
                                $parts = explode('***', $m);
                                if (count($parts) >= 5) {
                                    // Resolve Name (this is bit tricky if we didn't pass all_medicines but we can query it)
                                    $med_info = $this->db->get_where('medicine', ['id' => $parts[0]])->row();
                                    $med_name = $med_info ? $med_info->name : 'Unknown';
                                    ?>
                                    <tr>
                                        <td><strong>
                                                <?php echo $med_name; ?>
                                            </strong></td>
                                        <td>
                                            <?php echo $parts[1]; ?>
                                        </td>
                                        <td>
                                            <?php echo $parts[2]; ?>
                                        </td>
                                        <td>
                                            <?php echo $parts[3]; ?>
                                        </td>
                                        <td>
                                            <?php echo isset($parts[6]) ? $parts[6] : 'Oral'; ?>
                                        </td>
                                        <td class="small text-muted">
                                            <?php echo $parts[4]; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                    endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (!empty($patient->notes)): ?>
        <div class="clinical-notes p-3 bg-light rounded border mb-4">
            <h6 class="font-weight-bold small text-muted text-uppercase mb-2">Clinical Notes & Advice</h6>
            <div class="small" style="line-height: 1.5;">
                <?php echo nl2br($patient->notes); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($labs)): ?>
        <div class="labs-section">
            <h6 class="font-weight-bold border-left border-info pl-2 mb-3">Lab Investigations Ordered</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover border">
                    <thead class="bg-light">
                        <tr>
                            <th class="small">Investigation</th>
                            <th class="small">Date</th>
                            <th class="small">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($labs as $lab): ?>
                            <tr>
                                <td><strong>
                                        <?php
                                        $cat_name = 'Unknown';
                                        $pc = $this->finance_model->getPaymentCategoryById($lab->category_id);
                                        if ($pc) {
                                            $cat_name = $pc->category;
                                        }
                                        echo $cat_name;
                                        ?>
                                    </strong></td>
                                <td><?php echo date('d M Y', $lab->date); ?></td>
                                <td>
                                    <span
                                        class="badge badge-<?php echo ($lab->status == 'complete') ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($lab->status); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>