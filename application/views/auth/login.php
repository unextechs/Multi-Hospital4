<!DOCTYPE html>
<html lang="en">

<head>
  <base href="<?php echo base_url(); ?>">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo lang('login'); ?> - e-hospital</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="adminlte/plugins/flag-icon-css/css/flag-icon.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="adminlte/dist/css/adminlte.min.css">

  <style>
    :root {
      --primary-color: #007bff;
      --secondary-color: #6c757d;
      --success-color: #28a745;
      --card-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      --gradient-primary: linear-gradient(135deg, #0061f2 0%, #00c6f2 100%);
      --gradient-secondary: linear-gradient(135deg, #f6f9fc 0%, #f1f4f8 100%);
    }

    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      font-family: 'Source Sans Pro', sans-serif;
    }

    .main-container {
      display: flex;
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      gap: 2rem;
      align-items: center;
    }

    /* Add class for centered layout when sections are hidden */
    .main-container.centered-layout {
      justify-content: center;
      max-width: 450px;
    }

    .mobile-apps-container {
      flex: 1;
      max-width: 600px;
      width: 100%;
    }

    .login-container {
      flex: 1;
      max-width: 450px;
      width: 100%;
    }

    .mobile-apps-section {
      position: relative;
      overflow: hidden;
      background: white;
      border-radius: 20px;
      padding: 1.5rem;
      box-shadow: var(--card-shadow);
    }

    .section-title {
      position: relative;
      display: flex;
      align-items: center;
      font-size: 1.2rem;
      color: var(--secondary-color);
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 1px solid #e9ecef;
    }

    .section-title i {
      color: var(--primary-color);
    }

    .app-card {
      position: relative;
      background: var(--gradient-secondary);
      border-radius: 10px;
      padding: 1rem;
      margin-bottom: 0.75rem;
      overflow: hidden;
      display: flex;
      align-items: center;
      gap: 1rem;
      transition: all 0.3s ease;
    }

    .app-card:hover {
      transform: translateX(5px);
    }

    .app-icon-wrapper {
      width: 45px;
      height: 45px;
      background: var(--gradient-primary);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .app-icon {
      font-size: 1.2rem;
      color: white;
    }

    .app-content {
      flex: 1;
      min-width: 0;
    }

    .app-title {
      font-size: 1rem;
      font-weight: 600;
      margin: 0 0 0.25rem;
      color: #2d3436;
    }

    .app-download-btn {
      padding: 6px 12px;
      font-size: 0.85rem;
      margin: 0;
      background: white;
      color: var(--primary-color);
      border: 1px solid var(--primary-color);
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .app-download-btn:hover {
      background: var(--primary-color);
      color: white;
    }

    .app-download-btn i {
      font-size: 0.9rem;
    }

    .login-container {
      position: relative;
    }

    .login-header {
      text-align: center;
      margin-bottom: 2.5rem;
      padding-bottom: 1.5rem;
      border-bottom: 2px solid rgba(0, 123, 255, 0.1);
    }

    .brand-title {
      font-size: 2.5rem;
      font-weight: 800;
      color: #2d3436;
      margin-bottom: 0.5rem;
      letter-spacing: -1px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .brand-subtitle {
      font-size: 1.1rem;
      color: #6c757d;
      font-weight: 400;
      margin: 0;
    }

    .card {
      border: none;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(10px);
    }

    .login-card-body {
      padding: 2.5rem;
    }

    .welcome-title {
      font-size: 1.3rem;
      color: #2d3436;
      font-weight: 600;
      margin-bottom: 2rem;
      text-align: center;
    }

    .form-label {
      font-size: 0.9rem;
      font-weight: 600;
      color: #495057;
      margin-bottom: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .input-group {
      position: relative;
    }

    .form-control {
      border-radius: 12px;
      padding: 12px 20px;
      height: auto;
      font-size: 1rem;
      border: 2px solid #e9ecef;
      background: #f8f9fa;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      background: white;
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }

    .input-group-prepend {
      margin-right: -2px;
    }

    .input-group-text {
      border-radius: 12px 0 0 12px;
      background: #f8f9fa;
      border: 2px solid #e9ecef;
      border-right: none;
      padding: 0 1.2rem;
    }

    .input-group-text i {
      color: #667eea;
      font-size: 1.1rem;
    }

    .input-group .form-control {
      border-radius: 0 12px 12px 0;
    }

    .btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      padding: 14px 24px;
      font-weight: 600;
      letter-spacing: 0.5px;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .login-footer {
      text-align: center;
      margin-top: 2rem;
      padding-top: 1rem;
    }

    .login-footer p {
      font-size: 0.85rem;
    }

    .social-auth-links {
      margin-top: 2rem;
      text-align: center;
    }

    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 1.5rem 0;
      color: var(--secondary-color);
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #e9ecef;
    }

    .divider span {
      padding: 0 1rem;
    }

    .language-selector {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1000;
    }

    .language-selector .btn {
      border-radius: 10px;
      box-shadow: var(--card-shadow);
    }

    .forgot-password {
      color: var(--secondary-color);
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .forgot-password:hover {
      color: var(--primary-color);
      text-decoration: none;
    }

    .modal-content {
      border-radius: 15px;
      box-shadow: var(--card-shadow);
    }

    .modal-header {
      border-bottom: none;
      padding: 1.5rem;
    }

    .modal-body {
      padding: 1.5rem;
    }

    .modal-footer {
      border-top: none;
      padding: 1.5rem;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-box {
      animation: fadeIn 0.8s ease-out;
    }

    @media (max-width: 991.98px) {
      .main-container {
        flex-direction: column;
      }

      .mobile-apps-container,
      .login-container {
        max-width: 100%;
      }

      .mobile-apps-section,
      .card {
        margin-bottom: 2rem;
      }
    }

    .demo-section {
      margin-top: 1.5rem;
      background: white;
      border-radius: 20px;
      padding: 1.5rem;
      box-shadow: var(--card-shadow);
    }

    .demo-title {
      font-size: 1rem;
      color: var(--secondary-color);
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 1px solid #e9ecef;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .demo-buttons {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 0.75rem;
    }

    .demo-btn {
      padding: 8px 16px;
      font-size: 0.9rem;
      border: 1px solid #e9ecef;
      border-radius: 8px;
      background: var(--gradient-secondary);
      color: var(--secondary-color);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .demo-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .demo-btn i {
      font-size: 1rem;
      opacity: 0.8;
    }
  </style>
</head>

<body class="hold-transition login-page">
  <!-- Language Dropdown Menu -->
  <div class="language-selector">
    <div class="btn-group">
      <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
        <i class="flag-icon flag-icon-<?php echo $flagIcon; ?> mr-2"></i>
        <span class="text-dark"><?php echo ucfirst($this->language); ?></span>
      </button>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="frontend/changeLanguageFlag?lang=arabic"
          class="dropdown-item <?php echo ($this->language == 'arabic') ? 'active' : ''; ?>">
          <i class="flag-icon flag-icon-sa mr-2"></i> عربى
        </a>
        <a href="frontend/changeLanguageFlag?lang=english"
          class="dropdown-item <?php echo ($this->language == 'english') ? 'active' : ''; ?>">
          <i class="flag-icon flag-icon-us mr-2"></i> English
        </a>
        <!-- Temporarily hidden - Other languages -->
        <!-- <a href="frontend/changeLanguageFlag?lang=spanish"
          class="dropdown-item <?php echo ($this->language == 'spanish') ? 'active' : ''; ?>">
          <i class="flag-icon flag-icon-es mr-2"></i> Español
        </a>
        <a href="frontend/changeLanguageFlag?lang=french"
          class="dropdown-item <?php echo ($this->language == 'french') ? 'active' : ''; ?>">
          <i class="flag-icon flag-icon-fr mr-2"></i> Français
        </a>
        <a href="frontend/changeLanguageFlag?lang=italian"
          class="dropdown-item <?php echo ($this->language == 'italian') ? 'active' : ''; ?>">
          <i class="flag-icon flag-icon-it mr-2"></i> Italiano
        </a>
        <a href="frontend/changeLanguageFlag?lang=portuguese"
          class="dropdown-item <?php echo ($this->language == 'portuguese') ? 'active' : ''; ?>">
          <i class="flag-icon flag-icon-pt mr-2"></i> Português
        </a>
        <a href="frontend/changeLanguageFlag?lang=turkish"
          class="dropdown-item <?php echo ($this->language == 'turkish') ? 'active' : ''; ?>">
          <i class="flag-icon flag-icon-tr mr-2"></i> Türkçe
        </a> -->
      </div>
    </div>
  </div>

  <!-- For production -->
  <div class="main-container centered-layout">
    <div class="login-container">
      <?php $settings = $this->db->get('settings')->row(); ?>

      <!-- Professional Header -->
      <div class="login-header">
        <h1 class="brand-title">e-hospital</h1>
        <p class="brand-subtitle"><?php echo $settings->title; ?></p>
      </div>

      <div class="card">
        <div class="card-body login-card-body">
          <h3 class="welcome-title">
            <?php echo lang('Sign in to start your session') ?>
          </h3>

          <?php if (!empty($message)) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fas fa-exclamation-circle mr-2"></i>
              <?php echo $message; ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php } ?>

          <form method="post" action="auth/login">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label class="form-label"><?php echo lang('email') ?></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                  </span>
                </div>
                <input type="email" name="identity" class="form-control" placeholder="<?php echo lang('email') ?>"
                  required>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label"><?php echo lang('password') ?></label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                  </span>
                </div>
                <input type="password" name="password" class="form-control" placeholder="<?php echo lang('password') ?>"
                  required>
              </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
              <?php echo lang('sign_in') ?>
              <i class="fas fa-arrow-right ml-2"></i>
            </button>
          </form>

          <div class="text-center mt-4">
            <a href="#" class="forgot-password" data-toggle="modal" data-target="#myModal">
              <i class="fas fa-key mr-1"></i>
              <?php echo lang('forgot_your_password') ?>?
            </a>
          </div>
        </div>
      </div>

      <!-- Footer credit -->
      <div class="login-footer">
        <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> e-hospital. All rights reserved.</p>
      </div>
    </div>
  </div>
  <!-- End For production -->


  <!-- Forgot Password Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="auth/forgot_password">
          <div class="modal-header">
            <h4 class="modal-title"><?php echo lang('forgot_your_password') ?>?</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            <p class="text-muted"><?php echo lang('enter_your_email_address_to_reset_your_password') ?></p>
            <div class="form-group">
              <input type="email" name="email" class="form-control" placeholder="<?php echo lang('email') ?>" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal"><?php echo lang('cancel') ?></button>
            <button type="submit" class="btn btn-primary"><?php echo lang('submit') ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="adminlte/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="adminlte/dist/js/adminlte.min.js"></script>
  <script>
    function fillCredentials(email, password) {
      document.querySelector('input[name="identity"]').value = email;
      document.querySelector('input[name="password"]').value = password;

      // Add animation to show the form has been filled
      const form = document.querySelector('form');
      form.style.transition = 'all 0.3s ease';
      form.style.transform = 'scale(1.02)';
      setTimeout(() => {
        form.style.transform = 'scale(1)';
      }, 200);
    }

    function toggleSections(showSections = true) {
      const mainContainer = document.querySelector('.main-container');
      const mobileAppsContainer = document.querySelector('.mobile-apps-container');
      const demoSection = document.querySelector('.demo-section');

      if (showSections) {
        mainContainer.classList.remove('centered-layout');
        mobileAppsContainer.style.display = '';
        demoSection.style.display = '';
      } else {
        mainContainer.classList.add('centered-layout');
        mobileAppsContainer.style.display = 'none';
        demoSection.style.display = 'none';
      }
    }

    // You can call toggleSections(false) to hide sections and center the login
    // toggleSections(true) to show them again
  </script>
</body>

</html>