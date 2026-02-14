<!-- <link href="common/extranal/css/settings/settings.css" rel="stylesheet"> -->

<div class="content-wrapper bg-gradient-light">
    <!-- Content Header -->
    <section class="content-header py-4 bg-white shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="display-4 font-weight-black mb-0">
                        <i class="fas fa-cog fa-lg mr-3"></i>
                        <?php echo lang('settings'); ?>
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0">
                            <li class="breadcrumb-item"><a href="home"><?php echo lang('home'); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo lang('settings'); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content py-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-body bg-white p-5">
                            <?php echo validation_errors(); ?>
                            <form role="form" action="settings/update" method="post" enctype="multipart/form-data">

                                <!-- General Settings Card -->
                                <div class="card shadow-sm border-0 mb-5">
                                    <div class="card-header bg-info text-white py-3">
                                        <h3 class="card-title font-weight-bold mb-0">
                                            <i class="fas fa-wrench mr-2"></i>
                                            <?php echo lang('general_settings'); ?>
                                        </h3>
                                    </div>

                                    <div class="card-body bg-light p-4">
                                        <div class="row">
                                            <!-- System Name -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-building mr-2 text-muted"></i>
                                                        <?php echo lang('system_name'); ?>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-lg shadow-sm"
                                                        name="name" value='<?php if (!empty($settings->system_vendor)) {
                                                            echo $settings->system_vendor;
                                                        } ?>' required="">
                                                </div>
                                            </div>

                                            <!-- Title -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-heading mr-2 text-muted"></i>
                                                        <?php echo lang('title'); ?>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-lg shadow-sm"
                                                        name="title" value='<?php if (!empty($settings->title)) {
                                                            echo $settings->title;
                                                        } ?>' required="">
                                                </div>
                                            </div>

                                            <!-- Address -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-map-marker-alt mr-2 text-muted"></i>
                                                        <?php echo lang('address'); ?>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-lg shadow-sm"
                                                        name="address" value='<?php if (!empty($settings->address)) {
                                                            echo $settings->address;
                                                        } ?>' required="">
                                                </div>
                                            </div>

                                            <!-- Phone -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-phone mr-2 text-muted"></i>
                                                        <?php echo lang('phone'); ?>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-lg shadow-sm"
                                                        name="phone" value='<?php if (!empty($settings->phone)) {
                                                            echo $settings->phone;
                                                        } ?>' required="">
                                                </div>
                                            </div>

                                            <!-- Hospital Email -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-envelope mr-2 text-muted"></i>
                                                        <?php echo lang('hospital_email'); ?>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="email" class="form-control form-control-lg shadow-sm"
                                                        name="email" value='<?php if (!empty($settings->email)) {
                                                            echo $settings->email;
                                                        } ?>' required="">
                                                </div>
                                            </div>

                                            <!-- Currency -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-dollar-sign mr-2 text-muted"></i>
                                                        <?php echo lang('currency'); ?>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-lg shadow-sm"
                                                        name="currency" value='<?php if (!empty($settings->currency)) {
                                                            echo $settings->currency;
                                                        } ?>' required="">
                                                </div>
                                            </div>

                                            <!-- Footer Message -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-comment mr-2 text-muted"></i>
                                                        <?php echo lang('footer_message'); ?>
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" class="form-control form-control-lg shadow-sm"
                                                        name="footer_message" value='<?php if (!empty($settings->footer_message)) {
                                                            echo $settings->footer_message;
                                                        } ?>' required="">
                                                </div>
                                            </div>

                                            <!-- Sidebar Color -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-palette mr-2 text-muted"></i>
                                                        <?php echo lang('sidebar_color'); ?>
                                                    </label>
                                                    <input type="color" class="form-control form-control-lg shadow-sm"
                                                        name="sidebar_color"
                                                        value='<?php echo !empty($settings->sidebar_color) ? $settings->sidebar_color : '#2c3e50'; ?>'>
                                                </div>
                                            </div>
                                            <?php if ($this->ion_auth->in_group(array('superadmin'))) { ?>

                                            <?php } ?>
                                            <?php if ($this->ion_auth->in_group(array('admin'))) { ?>
                                                <!-- VAT -->
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-group">
                                                        <label class="text-uppercase font-weight-bold text-dark">
                                                            <i class="fas fa-percent mr-2 text-muted"></i>
                                                            <?php echo lang('default'); ?>     <?php echo lang('vat'); ?> (%)
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="number" min="0" max="100"
                                                            class="form-control form-control-lg shadow-sm" name="vat" value='<?php if (!empty($settings->vat)) {
                                                                echo $settings->vat;
                                                            } else {
                                                                echo 0;
                                                            } ?>' required="">
                                                    </div>
                                                </div>

                                                <!-- Discount -->
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-group">
                                                        <label class="text-uppercase font-weight-bold text-dark">
                                                            <i class="fas fa-tags mr-2 text-muted"></i>
                                                            <?php echo lang('default'); ?>     <?php echo lang('discount'); ?>
                                                            (%)
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="number" min="0" max="100"
                                                            class="form-control form-control-lg shadow-sm"
                                                            name="discount_percent" value='<?php if (!empty($settings->discount_percent)) {
                                                                echo $settings->discount_percent;
                                                            } else {
                                                                echo 0;
                                                            } ?>' required="">
                                                    </div>
                                                </div>

                                                <!-- Patient Follow-up Period -->
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-group">
                                                        <label class="text-uppercase font-weight-bold text-dark">
                                                            <i class="fas fa-history mr-2 text-muted"></i>
                                                            Patient Follow-up Period (Days)
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="number" min="0"
                                                            class="form-control form-control-lg shadow-sm"
                                                            name="followup_period"
                                                            value='<?php echo !empty($settings->followup_period) ? $settings->followup_period : 15; ?>'
                                                            required="">
                                                        <small class="form-text text-muted">Number of days a patient visit
                                                            is considered a "Follow-up" instead of a "New
                                                            Appointment".</small>
                                                    </div>
                                                </div>

                                                <!-- Time Format -->
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-group">
                                                        <label class="text-uppercase font-weight-bold text-dark">
                                                            <i class="fas fa-clock mr-2 text-muted"></i>
                                                            <?php echo lang('time_format'); ?>
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <select name="time_format"
                                                            class="form-control form-control-lg shadow-sm" required>
                                                            <option value="12" <?php if ($settings->time_format == '12') {
                                                                echo 'selected';
                                                            } ?>><?php echo lang('12_hours_am_pm'); ?></option>
                                                            <option value="24" <?php if ($settings->time_format == '24') {
                                                                echo 'selected';
                                                            } ?>><?php echo lang('24_hours'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Show Odontogram -->
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-group">
                                                        <label class="text-uppercase font-weight-bold text-dark">
                                                            <i class="fas fa-tooth mr-2 text-muted"></i>
                                                            <?php echo lang('show_odontogram_in_history'); ?>
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <select name="show_odontogram_in_history"
                                                            class="form-control form-control-lg shadow-sm" required>
                                                            <option value="yes" <?php if ($settings->show_odontogram_in_history == 'yes') {
                                                                echo 'selected';
                                                            } ?>><?php echo lang('yes'); ?></option>
                                                            <option value="no" <?php if ($settings->show_odontogram_in_history == 'no') {
                                                                echo 'selected';
                                                            } ?>><?php echo lang('no'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-group">
                                                        <label class="text-uppercase font-weight-bold text-dark">
                                                            <i class="fas fa-time mr-2 text-muted"></i>
                                                            <?php echo lang('timezone'); ?>
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <select name="timezone"
                                                            class="form-control form-control-lg shadow-sm" id="timezone"
                                                            required>
                                                            <?php
                                                            foreach ($timezones as $key => $timezone) {
                                                                ?>
                                                                <option value="<?php echo $key ?>" <?php
                                                                   if ($key == $settings->timezone) {
                                                                       echo 'selected';
                                                                   }
                                                                   ?>><?php echo $timezone; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>


                                                <!-- Footer Invoice Message -->
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-group">
                                                        <label class="text-uppercase font-weight-bold text-dark">
                                                            <i class="fas fa-file-invoice mr-2 text-muted"></i>
                                                            <?php echo lang('footer_invoice_message'); ?>
                                                        </label>
                                                        <textarea name="footer_invoice_message"
                                                            class="form-control form-control-lg shadow-sm" rows="3"><?php if (!empty($settings->footer_invoice_message)) {
                                                                echo $settings->footer_invoice_message;
                                                            } ?></textarea>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <!-- Logo Upload Section -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-image mr-2 text-muted"></i>
                                                        <?php echo lang('title') . ' ' . lang('logo'); ?>
                                                    </label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input"
                                                            name="img_url_title" id="titleLogo">
                                                        <label class="custom-file-label"
                                                            for="titleLogo"><?php echo lang('choose_file'); ?></label>
                                                    </div>
                                                    <?php if (!empty($settings->logo_title)) { ?>
                                                        <img src="<?php echo $settings->logo_title; ?>" class="mt-2"
                                                            style="height: 100px; border: 1px dashed #ccc; padding: 5px;">
                                                    <?php } ?>
                                                    <small
                                                        class="form-text text-muted"><?php echo lang('recommended_size'); ?>:
                                                        200x100</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-image mr-2 text-muted"></i>
                                                        <?php echo $this->ion_auth->in_group(array('superadmin')) ? lang('website_logo') : lang('invoice_logo'); ?>
                                                    </label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="img_url"
                                                            id="websiteLogo">
                                                        <label class="custom-file-label"
                                                            for="websiteLogo"><?php echo lang('choose_file'); ?></label>
                                                    </div>
                                                    <?php if (!empty($settings->logo)) { ?>
                                                        <img src="<?php echo $settings->logo; ?>" class="mt-2"
                                                            style="height: 100px; border: 1px dashed #ccc; padding: 5px;">
                                                    <?php } ?>
                                                    <small
                                                        class="form-text text-muted"><?php echo lang('recommended_size'); ?>:
                                                        200x100</small>
                                                </div>
                                            </div>

                                            <!-- Daycare Fee -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-child mr-2 text-muted"></i>
                                                        <?php echo lang('daycare_fee'); ?>
                                                        (<?php echo $settings->currency; ?>)
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="number" step="0.01" min="0"
                                                        class="form-control form-control-lg shadow-sm"
                                                        name="daycare_fee"
                                                        value='<?php echo !empty($settings->daycare_fee) ? $settings->daycare_fee : '10.00'; ?>'
                                                        required="">
                                                </div>
                                            </div>

                                            <!-- Emergency Fee -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-ambulance mr-2 text-muted"></i>
                                                        <?php echo lang('emergency_fee'); ?>
                                                        (<?php echo $settings->currency; ?>)
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="number" step="0.01" min="0"
                                                        class="form-control form-control-lg shadow-sm"
                                                        name="emergency_fee"
                                                        value='<?php echo !empty($settings->emergency_fee) ? $settings->emergency_fee : '5.00'; ?>'
                                                        required="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <input type="hidden" name="id" value='<?php if (!empty($settings->id)) {
                                    echo $settings->id;
                                } ?>'>

                                <!-- TV Display Settings Card -->
                                <div class="card shadow-sm border-0 mb-4">
                                    <div class="card-header bg-gradient-primary text-white py-3">
                                        <h3 class="card-title font-weight-bold mb-0">
                                            <i class="fas fa-tv mr-2"></i>
                                            TV Queue Display Settings
                                        </h3>
                                    </div>
                                    <div class="card-body bg-light p-4">
                                        <div class="row">
                                            <!-- TV Display Enable/Disable -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-toggle-on mr-2 text-muted"></i>
                                                        Enable TV Display
                                                    </label>
                                                    <select name="tv_display_enabled"
                                                        class="form-control form-control-lg shadow-sm">
                                                        <option value="1" <?php if (!empty($settings->tv_display_enabled) && $settings->tv_display_enabled == 1)
                                                            echo 'selected'; ?>>
                                                            Enabled</option>
                                                        <option value="0" <?php if (empty($settings->tv_display_enabled) || $settings->tv_display_enabled == 0)
                                                            echo 'selected'; ?>>
                                                            Disabled</option>
                                                    </select>
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Disable to block all TV display access
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Generate Token Button -->
                                            <div class="col-md-6 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-key mr-2 text-muted"></i>
                                                        Access Token
                                                    </label>
                                                    <button type="button" class="btn btn-success btn-lg btn-block"
                                                        id="generateTokenBtn">
                                                        <i class="fas fa-sync-alt mr-2"></i> Generate New Token
                                                    </button>
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-shield-alt mr-1"></i>
                                                        Generate a secure access token for your TV display
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- TV Display URL -->
                                            <div class="col-md-12 mb-4" id="tvUrlSection"
                                                style="display: <?php echo !empty($settings->tv_access_token) ? 'block' : 'none'; ?>;">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-link mr-2 text-muted"></i>
                                                        Secure TV Display URL
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="text"
                                                            class="form-control form-control-lg shadow-sm" value='<?php
                                                            if (!empty($settings->tv_access_token)) {
                                                                echo base_url('appointment/tv?token=' . $settings->tv_access_token . '&hospital_id=' . $this->hospital_id);
                                                            } else {
                                                                echo "Click 'Generate New Token' to create a secure URL";
                                                            }
                                                            ?>' readonly id="tv_display_url">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary" type="button"
                                                                onclick="copyTvUrl()">
                                                                <i class="fas fa-copy"></i> Copy
                                                            </button>
                                                            <?php if (!empty($settings->tv_access_token)) { ?>
                                                                <a href="<?php echo base_url('appointment/tv?token=' . $settings->tv_access_token . '&hospital_id=' . $this->hospital_id); ?>"
                                                                    target="_blank" class="btn btn-info">
                                                                    <i class="fas fa-external-link-alt"></i> Open TV
                                                                </a>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-success">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Use this secure URL on your TV/monitor. Token required for
                                                        access.
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- TV Display Color Customization -->
                                            <div class="col-md-12 mb-3">
                                                <h5 class="font-weight-bold text-dark mb-3">
                                                    <i class="fas fa-palette mr-2"></i>
                                                    Custom TV Display Colors
                                                </h5>
                                            </div>

                                            <div class="col-md-4 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-circle mr-2 text-muted"></i>
                                                        Primary Color
                                                    </label>
                                                    <input type="color" class="form-control form-control-lg shadow-sm"
                                                        name="tv_primary_color" id="tvPrimaryColor"
                                                        value='<?php echo !empty($settings->tv_primary_color) ? $settings->tv_primary_color : '#667eea'; ?>'>
                                                    <small class="form-text text-muted">
                                                        Main gradient color for TV display background
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-circle mr-2 text-muted"></i>
                                                        Secondary Color
                                                    </label>
                                                    <input type="color" class="form-control form-control-lg shadow-sm"
                                                        name="tv_secondary_color" id="tvSecondaryColor"
                                                        value='<?php echo !empty($settings->tv_secondary_color) ? $settings->tv_secondary_color : '#764ba2'; ?>'>
                                                    <small class="form-text text-muted">
                                                        Secondary gradient color for TV display
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-4">
                                                <div class="form-group">
                                                    <label class="text-uppercase font-weight-bold text-dark">
                                                        <i class="fas fa-circle mr-2 text-muted"></i>
                                                        Accent Color
                                                    </label>
                                                    <input type="color" class="form-control form-control-lg shadow-sm"
                                                        name="tv_accent_color" id="tvAccentColor"
                                                        value='<?php echo !empty($settings->tv_accent_color) ? $settings->tv_accent_color : '#f093fb'; ?>'>
                                                    <small class="form-text text-muted">
                                                        Accent color for highlights and effects
                                                    </small>
                                                </div>
                                            </div>

                                            <!-- Color Preview -->
                                            <div class="col-md-12 mb-4">
                                                <div class="alert alert-light border" id="colorPreview" style="
                                                    background: linear-gradient(135deg, 
                                                        <?php echo !empty($settings->tv_primary_color) ? $settings->tv_primary_color : '#667eea'; ?> 0%, 
                                                        <?php echo !empty($settings->tv_secondary_color) ? $settings->tv_secondary_color : '#764ba2'; ?> 50%, 
                                                        <?php echo !empty($settings->tv_accent_color) ? $settings->tv_accent_color : '#f093fb'; ?> 100%
                                                    );
                                                    min-height: 80px;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    color: white;
                                                    font-weight: bold;
                                                    font-size: 1.5em;
                                                ">
                                                    TV Display Color Preview
                                                </div>
                                                <small class="form-text text-muted">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Preview of your TV display gradient colors
                                                </small>
                                            </div>

                                            <!-- Setup Guide Link -->
                                            <div class="col-md-12">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-book-open mr-2"></i>
                                                    <strong>Setup Guide:</strong> For kiosk mode setup, Raspberry Pi
                                                    configuration, and troubleshooting,
                                                    see <code>tv_setup_guide.md</code> in the documentation.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <?php if ($this->ion_auth->in_group(array('superadmin'))) { ?>
                                    <!-- Cron Jobs Settings Card -->
                                    <div class="card shadow-sm border-0 mb-4">
                                        <div class="card-header bg-warning text-dark py-3">
                                            <h3 class="card-title font-weight-bold mb-0">
                                                <i class="fas fa-clock mr-2"></i>
                                                <?php echo lang('cron_jobs_settings'); ?>
                                            </h3>
                                        </div>
                                        <div class="card-body bg-light p-4">
                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-group">
                                                        <label class="text-uppercase font-weight-bold text-dark">
                                                            <i class="fas fa-terminal mr-2 text-muted"></i>
                                                            <?php echo lang('cron_job'); ?>
                                                        </label>
                                                        <?php
                                                        $base_url = base_url();
                                                        $base_url_explode = explode("//", $base_url);
                                                        ?>
                                                        <input type="text" class="form-control form-control-lg shadow-sm"
                                                            value='wget <?php echo $base_url_explode[1]; ?>cronjobs/appointmentRemainder -O /dev/null 2>&1'
                                                            readonly>
                                                        <small class="form-text text-success">
                                                            <i class="fas fa-info-circle mr-1"></i>
                                                            <?php echo lang('please_paste_this_code_in_ccard_cronjob_add_command_field'); ?>
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <div class="form-group">
                                                        <label class="text-uppercase font-weight-bold text-dark">
                                                            <i class="fas fa-bell mr-2 text-muted"></i>
                                                            <?php echo lang('remainder_before_appointment'); ?>
                                                        </label>
                                                        <div class="input-group">
                                                            <input type="number" min="1"
                                                                class="form-control form-control-lg shadow-sm"
                                                                name="remainder_appointment" value='<?php if (!empty($settings->remainder_appointment)) {
                                                                    echo $settings->remainder_appointment;
                                                                } ?>'>
                                                            <div class="input-group-append">
                                                                <span
                                                                    class="input-group-text"><?php echo lang('hours'); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <input type="hidden" name="tv_access_token" id="tv_access_token_field" value='<?php if (!empty($settings->tv_access_token))
                                    echo $settings->tv_access_token; ?>'>

                                <div class="text-right">
                                    <button type="submit" name="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save mr-2"></i>
                                        <?php echo lang('submit'); ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>






<!--main content end-->
<!--footer start-->


<script src="common/extranal/js/settings/settings.js"></script>

<script>
    // TV Display Token Generation
    $('#generateTokenBtn').click(function () {
        Swal.fire({
            title: 'Generate New Token?',
            text: 'This will create a new secure access token. The old token will be invalidated.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, generate new token!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('appointment/tv/generate_token'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // Update hidden field
                            $('#tv_access_token_field').val(response.token);

                            // Update URL display
                            $('#tv_display_url').val(response.url);

                            // Show URL section
                            $('#tvUrlSection').show();

                            // Show success message
                            Swal.fire({
                                title: 'Token Generated!',
                                html: `
                                <div class="text-left">
                                    <p><strong>Your secure TV display URL:</strong></p>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="${response.url}" readonly id="swalTvUrl">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" onclick="copyFromSwal()">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Don't forget to save settings to persist this token!
                                    </small>
                                </div>
                            `,
                                icon: 'success',
                                width: '600px'
                            });
                        } else {
                            Swal.fire('Error!', 'Failed to generate token', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Failed to generate token. Please try again.', 'error');
                    }
                });
            }
        });
    });

    // Copy TV URL to clipboard
    function copyTvUrl() {
        var copyText = document.getElementById("tv_display_url");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand("copy");

        Swal.fire({
            title: 'Copied!',
            text: 'TV display URL copied to clipboard',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    }

    // Copy from SweetAlert modal
    function copyFromSwal() {
        var copyText = document.getElementById("swalTvUrl");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");

        Swal.fire({
            title: 'Copied!',
            text: 'URL copied to clipboard',
            icon: 'success',
            timer: 1000,
            showConfirmButton: false
        });
    }
</script>