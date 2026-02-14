<div class="content-wrapper bg-light">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>HL7 Messages</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">List of HL7 Messages (Superadmin Only)</h3>
                            <button class="btn btn-primary float-right" data-toggle="modal" data-target="#myModal">Add
                                New Message</button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Payload</th>
                                        <th>Created At</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $message) { ?>
                                        <tr>
                                            <td><?php echo $message->id; ?></td>
                                            <td><?php echo $message->message_type; ?></td>
                                            <td><?php echo substr($message->payload, 0, 50) . '...'; ?></td>
                                            <td><?php echo $message->created_at; ?></td>
                                            <td>
                                                <a class="btn btn-danger btn-sm"
                                                    href="hl7/delete?id=<?php echo $message->id; ?>"
                                                    onclick="return confirm('Are you sure?');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Add Message Modal -->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-weight-bold">Add HL7 Message</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form role="form" action="hl7/add" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="message_type">Message Type</label>
                        <input type="text" class="form-control" name="message_type" placeholder="e.g. ADT^A01" required>
                    </div>
                    <div class="form-group">
                        <label for="payload">Payload</label>
                        <textarea class="form-control" name="payload" rows="5" placeholder="Paste HL7 message here"
                            required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>