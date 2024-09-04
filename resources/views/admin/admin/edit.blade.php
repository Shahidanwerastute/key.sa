@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <form action="<?php echo custom::baseurl('/'); ?>/admin/page/updateAdmin" method="post" onsubmit="return false"
                  class="ajax_form">
                <input type="hidden" name="id" value="<?php echo $admin_details->id; ?>">
                <div class="md-card">
                    <div class="md-card-content">
                        <h3 class="heading_a">Edit Admin</h3>
                        <div class="uk-grid" data-uk-grid-margin>

                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Name</label>
                                    <input type="text" class="md-input" value="<?php echo $admin_details->name; ?>" name="name" required autocomplete="off"/>
                                </div>
                            </div>

                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>E-Mail Address</label>
                                    <input type="email" class="md-input" value="<?php echo $admin_details->email; ?>" name="email" required
                                           autocomplete="off"/>
                                </div>
                            </div>



                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>User Role</label>
                                    <select name="role" id="select_demo_5" data-md-selectize data-md-selectize-bottom
                                            data-uk-tooltip="{pos:'top'}" title="Select user role" required>
                                        <option value="">Select User Role</option>
                                        <?php
                                        foreach ($roles as $role) {
                                            if ($role->id == $adminRole->role_id) {
                                                $roleSelected = 'selected';
                                            } else {
                                                $roleSelected = '';
                                            }
                                            echo '<option value="' . $role->id . '" '.$roleSelected.'>' . $role->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Active Status</label>
                                    <select name="active_status" id="select_demo_5" data-md-selectize data-md-selectize-bottom
                                            data-uk-tooltip="{pos:'top'}" title="Select Status" required>
                                        <option value="active" <?php echo ($admin_details->active_status == 'active' ? 'selected' : ''); ?>>Active</option>
                                        <option value="inactive" <?php echo ($admin_details->active_status == 'inactive' ? 'selected' : ''); ?>>In-Active</option>
                                    </select>
                                </div>
                            </div>

                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Branches</label>
                                    <select class="multiselect" name="branches[]" multiple>
                                        <?php
                                        foreach ($branches as $branch) {
                                            if (in_array($branch->id, $adminBranches))
                                                {
                                                    $branchSelected = 'selected';
                                                }else{
                                                $branchSelected = '';
                                            }
                                            echo '<option value = "'.$branch->branch_id.'" '.$branchSelected.'>'.$branch->branch_title.', '.$branch->c_eng_title.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <span class="icheck-inline">
                                    <input type="checkbox" name="all_branches" id="all" value="1" data-md-icheck/>
                                    <label for="all" class="inline-label">All Branches</label>
                                </span>
                            </div>

                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Password</label>
                                    <input type="text" class="md-input" value="" name="password" required
                                           autocomplete="off"/>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>


                <div class="md-fab-wrapper">
                    <a class="md-fab md-fab-primary submit_ajax_form" href="javascript:void(0);" id="">
                        <i class="material-icons"></i>
                    </a>
                </div>
            </form>

            <!-- end light box for image -->
        </div>
    </div>
@endsection