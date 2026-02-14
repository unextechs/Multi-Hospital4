<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fas fa-plus"></i> <?php echo isset($connection) ? 'Edit' : 'Add'; ?> HL7 Connection
        </h1>
        <ol class="breadcrumb">
            <li><a href="home"><i class="fas fa-tachometer-alt"></i> Home</a></li>
            <li><a href="hl7">HL7</a></li>
            <li><a href="hl7/connections">Connections</a></li>
            <li class="active"><?php echo isset($connection) ? 'Edit' : 'Add'; ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Connection Details</h3>
                    </div>
                    <form action="hl7/addConnection" method="post">
                        <?php if (isset($connection)): ?>
                            <input type="hidden" name="id" value="<?php echo $connection->id; ?>">
                        <?php endif; ?>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="name">Connection Name *</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo isset($connection) ? htmlspecialchars($connection->name) : ''; ?>"
                                    placeholder="e.g., Lab System, RIS, PACS" required>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="host">Host / IP Address *</label>
                                        <input type="text" class="form-control" id="host" name="host"
                                            value="<?php echo isset($connection) ? htmlspecialchars($connection->host) : ''; ?>"
                                            placeholder="e.g., 192.168.1.100 or lis.hospital.com" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="port">Port *</label>
                                        <input type="number" class="form-control" id="port" name="port"
                                            value="<?php echo isset($connection) ? $connection->port : '2575'; ?>"
                                            placeholder="2575" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="connection_type">Connection Type</label>
                                <select class="form-control" id="connection_type" name="connection_type">
                                    <option value="MLLP" <?php echo (isset($connection) && $connection->connection_type == 'MLLP') ? 'selected' : ''; ?>>
                                        MLLP (Minimal Lower Layer Protocol)
                                    </option>
                                    <option value="TCP" <?php echo (isset($connection) && $connection->connection_type == 'TCP') ? 'selected' : ''; ?>>
                                        TCP/IP Raw
                                    </option>
                                </select>
                            </div>

                            <hr>
                            <h4>Application Identity</h4>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sending_application">Sending Application</label>
                                        <input type="text" class="form-control" id="sending_application"
                                            name="sending_application"
                                            value="<?php echo isset($connection) ? htmlspecialchars($connection->sending_application) : 'HOSPITAL'; ?>"
                                            placeholder="Sending Application Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sending_facility">Sending Facility</label>
                                        <input type="text" class="form-control" id="sending_facility"
                                            name="sending_facility"
                                            value="<?php echo isset($connection) ? htmlspecialchars($connection->sending_facility) : ''; ?>"
                                            placeholder="Sending Facility Name">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="receiving_application">Receiving Application</label>
                                        <input type="text" class="form-control" id="receiving_application"
                                            name="receiving_application"
                                            value="<?php echo isset($connection) ? htmlspecialchars($connection->receiving_application) : ''; ?>"
                                            placeholder="Receiving Application Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="receiving_facility">Receiving Facility</label>
                                        <input type="text" class="form-control" id="receiving_facility"
                                            name="receiving_facility"
                                            value="<?php echo isset($connection) ? htmlspecialchars($connection->receiving_facility) : ''; ?>"
                                            placeholder="Receiving Facility Name">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?php echo isset($connection) ? 'Update' : 'Save'; ?>
                                Connection
                            </button>
                            <a href="hl7/connections" class="btn btn-default">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fas fa-question-circle"></i> Help</h3>
                    </div>
                    <div class="box-body">
                        <p><strong>Host:</strong> The IP address or hostname of the remote HL7 system.</p>
                        <p><strong>Port:</strong> The TCP port number. Common ports:</p>
                        <ul>
                            <li><code>2575</code> - Standard HL7 MLLP</li>
                            <li><code>6661</code> - Alternative HL7</li>
                        </ul>
                        <p><strong>MLLP:</strong> Standard HL7 transport protocol with message framing.</p>
                        <p><strong>Application/Facility:</strong> Used in the MSH segment to identify the sending and
                            receiving systems.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>