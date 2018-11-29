<?php
/**
 * Logs Controller
 *
 * @category LogsController
 * @package  Controller
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserLog;
use Response;
use PDF;

/**
 * Logs Controller
 *
 * @category Class
 * @package  LogsController
 */
class LogsController extends Controller
{
    /**
     * Function for displaying logs list.
     *
     * @return String
     */
    public function index()
    {
        $data = [];
        $data['datatable_buttons'] = true;

        // $sessionInfo = UserLog::find(1)->session;
        // echo '<pre>';print_r($sessionInfo);

        return view('admin.logs.list', $data);
    }

    /**
     * Function for logs list pagination.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function page(Request $request)
    {
        $columns = array( 
                           // 0 =>'id', 
                            0 =>'name',
                            1=> 'email',
                            2=> 'log',
                            3=> 'created_at',
                            4=> 'timestamp',
                            //4=> 'id',
                        );
        $totalData = UserLog::join('session', 'userlog.sessionid', '=', 'session.id')
                      ->join('action', 'userlog.action', '=', 'action.id')
                      ->join('user', 'session.userid', '=', 'user.id')
                      ->select('userlog.*', 'session.start', 'session.end', 'action.action_type', 'user.firstname', 'user.lastname', 'user.email')
                      ->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        //$order = $columns[$request->input('order.0.column')];
        $order = ($request->input('order.0.column') == 0) ? 'firstname' : ( ($request->input('order.0.column') == 1) ? 'email': ( ($request->input('order.0.column') == 2) ? 'action_type' : (($request->input('order.0.column') == 3) ? 'id' : $columns[$request->input('order.0.column')]) ) );
        $dir = $request->input('order.0.dir');
            
        if (empty($request->input('search.value'))) {            
            $logs = UserLog::join('session', 'userlog.sessionid', '=', 'session.id')
                      ->join('action', 'userlog.action', '=', 'action.id')
                      ->join('user', 'session.userid', '=', 'user.id')
                      ->select('userlog.*', 'session.start', 'session.end', 'action.action_type', 'user.firstname', 'user.lastname', 'user.email')
                      ->offset($start)
                      ->limit($limit)
                      ->orderBy($order, $dir)
                      ->get();
        } else {
            $search = $request->input('search.value'); 

            $logs =  UserLog::join('session', 'userlog.sessionid', '=', 'session.id')
                      ->join('action', 'userlog.action', '=', 'action.id')
                      ->join('user', 'session.userid', '=', 'user.id')
                      ->select('userlog.*', 'session.start', 'session.end', 'action.action_type', 'user.firstname', 'user.lastname', 'user.email')
                      ->where('userlog.id', 'LIKE', "%{$search}%")
                      ->orWhere('action.action_type', 'LIKE', "%{$search}%")
                      ->orWhere('user.firstname', 'LIKE', "%{$search}%")
                      ->orWhere('user.lastname', 'LIKE', "%{$search}%")
                      ->orWhere('user.email', 'LIKE', "%{$search}%")
                      ->offset($start)
                      ->limit($limit)
                      ->orderBy($order, $dir)
                      ->get();

            $totalFiltered = UserLog::join('session', 'userlog.sessionid', '=', 'session.id')
                              ->join('action', 'userlog.action', '=', 'action.id')
                              ->join('user', 'session.userid', '=', 'user.id')
                              ->select('userlog.*', 'session.start', 'session.end', 'action.action_type', 'user.firstname', 'user.lastname', 'user.email')
                              ->where('userlog.id', 'LIKE', "%{$search}%")
                              ->orWhere('action.action_type', 'LIKE', "%{$search}%")
                              ->orWhere('user.firstname', 'LIKE', "%{$search}%")
                              ->orWhere('user.lastname', 'LIKE', "%{$search}%")
                              ->orWhere('user.email', 'LIKE', "%{$search}%")
                              ->count();
        }

        $data = [];
        if (!empty($logs)) {
            foreach ($logs as $log) {
                $nestedData['DT_RowId'] = 'row_'.$log->id;
                $nestedData['name'] = $log->firstname.' '.$log->lastname;
                $nestedData['email'] = $log->email;
                $nestedData['log'] = $log->action_type;
                $nestedData['created_at'] = date('Y-m-d', strtotime($log->timestamp));//$log->start;//($log->action_type == 'logout')?$log->end:$log->start;// date('Y-m-d', strtotime($log->end)) : date('Y-m-d', strtotime($log->start));
                $nestedData['timestamp'] = date('h:i:s A', strtotime($log->timestamp));//$log->timestamp;
                $data[] = $nestedData;
            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data); 
    }

    /**
     * Function for export data.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function export(Request $request)
    {
        $type = $request->input('type');
        $search = $request->input('searchvalue');
      

        if (empty($search)) {
            $logs = UserLog::join('session', 'userlog.sessionid', '=', 'session.id')
                      ->join('action', 'userlog.action', '=', 'action.id')
                      ->join('user', 'session.userid', '=', 'user.id')
                      ->select('userlog.*', 'session.start', 'session.end', 'action.action_type', 'user.firstname', 'user.lastname', 'user.email')
                      ->orderBy('userlog.timestamp', 'desc')
                      ->get();
        } else {
            $logs = UserLog::join('session', 'userlog.sessionid', '=', 'session.id')
                      ->join('action', 'userlog.action', '=', 'action.id')
                      ->join('user', 'session.userid', '=', 'user.id')
                      ->select('userlog.*', 'session.start', 'session.end', 'action.action_type', 'user.firstname', 'user.lastname', 'user.email')
                      ->where('userlog.id', 'LIKE', "%{$search}%")
                      ->orWhere('action.action_type', 'LIKE', "%{$search}%")
                      ->orWhere('user.firstname', 'LIKE', "%{$search}%")
                      ->orWhere('user.lastname', 'LIKE', "%{$search}%")
                      ->orWhere('user.email', 'LIKE', "%{$search}%")
                      ->orderBy('userlog.timestamp', 'desc')
                      ->get();
        }

        if ($type == 'pdf') {
        
            $pdf = PDF::loadView('admin.logs.pdf', compact('logs'));
            return $pdf->download('userlog.pdf');
        } else { 

            $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=userlog.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
            );

            $columns = array('Name', 'Email', 'Action', 'Date', 'Time');

            $callback = function () use ($logs, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($logs as $log) {
                    fputcsv($file, array($log->firstname.' '.$log->lastname, $log->email, $log->action_type, date('Y-m-d', strtotime($log->timestamp)), date('h:i:s A', strtotime($log->timestamp))));
                }
                fclose($file);
            };
            return Response::stream($callback, 200, $headers);
        }
    }
}
