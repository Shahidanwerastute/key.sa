@extends('admin.layouts.template')

@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <form action="<?php echo custom::baseurl('/'); ?>/admin/page/saveAdmin" method="post" onsubmit="return false"
                  class="ajax_form">
                <div class="md-card">
                    <div class="md-card-content">
                        <h3 class="heading_a">Add Admin</h3>
                        <div class="uk-grid" data-uk-grid-margin>

                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Name</label>
                                    <input type="text" class="md-input" value="" name="name" required
                                           autocomplete="off"/>
                                </div>
                            </div>

                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>E-Mail Address</label>
                                    <input type="email" class="md-input" value="" name="email" required
                                           autocomplete="off"/>
                                </div>
                            </div>



                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>User Role</label>
                                    <select name="role" id="select_demo_5" data-md-selectize data-md-selectize-bottom
                                            data-uk-tooltip="{pos:'top'}" title="Select user role">
                                        @foreach($roles as $role)
                                            {
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                            }
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Active Status</label>
                                    <select name="active_status" id="select_demo_5" data-md-selectize data-md-selectize-bottom
                                            data-uk-tooltip="{pos:'top'}" title="Select Status" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">In-Active</option>
                                    </select>
                                </div>
                            </div>

                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <label>Branches</label>
                                    <select class="multiselect" name="branches[]" multiple>
                                        @foreach($branches as $branch)
                                            {
                                            <option value="{{$branch->branch_id}}">{{$branch->branch_title}}
                                                , {{$branch->c_eng_title}}</option>
                                            }
                                        @endforeach
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


                <?php
                if (custom::rights(9, 'add'))
                {
                ?>
                <div class="md-fab-wrapper">
                    <a class="md-fab md-fab-primary submit_ajax_form" href="javascript:void(0);" id="">
                        <i class="material-icons"></i>
                    </a>
                </div>
                <?php } ?>
            </form>

            <!-- end light box for image -->
        </div>
    </div>
@endsection