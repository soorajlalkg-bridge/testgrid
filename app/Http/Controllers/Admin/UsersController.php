<?php
/**
 * Users Controller
 *
 * @category UsersController
 * @package  Controller
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Hash;
use App\Library\Encrypt;

/**
 * Users Controller
 *
 * @category Class
 * @package  UsersController
 */
class UsersController extends Controller
{
    
    /**
     * Function for displaying users list.
     *
     * @return String
     */
    public function index()
    {
        $data = [];
        $data['userLicenseLimit'] = $this->getUserLicenseLimit();
        return view('admin.users.list', $data);
    }

    /**
     * Function for users list pagination.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function page(Request $request)
    {
        $columns = array( 
                           // 0 =>'id', 
                            0 =>'firstname',
                            1 =>'lastname',
                            2 =>'username',
                            3 => 'email',
                            4 => 'loginlimit',
                            5 => 'created_at',
                            6 => 'actions'
                            //4=> 'id',
                        );
        $totalData = User::count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if (empty($request->input('search.value'))) {            
            $users = User::offset($start)
                         ->limit($limit)
                         ->orderBy($order, $dir)
                         ->get();
        } else {
            $search = $request->input('search.value'); 

            $users =  User::where('id', 'LIKE', "%{$search}%")
                            ->orWhere('firstname', 'LIKE', "%{$search}%")
                            ->orWhere('lastname', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order, $dir)
                            ->get();

            $totalFiltered = User::where('id', 'LIKE', "%{$search}%")
                             ->orWhere('firstname', 'LIKE', "%{$search}%")
                            ->orWhere('lastname', 'LIKE', "%{$search}%")
                             ->orWhere('email', 'LIKE', "%{$search}%")
                             ->count();
        }

        $data = [];
        if (!empty($users)) {
            foreach ($users as $user) {
                //$csrf_token = csrf_token();

                $nestedData['DT_RowId'] = 'row_'.$user->id;
                //$nestedData['id'] = $user->id;
                $nestedData['firstname'] = $user->firstname;
                $nestedData['lastname'] = $user->lastname;
                $nestedData['username'] = $user->username;
                $nestedData['email'] = $user->email;
                $nestedData['loginlimit'] = $user->loginlimit;
                $nestedData['created_at'] = ($user->created_at)? date('Y-m-d h:i A', strtotime($user->created_at)):'';
                $nestedData['actions'] = '<a href="users/edit/'.$user->id.'" class="btn btn-xs btn-primary  edit-user" title="Edit"><i class="fa fa-edit"></i></a> 
                    <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm-user" title="Delete"><i class="fa fa-remove"></i></a>';
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
     * Function for displaying create user form page.
     *
     * @return String
     */
    public function create()
    {
        $data = [];
        $userLicenseLimitExceeded = $this->checkUserLicenseLimitExceeded();
        if ($userLicenseLimitExceeded == true) {
            return redirect('/admin/users')->with('failure', 'Maximum account limit has been reached!');
        }

        return view('admin.users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userLicenseLimitExceeded = $this->checkUserLicenseLimitExceeded();
        if ($userLicenseLimitExceeded == true) {
            return redirect('/admin/users')->with('failure', 'Maximum account limit has been reached!');
        }

        $this->validate(
            $request, [
            'username' => 'required|min:3|max:100|unique:user,username,NULL,id,deleted_at,NULL',
            'email' => 'email|required|min:3|max:100|unique:user,email,NULL,id,deleted_at,NULL',
            'firstname' => 'required|min:3|max:50',
            'lastname' => 'required|min:1|max:50',
            'password' => 'required|confirmed|min:4',
            'loginlimit' => 'required|numeric',
            ]
        );

        $input['firstname'] = $request->get('firstname');
        $input['lastname'] = $request->get('lastname');
        $input['email'] = $request->get('email');
        $input['username'] = $request->get('username');
        $input['pwhash'] = bcrypt($request->get('password'));
        $input['loginlimit'] = ($request->get('loginlimit'))?$request->get('loginlimit'):1;
        $user = User::create($input);

        return redirect('/admin/users')->with('success', 'New user has been created!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id userid
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id userid
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id', $id)
                        //->where('id', $id)
                        ->first();

        return view('admin.users.edit', compact('user', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request request
     * @param int                      $id      userid
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request, [
            'username' => 'required|min:3|max:100|unique:user,username,'.$id.',id,deleted_at,NULL',
            'email' => 'email|required|min:3|max:100|unique:user,email,'.$id.',id,deleted_at,NULL',
            'firstname' => 'required|min:3|max:50',
            'lastname' => 'required|min:1|max:50',
            'password' => 'confirmed',
            'loginlimit' => 'required|numeric',
            ]
        );

        $user = User::find($id);
        $user->firstname       = $request->get('firstname');
        $user->lastname       = $request->get('lastname');
        $user->email      = $request->get('email');
        $user->username      = $request->get('username');
        $user->loginlimit = $request->get('loginlimit');
        if ($request->has('password') && !empty($request->get('password'))) {
            $user->pwhash = bcrypt($request->get('password'));
        }
        $user->save();

        return redirect('/admin/users')->with('success', 'User info has been updated!!');
    }

     /**
      * Delete the specified resource in storage.
      *
      * @param \Illuminate\Http\Request $request request
      *
      * @return \Illuminate\Http\Response
      */
    public function delete(Request $request)
    {
        $this->validate(
            $request, [
            'id' => 'required|numeric',
            ]
        );
        
        $id = $request->get('id');
        $user = User::find($id);

        /*$q = 'delete configchannel FROM configchannel inner join `configgroup` on configchannel.configgroupid=configgroup.id inner join config on configgroup.configid=config.id where config.userid=?';        
        $status = \DB::delete($q, array($id));

        $q = 'delete configgroup FROM `configgroup` inner join config on configgroup.configid=config.id where config.userid=?';        
        $status = \DB::delete($q, array($id));

        $user->config()->delete();
        $q = 'delete userlog FROM `userlog` inner join session on userlog.sessionid=session.id where session.userid=?';        
        $status = \DB::delete($q, array($id));

        $user->session()->delete();*/

        $user->delete();

        return response()->json(['status' => 'success', 'message'=>'User has been deleted!!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id userid
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        
        $user->config()->configgroup()->configchannel()->delete();
        $user->config()->configgroup()->delete();
        $user->config()->delete();
        $user->session()->userlog()->delete();
        $user->session()->delete();

        $user->delete();

        return redirect('/admin/users')->with('success', 'User has been deleted!!');
    }

    /**
     * checking user license limit exceeded or not.
     *
     * @return Bool
     */
    private function checkUserLicenseLimitExceeded()
    {
        //get user active license
        $licenseExists = \Storage::disk('local')->exists('.ual');
        if (!$licenseExists) {
            return config('constants.options.ual');
        }
        $ul = \Storage::get('.ual');
        //$decUAL = Encrypt::decrypt($ul);
        $decUAL = Encrypt::decryptIt($ul);
        $ual = (int) $decUAL;
        $totalUser = User::count();
        if ($totalUser >= $ual) {
            return true;
        }
        return false;
    }

    /**
     * get user license limit.
     *
     * @return Bool
     */
    private function getUserLicenseLimit()
    {
        //get user active license
        $licenseExists = \Storage::disk('local')->exists('.ual');
        if (!$licenseExists) {
            return config('constants.options.ual');
        }
        $ul = \Storage::get('.ual');
        //$decUAL = Encrypt::decrypt($ul);
        $decUAL = Encrypt::decryptIt($ul);
        $ual = (int) $decUAL;
        return $ual;
    }
}
