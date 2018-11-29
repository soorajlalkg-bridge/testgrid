<?php
/**
 * Nodes Controller
 *
 * @category NodesController
 * @package  Controller
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Session;
use App\Node;

/**
 * Nodes Controller
 *
 * @category Class
 * @package  NodesController
 */
class NodesController extends Controller
{
    /**
     * Function for displaying nodes list.
     *
     * @return String
     */
    public function index()
    {
        $data = [];

        return view('admin.nodes.list', $data);
    }

    /**
     * Function for nodes list pagination.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function page(Request $request)
    {
        $columns = array( 
                           // 0 =>'id', 
                            0=> 'ip',
                        );
        $totalData = Node::count();

        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if (empty($request->input('search.value'))) {            
            $nodes = Node::offset($start)
                      ->limit($limit)
                      ->orderBy($order, $dir)
                      ->get();
        } else {
            $search = $request->input('search.value'); 

            $nodes =  Node::where('ip', 'LIKE', "%{$search}%")
                      ->offset($start)
                      ->limit($limit)
                      ->orderBy($order, $dir)
                      ->get();

            $totalFiltered = Node::where('ip', 'LIKE', "%{$search}%")
                            ->count();
        }

        $data = [];
        if (!empty($nodes)) {
            foreach ($nodes as $node) {
                $nestedData['DT_RowId'] = 'row_'.$node->id;
                //$nestedData['name'] = $acitveUser->firstname.' '.$acitveUser->lastname;
                //$nestedData['email'] = $acitveUser->email;
                $nestedData['ip'] = $node->ip;
                $nestedData['actions'] = '<a href="nodes/edit/'.$node->id.'" class="btn btn-xs btn-primary  edit-user" title="Edit"><i class="fa fa-edit"></i></a>
                	';
                	//<a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm-node" title="Delete"><i class="fa fa-remove"></i></a>
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
        return view('admin.nodes.create', $data);
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
        $this->validate(
            $request, [
            'ip' => 'required|ipv4|unique:node',
            ]
        );

        $input['ip'] = $request->get('ip');
        $user = Node::create($input);

        return redirect('/admin/nodes')->with('success', 'New node has been created!');
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
        $node = Node::where('id', $id)
                        ->first();

        return view('admin.nodes.edit', compact('node', 'id'));
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
            'ip' => 'required|ipv4|unique:node,ip,'.$id
            ]
        );

        $node = Node::find($id);
        $node->ip = $request->get('ip');
        $node->save();

        return redirect('/admin/nodes')->with('success', 'Node has been updated!!');
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
        $node = Node::find($id);

        //@todo - nodestatus, channel, channelstatus, configchannel
        /*
		$q = 'delete nodestatus FROM `nodestatus` where nodeid=?';        
        $status = \DB::delete($q, array($id));
		
        $node->channel()->delete();

        $q = 'delete channel FROM channel inner join `channelstatus` on channelstatus.channelid=channel.id inner join config on configgroup.configid=config.id where config.userid=?';        
        $status = \DB::delete($q, array($id));

        $q = 'delete userlog FROM `userlog` inner join session on userlog.sessionid=session.id where session.userid=?';        
        $status = \DB::delete($q, array($id));

        $user->session()->delete();*/

        //$node->delete();

        return response()->json(['status' => 'success', 'message'=>'Node has been deleted!!'], 200);
    }

}
