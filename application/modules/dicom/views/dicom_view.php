<div class="content-wrapper bg-light">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>DICOM Files</h1>
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
                            <h3 class="card-title">List of DICOM Files (Superadmin Only)</h3>
                            <button class="btn btn-primary float-right" data-toggle="modal"
                                data-target="#myModal">Upload New File</button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>File Path</th>
                                        <th>Patient ID</th>
                                        <th>Study Date</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($files as $file) { ?>
                                        <tr>
                                            <td><?php echo $file->id; ?></td>
                                            <td><a href="<?php echo $file->file_path; ?>"
                                                    target="_blank"><?php echo basename($file->file_path); ?></a></td>
                                            <td><?php echo $file->patient_id; ?></td>
                                            <td><?php echo $file->study_date; ?></td>
                                            <td>
                                                <a class="btn btn-danger btn-sm"
                                                    href="dicom/delete?id=<?php echo $file->id; ?>"
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

<!-- Upload File Modal -->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-weight-bold">Upload DICOM File</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form role="form" action="dicom/upload" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="patient_id">Patient ID</label>
                        <input type="text" class="form-control" name="patient_id" placeholder="Patient ID" required>
                    </div>
                    <div class="form-group">
                        <label for="img_url">DICOM File</label>
                        <input type="file" class="form-control" name="img_url" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>