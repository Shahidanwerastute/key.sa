@extends('admin.layouts.template')
@section('content')
    <div id="page_content">
        <div id="page_content_inner">
            <!-- circular charts -->
            <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
                <div class="uk-width-medium-1-1">
                    <div class="">
                        <div class="md-card-content">
                            <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
                                <div class="uk-width-medium-1-1">
                                    <div class="md-card">
                                        <div class="md-card-toolbar">
                                            <div class="md-card-toolbar-actions">
                                                <!--<a href="{{custom::baseurl('admin/exportOasisSurveyData')}}" target="_blank">
                                                    <button type="submit"
                                                            class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light"
                                                            title="Export Oasis Survey Feedback To Excel">
                                                        Export Report
                                                    </button>
                                                </a>-->
                                                <form action="<?php echo custom::baseurl('/'); ?>/admin/exportOasisSurveyData" method="post" target="_blank">
                                                    <input type="hidden" name="start_date" id="start_date_a" value="">
                                                    <input type="hidden" name="end_date" id="end_date_a1" value="">

                                                    <label for="kUI_datepicker_a" class="uk-form-label">Start Date</label>
                                                    <input type="text" id="kUI_datepicker_a" name="" value="" />

                                                    <label for="kUI_datepicker_a1" class="uk-form-label">End Date</label>
                                                    <input type="text" id="kUI_datepicker_a1" name="" value="" />

                                                    <button type="submit" class="md-btn md-btn-primary md-btn-mini md-btn-wave-light waves-effect waves-button waves-light" title="Export Oasis Survey Feedback To Excel">
                                                        Export Report
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="md-card-content">
                                            <div id="total_feedbacks_chart" class="chartist"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-grid uk-grid-width-small-1-1 uk-grid-width-large-1-1 uk-grid-width-xlarge-1-1 uk-text-center uk-sortable sortable-handler"
                 id="dashboard_sortable_cards" data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card-hover md-card-overlay">
                        <div class="md-card-content">
                            <div id="car_quality_and_cleanliness_pie_chart" style="width: auto; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-grid uk-grid-width-small-1-1 uk-grid-width-large-1-1 uk-grid-width-xlarge-1-1 uk-text-center uk-sortable sortable-handler"
                 id="dashboard_sortable_cards" data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card-hover md-card-overlay">
                        <div class="md-card-content">
                            <div id="employee_performance_pie_chart" style="width: auto; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-grid uk-grid-width-small-1-1 uk-grid-width-large-1-1 uk-grid-width-xlarge-1-1 uk-text-center uk-sortable sortable-handler"
                 id="dashboard_sortable_cards" data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card-hover md-card-overlay">
                        <div class="md-card-content">
                            <div id="branch_employees_behavior_and_performance_pie_chart" style="width: auto; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-grid uk-grid-width-small-1-1 uk-grid-width-large-1-1 uk-grid-width-xlarge-1-1 uk-text-center uk-sortable sortable-handler"
                 id="dashboard_sortable_cards" data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card-hover md-card-overlay">
                        <div class="md-card-content">
                            <div id="the_quickness_and_efficiency_of_completing_your_rental_procedure_pie_chart" style="width: auto; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-grid uk-grid-width-small-1-1 uk-grid-width-large-1-1 uk-grid-width-xlarge-1-1 uk-text-center uk-sortable sortable-handler"
                 id="dashboard_sortable_cards" data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card-hover md-card-overlay">
                        <div class="md-card-content">
                            <div id="the_accuracy_of_the_rental_information_provided_to_you_pie_chart" style="width: auto; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-grid uk-grid-width-small-1-1 uk-grid-width-large-1-1 uk-grid-width-xlarge-1-1 uk-text-center uk-sortable sortable-handler"
                 id="dashboard_sortable_cards" data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card-hover md-card-overlay">
                        <div class="md-card-content">
                            <div id="the_safety_and_the_quality_of_the_vehicle_structure_pie_chart" style="width: auto; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-grid uk-grid-width-small-1-1 uk-grid-width-large-1-1 uk-grid-width-xlarge-1-1 uk-text-center uk-sortable sortable-handler"
                 id="dashboard_sortable_cards" data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card-hover md-card-overlay">
                        <div class="md-card-content">
                            <div id="the_cleanliness_of_the_vehicle_externally_and_internally_pie_chart" style="width: auto; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-grid uk-grid-width-small-1-1 uk-grid-width-large-1-1 uk-grid-width-xlarge-1-1 uk-text-center uk-sortable sortable-handler"
                 id="dashboard_sortable_cards" data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card-hover md-card-overlay">
                        <div class="md-card-content">
                            <div id="how_likely_are_you_to_recommend_our_company_pie_chart" style="width: auto; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-grid uk-grid-width-small-1-1 uk-grid-width-large-1-1 uk-grid-width-xlarge-1-1 uk-text-center uk-sortable sortable-handler"
                 id="dashboard_sortable_cards" data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card-hover md-card-overlay">
                        <div class="md-card-content">
                            <div id="your_experience_with_key_pie_chart" style="width: auto; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="uk-grid uk-grid-width-small-1-1 uk-grid-width-large-1-1 uk-grid-width-xlarge-1-1 uk-text-center uk-sortable sortable-handler"
                 id="dashboard_sortable_cards" data-uk-sortable data-uk-grid-margin>
                <div>
                    <div class="md-card-hover md-card-overlay">
                        <div class="md-card-content">
                            <div id="piechart_for_categories_feedback" style="width: auto; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
@endsection