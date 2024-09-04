@extends('admin.layouts.template')
@section('content')
    <style>
        .child-inline-block a {
            display: inline-block;
            vertical-align: bottom;
        }

        .selected {
            background-color: #D6D6D6 !important;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div id="page_content">
        <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-content">
                    <div class="uk-grid" data-uk-grid-margin>
                        <h3 class="heading_b md-card-toolbar-heading-text">Corporate Customers</h3>
                        <div class="uk-width-1-1">
                            <div class="uk-overflow-container">
                                <div class="md-card uk-margin-medium-bottom">
                                    <div class="md-card-content">
                                        <table id="dt_colVis" class="uk-table" cellspacing="0" width="100%">

                                            <thead>
                                            <tr>
                                                <th>Active Status</th>
                                                <th>Company Code</th>
                                                <th>Company Name</th>
                                                <th>Primary Contact Name</th>
                                                <th>Primary Position</th>
                                                <th>Primary Email</th>
                                                <th>Primary Phone</th>
                                                <th>Secondary Contact Name</th>
                                                <th>Secondary Position</th>
                                                <th>Secondary Email</th>
                                                <th>Secondary Phone</th>
                                                <th>Membership Level</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($customers as $customer)
                                            {
                                            if ($customer->active_status == 'active') {
                                                $active = '<i class="material-icons md-color-light-blue-600 md-24">check_circle</i>';
                                            } else {
                                                $active = '<i class="material-icons md-color-light-red-600 md-24">highlight_off</i>';
                                            }
                                            ?>
                                            <tr id="<?php echo $customer->id; ?>">
                                                <td><?php echo $active; ?></td>
                                                <td><?php echo $customer->company_code; ?></td>
                                                <td><?php echo $customer->company_name_en; ?></td>
                                                <td><?php echo $customer->primary_name; ?></td>
                                                <td><?php echo $customer->primary_position; ?></td>
                                                <td><?php echo $customer->primary_email; ?></td>
                                                <td><?php echo $customer->primary_phone; ?></td>
                                                <td><?php echo $customer->secondary_name; ?></td>
                                                <td><?php echo $customer->secondary_position; ?></td>
                                                <td><?php echo $customer->secondary_email; ?></td>
                                                <td><?php echo $customer->secondary_phone; ?></td>
                                                <td><?php echo $customer->membership_level; ?></td>
                                                <td><?php echo $customer->created_at; ?></td>
                                                <td><?php echo $customer->updated_at; ?></td>

                                            </tr>
                                            <?php }
                                            ?>
                                            </tbody>

                                        </table>
                                        <div class="md-fab-wrapper child-inline-block">
                                            <?php if ((custom::rights(20, 'add')))
                                                { ?>
                                                <a class="md-fab md-fab-primary"
                                                   href="<?php echo custom::baseurl('/'); ?>/admin/corporate_customer/add">
                                                    <i class="material-icons">&#xE7FE;</i>
                                                </a>
                                                <?php } ?>

                                                <?php if ((custom::rights(20, 'edit')))
                                                { ?>
                                                <a class="md-fab md-fab-success" href="javascript:void(0);"
                                                   id="edit_corporate_customer_btn">
                                                    <i class="material-icons">&#xE254;</i>
                                                </a>
                                                <?php } ?>

                                                <?php if ((custom::rights(20, 'delete')))
                                                { ?>
                                                <a class="md-fab md-fab-danger" href="javascript:void(0);"
                                                   id="delete_corporate_customer_btn" data-deleteable="no" data-url="">
                                                    <i class="material-icons">&#xE872;</i>
                                                </a>
                                                <?php } ?>

                                        </div>
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