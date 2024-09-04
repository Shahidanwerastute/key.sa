@extends('admin.layouts.template')

@section('content')
        <!-- Content Wrapper. Contains page content -->
<div id="page_content">
    <div id="page_content_inner">
        {{--<a href="#" class="md-btn"> Add</a>--}}
        <div class="md-card">
            <div class="md-card-content">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-1-1">
                        <div class="uk-overflow-container">
                            <div class="md-card uk-margin-medium-bottom">
                                <div class="md-card-content">
                                    <table id="dt_default" class="uk-table" cellspacing="0" width="100%">

                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Created At</th>
                                            <?php if (custom::rights(9, 'edit') || custom::rights(9, 'delete'))
                                            { ?>
                                            <th>Actions</th>
                                            <?php } ?>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <?php
                                        foreach ($admin_users as $admin)
                                        { ?>
                                        <tr id="<?php echo $admin->id; ?>_row">
                                            <td><?php echo $admin->id; ?></td>
                                            <td><?php echo $admin->name; ?></td>
                                            <td><?php echo $admin->email; ?></td>
                                            <td><?php echo $admin->admin_role; ?></td>
                                            <td><?php echo $admin->created_at; ?></td>
                                            <td><?php if (custom::rights(9, 'edit'))
                                            { ?>
                                            <a href="<?php echo custom::baseurl('/'); ?>/admin/admins/edit/<?php echo $admin->id; ?>" title="Edit"><i class="material-icons">&#xE254;</i></a>
                                            <?php } ?>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php if (custom::rights(9, 'delete'))
                                            { ?>
                                            <a href="javascript:void(0);" onclick="deleteAdmin('<?php echo $admin->id; ?>');" title="Delete"><i class="material-icons">&#xE872;</i></a>
                                            <?php } ?></td>
                                        </tr>
                                        <?php }
                                        ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <a class="md-fab md-fab-accent" href="<?php echo custom::baseurl('/').'/admin/admins/create'; ?>" id="recordAdd" style="float: right;margin-top: 30px;margin-right: 30px;">
                <i class="material-icons">&#xE145;</i>
            </a>
        </div>
    </div>
</div>
<!-- /.content-wrapper -->
@endsection