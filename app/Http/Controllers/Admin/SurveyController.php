<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Settings;
use Illuminate\Http\Request;
use App\Models\Admin\Survey;
use App\Models\Admin\DropoffCharges;
use App\Models\Admin\Page;
use App\Helpers\Custom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Excel;

class SurveyController extends Controller
{


    public function index()
    {
        if (!custom::rights(31, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'survey';
        $data['inner_section'] = 'manage_survey';
        return view('admin/survey/manage', $data);
    }

    public function reports()
    {
        if (!custom::rights(32, 'view')) {
            return redirect('admin/dashboard');
        }
        $emoji_titles = array();
        $emojis_count = array();
        $option_titles = array();
        $options_count = array();
        $page = new Page();

        $for_today = Carbon::parse(Carbon::now())->format('Y-m-d');
        $for_yesterday = Carbon::parse(Carbon::now()->subDays(1))->format('Y-m-d');
        $for_last_seven_days = Carbon::parse(Carbon::now()->subDays(7))->format('Y-m-d');
        $for_last_week = Carbon::parse(Carbon::now()->subWeeks(1))->format('Y-m-d');
        $for_last_month = Carbon::parse(Carbon::now()->subMonth(1))->format('Y-m-d');

        $survey = new Survey();

        $emojis = $page->getAll('survey_emoji');
        foreach ($emojis as $emoji) {
            $emoji_titles[] = $emoji->eng_title;
            $count = $survey->get_count_of_surveys_filled_for_emoji($emoji->id);
            $emojis_count[] = $count;
        }

        /*$categories = $page->getAll('survey_category');
        foreach ($categories as $category)
        {
            $category_titles[] = $category->eng_question.' ('.$category->eng_title.')';
            $count = $survey->get_count_of_surveys_filled_for_category($category->id);
            $categories_count[] = $count;
        }*/

        $survey_options = $page->getAll('survey_category_options');
        foreach ($survey_options as $option)
        {
            $emoji_title_for_option = '';
            $category_data = $page->getSingleRow('survey_category', array('id' => $option->category_id));
            if ($category_data)
            {
                $emoji_data = $page->getSingleRow('survey_emoji', array('id' => $category_data->emoji_id));
                if ($emoji_data)
                {
                    $emoji_title_for_option = ' ('.$emoji_data->eng_title.')';
                }
            }

            $option_titles[] = $option->eng_title.$emoji_title_for_option;
            $count = $survey->get_count_of_surveys_filled_for_option($option->id);
            $options_count[] = $count;
        }

        /*$survey_feedbacks = $page->getAll('survey_feedback');
        foreach ($survey_feedbacks as $feedback) {
            $option_id = $feedback->option_id;
            if ($option_id > 0) {
                $option_detail = $page->getSingleRow('survey_category_options', array());
                $option_titles[] = $option_detail->eng_title;
            } elseif ($option_id == 0) {
                $option_titles[] = $feedback->answer_desc;
            }

            $count = $survey->get_count_of_surveys_filled_for_option($option->id);
            $options_count[] = $count;
        }*/

        $data['surveys_count_for_today'] = $survey->get_surveys_count('created_at', '>=', $for_today);
        $data['surveys_count_for_yesterday'] = $survey->get_surveys_count('created_at', '=', $for_yesterday);
        $data['surveys_count_for_last_week'] = $survey->get_surveys_count('created_at', '>=', $for_last_week);
        $data['surveys_count_for_last_month'] = $survey->get_surveys_count('created_at', '>=', $for_last_month);
        $data['surveys_count_total'] = $survey->get_surveys_count('created_at', '>=', "");

        $data['show_survey_charts'] = true;
        $data['emoji_titles'] = $emoji_titles;
        $data['emojis_count'] = $emojis_count;

        $data['category_titles'] = $option_titles;
        $data['categories_count'] = $options_count;

        $data['main_section'] = 'survey';
        $data['inner_section'] = 'survey_reports';
        return view('admin/survey/reports', $data);
    }

    public function oasis_survey_reports()
    {
        if (!custom::rights(33, 'view')) {
            return redirect('admin/dashboard');
        }

        $survey = new Survey();

        $for_today = Carbon::parse(Carbon::now())->format('Y-m-d');
        $for_yesterday = Carbon::parse(Carbon::now()->subDays(1))->format('Y-m-d');
        $for_last_week = Carbon::parse(Carbon::now()->subWeeks(1))->format('Y-m-d');
        $for_last_month = Carbon::parse(Carbon::now()->subMonth(1))->format('Y-m-d');

        $data['surveys_count_for_today'] = $survey->get_oasis_surveys_count('created_at', '>=', $for_today);
        $data['surveys_count_for_yesterday'] = $survey->get_oasis_surveys_count('created_at', '=', $for_yesterday);
        $data['surveys_count_for_last_week'] = $survey->get_oasis_surveys_count('created_at', '>=', $for_last_week);
        $data['surveys_count_for_last_month'] = $survey->get_oasis_surveys_count('created_at', '>=', $for_last_month);
        $data['surveys_count_total'] = $survey->get_oasis_surveys_count('created_at', '>=', "");

        for ($i=1; $i <= 5; $i++) {
            $car_quality_and_cleanliness_titles[] = $i;
            $car_quality_and_cleanliness_count[] = $survey->get_question_answers_count_in_oasis_survey_feedback('car_quality_and_cleanliness', $i);
        }

        for ($i=1; $i <= 5; $i++) {
            $employee_performance_titles[] = $i;
            $employee_performance_count[] = $survey->get_question_answers_count_in_oasis_survey_feedback('employee_performance', $i);
        }

        for ($i=1; $i <= 5; $i++) {
            $branch_employees_behavior_and_performance_titles[] = $i;
            $branch_employees_behavior_and_performance_count[] = $survey->get_question_answers_count_in_oasis_survey_feedback('branch_employees_behavior_and_performance', $i);
        }

        for ($i=1; $i <= 5; $i++) {
            $the_quickness_and_efficiency_of_completing_your_rental_procedure_titles[] = $i;
            $the_quickness_and_efficiency_of_completing_your_rental_procedure_count[] = $survey->get_question_answers_count_in_oasis_survey_feedback('the_quickness_and_efficiency_of_completing_your_rental_procedure', $i);
        }

        for ($i=1; $i <= 5; $i++) {
            $the_accuracy_of_the_rental_information_provided_to_you_titles[] = $i;
            $the_accuracy_of_the_rental_information_provided_to_you_count[] = $survey->get_question_answers_count_in_oasis_survey_feedback('the_accuracy_of_the_rental_information_provided_to_you', $i);
        }

        for ($i=1; $i <= 5; $i++) {
            $the_safety_and_the_quality_of_the_vehicle_structure_titles[] = $i;
            $the_safety_and_the_quality_of_the_vehicle_structure_count[] = $survey->get_question_answers_count_in_oasis_survey_feedback('the_safety_and_the_quality_of_the_vehicle_structure', $i);
        }

        for ($i=1; $i <= 5; $i++) {
            $the_cleanliness_of_the_vehicle_externally_and_internally_titles[] = $i;
            $the_cleanliness_of_the_vehicle_externally_and_internally_count[] = $survey->get_question_answers_count_in_oasis_survey_feedback('the_cleanliness_of_the_vehicle_externally_and_internally', $i);
        }

        for ($i=1; $i <= 5; $i++) {
            $how_likely_are_you_to_recommend_our_company_titles[] = $i;
            $how_likely_are_you_to_recommend_our_company_count[] = $survey->get_question_answers_count_in_oasis_survey_feedback('how_likely_are_you_to_recommend_our_company', $i);
        }

        for ($i=1; $i <= 5; $i++) {
            $your_experience_with_key_titles[] = $i;
            $your_experience_with_key_count[] = $survey->get_question_answers_count_in_oasis_survey_feedback('your_experience_with_key', $i);
        }

        $data['show_survey_charts'] = true;

        $data['car_quality_and_cleanliness_titles'] = $car_quality_and_cleanliness_titles;
        $data['car_quality_and_cleanliness_count'] = $car_quality_and_cleanliness_count;

        $data['employee_performance_titles'] = $employee_performance_titles;
        $data['employee_performance_count'] = $employee_performance_count;

        $data['branch_employees_behavior_and_performance_titles'] = $branch_employees_behavior_and_performance_titles;
        $data['branch_employees_behavior_and_performance_count'] = $branch_employees_behavior_and_performance_count;

        $data['the_quickness_and_efficiency_of_completing_your_rental_procedure_titles'] = $the_quickness_and_efficiency_of_completing_your_rental_procedure_titles;
        $data['the_quickness_and_efficiency_of_completing_your_rental_procedure_count'] = $the_quickness_and_efficiency_of_completing_your_rental_procedure_count;

        $data['the_accuracy_of_the_rental_information_provided_to_you_titles'] = $the_accuracy_of_the_rental_information_provided_to_you_titles;
        $data['the_accuracy_of_the_rental_information_provided_to_you_count'] = $the_accuracy_of_the_rental_information_provided_to_you_count;

        $data['the_safety_and_the_quality_of_the_vehicle_structure_titles'] = $the_safety_and_the_quality_of_the_vehicle_structure_titles;
        $data['the_safety_and_the_quality_of_the_vehicle_structure_count'] = $the_safety_and_the_quality_of_the_vehicle_structure_count;

        $data['the_cleanliness_of_the_vehicle_externally_and_internally_titles'] = $the_cleanliness_of_the_vehicle_externally_and_internally_titles;
        $data['the_cleanliness_of_the_vehicle_externally_and_internally_count'] = $the_cleanliness_of_the_vehicle_externally_and_internally_count;

        $data['how_likely_are_you_to_recommend_our_company_titles'] = $how_likely_are_you_to_recommend_our_company_titles;
        $data['how_likely_are_you_to_recommend_our_company_count'] = $how_likely_are_you_to_recommend_our_company_count;

        $data['your_experience_with_key_titles'] = $your_experience_with_key_titles;
        $data['your_experience_with_key_count'] = $your_experience_with_key_count;

        $data['main_section'] = 'survey';
        $data['inner_section'] = 'oasis_survey_reports';
        return view('admin/survey/oasis_survey_reports', $data);
    }


    public function getAllEmojis()
    {
        $page = new Page();
        $rows = array();
        $records = $page->getAll('survey_emoji', 'sort_col');
        foreach ($records as $record) {
            $rows[] = $record;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function saveData(Request $request)
    {
        $group = new DropoffCharges();
        $data = $request->input();
        $data = custom::isNullToEmpty($data);
        if ($data['applies_to'] == "") {
            $data['applies_to'] = null;
        }
        $result = $group->checkIfConflictDataExist($data);
        if (count($result) > 0) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record already exist with this data and date range.';
        } else {
            $id = $group->saveData($data);
            if ($id > 0) {
                custom::log('Drop-off Charges', 'add');
            }
            $responseData = $group->get_single_record($id);
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        }
        print json_encode($jTableResult);

    }

    public function updateData(Request $request)
    {
        $group = new DropoffCharges();
        $id = $request->input('id');
        $data = $request->input();
        unset($data['id']);
        $result = $group->checkIfConflictDataExist($data);
        if (count($result) > 0) {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record already exist with this data and date range.';
        } else {
            $id = $group->updateData($data, $id);
            custom::log('Drop-off Charges', 'update');
            $responseData = $group->get_single_record($id);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        }
        print json_encode($jTableResult);

    }

    public function deleteData(Request $request)
    {
        $id = $request->input('id');
        $group = new DropoffCharges();
        $group->deleteData($id);
        custom::log('Drop-off Charges', 'delete');
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    public function getAllSurveyCategories()
    {
        $emoji_id = $_REQUEST['emoji_id'];
        $page = new Page();
        $rows = array();
        $records = $page->getMultipleRows('survey_category', array('emoji_id' => $emoji_id), 'sort_col');
        foreach ($records as $record) {
            $rows[] = $record;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function saveSurveyCategoryData(Request $request)
    {
        $page = new Page();
        $data = $request->input();
        $data = custom::isNullToEmpty($data);

        if (isset($data['is_other_type'])) {
            $data['is_other_type'] = 'yes';
        } else {
            $data['is_other_type'] = 'no';
        }

        if (isset($data['publish'])) {
            $data['publish'] = 'yes';
        } else {
            $data['publish'] = 'no';
        }

        $id = $page->saveData('survey_category', $data);
        if ($id > 0) {
            custom::log('Survey Categories', 'add');
        }
        $responseData = $page->getSingleRow('survey_category', array('id' => $id));
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function updateSurveyCategoryData(Request $request)
    {
        $page = new Page();
        $id = $request->input('id');
        $data = $request->input();
        unset($data['id']);

        if (isset($data['is_other_type'])) {
            $data['is_other_type'] = 'yes';
        } else {
            $data['is_other_type'] = 'no';
        }

        if (isset($data['publish'])) {
            $data['publish'] = 'yes';
        } else {
            $data['publish'] = 'no';
        }
        $id = $page->updateData('survey_category', $data, array('id' => $id));
        custom::log('Survey Categories', 'update');
        $responseData = $page->getSingleRow('survey_category', array('id' => $id));
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);
    }

    public function deleteSurveyCategoryData(Request $request)
    {
        $id = $request->input('id');
        $page = new Page();
        $page->deleteData('survey_category', array('id' => $id));
        custom::log('Survey Categories', 'delete');
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    public function getAllSurveyCategoryOptions()
    {
        $category_id = $_REQUEST['category_id'];
        $page = new Page();
        $rows = array();
        $records = $page->getMultipleRows('survey_category_options', array('category_id' => $category_id), 'sort_col');
        foreach ($records as $record) {
            $rows[] = $record;
        }
        $recordCount = count($rows);
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['TotalRecordCount'] = $recordCount;
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);
    }

    public function saveSurveyCategoryOptionData(Request $request)
    {
        $page = new Page();
        $data = $request->input();
        $data = custom::isNullToEmpty($data);

        if (isset($data['publish'])) {
            $data['publish'] = 'yes';
        } else {
            $data['publish'] = 'no';
        }

        $id = $page->saveData('survey_category_options', $data);
        if ($id > 0) {
            custom::log('Survey Categories Options', 'add');
        }
        $responseData = $page->getSingleRow('survey_category_options', array('id' => $id));
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);

    }

    public function updateSurveyCategoryOptionData(Request $request)
    {
        $page = new Page();
        $id = $request->input('id');
        $data = $request->input();
        unset($data['id']);

        if (isset($data['publish'])) {
            $data['publish'] = 'yes';
        } else {
            $data['publish'] = 'no';
        }
        $id = $page->updateData('survey_category_options', $data, array('id' => $id));
        custom::log('Survey Categories Options', 'update');
        $responseData = $page->getSingleRow('survey_category_options', array('id' => $id));
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Record'] = $responseData;
        print json_encode($jTableResult);
    }

    public function deleteSurveyCategoryOptionData(Request $request)
    {
        $id = $request->input('id');
        $page = new Page();
        $page->deleteData('survey_category_options', array('id' => $id));
        custom::log('Survey Categories Options', 'delete');
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    public function exportSurveyData(Request $request)
    {
        $survey = new Survey();
        $yourFileName = 'export-' . date('d-m-y-H-i-s');
        $data_for_export = array();
        $filter_date_start = $request->input('start_date');
        $filter_date_end = $request->input('end_date');
        $survey_data = $survey->getAllQuestionsForSurvey($filter_date_start,$filter_date_end);
        foreach ($survey_data as $survey_datum) {
            $data_for_export[] = $survey_datum;
        }

        return custom::excelExport($yourFileName, $data_for_export);
    }

    public function exportOasisSurveyData(Request $request)
    {
        set_time_limit(0);
        $survey = new Survey();
        $yourFileName = 'export-' . date('d-m-y-H-i-s');
        $data_for_export = array();
        $filter_date_start = date('Y-m-d', strtotime($request->input('start_date')));
        $filter_date_end = date('Y-m-d', strtotime($request->input('end_date')));

        //$filter_date_start = "";
        //$filter_date_end = "";

        if(isset($_GET['start_date'])) $filter_date_start = date('Y-m-d', strtotime($_GET['start_date']));
        if(isset($_GET['end_date'])) $filter_date_end = date('Y-m-d', strtotime($_GET['end_date']));

        $survey_data = $survey->getAllQuestionsForOasisSurvey($filter_date_start,$filter_date_end);
        foreach ($survey_data as $survey_datum) {
            $data_for_export[] = $survey_datum;
        }

        return custom::excelExport($yourFileName, $data_for_export);
    }

}

?>