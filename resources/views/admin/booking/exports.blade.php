@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">


            <div class="md-card">
                <div class="md-card-toolbar">
                    <div class="md-card-toolbar-actions">
                        {{--<a class="md-btn md-btn-primary md-btn-small md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="javascript:void(0)" target="_blank" title="Export To Excel">
                            <i class="material-icons">?</i>
                            Export
                        </a>--}}

                    </div>
                    <h3 class="heading_b md-card-toolbar-heading-text"><a href="<?php echo custom::baseurl('admin/bookings'); ?>">Go Back</a></h3>

                </div>
                <div class="md-card-content">
                    <div class="md-card-content">
                        <h3 class="heading_a uk-margin-bottom">Exported Files</h3>
                        <table id="dt_default" class="uk-table" cellspacing="0" width="100%">

                            <thead>
                            <tr>
                                <th>Exported By</th>
                                <th>File Name</th>
                                <th>Exported At</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($exports as $export)
                            { ?>
                            <tr id="<?php echo $export->id; ?>_row">
                                <td><?php echo $export->exported_by; ?></td>
                                <td><a href="<?php echo custom::baseUrl('storage/app/public/' . $export->filename); ?>" target="_blank" download>Click here to download file</a></td>
                                <td><?php echo $export->exported_at; ?></td>
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

@endsection