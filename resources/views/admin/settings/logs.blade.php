@extends('admin.layouts.template')


@section('content')
        <!-- Content Wrapper. Contains page content -->
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-content">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-1-1">
                        <div class="uk-overflow-container">
                            <div class="md-card uk-margin-medium-bottom">
                                <div class="md-card-content">
                                    <h3 class="heading_a uk-margin-bottom">Site Logs</h3>
                                    <table id="dt_default" class="uk-table" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Activity</th>
                                            <th>Section</th>
                                            <th>Created At</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($logs as $log)
                                        {
                                        if ($log->type == 'add') {
                                            $class = 'uk-badge uk-badge-primary';
                                        } elseif ($log->type == 'update') {
                                            $class = 'uk-badge uk-badge-success';
                                        } elseif ($log->type == 'delete') {
                                            $class = 'uk-badge uk-badge-danger';
                                        } elseif ($log->type == 'export') {
                                            $class = 'uk-badge uk-badge-warning';
                                        } elseif ($log->type == 'import') {
                                            $class = 'uk-badge';
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $log->user_name; ?></td>
                                            <td><span class="<?php echo $class; ?>"><?php echo $log->message; ?></span></td>
                                            <td><?php echo $log->section; ?></td>
                                            <td><?php echo date('l\, jS F Y h:i:s A', strtotime($log->created_at)); ?></td>
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
        </div>

    </div>
</div>
<!-- /.content-wrapper -->
@endsection