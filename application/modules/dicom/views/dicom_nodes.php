<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fas fa-server"></i> DICOM Nodes
            <small>Manage DICOM device connections</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fas fa-tachometer-alt"></i> Home</a></li>
            <li><a href="dicom">DICOM</a></li>
            <li class="active">Nodes</li>
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

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">DICOM Nodes Configuration</h3>
                        <div class="box-tools pull-right">
                            <a href="dicom/addNodeView" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Node
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>AE Title</th>
                                        <th>Host:Port</th>
                                        <th>Type</th>
                                        <th>Default</th>
                                        <th>Last Echo</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($nodes)): ?>
                                        <?php foreach ($nodes as $node): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($node->name); ?></strong></td>
                                                <td><code><?php echo htmlspecialchars($node->ae_title); ?></code></td>
                                                <td><?php echo htmlspecialchars($node->host) . ':' . $node->port; ?></td>
                                                <td><span class="label label-default"><?php echo $node->node_type; ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($node->is_default): ?>
                                                        <span class="label label-primary"><i class="fas fa-star"></i> Default</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($node->last_echo): ?>
                                                        <?php echo date('d M Y H:i', strtotime($node->last_echo)); ?>
                                                        <?php if ($node->last_echo_status == 'success'): ?>
                                                            <span class="label label-success"><i class="fas fa-check"></i></span>
                                                        <?php else: ?>
                                                            <span class="label label-danger"><i class="fas fa-times"></i></span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        Never
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span
                                                        class="label label-<?php echo $node->status == 'active' ? 'success' : 'danger'; ?>">
                                                        <?php echo ucfirst($node->status); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-xs echo-test"
                                                        data-id="<?php echo $node->id; ?>">
                                                        <i class="fas fa-plug"></i> Echo
                                                    </button>
                                                    <a href="dicom/editNode?id=<?php echo $node->id; ?>"
                                                        class="btn btn-warning btn-xs">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="dicom/deleteNode?id=<?php echo $node->id; ?>"
                                                        class="btn btn-danger btn-xs"
                                                        onclick="return confirm('Are you sure?');">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No DICOM nodes configured</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fas fa-info-circle"></i> About DICOM</h3>
                    </div>
                    <div class="box-body">
                        <p><strong>DICOM</strong> (Digital Imaging and Communications in Medicine) is the standard for
                            medical imaging.</p>
                        <p><strong>AE Title:</strong> Application Entity Title - unique identifier (max 16 characters)
                        </p>
                        <p><strong>Node Types:</strong></p>
                        <ul>
                            <li><strong>SCU</strong> - Service Class User (client)</li>
                            <li><strong>SCP</strong> - Service Class Provider (server)</li>
                        </ul>
                        <p><strong>Default Port:</strong> <code>104</code></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fas fa-list"></i> Common Modalities</h3>
                    </div>
                    <div class="box-body">
                        <ul class="list-unstyled">
                            <li><code>CT</code> - Computed Tomography</li>
                            <li><code>MR</code> - Magnetic Resonance</li>
                            <li><code>US</code> - Ultrasound</li>
                            <li><code>XR</code> - X-Ray</li>
                            <li><code>CR</code> - Computed Radiography</li>
                            <li><code>DX</code> - Digital Radiography</li>
                            <li><code>MG</code> - Mammography</li>
                            <li><code>NM</code> - Nuclear Medicine</li>
                            <li><code>PT</code> - PET</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        $('.echo-test').click(function () {
            var btn = $(this);
            var id = btn.data('id');

            btn.html('<i class="fas fa-spinner fa-spin"></i>');
            btn.prop('disabled', true);

            $.ajax({
                url: 'dicom/echoTest?id=' + id,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert('C-ECHO Success: ' + response.message);
                        location.reload();
                    } else {
                        alert('C-ECHO Failed: ' + response.message);
                    }
                },
                error: function () {
                    alert('Error testing connection');
                },
                complete: function () {
                    btn.html('<i class="fas fa-plug"></i> Echo');
                    btn.prop('disabled', false);
                }
            });
        });
    });
</script>