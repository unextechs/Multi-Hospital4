<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fas fa-network-wired"></i> HL7 Connections
            <small>Manage HL7 interface connections</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fas fa-tachometer-alt"></i> Home</a></li>
            <li><a href="hl7">HL7</a></li>
            <li class="active">Connections</li>
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
                        <h3 class="box-title">HL7 Connections</h3>
                        <div class="box-tools pull-right">
                            <a href="hl7/addConnectionView" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Connection
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Host</th>
                                        <th>Port</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Last Connected</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($connections)): ?>
                                        <?php foreach ($connections as $conn): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($conn->name); ?></strong></td>
                                                <td><?php echo htmlspecialchars($conn->host); ?></td>
                                                <td><?php echo $conn->port; ?></td>
                                                <td><span
                                                        class="label label-default"><?php echo $conn->connection_type; ?></span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="label label-<?php echo $conn->status == 'active' ? 'success' : 'danger'; ?>">
                                                        <?php echo ucfirst($conn->status); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php echo $conn->last_connected ? date('d M Y H:i', strtotime($conn->last_connected)) : 'Never'; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-xs test-connection"
                                                        data-id="<?php echo $conn->id; ?>">
                                                        <i class="fas fa-plug"></i> Test
                                                    </button>
                                                    <a href="hl7/editConnection?id=<?php echo $conn->id; ?>"
                                                        class="btn btn-warning btn-xs">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="hl7/deleteConnection?id=<?php echo $conn->id; ?>"
                                                        class="btn btn-danger btn-xs"
                                                        onclick="return confirm('Are you sure?');">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No connections configured</td>
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
                        <h3 class="box-title"><i class="fas fa-info-circle"></i> About HL7 Connections</h3>
                    </div>
                    <div class="box-body">
                        <p>HL7 (Health Level 7) is a messaging standard for healthcare information exchange. Configure
                            connections to:</p>
                        <ul>
                            <li><strong>Laboratory Systems (LIS)</strong> - Receive lab results</li>
                            <li><strong>Radiology Systems (RIS)</strong> - Send/receive radiology orders</li>
                            <li><strong>Hospital Information Systems (HIS)</strong> - Sync patient data</li>
                            <li><strong>PACS Systems</strong> - Connect imaging systems</li>
                        </ul>
                        <p><strong>Common Message Types:</strong></p>
                        <ul>
                            <li><code>ADT</code> - Admission, Discharge, Transfer</li>
                            <li><code>ORM</code> - Order Message</li>
                            <li><code>ORU</code> - Observation Result</li>
                            <li><code>SIU</code> - Scheduling Information</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fas fa-cog"></i> Connection Settings</h3>
                    </div>
                    <div class="box-body">
                        <p><strong>MLLP (Minimal Lower Layer Protocol)</strong></p>
                        <p>Standard HL7 transport protocol. Messages are framed with:</p>
                        <ul>
                            <li>Start: <code>&lt;VT&gt;</code> (0x0B)</li>
                            <li>End: <code>&lt;FS&gt;&lt;CR&gt;</code> (0x1C 0x0D)</li>
                        </ul>
                        <p><strong>Default Ports:</strong></p>
                        <ul>
                            <li>HL7 MLLP: <code>2575</code></li>
                            <li>HL7 over TCP: <code>6661</code></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        $('.test-connection').click(function () {
            var btn = $(this);
            var id = btn.data('id');

            btn.html('<i class="fas fa-spinner fa-spin"></i> Testing...');
            btn.prop('disabled', true);

            $.ajax({
                url: 'hl7/testConnection?id=' + id,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert('Connection successful!');
                        location.reload();
                    } else {
                        alert('Connection failed: ' + response.message);
                    }
                },
                error: function () {
                    alert('Error testing connection');
                },
                complete: function () {
                    btn.html('<i class="fas fa-plug"></i> Test');
                    btn.prop('disabled', false);
                }
            });
        });
    });
</script>