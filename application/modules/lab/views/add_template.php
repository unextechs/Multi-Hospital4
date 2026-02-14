<!--sidebar end-->
<!--main content start-->

<link href="common/extranal/css/lab/add_template.css" rel="stylesheet">

<div class="content-wrapper bg-light">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row my-2 pl-1">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold"><i class="fas fa-flask mr-2"></i><?php
                    if (!empty($template->id))
                        echo lang('edit_lab_report') . ' ' . lang('template');
                    else
                        echo lang('add_lab_report') . ' ' . lang('template');
                    ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="home"><?php echo lang('home') ?></a></li>
                        <li class="breadcrumb-item active"><?php
                        if (!empty($template->id))
                            echo lang('edit_lab_report') . ' ' . lang('template');
                        else
                            echo lang('add_lab_report') . ' ' . lang('template');
                        ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All the department names and related informations</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form role="form" id="editLabForm" class="clearfix" action="lab/addTemplate" method="post"
                                enctype="multipart/form-data">
                                <div class="col-md-12 lab pad_bot row">
                                    <div class="col-md-3 lab_label">
                                        <label for="exampleInputEmail1"> <?php echo lang('template'); ?>
                                            <?php echo lang('name'); ?> &ast; </label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control pay_in" name="name" value='<?php
                                        if (!empty($template->name)) {
                                            echo $template->name;
                                        }
                                        ?>'
                                            placeholder="" required="">
                                    </div>
                                </div>

                                <div class="col-md-12 lab pad_bot row">
                                    <div class="col-md-3 lab_label">
                                        <label for="exampleInputEmail1"> <?php echo lang('category'); ?> &ast; </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-10 col-xs-10">
                                                <select class="form-control category" name="category_id" required>
                                                    <option value=""><?php echo "Select"; ?></option>
                                                    <?php foreach ($categories as $category) { ?>
                                                        <option value="<?php echo $category->id; ?>" <?php
                                                                                                        if (!empty($template->name) && ($template->category_id == $category->id)) {
                                                                                                        ?> selected <?php
                                                                                                                }
                                                                                                                    ?>><?php echo $category->category; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2 col-xs-2">
                                                <a data-toggle="modal" href="#myModalCategory" class="btn btn-icon btn-primary" title="Add Category">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        <div class="col-md-12 lab pad_bot row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1"> <?php echo lang('template'); ?> &ast; </label>
                            </div>
                            <div class="col-md-9">
                                <textarea class="ckeditor form-control" id="editor" name="template" value="" rows="10"
                                    required=""><?php
                                    if (!empty($setval)) {
                                        echo set_value('template');
                                    }
                                    if (!empty($template->template)) {
                                        echo $template->template;
                                    }
                                    ?>
                                    </textarea>
                            </div>
                        </div>

                        <input type="hidden" name="id" value='<?php
                        if (!empty($template->id)) {
                            echo $template->id;
                        }
                        ?>'>


                        <div class="col-md-12">
                            <button type="submit" name="submit"
                                class="btn btn-info float-right"><?php echo lang('submit'); ?></button>
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




<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <link href="common/extranal/css/lab/add_template.css" rel="stylesheet">
        <section class="panel col-md-7">
            <header class="panel-heading no-print">
                <?php
                if (!empty($template->id))
                    echo lang('edit_lab_report') . ' ' . lang('template');
                else
                    echo lang('add_lab_report') . ' ' . lang('template');
                ?>
            </header>
            <div class="no-print row">
                <div class="adv-table editable-table ">
                    <div class="clearfix">
                        <form role="form" id="editLabForm" class="clearfix" action="lab/addTemplate" method="post"
                            enctype="multipart/form-data">
                            <div class="col-md-12 lab pad_bot row">
                                <div class="col-md-3 lab_label">
                                    <label for="exampleInputEmail1"> <?php echo lang('template'); ?>
                                        <?php echo lang('name'); ?> &ast; </label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control pay_in" name="name" value='<?php
                                    if (!empty($template->name)) {
                                        echo $template->name;
                                    }
                                    ?>'
                                        placeholder="" required="">
                                </div>
                            </div>

                            <div class="col-md-12 lab pad_bot row">
                                <div class="col-md-3 lab_label">
                                    <label for="exampleInputEmail1"> <?php echo lang('category'); ?> &ast; </label>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-10 col-xs-10">
                                            <select class="form-control category" name="category_id" required>
                                                <option value=""><?php echo "Select"; ?></option>
                                                <?php foreach ($categories as $category) { ?>
                                                    <option value="<?php echo $category->id; ?>" <?php
                                                                                                    if (!empty($template->name) && ($template->category_id == $category->id)) {
                                                                                                    ?> selected <?php
                                                                                                            }
                                                                                                                ?>><?php echo $category->category; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-2">
                                            <a data-toggle="modal" href="#myModalCategory" class="btn btn-icon btn-primary" title="Add Category">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 lab pad_bot row">
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1"> <?php echo lang('template'); ?> &ast; </label>
                                </div>
                                <div class="col-md-9">
                                    <textarea class="ckeditor form-control" id="editor" name="template" value=""
                                        rows="10" required=""><?php
                                        if (!empty($setval)) {
                                            echo set_value('template');
                                        }
                                        if (!empty($template->template)) {
                                            echo $template->template;
                                        }
                                        ?>
                                    </textarea>
                                </div>
                            </div>

                            <input type="hidden" name="id" value='<?php
                            if (!empty($template->id)) {
                                echo $template->id;
                            }
                            ?>'>


                            <div class="col-md-12">
                                <button type="submit" name="submit"
                                    class="btn btn-info float-right"><?php echo lang('submit'); ?></button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>



        </section>
    </section>
</section>
<!--main content end-->
<!--footer start-->


<script src="common/assets/tinymce/tinymce.min.js"></script>
<script src="common/extranal/js/lab/add_template_view.js"></script>

<div class="modal fade" id="myModalCategory" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('add_new'); ?> <?php echo lang('category'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="addCategoryForm" action="lab/addCategoryByJson" method="post"
                    enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('category'); ?> <?php echo lang('name'); ?>
                            &ast; </label>
                        <input type="text" class="form-control" name="category" placeholder="" required="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('description'); ?> &ast; </label>
                        <input type="text" class="form-control" name="description" placeholder="" required="">
                    </div>
                    <button type="submit" name="submit" class="btn btn-info"><?php echo lang('submit'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#addCategoryForm").submit(function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        var newOption = new Option(data.category, data.id, true, true);
                        $('.category').append(newOption).trigger('change');
                        $('#myModalCategory').modal('hide');
                        form[0].reset();
                    }
                }
            });
        });
    });
</script>