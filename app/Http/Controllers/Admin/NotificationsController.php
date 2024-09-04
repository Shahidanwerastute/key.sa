<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Notifications;
use App\Helpers\Custom;
use Unifonic\API\Exception;
use DB;

class NotificationsController extends Controller
{
    public function index()
    {
        if (!custom::rights(55, 'view')) {
            return redirect('admin/dashboard');
        }
        $data['main_section'] = 'settings';
        $data['inner_section'] = 'notifications';
        return view('admin/settings/notifications', $data);
    }

    public function getAll()
    {
        $rows = array();
        $obj = new Notifications();
        $records = $obj->getAll();
        foreach ($records as $record) {
            $record->total_active_device_tokens = custom::total_active_device_tokens();
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
        $obj = new Notifications();
        $data = $request->input();
        $data['status'] = 'Running';
        $data['created_at'] = date('Y-m-d H:i:s');

        // marking previously running notification as completed and saving there sent count
        $this->mark_previously_running_notification_as_completed();

        $id = $obj->saveData($data);
        if ($id > 0) {
            custom::log('Settings Notifications', 'add');
            $responseData = $obj->getSingle($id);
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $responseData;
        } else {
            $jTableResult['Result'] = "ERROR";
            $jTableResult['Message'] = 'Record failed to get saved. Please try again.';
        }

        print json_encode($jTableResult);

    }

    public function deleteData(Request $request)
    {
        $id = $request->input('id');
        $obj = new Notifications();
        $obj->deleteData($id);
        custom::log('Settings Notifications', 'delete');
        $jTableResult['Result'] = "OK";
        print json_encode($jTableResult);

    }

    private function mark_previously_running_notification_as_completed() {
        $running_notification = Notifications::where('status', 'Running')->first();
        if ($running_notification) {
            $notification_sent_to = custom::notification_sent_to($running_notification->id);
            $update_data['notification_sent_status'] = $notification_sent_to;
            $update_data['status'] = 'Completed';
            Notifications::where('id', $running_notification->id)->update($update_data);
        }
    }

    public function send_notification(Request $request) {
        try {
            $notification_detail = Notifications::where('id', $request->notification_id)->first();
            if ($notification_detail) {
                ini_set('max_execution_time', 6000);
                $title = $notification_detail->title;
                $message = $notification_detail->body;

                $chunk_size = $request->chunk_size; // no of notifications to send at a time, this is coming from a hidden input field in notification view
                $total_tokens = DB::table('device_token')->where('token_status', 'Active')->where('fcm_token', '!=', 'null')->where('push_notifications_log_id', '<', $request->notification_id)->count();
                $total_no_of_pages = ceil($total_tokens / $chunk_size);
                for ($i = 0; $i < $total_no_of_pages; $i++) {
                    $tokens = [];
                    $tokens_list = DB::table('device_token')->where('token_status', 'Active')->where('fcm_token', '!=', 'null')->where('push_notifications_log_id', '<', $request->notification_id)->offset($i * $chunk_size)->limit($chunk_size)->get();
                    foreach ($tokens_list as $t) {
                        $tokens[] = $t->fcm_token;
                    }
                    $res = custom::sendPushNotification($title, $message, $tokens, 0, "general_notification");
                    if ($res['success'] > 0) {
                        DB::table('device_token')->whereIn('fcm_token', $tokens)->update(['push_notifications_log_id' => $request->notification_id]);

                        $notification_sent_to = custom::notification_sent_to($request->notification_id);
                        $update_data['notification_sent_status'] = $notification_sent_to;
                        Notifications::where('id', $request->notification_id)->update($update_data);
                    }
                }
                $response['status'] = true;
                $response['message'] = "Notifications sent successfully.";
                echo json_encode($response);
                exit();
            }
            $response['status'] = false;
            $response['message'] = "Something went wrong.";
            echo json_encode($response);
            exit();
        } catch (\Exception $e) {
            $response['status'] = false;
            $response['message'] = $e->getMessage();
            echo json_encode($response);
            exit();
        }
    }


}

?>