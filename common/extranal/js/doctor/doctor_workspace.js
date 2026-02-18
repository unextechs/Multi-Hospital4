// Doctor Workspace JS
(function () {
    'use strict';

    var containerId = 'doctor-workspace-container';

    // Ensure base_url is defined to prevent errors
    if (typeof base_url === 'undefined') {
        // Attempt to guess base_url from current location if not defined
        var pathArray = window.location.pathname.split('/');
        // Assuming typical structure /ProjectName/module/method or /module/method
        // We generally need the root. This is a fallback.
        // Best effort: protocol + host + first path segment if it's not a standard page
        // But for this app, it seems to be in a subfolder "Multi-Hospital" often.
        if (window.location.hostname === 'localhost' || pathArray[1] === 'Multi-Hospital') {
             // Hardcoded common fallback for this environment setup
             window.base_url = window.location.origin + '/Multi-Hospital/'; 
        } else {
             window.base_url = window.location.origin + '/';
        }
        console.warn('base_url was undefined. Auto-defined as: ' + window.base_url);
    }

    // Medical Info Edit Handler
    $(document).off('click', '#editMedicalInfoBtn').on('click', '#editMedicalInfoBtn', function(e) {
        e.preventDefault();
        $('#medical_info_form textarea').prop('readonly', false).addClass('border-primary');
        $('#medical_info_form select').prop('disabled', false).trigger('change');
        $('#saveMedicalInfoBtn').show();
        $('#editMedicalInfoBtn').hide();
    });

    // Medical Info Save Handler
    $(document).off('click', '#saveMedicalInfoBtn').on('click', '#saveMedicalInfoBtn', function(e) {
        e.preventDefault();
        var $form = $('#medical_info_form');
        var formData = $form.serialize();
        var $btn = $(this);
        var oldBtnText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: window.base_url + 'doctor/workspace/save_medical_info',
            type: 'POST',
            data: formData,
            dataType: 'json',
            cache: false,
            success: function(response) {
                if (response.success) {
                    alert('Medical Info Updated Successfully');
                    $('#medical_info_form textarea').prop('readonly', true).removeClass('border-primary');
                    $('#medical_info_form select').prop('disabled', true);
                    $('#saveMedicalInfoBtn').hide();
                    $('#editMedicalInfoBtn').show();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error saving medical info');
            },
            complete: function() {
                $btn.prop('disabled', false).html(oldBtnText);
            }
        });
    });
    
    // Vital Signs Edit Handler
    $(document).off('click', '#editVitalSignsBtn').on('click', '#editVitalSignsBtn', function(e) {
        e.preventDefault();
        $('#vital_signs_form input').prop('readonly', false).addClass('border-primary');
        $('#saveVitalSignsBtn').show();
        $('#editVitalSignsBtn').hide();
    });
    
    // Vital Signs Save Handler
    $(document).off('click', '#saveVitalSignsBtn').on('click', '#saveVitalSignsBtn', function(e) {
        e.preventDefault();
        var $form = $('#vital_signs_form');
        var formData = $form.serialize();
        var $btn = $(this);
        var oldBtnText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: window.base_url + 'doctor/workspace/save_vital_signs',
            type: 'POST',
            data: formData,
            dataType: 'json',
            cache: false,
            success: function(response) {
                if (response.success) {
                    alert('Vital Signs Updated Successfully');
                    $('#vital_signs_form input').prop('readonly', true).removeClass('border-primary');
                    $('#saveVitalSignsBtn').hide();
                    $('#editVitalSignsBtn').show();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error saving vital signs');
            },
            complete: function() {
                $btn.prop('disabled', false).html(oldBtnText);
            }
        });
    });
    
    // Initialize Select2 for Symptoms, Diagnosis, and Medicine
    function initializeWorkspaceSelect2() {
        var $modal = $('#doctorWorkspaceModal');
        if(!$modal.length) return;
        
        // Symptoms Select2
        if ($('#workspace_symptom_select').length && !$('#workspace_symptom_select').hasClass('select2-hidden-accessible')) {
            $('#workspace_symptom_select').select2({
                placeholder: "Select Symptoms",
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: true,
                dropdownParent: $modal,
                ajax: {
                    url: window.base_url + 'symptom/getSymptomInfo',
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { searchTerm: params.term };
                    },
                    processResults: function (response) {
                        return { results: response };
                    },
                    cache: false
                }
            });
        }
        
        // Diagnosis Select2
        if ($('#workspace_diagnosis_select').length && !$('#workspace_diagnosis_select').hasClass('select2-hidden-accessible')) {
            $('#workspace_diagnosis_select').select2({
                placeholder: "Select Diagnosis",
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: true,
                dropdownParent: $modal,
                ajax: {
                    url: window.base_url + 'diagnosis/getDiagnosisInfo',
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { searchTerm: params.term };
                    },
                    processResults: function (response) {
                        return { results: response };
                    },
                    cache: false
                }
            });
        }

        // Medicine Select2 (Prescription)
        if ($('#workspace_medicine_select').length && !$('#workspace_medicine_select').hasClass('select2-hidden-accessible')) {
            $('#workspace_medicine_select').select2({
                placeholder: "Search Medicine",
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: true,
                dropdownParent: $modal,
                ajax: {
                    url: window.base_url + 'medicine/getMedicineListForSelect2',
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { searchTerm: params.term };
                    },
                    processResults: function (response) {
                        return { results: response };
                    },
                    cache: false
                }
            });
        }
        
        // Lab Test Categories Select2
        if ($('#workspace_lab_category').length && !$('#workspace_lab_category').hasClass('select2-hidden-accessible')) {
            $('#workspace_lab_category').select2({
                placeholder: "Select Lab Tests",
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: true,
                dropdownParent: $modal
            });
        }
    }
    
    // Save new symptom
    $(document).off('click', '#saveSymptomBtn').on('click', '#saveSymptomBtn', function(e) {
        e.preventDefault();
        var name = $('#new_symptom_name').val();
        if (!name) {
            alert('Please enter symptom name');
            return;
        }
        
        var $btn = $(this);
        $btn.find('.spinner-border').show();
        $btn.find('.button-text').hide();
        $btn.prop('disabled', true);
        
        $.ajax({
            url: window.base_url + 'symptom/addNewSymptom',
            type: 'POST',
            data: { name: name },
            dataType: 'json',
            success: function(response) {
                if (response && response.id) {
                    var newOption = new Option(response.name, response.id, true, true);
                    $('#workspace_symptom_select').append(newOption).trigger('change');
                    $('#addSymptomModal').modal('hide');
                    $('#new_symptom_name').val('');
                    alert('Symptom added successfully');
                } else {
                    alert('Error adding symptom');
                }
            },
            error: function() {
                alert('Error adding symptom');
            },
            complete: function() {
                $btn.find('.spinner-border').hide();
                $btn.find('.button-text').show();
                $btn.prop('disabled', false);
            }
        });
    });
    
    // Save new diagnosis
    $(document).off('click', '#saveDiagnosisBtn').on('click', '#saveDiagnosisBtn', function(e) {
        e.preventDefault();
        var name = $('#new_diagnosis_name').val();
        var code = $('#new_diagnosis_code').val();
        var description = $('#new_diagnosis_description').val();
        
        if (!name) {
            alert('Please enter diagnosis name');
            return;
        }
        
        var $btn = $(this);
        $btn.find('.spinner-border').show();
        $btn.find('.button-text').hide();
        $btn.prop('disabled', true);
        
        $.ajax({
            url: window.base_url + 'diagnosis/addNewDiagnosis',
            type: 'POST',
            data: { 
                name: name,
                code: code,
                description: description
            },
            dataType: 'json',
            success: function(response) {
                if (response && response.id) {
                    var newOption = new Option(response.name, response.id, true, true);
                    $('#workspace_diagnosis_select').append(newOption).trigger('change');
                    $('#addDiagnosisModal').modal('hide');
                    $('#new_diagnosis_name').val('');
                    $('#new_diagnosis_code').val('');
                    $('#new_diagnosis_description').val('');
                    alert('Diagnosis added successfully');
                } else {
                    alert('Error adding diagnosis');
                }
            },
            error: function() {
                alert('Error adding diagnosis');
            },
            complete: function() {
                $btn.find('.spinner-border').hide();
                $btn.find('.button-text').show();
                $btn.prop('disabled', false);
            }
        });
    });

    // Handle workspace opening triggers
    $(document).off('click', '.open-doctor-workspace').on('click', '.open-doctor-workspace', function(e) {
        e.preventDefault();
        var patientId = $(this).attr('data-id');
        window.openDoctorWorkspace(patientId);
    });

    // Generic Modal Close Handler (Manual fix for persistent modals)
    $(document).on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"]', function() {
        $(this).closest('.modal').modal('hide');
    });
    
    // Manual Tab Switching Handler (Fix for navigation issues)
    $(document).off('click', '.workspace-nav .nav-link').on('click', '.workspace-nav .nav-link', function(e) {
        e.preventDefault();
        
        // Remove active class from all tabs
        $('.workspace-nav .nav-link').removeClass('active');
        
        // Add active class to clicked tab
        $(this).addClass('active');
        
        // Get target ID from href (e.g. #section-medical-info)
        var targetId = $(this).attr('href');
        
        // Hide all sections first
        $('.workspace-section').removeClass('active').hide();
        
        // Show the target section
        if ($(targetId).length) {
            $(targetId).addClass('active').show();
        } 
        
        // Trigger Select2 re-init if needed
        setTimeout(initializeWorkspaceSelect2, 100);
    });

    // Lab: Mark as Seen
    $(document).off('click', '.mark-lab-seen').on('click', '.mark-lab-seen', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        var $row = $(this).closest('tr');
        var $btn = $(this);

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: window.base_url + 'doctor/workspace/mark_lab_as_seen',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $row.fadeOut(300, function() {
                        $(this).remove();
                    });
                } else {
                    alert('Error: ' + response.message);
                    $btn.prop('disabled', false).html('<i class="fas fa-eye-slash"></i> Seen');
                }
            },
            error: function() {
                alert('Error marking lab as seen');
                $btn.prop('disabled', false).html('<i class="fas fa-eye-slash"></i> Seen');
            }
        });
    });

    // Lab: Reprint Request
    $(document).off('click', '.reprint-lab-workspace').on('click', '.reprint-lab-workspace', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        var url = window.base_url + 'doctor/workspace/print_lab_request?ids=' + id;
        window.open(url, '_blank');
    });

    // Lab: Delete Request
    $(document).off('click', '.delete-lab-workspace').on('click', '.delete-lab-workspace', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        var $row = $(this).closest('tr');
        var $btn = $(this);

        if (!confirm('Are you sure you want to delete this lab request? This action cannot be undone.')) {
            return;
        }

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: window.base_url + 'doctor/workspace/delete_lab_ajax',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $row.fadeOut(300, function() {
                        $(this).remove();
                    });
                } else {
                    alert('Error: ' + response.message);
                    $btn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            },
            error: function() {
                alert('Error deleting lab request');
                $btn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
            }
        });
    });

    // Prescription: Add Medicine to List
    $(document).off('select2:select', '#workspace_medicine_select').on('select2:select', '#workspace_medicine_select', function(e) {
        var data = e.params.data;
        var template = document.getElementById('medicine_row_template');
        var $list = $('#prescription_medicine_list');
        
        // Prevent duplicate medicines in the same list
        if ($list.find('.med-id[value="' + data.id + '"]').length > 0) {
            alert('Medicine already added');
            $('#workspace_medicine_select').val(null).trigger('change');
            return;
        }

        var clone = document.importNode(template.content, true);
        $(clone).find('.med-name').text(data.text);
        $(clone).find('.med-id').val(data.id);
        
        $list.append(clone);
        
        // Reset select2
        $('#workspace_medicine_select').val(null).trigger('change');
    });

    // Prescription: Remove Medicine from List
    $(document).off('click', '.remove-med-btn').on('click', '.remove-med-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $card = $(this).closest('.medicine-entry-card');
        $card.fadeOut(300, function() {
            $(this).remove();
        });
    });

    // Capture submission type (Save vs Print)
    $(document).off('click', '.submit-btn').on('click', '.submit-btn', function() {
        var type = $(this).attr('data-type');
        $(this).closest('form').find('.submission-type').val(type);
    });

    // Prescription: Finalize & Save
    $(document).off('submit', '#workspace_prescription_form').on('submit', '#workspace_prescription_form', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button.submit-btn:focus').length ? $form.find('button.submit-btn:focus') : $form.find('button[type="submit"]');
        var patientId = $('#medical_info_form input[name="patient_id"]').val();
        var doctorId = $('#medical_info_form input[name="doctor_id"]').val();
        var subType = $form.find('.submission-type').val();
        
        if ($('#prescription_medicine_list .medicine-entry-card').length === 0) {
            alert('Please add at least one medicine');
            return;
        }

        var medicine_ids = [];
        var dosages = [];
        var frequencies = [];
        var days = [];
        var routes = [];
        var quantities = [];
        var instructions = [];

        $('#prescription_medicine_list .medicine-entry-card').each(function() {
            var $card = $(this);
            medicine_ids.push($card.find('.med-id').val());
            dosages.push($card.find('input[name="dosage[]"]').val());
            frequencies.push($card.find('input[name="frequency[]"]').val());
            days.push($card.find('input[name="days[]"]').val());
            routes.push($card.find('input[name="route[]"]').val());
            quantities.push($card.find('input[name="quantity[]"]').val());
            instructions.push($card.find('input[name="instruction[]"]').val());
        });

        var oldHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: window.base_url + 'doctor/workspace/add_prescription_ajax',
            type: 'POST',
            data: {
                id: $form.find('.prescription-id').val(),
                patient: patientId,
                doctor: doctorId,
                date: new Date().toISOString().slice(0, 10),
                note: $form.find('textarea[name="note"]').val(),
                medicine: medicine_ids,
                dosage: dosages,
                frequency: frequencies,
                days: days,
                route: routes,
                quantity: quantities,
                instruction: instructions,
                submission_type: subType
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Prescription Saved Successfully');
                    if (response.print_url) {
                        window.open(response.print_url, '_blank');
                    }
                    window.openDoctorWorkspace(patientId);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error saving prescription');
            },
            complete: function() {
                $btn.prop('disabled', false).html(oldHtml);
            }
        });
    });

    // Lab Request: Submit form
    $(document).off('submit', '#workspace_lab_request_form').on('submit', '#workspace_lab_request_form', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button.submit-btn:focus').length ? $form.find('button.submit-btn:focus') : $form.find('button[type="submit"]');
        var patientId = $form.find('input[name="patient_id"]').val();
        var doctorId = $('#medical_info_form input[name="doctor_id"]').val();

        var oldHtml = $btn.html();
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: window.base_url + 'doctor/workspace/add_lab_request_ajax',
            type: 'POST',
            data: $form.serialize() + '&patient=' + patientId + '&doctor=' + doctorId,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Lab Request Sent Successfully');
                    if (response.print_url) {
                         // print_url might be relative or full path, base_url helps
                         var url = response.print_url.startsWith('http') ? response.print_url : window.base_url + response.print_url;
                         window.open(url, '_blank');
                    }
                    window.openDoctorWorkspace(patientId);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error sending lab request');
            },
            complete: function() {
                $btn.prop('disabled', false).html(oldHtml);
            }
        });
    });
    // Prescription: Edit Previous Prescription
    $(document).off('click', '.edit-prescription-workspace').on('click', '.edit-prescription-workspace', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        
        // Switch to prescription tab
        $('#tab-prescription').trigger('click');
        
        // Show loading in form
        var $form = $('#workspace_prescription_form');
        $form.css('opacity', '0.5');
        
        $.ajax({
            url: window.base_url + 'prescription/editPrescriptionByJason?id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if(response && response.prescription) {
                    var p = response.prescription;
                    
                    // Clear existing
                    $form.find('.prescription-id').val(p.id);
                    $form.find('textarea[name="note"]').val(p.note || p.advice || p.symptom);
                    $('#prescription_medicine_list').empty();
                    
                    // Parse medicines
                    if(p.medicine) {
                        var meds = p.medicine.split('###');
                        meds.forEach(function(m) {
                            var parts = m.split('***');
                            if(parts.length >= 5) {
                                var medId = parts[0];
                                var dosage = parts[1];
                                var frequency = parts[2];
                                var days = parts[3];
                                var instruction = parts[4];
                                var quantity = parts[5] || '';
                                var route = parts[6] || 'Oral';
                                
                                // Fetch name and add row
                                $.ajax({
                                    url: window.base_url + 'medicine/getMedicineByIdByJason?id=' + medId,
                                    type: 'GET',
                                    dataType: 'json',
                                    success: function(medRes) {
                                        var medName = medRes ? medRes.medicine.name : 'Unknown Medicine';
                                        
                                        var template = document.getElementById('medicine_row_template');
                                        var clone = document.importNode(template.content, true);
                                        
                                        $(clone).find('.med-name').text(medName);
                                        $(clone).find('.med-id').val(medId);
                                        $(clone).find('input[name="dosage[]"]').val(dosage);
                                        $(clone).find('input[name="frequency[]"]').val(frequency);
                                        $(clone).find('input[name="days[]"]').val(days);
                                        $(clone).find('input[name="route[]"]').val(route);
                                        $(clone).find('input[name="quantity[]"]').val(quantity);
                                        $(clone).find('input[name="instruction[]"]').val(instruction);
                                        
                                        $('#prescription_medicine_list').append(clone);
                                    }
                                });
                            }
                        });
                    }
                }
            },
            complete: function() {
                $form.css('opacity', '1');
            }
        });
    });

    // Lab: Edit Investigation
    $(document).off('click', '.edit-lab-workspace').on('click', '.edit-lab-workspace', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        var $modal = $('#editLabModal');
        
        $.ajax({
            url: window.base_url + 'doctor/workspace/get_lab_for_edit?id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#edit_lab_id').val(response.lab.id);
                    $('#edit_lab_category').val(response.lab.category_id).trigger('change');
                    $('#edit_lab_notes').val(response.lab.report || '');
                    $modal.modal('show');
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    });

    // Lab: Update Investigation
    $(document).off('submit', '#edit_lab_form').on('submit', '#edit_lab_form', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var oldHtml = $btn.html();
        
        $btn.prop('disabled', true).find('.spinner-border').show();
        
        $.ajax({
            url: window.base_url + 'doctor/workspace/update_lab_ajax',
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editLabModal').modal('hide');
                    alert('Lab Request Updated Successfully');
                    var patientId = $('#medical_info_form input[name="patient_id"]').val();
                    window.openDoctorWorkspace(patientId);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error updating lab request');
            },
            complete: function() {
                $btn.prop('disabled', false).find('.spinner-border').hide();
                $btn.html(oldHtml);
            }
        });
    });

    // Lab: View Results
    $(document).off('click', '.view-lab-results').on('click', '.view-lab-results', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        var $modal = $('#labResultModal');
        $('#lab_result_content').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Fetching investigation results...</p></div>');
        $modal.modal('show');
        
        $.ajax({
            url: window.base_url + 'doctor/workspace/get_lab_results?id=' + id,
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#lab_result_content').html(response);
            },
            error: function() {
                $('#lab_result_content').html('<div class="alert alert-danger">Error loading results</div>');
            }
        });
    });

    // Medicine Log: View Detailed Record
    $(document).off('click', '.view-prescription-details').on('click', '.view-prescription-details', function(e) {
        e.preventDefault();
        var date = $(this).attr('data-date');
        var patientId = $('#medical_info_form input[name="patient_id"]').val();
        var $modal = $('#prescriptionDetailsModal');
        
        $('#prescription_details_content').html('<div class="text-center p-5"><div class="spinner-border text-success" role="status"></div><p class="mt-2 text-muted">Loading medical record details...</p></div>');
        $modal.modal('show');
        
        $.ajax({
            url: window.base_url + 'doctor/workspace/get_prescription_details',
            type: 'GET',
            data: { date: date, patient_id: patientId },
            dataType: 'html',
            success: function(response) {
                $('#prescription_details_content').html(response);
            },
            error: function() {
                $('#prescription_details_content').html('<div class="alert alert-danger">Error loading record details</div>');
            }
        });
    });

    // Day Care Request Submit
    $(document).off('submit', '#workspace_daycare_request_form').on('submit', '#workspace_daycare_request_form', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var oldHtml = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
        
        $.ajax({
            url: window.base_url + 'doctor/workspace/add_daycare_request_ajax',
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message + (response.fee ? ' (Admission Fee: $' + response.fee + ')' : ''));
                    $form[0].reset();
                    // Optional: Refresh list if we add one
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error submitting day care request');
            },
            complete: function() {
                $btn.prop('disabled', false).html(oldHtml);
            }
        });
    });

    // Emergency Request Submit
    $(document).off('submit', '#workspace_emergency_request_form').on('submit', '#workspace_emergency_request_form', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var oldHtml = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Notifying Emergency Dept...');
        
        $.ajax({
            url: window.base_url + 'doctor/workspace/add_emergency_request_ajax',
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message + (response.fee ? ' (Priority Fee: $' + response.fee + ')' : ''));
                    $form[0].reset();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error submitting emergency request');
            },
            complete: function() {
                $btn.prop('disabled', false).html(oldHtml);
            }
        });
    });

    var init = function () {
        if ($('#' + containerId).length === 0) {
            $('body').append('<div id="' + containerId + '"></div>');
        }
    };

    // Window-level function to open the workspace
    window.openDoctorWorkspace = function (patientId) {
        // Ensure container exists
        init();
        
        // Show loading indicator
        var $loader = $('<div class="workspace-loader-overlay"><div class="workspace-spinner"></div><div class="workspace-loading-text">Loading Patient Workspace...</div></div>');
        $('body').append($loader);
        
        // Add timestamp to prevent browser cache
        var requestUrl = window.base_url + 'doctor/workspace/load_workspace?_t=' + new Date().getTime();
        
        $.ajax({
            url: requestUrl,
            type: 'GET',
            data: { id: patientId },
            cache: false,
            success: function (response) {
                if (!response || response.trim() === '') {
                     alert('Error: Received empty response from server.');
                     return;
                }
                
                // Clear existing modal if it exists to force fresh render
                $('#doctorWorkspaceModal').remove();
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');
                
                $('#' + containerId).html(response);
                
                var modal = $('#doctorWorkspaceModal');
                if (modal.length > 0) {
                    modal.modal('show');
                    
                    // Initialize Select2 for all tabs
                    setTimeout(function() {
                        initializeWorkspaceSelect2();
                        $('.workspace-nav .nav-link.active').trigger('click');
                    }, 500);
                } else {
                    alert('Error: Workspace modal not found in response.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Workspace AJAX Error:', status, error);
                alert('Error loading workspace: ' + (error || 'Unknown Error'));
            },
            complete: function() {
                $loader.remove();
            }
        });
    };

    $(document).on('click', '.open-doctor-workspace', function(e) {
        e.preventDefault();
        var patientId = $(this).data('id') || $(this).attr('data-id');
        if (patientId) {
            window.openDoctorWorkspace(patientId);
        }
    });

    $(document).ready(function () {
        init();
    });

})();
