<?php
/**
 * Configure Controller
 *
 * @category ConfigureController
 * @package  Controller
 */

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Node;
use App\Log;
use App\User;
use App\Config;
use App\ConfigGroup;
use App\ConfigChannel;
use App\Channel;
use App\StreamUrl;
use App\Library\CustomLog;
use Carbon\Carbon;
use File;

/**
 * Configure Controller
 *
 * @category Class
 * @package  ConfigureController
 */
class ConfigureController extends Controller
{
    /**
     * Function for displaying configure page.
     *
     * @return String
     */
    public function index()
    {
        $data = [];
        $currentUser = \Auth::guard('user')->user();

        //load channel data
        $channels = Channel::orderBy('index', 'asc')->get();
        $data['channels'] = $channels;

        //set default config
        $defaultCfgFile = (object)['name' => config('constants.config.default'),'numgroups' => '1','numrows'=>'1','numcols'=>'1'];
        //get config data from DB
        $cfgFiles = Config::where('userid', '=', $currentUser->id)->orderBy('name', 'asc')->get();
        $cfgFilesCount = $cfgFiles->count();
        $data['defaultCfgFile'] = $defaultCfgFile;
        $data['cfgFiles'] = $cfgFiles;
        $data['cfgFilesCount'] = $cfgFilesCount;

        $gridConfig = session('gridConfig');
        $gridConfigGroup = session('gridConfigGroup');
        $latestCfgFile = null;
        if ($gridConfig) {
            $latestCfgFile = Config::where('id', '=', $gridConfig)->where('userid', '=', $currentUser->id)->orderBy('id', 'desc')->first();
        }
        if (empty($latestCfgFile)) {
            //get last config data
            $latestCfgFile = Config::where('userid', '=', $currentUser->id)->orderBy('id', 'desc')->first();
        }
        //get first config group data
        if ($latestCfgFile) {
            $firstCfgGroup = $latestCfgFile->configgroup->first();
            if ($gridConfig && $gridConfigGroup) {
                $firstCfgGroup = $latestCfgFile->configgroup()->find($gridConfigGroup);
            }
            $latestCfgFile->firstconfiggroup = $firstCfgGroup;
            $channels = null;
            $firstCfgGroupId = null;
            if ($firstCfgGroup) {
                $firstCfgGroupId = $firstCfgGroup->id;
                //first config group channels
                $channels = ConfigChannel::where('configgroupid', '=', $firstCfgGroupId)->orderBy('position', 'asc')->orderBy('column_position', 'ASC')->get();
            } else {

            }
            $latestCfgFile->selectedCfgGroupId = $firstCfgGroupId;
            $latestCfgFile->channels = $channels;

            //set session
            session(['gridConfig' => $latestCfgFile->id]);
            session(['gridConfigGroup' => $firstCfgGroupId]);
        }

        $data['latestCfgFile'] = $latestCfgFile;

        return view('user.configure.index', $data);
    }

