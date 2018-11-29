<?php
/**
 * Online Controller
 *
 * @category OnlineController
 * @package  Controller
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Session;

/**
 * Online Controller
 *
 * @category Class
 * @package  OnlineController
 */
class OnlineController extends Controller
{
    /**
     * Function for displaying active users list.
     *
     * @return String
     */
    public function index()
    {
        $data = [];

        $activeUsersList = Session::whereNull('end')->groupBy('userid')->get();
        $activeUsers = $activeUsersList->count();
        $activeMachinesList = Session::whereNull('end')->groupBy('ip')->get();
        $activeMachines = $activeMachinesList->count();

        //@todo - get active machines, for thsi store user ip when he logged in

        $data['activeUsers'] = $activeUsers;
        $data['activeMachines'] = $activeMachines;
        return view('admin.online.list', $data);
    }

    /**
     * Function for active users list pagination.
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
                            2=> 'ip',
                            3=> 'start',
                            //4=> 'id',
                        );
        $totalData = Session::join('user', 'session.userid', '=', 'user.id')
                      ->select('session.start', 'session.end', 'user.firstname', 'user.lastname', 'user.email')
                      ->whereNull('session.end')
                      ->count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = ($request->input('order.0.column') == 0)? 'firstname' : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if (empty($request->input('search.value'))) {            
            $acitveUsers = Session::join('user', 'session.userid', '=', 'user.id')
                      ->select('session.start', 'session.end', 'session.ip', 'user.firstname', 'user.lastname', 'user.email')
                      ->whereNull('session.end')
                      ->offset($start)
                      ->limit($limit)
                      ->orderBy($order, $dir)
                      ->get();
        } else {
            $search = $request->input('search.value'); 

            $acitveUsers =  Session::join('user', 'session.userid', '=', 'user.id')
                      ->select('session.start', 'session.end', 'session.ip', 'user.firstname', 'user.lastname', 'user.email')
                      ->whereNull('session.end')
                    ->where(
                        function ($q) use ($search) {
                            $q->where('session.id', 'LIKE', "%{$search}%")
                                ->orWhere('user.firstname', 'LIKE', "%{$search}%")
                                ->orWhere('user.lastname', 'LIKE', "%{$search}%");
                        }
                    )
                      //->where('session.id', 'LIKE', "%{$search}%")
                      //->orWhere('user.firstname', 'LIKE', "%{$search}%")
                      //->orWhere('user.lastname', 'LIKE', "%{$search}%")
                      ->offset($start)
                      ->limit($limit)
                      ->orderBy($order, $dir)
                      ->get();

            $totalFiltered = Session::join('user', 'session.userid', '=', 'user.id')
                              ->select('session.start', 'session.end', 'session.ip', 'user.firstname', 'user.lastname', 'user.email')
                            ->whereNull('session.end')
                            ->where(
                                function ($q) use ($search) {
                                    $q->where('session.id', 'LIKE', "%{$search}%")
                                        ->orWhere('user.firstname', 'LIKE', "%{$search}%")
                                        ->orWhere('user.lastname', 'LIKE', "%{$search}%");
                                }
                            )
                            //->where('session.id', 'LIKE', "%{$search}%")
                            //->orWhere('user.firstname', 'LIKE', "%{$search}%")
                            //->orWhere('user.lastname', 'LIKE', "%{$search}%")
                            ->count();
        }

        $data = [];
        if (!empty($acitveUsers)) {
            foreach ($acitveUsers as $acitveUser) {
                $nestedData['DT_RowId'] = 'row_'.$acitveUser->id;
                $nestedData['name'] = $acitveUser->firstname.' '.$acitveUser->lastname;
                $nestedData['email'] = $acitveUser->email;
                $nestedData['ip'] = $acitveUser->ip;
                $nestedData['start'] = $acitveUser->start;
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
}
