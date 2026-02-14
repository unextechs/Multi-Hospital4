<div class="content-wrapper bg-light">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row my-2 pl-1">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold"><i
                            class="fas fa-user-shield mr-2"></i><?php echo lang('edit_permission'); ?> :
                        <?php echo $group->name; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home"><?php echo lang('home') ?></a></li>
                        <li class="breadcrumb-item"><a
                                href="hospital/role_permission"><?php echo lang('role_permission'); ?></a></li>
                        <li class="breadcrumb-item active"><?php echo lang('edit_permission'); ?></li>
                    </ol>
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
                            <h3 class="card-title"><?php echo lang('edit_permission'); ?> - <?php echo $group->name; ?>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form role="form" action="hospital/role_permission/updatePermission" method="post"
                                enctype="multipart/form-data">
                                <input type="hidden" name="group_id" value="<?php echo $group->id; ?>">

                                <div class="form-group">
                                    <label><?php echo lang('modules'); ?></label>
                                    <div class="row">
                                        <?php foreach ($hospital_modules as $module) { ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="module[]"
                                                        value="<?php echo $module; ?>" id="module_<?php echo $module; ?>"
                                                        <?php if (in_array($module, $group_modules)) {
                                                            echo 'checked';
                                                        } ?>>
                                                    <label class="form-check-label" for="module_<?php echo $module; ?>">
                                                        <?php echo lang($module) ? lang($module) : ucfirst(str_replace('_', ' ', $module)); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-info">
                                        <i class="fa fa-save"></i> <?php echo lang('submit'); ?>
                                    </button>
                                    <a href="hospital/role_permission" class="btn btn-default">
                                        <i class="fa fa-arrow-left"></i> <?php echo lang('back'); ?>
                                    </a>
                                </div>
                            </form>
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