    /**
     * Function for import and export cfg file.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function actionConfigure(Request $request)
    {
        $successMsg = '';
        $errorMsg = '';
        $configId = $request->get('configid');
        $currentUser = \Auth::guard('user')->user();
        //echo '<pre>';print_r($request->all());exit;

        switch ($request->input('action')) {

        case 'import':
            $response = $this->importCfg($request);
            $resp = json_decode($response->content());
            if ($resp->status == 'success') {
                //save log
                CustomLog::saveLog('', 'import config');

                $successMsg = 'Configuration has been imported successfully!!';
            } else {
                $errorMsg = $resp->errors[0];
            }
            break;

        case 'export':
            if ($configId) {
                //create config file and download
                $cfgFile = Config::with('configgroup')->where('id', '=', $configId)->where('userid', '=', $currentUser->id)->first();
                if ($cfgFile) {
                        
                    $cfgGroupData = [];
                    $data = ['name'=>'Gridview Configuration', 'version'=>'1.0', 'configs'=>$cfgGroupData];

                    if ($cfgFile->configgroup) {
                        foreach ($cfgFile->configgroup as $key1=>$configGroup) {
                            $cfgGroupData[$key1]['num_rows'] = $configGroup->numrows;
                            $cfgGroupData[$key1]['num_cols'] = $configGroup->numcols;
                            $cfgGroupData[$key1]['channels'] = [];
                           
                            //@todo - export in position, column position order
                            $configChannels = ConfigChannel::where('configgroupid', '=', $configGroup->id)->orderBy('position', 'asc')->orderBy('column_position', 'ASC')->get();
                            foreach ($configChannels as $key2=>$configChannel) {
                                //foreach ($configGroup->configchannel as $key2=>$configChannel) {
                                $cfgGroupData[$key1]['channels'][$key2]['label'] = !empty($configChannel->label)?$configChannel->label:'';
                                $cfgGroupData[$key1]['channels'][$key2]['url'] = !empty($configChannel->url)?$configChannel->url:'';
                                $cfgGroupData[$key1]['channels'][$key2]['node_ip'] = isset($configChannel->channel)?$configChannel->channel->node->ip:'';
                                $cfgGroupData[$key1]['channels'][$key2]['channel_index'] = isset($configChannel->channel)?$configChannel->channel->index:'';
                            }
                        }
                        $data['configs'] = $cfgGroupData;
                    }
                        
                    $ext = pathinfo($cfgFile->name, PATHINFO_EXTENSION);

                    $cfgData = json_encode($data);
                    $file = ($ext=='cfg')?$cfgFile->name:$cfgFile->name.'.cfg';
                    $destinationPath=base_path()."/cfg-uploads/";

                    if (!is_dir($destinationPath)) {  
                        mkdir($destinationPath, 0777, true);  
                    }
                    File::put($destinationPath.$file, $cfgData);

                    //save log
                    CustomLog::saveLog('', 'export config');

                    //return response()->download($destinationPath.$file);
                    //delete after download
                    return response()->download($destinationPath.$file)->deleteFileAfterSend(true);
                }
                $errorMsg = 'Configuration doesnot exist.';
            } else {
                $errorMsg = 'Please save current configuration.';
            }
            break;
        case 'delete':
            if ($configId) {
                $config = Config::with('configgroup')->where('id', '=', $configId)->where('userid', '=', $currentUser->id)->first();

                $config->delete();
                
                //unset session
                session()->forget('gridConfigGroup');
                session()->forget('gridConfig');

                //save log
                CustomLog::saveLog('', 'delete config');

                $successMsg = 'Configuration has been deleted successfully!!';
            } else {
                $errorMsg = 'Couldn\'t delete current configuration.';
            }
            break;
        }
        return redirect('user/configure')->with('success', $successMsg)->with('error', $errorMsg);
    }
    
    /**
     * Function for import cfg file.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function importCfg(Request $request)
    {
        $currentUser = \Auth::guard('user')->user();
        
        //@todo - validate mime type, allow cfg, sql, json type
        $fileExt = '';
        if ($request->hasFile('importcfg')) {
            $fileExt = $request->file('importcfg')->getClientOriginalExtension();
        }
        
        $validator = \Validator::make(
            $request->all(), [
            'importcfg' => 'required|cfg_type:'.$fileExt.'',
            ]
        );
        
        if ($validator->passes()) {
            if ($request->hasFile('importcfg')) {
                $cfgFile = $request->file('importcfg');

                $fileName = $cfgFile->getClientOriginalName();
                $filePath = $cfgFile->getPathName();
                $fileExt = $cfgFile->getClientOriginalExtension();

                $maxGroups = config('constants.options.max_groups');
                $maxRows = config('constants.options.max_rows');
                $maxCols = config('constants.options.max_cols');

                //@todo - check file already uploaded
                $checkCfgFileExists = Config::where('name', '=', $fileName)->where('userid', '=', $currentUser->id)->first();
                if ($checkCfgFileExists) {
                    return response()->json(['status'=>'failure','errors'=>['Configuration with same name is already imported.']]);
                }

                //read full data
                $cfgFullData = file_get_contents($cfgFile->getRealPath());
                //print_r(json_decode($cfgFullData));
                $cfgData  = json_decode($cfgFullData);
                //if json
                if (isset($cfgData->configs)) {
                    $cfgConfigGroups = $cfgData->configs;
                    $numGroups = count($cfgConfigGroups);

                    if ($numGroups > $maxGroups) {
                        return response()->json(['status'=>'failure','errors'=>['Maximum allowed group count is '.$maxGroups.'.']]);
                    }

                    //save new config
                    $config['userid'] = $currentUser->id;
                    $config['name'] = $fileName;
                    $config['numgroups'] = $numGroups;
                    $configResult = Config::create($config);
                    $configId = $configResult->id;

                    //save configgroup
                    if ($configId) {
                        //loop configs
                        $groupIndex = 1;
                        foreach ($cfgConfigGroups as $cfgConfigGroup) {
                            $numRows = (int) $cfgConfigGroup->num_rows;
                            $numCols = (int) $cfgConfigGroup->num_cols;

                            $numRows = ($numRows>$maxRows) ? $maxRows:$numRows;
                            $numCols = ($numCols>$maxCols) ? $maxCols:$numCols;

                            $configgroup['configid'] = $configId;
                            $configgroup['groupindex'] = $groupIndex;
                            $configgroup['numrows'] = $cfgConfigGroup->num_rows;
                            $configgroup['numcols'] = $cfgConfigGroup->num_cols;
                            $configGroupResult = ConfigGroup::create($configgroup);
                            $configGroupId = $configGroupResult->id;

                            //The node ip and index need to be specified to be able to recreate the node and channel table data if not already existing
                            if (isset($cfgConfigGroup->channels) && $numRows>0 && $numCols>0) {
                                $cfgConfigGroupChannels = $cfgConfigGroup->channels;
                                $channelsCount = count($cfgConfigGroupChannels);
                                $expChannelsCount = $numRows*$numCols;
                                $rowNumber = 1;
                                $colNumber = 1;
                                //$numCols = $cfgConfigGroup->num_cols;
                                $channelNumber = 1;
                                foreach ($cfgConfigGroupChannels as $cfgConfigGroupChannel) {
                                    
                                    //check node ip exists
                                    $nodeIp = $cfgConfigGroupChannel->node_ip;
                                    $node = Node::where('ip', '=', $nodeIp)->first();
                                    if ($node === null) {
                                        //create new node
                                        $node = Node::create(['ip'=>$nodeIp]);
                                    }
                                    $nodeId = $node->id;

                                    //check channel index exists
                                    $channelIndex = $cfgConfigGroupChannel->channel_index;
                                    $channel = Channel::where('index', '=', $channelIndex)->where('nodeid', '=', $nodeId)->first();
                                    if ($channel === null) {
                                        //create new channel
                                        $channel = Channel::create(['index'=>$channelIndex, 'nodeid'=>$nodeId]);
                                    }
                                    $channelId = $channel->id;

                                    //save configchannel
                                    $configchannel['configgroupid'] = $configGroupId;
                                    $configchannel['groupindex'] = $groupIndex;//@todo - what is groupindex here
                                    $configchannel['position'] = $rowNumber; //@todo - position means row number
                                    $configchannel['column_position'] = $colNumber;
                                    $configchannel['label'] = $cfgConfigGroupChannel->label;
                                    $configchannel['channelid'] = $channelId;
                                    $configchannel['url'] = $cfgConfigGroupChannel->url;
                                    $configChannelResult = ConfigChannel::create($configchannel);

                                    if ($channelNumber%$numCols == 0) {
                                        $rowNumber++;
                                        $colNumber = 0;
                                    }

                                    $colNumber++;
                                    $channelNumber++;

                                    //if channel greater than numrows
                                    if ($rowNumber > $numRows) {
                                        break;
                                    }
                                }

                                //check if channels are less than expected count
                                if ($channelsCount < $expChannelsCount) {
                                    $newChannlesCount = $expChannelsCount - $channelsCount;

                                    for ($i=0;$i<$newChannlesCount;$i++) {
                                        //echo 'rowNumber: '.$rowNumber.', colNumber: '.$colNumber;exit;
                                        //save configchannel
                                        $configchannel['configgroupid'] = $configGroupId;
                                        $configchannel['groupindex'] = $groupIndex;
                                        $configchannel['position'] = $rowNumber; //@todo - position means row number
                                        $configchannel['column_position'] = $colNumber;
                                        $configchannel['label'] = null;
                                        $configchannel['channelid'] = null;
                                        $configchannel['url'] = null;
                                        ConfigChannel::create($configchannel);
                                        
                                        if ($channelNumber%$numCols == 0) {
                                            $rowNumber++;
                                            $colNumber = 0;
                                        }

                                        $colNumber++;
                                        $channelNumber++;
                                    }
                                }
                                //echo 'rowNumber: '.$rowNumber.', colNumber: '.$colNumber;exit;
                            }

                            $groupIndex++;
                        }

                        //set session
                        session(['gridConfig' => $configId]);
                        session()->forget('gridConfigGroup');                        
                    }

                    return response()->json(['status' => 'success', 'message'=>'Config file has been imported successfully.'], 200);
                }
                return response()->json(['status'=>'failure','errors'=>['Couldn\'t read configuration file.']]);

            }
            return response()->json(['status'=>'failure','errors'=>['Please import configuration file.']]);
        }
        return response()->json(['status'=>'failure','errors'=>$validator->errors()->all()]);
    }

     /**
      * Function for create new cfg file.
      *
      * @param \Illuminate\Http\Request $request All request parameters.
      *
      * @return String
      */
    public function createConfig(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
            'name' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9]{1}[a-zA-Z0-9 ._\-@]+$/u',
            'config-action'=>'required'
            ]
        );

        if ($validator->passes()) {
            $currentUser = \Auth::guard('user')->user();

            $configName = $request->get('name');
            $numGroups = 1;

            //@todo - check same name exists
            $checkNameExists = Config::where('name', '=', $configName)->where('userid', '=', $currentUser->id)->first();
            if ($checkNameExists) {
                return response()->json(['status'=>'failure','errors'=>['name'=>['Same configuration name already exists.']]]);
            }

            //create basic cfg with 1 x 1 group
            //save new config
            $config['userid'] = $currentUser->id;
            $config['name'] = $configName;
            $config['numgroups'] = $numGroups;
            $configResult = Config::create($config);
            $configId = $configResult->id;

            if ($configId) {
                $cfgFile = $this->addBasicConfigGroup($configId);
                $cfgFile['name'] = $configName;
                $cfgFile['id'] = $configId;
                

                //save log
                CustomLog::saveLog('', 'save config');

                //set session
                session(['gridConfig' => $configId]);

                return response()->json(['status'=>'success','message'=>'Config has been created successfully.','data'=>$cfgFile]);
            }

            return response()->json(['status'=>'failure','errors'=>['Failed to save configuration.']]);
            
        }
        return response()->json(['status'=>'failure','errors'=>$validator->getMessageBag()->toArray()]);
    }

    /**
     * Function for update config.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function updateConfig(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
            'configid' => 'required',
            'numrows'=>'required',
            'numcols'=>'required',
            'channels'=>'required'
            ],
            ['channels.required' => 'Please add channels.']
        );

        if ($validator->passes()) {
            $currentUser = \Auth::guard('user')->user();

            //@todo - save config
            $configId = $request->get('configid');
            $configGroupId = $request->get('configgroupid');
            $configChannels = $request->get('channels');
            //echo '<pre>';print_r($request->all());exit;
            
            //update configgroup
            $configgroup = ConfigGroup::find($configGroupId);
            $configgroup->numrows       = $request->get('numrows');
            $configgroup->numcols       = $request->get('numcols');
            $configgroup->save();

            $configChannelsCount = count($configgroup->configchannel);
            $channelsId = [];

            if ($configId && $configGroupId) {
                
                
                foreach ($configChannels as $row=>$configChannel) {
                    foreach ($configChannel as $col=>$channel) {
                        $currentConfigChannel = null;
                        if ($col == 1) {
                            $currentConfigChannel = ConfigChannel::where('configgroupid', '=', $configGroupId)->where('position', '=', $row)->where('column_position', '=', $col)->first();
                        } else {
                            $skip = $col-1;
                            $currentConfigChannel = ConfigChannel::where('configgroupid', '=', $configGroupId)->where('position', '=', $row)->where('column_position', '=', $col)->skip($skip)->take(1)->first();
                        }

                        $configchannel['label'] = $channel['channellabel'];
                        $configchannel['channelid'] = $channel['channel'];
                        $configchannel['url'] = isset($channel['channelurl'])?$channel['channelurl']:null;
                        $configchannel['position'] = $row;
                        $configchannel['column_position'] = $col;

                        if ($currentConfigChannel) {
                            //update
                            ConfigChannel::where('id', $currentConfigChannel->id)
                              ->update($configchannel); 

                            $channelsId[] = $currentConfigChannel->id;
                            
                        } else {
                            //new
                            //$configchannel['position'] = $row; //@todo - position means row number
                            $configchannel['configgroupid'] = $configGroupId;
                            $configchannel['groupindex'] = $configgroup->groupindex;//@todo - what is groupindex here
                            //print_r($configchannel);
                            $configChannelResult = ConfigChannel::create($configchannel);

                            $channelsId[] = $configChannelResult->id;
                        }

                    }
                }

                //remove other channels 
                ConfigChannel::where('configgroupid', '=', $configGroupId)
                            ->whereNotIn('id', $channelsId)
                            ->delete();
            }

            //save log
            CustomLog::saveLog('', 'save config');

            return response()->json(['status'=>'success','message'=>'Config has been saved successfully.']);
        }

        return response()->json(['status'=>'failure','errors'=>$validator->errors()->all()]);
    }

    /**
     * Function for copy config from another.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function copyConfig(Request $request)
    {
        
        $validator = \Validator::make(
            $request->all(), [
            'name' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9]{1}[a-zA-Z0-9 ._\-@]+$/u',
            'numrows'=>'required',
            'numcols'=>'required',
            'channels'=>'required',
            'groupindex'=>'required',
            //'configid'=>'required',
            //'configgroupid'=>'required',
            ]
        );

        if ($validator->passes()) {
            $currentUser = \Auth::guard('user')->user();

            $configName = $request->get('name');
            $numGroups = 1;

            //@todo - check same name exists
            $checkNameExists = Config::where('name', '=', $configName)->where('userid', '=', $currentUser->id)->first();
            if ($checkNameExists) {
                return response()->json(['status'=>'failure','errors'=>['Same configuration name already exists.']]);
            }

            
            //check config exists, 
            $originalConfigId = $request->get('configid');
            $originalConfigGroupId = $request->get('configgroupid');
            $originalCfgFile = Config::where('id', '=', $originalConfigId)->where('userid', '=', $currentUser->id)->first();
            if ($originalCfgFile) {
                $numGroups = $originalCfgFile->numgroups;
            }

            $configId = '';
            $configGroupId = '';
            $configChannels = $request->get('channels');
            $groupIndex = $request->get('groupindex');

            //save new config
            $config['userid'] = $currentUser->id;
            $config['name'] = $configName;
            $config['numgroups'] = $numGroups;//$originalCfgFile->numgroups; //($request->get('numgroups'))?$request->get('numgroups'):1;
            $configResult = Config::create($config);
            $configId = $configResult->id;

            if ($configId) {

                $cfgFile['name'] = $configName;
                $cfgFile['id'] = $configId;

                if ($originalCfgFile) {
                    //clone all groups to new config
                    //get all config group data
                    $originalCfgGroups = $originalCfgFile->configgroup;
                    //loop it
                    foreach ($originalCfgGroups as $cfgGroup) {
                        if ($cfgGroup->id != $originalConfigGroupId) {
                            //save configgroup
                            $configgroup['configid'] = $configId;
                            $configgroup['groupindex'] = $cfgGroup->groupindex;
                            $configgroup['numrows'] = $cfgGroup->numrows;
                            $configgroup['numcols'] = $cfgGroup->numcols;
                            $configGroupResult = ConfigGroup::create($configgroup);
                            $configGroupId = $configGroupResult->id;

                            $originalCfgGroupChannels = $cfgGroup->configchannel;
                            //loop it
                            foreach ($originalCfgGroupChannels as $originalCfgGroupChannel) {
                                //save configchannel
                                $configchannel['configgroupid'] = $configGroupId;
                                $configchannel['groupindex'] = $originalCfgGroupChannel->groupindex;
                                $configchannel['position'] = $originalCfgGroupChannel->position;
                                $configchannel['column_position'] = $originalCfgGroupChannel->column_position;
                                $configchannel['label'] = $originalCfgGroupChannel->label;
                                $configchannel['channelid'] = $originalCfgGroupChannel->channelid;
                                $configchannel['url'] = $originalCfgGroupChannel->url;
                                ConfigChannel::create($configchannel);
                            }
                        }
                    }
                }

                //save configgroup
                $configgroup['configid'] = $configId;
                $configgroup['groupindex'] = $groupIndex;
                $configgroup['numrows'] = $request->get('numrows');
                $configgroup['numcols'] = $request->get('numcols');
                $configGroupResult = ConfigGroup::create($configgroup);
                $configGroupId = $configGroupResult->id;

                if ($configGroupId) {
                    foreach ($configChannels as $row=>$configChannel) {
                        foreach ($configChannel as $col=>$channel) {
                            //save configchannel
                            $configchannel['configgroupid'] = $configGroupId;
                            $configchannel['groupindex'] = $groupIndex;
                            $configchannel['position'] = $row;//position means row number
                            $configchannel['column_position'] = $col;
                            $configchannel['label'] = $channel['channellabel'];
                            $configchannel['channelid'] = $channel['channel'];
                            $configchannel['url'] = isset($channel['channelurl'])?$channel['channelurl']:null;
                            $configChannelResult = ConfigChannel::create($configchannel);

                        }
                    }
                    $cfgFile['configgroupid'] = $configGroupId;
                }

                //save log
                CustomLog::saveLog('', 'save config');

                //set session
                session(['gridConfig' => $configId]);
                session()->forget('gridConfigGroup');

                return response()->json(['status'=>'success','message'=>'Config has been saved successfully.','data'=>$cfgFile]);
            }
                
            //}
            return response()->json(['status'=>'failure','errors'=>['Failed to save configuration.']]);
            
        }
        return response()->json(['status'=>'failure','errors'=>$validator->getMessageBag()->toArray()]);
    }

    /**
     * Function for getting config.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function getConfig(Request $request)
    {
        $currentUser = \Auth::guard('user')->user();
        
        $validator = \Validator::make(
            $request->all(), [
            'configid' => 'required',
            ],
            ['configid.required'=>'Config does not exist.']
        );
        if ($validator->passes()) {
            //@todo - get channel info
            $configId = $request->get('configid');
            //get config data
            $cfgFile = Config::with('configgroup')->where('id', $configId)->where('userid', '=', $currentUser->id)->first();

            if ($cfgFile == null) {
                return response()->json(['status'=>'failure','errors'=>['Config does not exist.']]);
            }

            //set session
            session(['gridConfig' => $configId]);
            session()->forget('gridConfigGroup');

            return response()->json(['status'=>'success', 'data'=>$cfgFile]);
        }
        return response()->json(['status'=>'failure','errors'=>$validator->errors()->all()]);
    }

    /**
     * Function for getting config groups.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function getConfigGroup(Request $request)
    {
        $currentUser = \Auth::guard('user')->user();
        
        $validator = \Validator::make(
            $request->all(), [
            'configid' => 'required',
            'configgroupid'=>'required',
            ],
            ['configid.required'=>'Config does not exist.',
            'configgroupid.required'=>'Config group does not exist.',
            ]
        );
        if ($validator->passes()) {
            //@todo - get channel info
            $configId = $request->get('configid');
            $configGroupId = $request->get('configgroupid');
            //get last config data
            $cfgFile = Config::where('id', $configId)->where('userid', '=', $currentUser->id)->first();
            
            if ($cfgFile == null) {
                return response()->json(['status'=>'failure','errors'=>['Config does not exist.']]);
            }

            $cfgGroup = [];
            //@todo - get channel info and channel urls
            $cfgGroup = $cfgFile->configgroup()->with('configchannel')->find($configGroupId);

            if ($cfgGroup == null) {
                return response()->json(['status'=>'failure','errors'=>['Config group does not exist.']]);
            }

            //set session
            session(['gridConfigGroup' => $cfgGroup->id]);
            session(['gridConfig' => $cfgGroup->configid]);

            return response()->json(['status'=>'success', 'data'=>$cfgGroup]);
        }
        return response()->json(['status'=>'failure','errors'=>$validator->errors()->all()]);
    }

    /**
     * Function for delete config group.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function createConfigGroup(Request $request)
    {
        $currentUser = \Auth::guard('user')->user();
        
        $validator = \Validator::make(
            $request->all(), [
            'configid' => 'required',
            ]
        );
        if ($validator->passes()) {
            $configId = $request->get('configid');

            $cfgFile = [];

            $config = Config::where('id', $configId)->where('userid', '=', $currentUser->id)->first();
            
            if ($config) {
                
                $cfgFile = $this->addBasicConfigGroup($configId);

                

                //@todo - send total count of groups
                $configData = Config::where('id', $configId)->where('userid', '=', $currentUser->id)->first();
                $numGroups = $configData->configgroup->count();
                
                //@update numrows
                $config->numgroups = $numGroups;
                $config->save();

                $cfgFile['numgroups'] = $numGroups;

                return response()->json(['status' => 'success', 'message'=>'Config group has been created successfully.','data'=>$cfgFile], 200);
            }

            return response()->json(['status'=>'failure','errors'=>['You are not allowed to create configuration group.']]);
        }
        return response()->json(['status'=>'failure','errors'=>$validator->errors()->all()]);
    }

    /**
     * Function for delete config group.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function deleteConfigGroup(Request $request)
    {
        $currentUser = \Auth::guard('user')->user();
        
        $validator = \Validator::make(
            $request->all(), [
            'configid' => 'required',
            'configgroupid'=>'required',
            ]
        );
        if ($validator->passes()) {
            $configId = $request->get('configid');
            $configGroupId = $request->get('configgroupid');

            $cfgFile['configGroupId'] = $configGroupId;

            $config = Config::where('id', $configId)->where('userid', '=', $currentUser->id)->first();
            
            if ($config) {
                $configgroup = ConfigGroup::where('configid', '=', $configId)->where('id', '=', $configGroupId)->first();

                $configgroup->delete();

                //@todo - send total count of groups
                $configData = Config::where('id', $configId)->where('userid', '=', $currentUser->id)->first();
                $numGroups = $configData->configgroup->count();

                //@update numrows
                $config->numgroups = $numGroups;
                $config->save();

                $cfgFile['numgroups'] = $numGroups;

                return response()->json(['status' => 'success', 'message'=>'Config group has been deleted successfully.','data'=>$cfgFile], 200);
            }

            return response()->json(['status'=>'failure','errors'=>['You are not allowed to delete this configuration group.']]);
        }
        return response()->json(['status'=>'failure','errors'=>$validator->errors()->all()]);
    }

    /**
     * Function for add/rmove multiple config groups.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function addRemoveConfigGroup(Request $request)
    {
        $currentUser = \Auth::guard('user')->user();
        
        $validator = \Validator::make(
            $request->all(), [
            'configid' => 'required',
            'numgroups'=>'required|integer|min:1|digits_between: 1,250',
            ]
        );
        if ($validator->passes()) {
            $configId = $request->get('configid');
            $newNumGroups = $request->get('numgroups');

            $config = Config::where('id', $configId)->where('userid', '=', $currentUser->id)->first();
            
            if ($config) {
                

                $numGroupsDiff = $newNumGroups - $config->numgroups;

                if ($numGroupsDiff > 0) {
                    //add groups with default data
                    //loop it
                    for ($i=0; $i<$numGroupsDiff; $i++) {

                        $this->addBasicConfigGroup($configId);
                    }

                    $message = 'Config group has been added successfully.';

                } else if ($numGroupsDiff <0) {
                    $latestnumGroupsCount = abs($numGroupsDiff);
                    //remove last groups
                    \DB::table('configgroup')
                    ->where('configid', '=', $configId)
                    ->orderBy('groupindex', 'DESC')
                    ->take($latestnumGroupsCount)
                    ->delete();

                    $message = 'Config group has been deleted successfully.';
                }

                //@todo - send total count of groups
                $config = Config::where('id', $configId)->where('userid', '=', $currentUser->id)->first();
                $numGroups = $config->configgroup->count();
                $configGroup = $config->configgroup;

                //@update numrows
                $config->numgroups = $numGroups;
                $config->save();

                $cfgFile['configgroup'] = $configGroup;
                $cfgFile['numgroups'] = $numGroups;

                return response()->json(['status' => 'success', 'message'=>$message,'data'=>$cfgFile], 200);
            }

            return response()->json(['status'=>'failure','errors'=>['You are not allowed to change this configuration.']]);
        }
        return response()->json(['status'=>'failure','errors'=>$validator->errors()->all()]);
    }

    /**
     * Function for add/rmove basic config group.
     *
     * @param Int $configId config id.
     *
     * @return String
     */
    private function addBasicConfigGroup($configId) 
    {
        //@todo - get last groupindex
        $groupIndex = 1;

        $latestConfigGroup = ConfigGroup::where('configid', '=', $configId)->orderBy('groupindex', 'desc')->first();

        if ($latestConfigGroup) {
            $groupIndex = $latestConfigGroup->groupindex + 1;
        }

        //create config group with 1 x 1 
        //@todo - save configgroup
        $numRows = 1;
        $numCols = 1;

        //save configgroup
        $configgroup['configid'] = $configId;
        $configgroup['groupindex'] = $groupIndex;
        $configgroup['numrows'] = $numRows;
        $configgroup['numcols'] = $numCols;
        $configGroupResult = ConfigGroup::create($configgroup);
        $configGroupId = $configGroupResult->id;

        //save configchannel
        $configchannel['configgroupid'] = $configGroupId;
        $configchannel['groupindex'] = $groupIndex;
        $configchannel['position'] = 1;
        $configchannel['column_position'] = 1;
        $configchannel['label'] = null;
        $configchannel['channelid'] = null;
        $configchannel['url'] = null;

        ConfigChannel::create($configchannel);

        $cfgGroup['id'] = $configGroupId;
        $cfgGroup['groupindex'] = $groupIndex;
        $cfgGroup['numrows'] = $numRows;
        $cfgGroup['numcols'] = $numCols;

        return $cfgGroup;

    }

    /**
     * Function for swap config group rows and columns.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function swapConfigGroupChannels(Request $request)
    {
        $currentUser = \Auth::guard('user')->user();
        
        $validator = \Validator::make(
            $request->all(), [
            'configid' => 'required',
            'configgroupid'=>'required',
            ],
            ['configid.required'=>'Config does not exist.',
            'configgroupid.required'=>'Config group does not exist.',
            ]
        );
        if ($validator->passes()) {
            $configId = $request->get('configid');
            $configGroupId = $request->get('configgroupid');
            //get config data
            $cfgFile = Config::where('id', $configId)->where('userid', '=', $currentUser->id)->count();
            
            if ($cfgFile == null) {
                return response()->json(['status'=>'failure','errors'=>['Config does not exist.']]);
            }

            $cfgGroup = [];
            $cfgGroup = ConfigGroup::with('configchannel')->where('id', $configGroupId)->where('configid', '=', $configId)->first();

            if ($cfgGroup == null) {
                return response()->json(['status'=>'failure','errors'=>['Config group does not exist.']]);
            }

            //swap rows and columns of groups and change rows,column positions
            if ($cfgGroup->numrows != $cfgGroup->numcols) {
                $configgroup = ConfigGroup::find($configGroupId);
                $configgroup->numrows       = $cfgGroup->numcols;
                $configgroup->numcols       = $cfgGroup->numrows;
                $configgroup->save();

                $configChannelsCount = count($configgroup->configchannel);
                $channelsId = [];
                $rowNumber = 1;
                $colNumber = 1;
                $channelNumber = 1;
                $numCols = $cfgGroup->numrows;

                if ($configChannelsCount > 0) {
                    foreach ($cfgGroup->configchannel as $configChannel) {
                        $configchannel = ConfigChannel::find($configChannel->id);
                        $configchannel->position        = $rowNumber;
                        $configchannel->column_position = $colNumber;
                        $configchannel->save();
                        if ($channelNumber%$numCols == 0) {
                            $rowNumber++;
                            $colNumber = 0;
                        }

                        $colNumber++;
                        $channelNumber++;
                    }
                }
            }

            return response()->json(['status'=>'success', 'data'=>$cfgGroup, 'message'=>'Rows and columns are switched successfully.']);
        }
        return response()->json(['status'=>'failure','errors'=>$validator->errors()->all()]);
    }
}
