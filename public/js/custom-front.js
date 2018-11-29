var numGroupsId = '#num-groups';
var numRowsId = '#num-rows';
var numColsId = '#num-cols';
var nextGroupBtnId = '#next-group-btn';
var prevGroupBtnId = '#prev-group-btn';
var createGroupBtnId = '#create-group-btn';
var deleteGroupBtnId = '#delete-group-btn';
var swapRowsColsBtn = '#swap-rows-cols';
var configGroupsId = '#config-groups';
var configureTableId = '#configure-table';
var channelsListId = '#channels-list';
var configFilesId = '#config-files';
var configFormId = '#configForm';
var triggerImportBtn = '#trigger-import';
var importCfgBtn = '#import-cfg';
var createCfgBtn = '#create-config';
var updateCfgBtn = '#update-config';
var copyCfgBtn = '#copy-config';
var deleteCfgBtn = '#delete-config';
var configSubmitBtn = '#ajax-config-submit';
var configFormId = '#configForm';
var createConfigModal = '#createConfigModal';
var loginModal = '#loginModal';
var accountModal = '#accountModal';
var confirmDeleteModal = '#confirmDeleteModal';
var loadingImageId = '#loading-image';
var disableSaveBtn = true;
var configureDataTableId = '#configure-table1';
var configDataTable;
var gridNextGroupBtnId = '#grid-next-group-btn';
var gridPrevGroupBtnId = '#grid-prev-group-btn';
var gridConfigGroupsId = '#grid-config-groups';
var videoOuterClass = '.video-outer';
var gridPlayerModeId = '#grid-player-mode';
var gridPlayerModeDynamicId = '#grid-player-mode-dynamic';
var windowWidth = null;
var toggleFullScreenBtn = '#toggle-full-screen';
var videoScroll = 'video-scroll';
var videoScrollClass = '.'+videoScroll;
var videoScrollId = '#'+videoScroll;
var toggleAutoPlayId = '#toggle-autoplay';
var gridFormId = '#gridForm';
var ajaxAccountSubmitBtn = '#ajax-account-submit';
var ajaxLoginSubmitBtn = '#ajax-login-submit';
var deleteConfigSubmitBtn = '#delete-config-submit';
var editAccountFormId = '#editAccountForm';
var configActionId = '#config-action';
var vPlayer = [];

var myAudio = [], audioCtx = [],audioSourceNode = [], idArr, webAudio=[], meterCtx=[], meterNode = [], showAverage=false;
var $canvas, $parent, audioGainInfo, audioSplitter = [], audioAnalyser = [], audioAnalyser2 = [], audioJavascriptNode = [], audioGainNode = [], meterGradient = [];
function init() {
	windowWidth = $(window).width();
}

init();

$(document).ready(function() {
	function initWebAudioApi(isFullscreenMode) {
		isFullscreenMode = isFullscreenMode || false;
		console.log('isFullscreenMode: '+isFullscreenMode);
		elementExists = document.getElementsByClassName('video-js');
		
		if (elementExists.length > 0) {
		    var menus = document.getElementsByClassName("video-js");

			
	        $('.video-js').each(function(j, obj) {
	            var videoId = this.id;
	            if (videoId) {
	            	idArr = videoId.split('-');
	            	if (idArr[1] != '') {
		                var i = idArr[1];
		                $canvas = $('#canvas-'+idArr[1]);
		                $parent = $canvas.parent();
		                if ($parent.width()!='undefined'){
		                	$canvas.width($parent.width());
		            	}
		            	if ($parent.height()!='undefined'){
		                	$canvas.height($parent.height());
		            	}
		            	
		            	$canvas.css({'padding':'5px 5px','max-height':$parent.height()+'px','max-width':$parent.width()+'px'});
		            	if ($parent.height() > 25) {
		            		$canvas.css('padding','10px 5px');
		            	}
		            	if (isFullscreenMode == true) {
		            		return true;
		            	}
		            	
		            	//if (audioCtx[i] == undefined) {
		            	$('#'+videoId).attr( 'data-vumeter-height', $parent.height() );
		            	//console.log('width: '+$parent.width()+' height: '+$parent.height());
		            	document.getElementById('canvas-'+idArr[1]).height = $parent.height();
		            	document.getElementById('canvas-'+idArr[1]).width = $parent.width();
		                meterCtx[i] = document.getElementById('canvas-'+idArr[1]).getContext('2d');//$canvas.get()[0].getContext('2d');
		                myAudio[i] = document.getElementById(videoId + '_html5_api');
		                audioCtx[i] = new (window.AudioContext || window.webkitAudioContext)();
		                //if (audioSourceNode[i] == undefined) {
		                audioSourceNode[i] = audioCtx[i].createMediaElementSource(myAudio[i]);
		            	//}
		                audioSplitter[i] = null;
		                audioAnalyser[i] = null;
		                audioAnalyser2[i] = null;
		                audioJavascriptNode[i] = null;
		                audioGainNode[i] = null;
		                meterGradient[i] = null;
		                meterCtx[i].clearRect(0, 0, document.getElementById('canvas-'+idArr[1]).width, document.getElementById('canvas-'+idArr[1]).height);

		                webAudio[i] = webAudioVUMeter();
		                audioCanvasInfo = webAudio[i].createMeterCanvas(meterCtx[i], {canvasWidth: $parent.width(), canvasHeight:$parent.height(), showAverage:showAverage});
		                meterGradient[i] = audioCanvasInfo.gradient;

		                audioGainInfo = webAudio[i].createMeter(meterCtx[i], audioCtx[i], audioSourceNode[i]);
		                audioGainNode[i] = audioGainInfo.gainNode;
		                //meterGradient[i] = audioGainInfo.gradient;
		                webAudio[i].createMeterNode(meterCtx[i], audioCtx[i], audioSourceNode[i], audioSplitter[i], audioAnalyser[i], audioAnalyser2[i], audioJavascriptNode[i], audioGainNode[i], meterGradient[i], {canvasWidth: $parent.width(), canvasHeight:$parent.height()});
		            	

		            	//}
		            	//audioGainNode[i].gain.value = 0; 
	            	}
	            }
	        });
		}
	}

	function initVideoJS() {
		if ($('.video-js').length > 0) {
			/*if ($(gridPlayerModeId).length > 0) {
	       		var playerMode = $(gridPlayerModeId).val();
	    		setPlayerWidth(playerMode);
	        }*/
	        if ($(gridPlayerModeDynamicId).length > 0) {
	       		var playerMode = $(gridPlayerModeDynamicId).val();
	    		setPlayerWidth(playerMode);
	        }
			$('.video-js').each(function() {

				var videoId = $(this).attr('id');
				var videoSrc = $(this).find('source').attr('src');
				
				if (videoSrc) {
					if(videojs.getPlayers()[videoId]) {
					    delete videojs.getPlayers()[videoId];
					}
					

					vPlayer[videoId] = videojs(videoId, {
					    controlBar: {
					     //muted: true
					    },
					    plugins: {
		                    customvolumebutton: {},
		                    customfullscreenbutton: {}
		                }
					}).ready(function() {
						if($(toggleAutoPlayId).prop('checked') == true){
					    	this.play();
						}
					    
					});
				}
			});
			initWebAudioApi();
		}
	}
	initVideoJS();

	$(toggleAutoPlayId).change(function(e) {
		
		var dataString = {autoplay:$(this).prop('checked')};

		$.ajax({
	      	url: SITE_URL+"user/toggleAutoPlay",
	      	method: 'post',
	        dataType: 'json',
	        headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        data: dataString,
		    success: function(result){
		      	if(result.status=='success') {
		      		var data = result.data;
		      		if (data.autoplay == 1) {
		      			//play
						$('.video-js').each(function() {

							var videoId = $(this).attr('id');
							
							if (vPlayer[videoId] != undefined) {
								vPlayer[videoId].play();
							}
						});

		      		} else {
		      			//pause
						$('.video-js').each(function() {

							var videoId = $(this).attr('id');
							
							if (vPlayer[videoId] != undefined) {
								vPlayer[videoId].pause();
							}
						});
		      		}
		      	} else {
		      		
		      	}
		  	},
	        complete: function() {
	           
	        }
		});
    });

	$(toggleFullScreenBtn).click(function (e) {
		e.preventDefault();
		
		$(videoScrollId).fullScreenHelper('request');
		$(videoScrollClass).css({'overflow':'auto', 'padding':'5px', 'background':'#000'});
	});

	$(document).on("fullscreenchange", function () {
	  if ($.fullScreenHelper("state")) {
	    
	    console.log("In fullscreen");
	    setPlayerWidthFullScreenMode();
	  } else {
	    console.log("Not in fullscreen");
	    $(videoScrollClass).removeClass('full-screen-helper')
	    //$(videoScrollClass).css('overflow', '');
	    //$(gridFormId).submit();
	    
	    $(videoScrollClass).css({'padding':'', 'background':''});
		$('.video-outer').css({'position':'', 'left':'', 'top':''});
		var playerMode = $(gridPlayerModeDynamicId).val();
		setPlayerWidth(playerMode);
		$('.my-vu-meter').each(function(){
			if ($(this).height() == 0) {
				setVUMeterWithPlayer();
				return false;
			}
		});
		initWebAudioApi(true);
	  }
	});

	//custom volume button js starts here
	$('body').on('input', '.vjs-custom-volume-level', function() {
        var volumeLevel = $(this).val();
        var videoId = $(this).parents('.video-js').find('video').attr('data-id');
        //console.log(videoId);
        audioGainNode[videoId].gain.value = parseFloat(volumeLevel/100);
    });

    $('body').on('click', '.vjs-custom-volume-mute-control', function() {
        var volumeLevel = $(this).parents('.video-js').find('.vjs-custom-volume-level').val();
        
        var videoId = $(this).parents('.video-js').find('video').attr('data-id');
        audioGainNode[videoId].gain.value = parseFloat(volumeLevel/100);
    });
    //custom volume button js ends here

    //custom fullscreen button js starts here
    $('body').on('click', '.vjs-custom-fullscreen-control', function() {

	    if ($.fullScreenHelper("state")) {
		    var videoId = $(this).parents('.video-js').attr('id'); 

		  	if (vPlayer[videoId] != undefined) {
		  		if ( $( this ).hasClass( "fullscreen-mode" ) ) {
			      vPlayer[videoId].requestFullscreen();
			    } else {
			      vPlayer[videoId].exitFullscreen();
			    }
		  	}

		    $(this).parents('.video-js').removeClass('full-screen-helper');
		    if ( !$( this ).hasClass( "fullscreen-mode" ) ) {
	    		setTimeout(function(){ 
	    			$('.vjs-fullscreen-control').attr('title', 'Fullscreen');
		    		$('.vjs-fullscreen-control').children('span').text('Fullscreen');
	    			$('.video-js').removeClass('vjs-fullscreen');
	    		}, 50);
	    	}
		}

	});
	//custom fullscreen button js ends here

	//fullscreen button js starts here
	$('body').on('click', '.vjs-fullscreen-control', function() {
		if ($.fullScreenHelper("state")) {
			if ($('.my-vu-meter').height() == 0) {
				setVUMeterWithPlayer();
			}
		} else {
			var audioCanvas, audioCanvasParent;
			var videoId = $(this).parents('.video-js').attr('id')
			if (videoId) {
				idArr = videoId.split('-');
				if (idArr[1] != '') {
					audioCanvas = $('#canvas-'+idArr[1]);
					audioCanvasParent = audioCanvas.parent();
					$('.video-js').attr( 'data-vumeter-height', audioCanvasParent.height() );
				}
			}
		}
	});
	//fullscreen button js ends here

	//datatable
	function initDataTable() {
		if ($(configureDataTableId).length > 0) {
			configDataTable = $(configureDataTableId).DataTable( {
			  "searching": false,
			  "ordering": false,
			  "displayStart": 0,
			  "columnDefs": [
		            {
		                "targets": [ 6 ],
		                "visible": false,
		                "searchable": false
		            }
		      ],
			  createdRow: function (row, data, dataIndex) {
			  		$(row).attr('id', 'row_'+data[0]+'_'+data[1]);
		            $(row).attr('data-row', data[0]);
		            $(row).attr('data-col', data[1]);
		            //console.log(data);
		            
		            $(row).find('.channel').val(data[6]);
		            //if ($(row).find('.channel option:selected').val() == '') {
		            if (data[6] == '' || data[6] == null) {
		            	$(row).find('.channelurl').prop('readonly', true);
		            } else {
		            	$(row).find('.channelurl').prop('readonly', false);
		            }
		        }
			} );
		}
	}
	initDataTable();

	$('[data-toggle="tooltip"]').tooltip({
		trigger : 'hover'
	});  
	closeAlertBox();

    function displayAlertBox(message, status) {
    	var alertClass = (status == 'failure')?'alert-danger':'alert-success';
    	$('.alert-message').html('<span>'+message+'</span>');
		$('.alert-message').removeClass('d-none').addClass(alertClass);
		
		closeAlertBox();
	}

	function closeAlertBox() {
		if ( !$('.alert-dismissible').hasClass('d-none') && $.trim($('.alert-dismissible span').html()) != '' ) {
			console.log('dismiss');
		    $(".alert-dismissible").fadeTo(2000, 500).slideUp(1000, function(){
			    $(".alert-dismissible").addClass('d-none');
		    	$('.alert-dismissible').html('<span></span>');
			});
		}
	}

	function toggleDisableSaveBtn() {
		if (disableSaveBtn == true) {
			$(updateCfgBtn).prop('disabled', true); 
		} else {
			$(updateCfgBtn).prop('disabled', false); 
		}
	}

	function validateChannelFields() {
		return true;
		/*$(configFormId).validate({
	        rules: {
	            numrows: {
			      required: true,
			      max: maxRows
			    },
	            numcols: {
			      required: true,
			      max: maxCols
			    }
	        },
	        messages: {
	            numrows: '',
	            numcols: ''

	        }
	    });

		/*$('.channellabel').each(function() {
	        $(this).rules('add', {
	            required: true,
	            messages: {
                    required: '',
                }
	        });
	    });
	    $('.channelurl').each(function() {
	        $(this).rules('add', {
	            url: true,
	            messages: {
                    url: '',
                }
	        });
	    });
	    $('.channel').each(function() {
	        $(this).rules('add', {
	            required: function (element) {
	            	return $(this).parents('tr').find('.channelurl').val()!='';  
	            },
	            messages: {
                    required: '',
                }
	        });
	    });*/
	    var validStatus = $(configFormId).valid();
	    return validStatus;
	}

	$('.modal-content').keypress(function(e){
	    if(e.which == 13) {
	      	e.preventDefault();
	      	if ($(loginModal).is(':visible')) {
	      		$(ajaxLoginSubmitBtn).trigger('click');
	      	} else if ($(accountModal).is(':visible')) {
	      		$(ajaxAccountSubmitBtn).trigger('click');
	      	} else if ($(createConfigModal).is(':visible')) {
	      		$(configSubmitBtn).trigger('click');
	      	}
	    }
	});

    //clear alert messages
    $(loginModal+', '+accountModal+', '+createConfigModal).on('hidden.bs.modal', function () {
	    $('.alert-danger, .alert-success').html('').addClass('d-none');	    
	    $('input').removeClass('invalid');
	    $('.invalid-error-msg').html('');
	    $('.modal input').val('');
	});

    $(createCfgBtn).on('click',function(e){
    	e.preventDefault();

    	$(createConfigModal+' '+configActionId).val('create');
    	$(createConfigModal).modal('show');
    });

	$(copyCfgBtn).on('click',function(e){
		e.preventDefault();

		var validStatus = validateChannelFields();
	    if (validStatus == false) {
	    	return false;
	    }
	    $(createConfigModal+' '+configActionId).val('copy');
	   	$(createConfigModal).modal('show');
	});

	$(configSubmitBtn).click(function(e){
  		var me = $(this);
  		$('.alert-danger, .alert-success').html('').addClass('d-none');

    	e.preventDefault();

    	if ( me.data('requestRunning') ) {
	        return;
	    }
	    me.data('requestRunning', true);

	    var configAction = $(configActionId).val();
	    if (configAction == 'create') {
	    	//create default config
	    	var dataString = $('#createConfigForm').serialize();

	    	$.ajax({
		      	url: SITE_URL+"user/createConfig",
		      	method: 'post',
		        dataType: 'json',
		        headers: {
		        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        data: dataString,
			    success: function(result){
			    	$('input').removeClass('invalid');
			      	if(result.status=='success') {
			      		var data = result.data;
			      		$(configFilesId+' option[value=""]').remove();
			      		$(configFilesId +' option:selected').prop('selected', false);
			      		$(configFilesId).append($("<option></option>").attr('value',data.id).text(data.name));
			      		$(configFilesId+' option[value="'+data.id+'"]').prop('selected', true);
			      		$(configFilesId).trigger('change');

			      		$(createConfigModal).modal('hide');
	            		setTimeout(function(){
		            		displayAlertBox(result.message, result.status);
		            	}, 500);
			      	} else {
			      		$('input[name="name"]').addClass('invalid');
			      		$('input[name="name"]').next('.invalid-error-msg').html(result.errors.name[0]);
		          		
		          		$('.alert-success').html('').addClass('d-none');
			      	}
			  	},
		        complete: function() {
		            me.data('requestRunning', false);
		        }
			});

	    } else {
    	
	    	var selConfigGroup = $(configGroupsId+' option:selected').text().match(/\d+/)[0];
	    	//var dataString = $(configFormId).serialize();
	    	//datatable
		   	var dataString1 = $(configFormId).serialize();
		   	var dataString2 = configDataTable.$('input, select').serialize();
		   	var dataString = dataString1 + "&" + dataString2;
	    	dataString += "&name=" + encodeURIComponent($('#config-name').val());
	    	dataString += "&groupindex=" + encodeURIComponent(selConfigGroup);
	    	
		  	$.ajax({
		      	url: SITE_URL+"user/copyConfig",
		      	method: 'post',
		        dataType: 'json',
		        headers: {
		        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        },
		        data: dataString,
			    success: function(result){
			    	$('input').removeClass('invalid');
			      	if(result.status=='success') {
			      		var data = result.data;
			      		$(configFilesId+' option[value=""]').remove();
			      		$(configFilesId +' option:selected').prop('selected', false);
			      		$(configFilesId).append($("<option></option>").attr("value",data.id).text(data.name));
			      		$(configFilesId+' option[value="'+data.id+'"]').prop("selected", true);
			      		$(configFilesId).trigger('change');

			      		$(createConfigModal).modal('hide');
	            		setTimeout(function(){
		            		displayAlertBox(result.message, result.status);
		            	}, 500);
			      	} else {
			      		$('input[name="name"]').addClass('invalid');
			      		$('input[name="name"]').next('.invalid-error-msg').html(result.errors.name[0]);
		          		//$('.alert-danger').removeClass('d-none');
		          		$('.alert-success').html('').addClass('d-none');
			      	}
			  	},
		        complete: function() {
		            me.data('requestRunning', false);
		        }
			});

	  	}
	});	

	$(updateCfgBtn).on('click',function(e){
		var me = $(this);
		e.preventDefault();
	   	
	   	if ( me.data('requestRunning') ) {
	        return;
	    }
	    me.data('requestRunning', true);

	   	//var dataString = $(configFormId).serialize();
	   	//datatable
	   	var dataString1 = $(configFormId).serialize();
	   	var dataString2 = configDataTable.$('input, select').serialize();
	   	var dataString = dataString1 + "&" + dataString2;

	    var validStatus = validateChannelFields();
	    if (validStatus == false) {
	    	return false;
	    }
	    me.text('Saving...');

	  	$.ajax({
	      	url: SITE_URL+"user/updateConfig",
	      	method: 'post',
	        dataType: 'json',
	        headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        data: dataString,
		    success: function(result){
		      	if(result.status=='success') {
		      		//$(configureDataTableId + ' tr').removeClass('edited-row');
		      		configDataTable.rows('.edited-row').nodes().to$().removeClass('edited-row');
		      		displayAlertBox(result.message, result.status);
		      	} else {
		      		displayAlertBox(result.errors[0], result.status);
		      	}
		  	},
		  	complete: function() {
	            me.data('requestRunning', false);
	            me.text('Save');
	            $(updateCfgBtn).prop('disabled', true);
	        }
		});
	});

	$(deleteCfgBtn).on('click',function(e){
		e.preventDefault();
		var configName = $(configFilesId+' option:selected').text();
		bootbox.confirm({
        	title: 'Confirm',
		    message: 'Are you sure you want to delete '+configName+'?',
		    buttons: {
		        confirm: {
		            label: 'Delete',
		            className: 'primary-btn'
		        },
		        cancel: {
		            label: 'Cancel',
		            className: 'cancel-btn'
		        }
		    },
		    callback: function (result) {
		    	if (result == true) {
		    		$(deleteConfigSubmitBtn).click();
		    	}
		    }
		});
	});

	//prevent entering non numeric values
	$(numRowsId+', '+numColsId).keypress(function (e) {
     	//if the letter is not digit then display error and don't type anything
     	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        	return false;
    	}
   });

	//save previous value of num rows and cols
	$(numRowsId+', '+numColsId).on('focusin', function(){
	    $(this).attr('data-val', $(this).val());
	});

	//prevent entering >32 value
	$(numRowsId+', '+numColsId).on('input', function () {	    
	    var value = $(this).val();	    
	    if ((value !== '') && (value.indexOf('.') === -1)) {	        
	        $(this).val(Math.max(Math.min(value, maxRows), -Math.abs(maxRows)));
	    }
	});

	//add or remove rows
	$(numRowsId).on('change',function(){
		//datatable
		var totalRows = configDataTable.rows().count();
		var lastIndex = totalRows-1;
		var lastRow = configDataTable.row(lastIndex).node();
		var lastRowNum = parseInt($(lastRow).data('row'));

		var numRows = parseInt($(this).val());		
		var prevNumRows = parseInt($(this).attr('data-val'));
		prevNumRows = isNaN(prevNumRows)? parseInt($(lastRow).data('row')):prevNumRows;
		var numCols = $(numColsId).val();
		var numNewRows = (numRows - prevNumRows);

		var channelOptions =  $(channelsListId).html(); 

		//get last row info
		var lastColNum = 0;
		var cols = '',newRow;

		lastRowNum = isNaN(lastRowNum)? 0:lastRowNum;

		var bootboxMsg = (numNewRows > 0)?'Are you sure you want to add '+numNewRows+' more rows?':'Are you sure you want to delete last '+Math.abs(numNewRows)+' rows?';
		var bootboxConfirmLabel = (numNewRows > 0)?'Add':'Delete';
		bootbox.confirm({
        	title: 'Confirm',
		    message: bootboxMsg,
		    buttons: {
		        confirm: {
		            label: bootboxConfirmLabel,
		            className: 'primary-btn'
		        },
		        cancel: {
		            label: 'Cancel',
		            className: 'cancel-btn'
		        }
		    },
		    callback: function (result) {
		    	if (result == true) {
		    		//@todo - add via ajax
		    		//add rows if greater
					if (numNewRows > 0) {
						for (var i=0; i< numNewRows; i++) {
							lastRowNum = lastRowNum+1;
							lastColNum = 0;
							for (var k=0; k< numCols; k++) {
								cols = '';
								lastColNum = lastColNum+1;
								
						        //datatable
						        configDataTable.row.add([
							            lastRowNum,
							            lastColNum,
							            '<input type="text" class="channellabel" name="channels['+lastRowNum+']['+lastColNum+'][channellabel]" placeholder="Enter Label">',
							            '<select class="form-control con-select channel" name="channels['+lastRowNum+']['+lastColNum+'][channel]">'+channelOptions+'</select>',
							            '',
							            '<input type="url" class="channelurl" name="channels['+lastRowNum+']['+lastColNum+'][channelurl]" placeholder="Enter Url">',
							            ''
							        ]).draw( false );
						    }

				    	}
					} else if (numNewRows < 0) {
						//remove last rows

						//datatable
						var rowCount = configDataTable.rows().count();
						var startIndex = rowCount + (numNewRows*numCols); 
						var lastIndex = rowCount - 1;
						for (var i=lastIndex;i>=startIndex;i--) {
							configDataTable.row(i).remove();
						}
						configDataTable.draw(false);
					}

					updateConfig();
		    	} else {
		    		$(numRowsId).val(prevNumRows);
			    	$(numRowsId).attr('data-val', prevNumRows);
		    	}
		    }
		});

		

	});

	//add or remove rows
	$(numColsId).on('change',function(){
		//datatable
		var totalRows = configDataTable.rows().count();
		var lastIndex = totalRows-1;
		var lastRow = configDataTable.row(lastIndex).node();
		//var lastRowCol = parseInt($(lastRow).data('col'));

		var numCols = parseInt($(this).val());
		if (isNaN(numCols)) return;
		var prevNumCols = parseInt($(this).attr('data-val')); //get old value
		
		prevNumCols = isNaN(prevNumCols)? parseInt($(lastRow).data('col')):prevNumCols;
		var numNewCols = numCols - prevNumCols;
		var numRows = $(numRowsId).val();

		var channelOptions =  $(channelsListId).html(); 

		var currentIndex,currentRow,currentRowId,currentRowNum=0,currentColNum=0,cols='',newRow='';

		

		var bootboxMsg = (numNewCols > 0)?'Are you sure you want to add '+numNewCols+' more columns to each rows?':'Are you sure you want to delete last '+Math.abs(numNewCols)+' columns of each rows?';
		var bootboxConfirmLabel = (numNewCols > 0)?'Add':'Delete';
		bootbox.confirm({
        	title: 'Confirm',
		    message: bootboxMsg,
		    buttons: {
		        confirm: {
		            label: bootboxConfirmLabel,
		            className: 'primary-btn'
		        },
		        cancel: {
		            label: 'Cancel',
		            className: 'cancel-btn'
		        }
		    },
		    callback: function (result) {
		    	if (result == true) {
		    		//add rows if greater
					if (numNewCols > 0) {
						if (totalRows > 0){
							console.log('one');
							

						    //datatable
						    $(configureDataTableId).DataTable().destroy();
						    $(configureDataTableId+' > tbody > tr').each(function(){
								currentRow = $(this);
								currentIndex = $(this).index();
								//get row info
								currentRowNum = parseInt(currentRow.data('row'));
								currentColNum = parseInt(currentRow.data('col'));

								newRow = '';
								//if last row and last column of this row
								if (prevNumCols == currentColNum) {
									for (var i=0; i< numNewCols; i++) {
										currentColNum = currentColNum+1;
										cols = '<tr id="row_'+currentRowNum+'_'+currentColNum+'" data-row="'+currentRowNum+'" data-col="'+currentColNum+'">';
										cols += '<td>'+currentRowNum+'</td>';
								        cols += '<td>'+currentColNum+'</td>';
								        cols += '<td><input type="text" class="channellabel" name="channels['+currentRowNum+']['+currentColNum+'][channellabel]" placeholder="Enter Label"></td>';
								        cols += '<td><select class="form-control con-select channel" name="channels['+currentRowNum+']['+currentColNum+'][channel]">'+channelOptions+'</select></td>';
								        cols += '<td></td>';
								        cols += '<td><input type="url" class="channelurl" name="channels['+currentRowNum+']['+currentColNum+'][channelurl]" placeholder="Enter Url"></td>';
								        cols += '<td></td>';
								       	newRow += cols;
							    	}
							    	currentRow.after(newRow);
						    	}
						    });
						    initDataTable();

						} else {
							console.log('second');
					        for (var i=0; i< numRows; i++) {
								currentRowNum = currentRowNum+1;
								currentColNum = 0;
								for (var k=0; k< numCols; k++) {
									cols = '';
									currentColNum = currentColNum+1;
									

							        //datatable
							        configDataTable.row.add([
							            currentRowNum,
							            currentColNum,
							            '<input type="text" class="channellabel" name="channels['+currentRowNum+']['+currentColNum+'][channellabel]" placeholder="Enter Label">',
							            '<select class="form-control con-select channel" name="channels['+currentRowNum+']['+currentColNum+'][channel]">'+channelOptions+'</select>',
							            '',
							            '<input type="url" class="channelurl" name="channels['+currentRowNum+']['+currentColNum+'][channelurl]" placeholder="Enter Url">',
							            ''
							        ]).draw( false );
							    }
					    	}
						}
						
					} else if (numNewCols < 0) {
						console.log('three');
						//remove last rows
						

						//datatable
					    var rowCount = configDataTable.rows().count();
					    for (var i=rowCount;i>=0;i--) {
					    	currentIndex = i;
					    	currentRow = configDataTable.row(currentIndex).node();
					    	//get row info
							currentRowNum = parseInt($(currentRow).data('row'));
							currentColNum = parseInt($(currentRow).data('col'));

							//if current row column greater than column value
							if (currentColNum > numCols) {
								configDataTable.row(i).remove();
							}				
					    }
					    configDataTable.draw(false);
					}

					updateConfig();
		    	} else {
		    		$(numColsId).val(prevNumCols);
			    	$(numColsId).attr('data-val', prevNumCols);
		    	}
		    }
		});
		
		

	});

	$(swapRowsColsBtn).on('click',function(e){
		e.preventDefault();
		var configGroupName = $(configGroupsId+' option:selected').text();
		bootbox.confirm({
        	title: 'Confirm',
		    message: 'Are you sure you want to swap rows and columns of '+configGroupName+'?',
		    buttons: {
		        confirm: {
		            label: 'Swap',
		            className: 'primary-btn'
		        },
		        cancel: {
		            label: 'Cancel',
		            className: 'cancel-btn'
		        }
		    },
		    callback: function (result) {
		    	if (result == true) {
					var configGroupId = $(configGroupsId).val();
					var configId = $(configFilesId).val();

					$(loadingImageId).show();

					$.ajax({
						url: SITE_URL+"user/swapConfigGroupChannels",
						method: 'post',
						dataType: 'json',
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						data: {'configid':configId, 'configgroupid':configGroupId},
						success: function(result){
							if(result.status=='success') {
								$(configGroupsId).trigger('change');
								displayAlertBox(result.message, result.status);
							} else {
								displayAlertBox(result.errors[0], result.status);
							}
						},
						complete: function() {
							$(loadingImageId).hide();
						}
					});
		    	}
		    }
		});
	});

	function updateConfig() {
		var configId = $(configFilesId).val();
		configId = (configId == '')? createDefaultConfig(configId):configId;
		var dataString1 = $(configFormId).serialize();
	   	var dataString2 = configDataTable.$('input, select').serialize();
	   	var dataString = dataString1 + "&" + dataString2;
	   	

	   	$(loadingImageId).show();
		$.ajax({
	      	url: SITE_URL+"user/updateConfig",
	      	method: 'post',
	        dataType: 'json',
	        headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        data: dataString,
		    success: function(result){
		      	
		  	},
		  	complete: function() {
	            
	            $(loadingImageId).hide();
	        }
		});
	}

	//ajax login
  	$(ajaxLoginSubmitBtn).click(function(e){

  		$('.alert-danger').html('').addClass('d-none');

    	e.preventDefault();
	  	$.ajax({
	      	url: SITE_URL+"user/loginAccount",
	      	method: 'post',
        	dataType: 'json',
        	headers: {
          		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        	},
	      	data: {
	         username: $('#login-username').val(),
	         password: $('#login-password').val()
	      	},
	      	statusCode: {
                419: function() {
                    window.location.reload();
                }
            },
	      	success: function(result){
	      		$('input').removeClass('invalid');
	      		$('.invalid-error-msg').html('');
		      	if(result.status=='success') {
		      		$('.alert-danger').html('').addClass('d-none');
	            	$(loginModal).modal('hide');
	            	location.href = result.redirect;
		      	} else {
		      		$.each(result.errors, function(key, value){
		      			if ($('input[name="'+key+'"]').length > 0) {
			      			$('input[name="'+key+'"]').addClass('invalid');
			      			$('input[name="'+key+'"]').next('.invalid-error-msg').html(value);
		      			} else {
		      				$('input[name="username"]').addClass('invalid');
			      			$('input[name="username"]').next('.invalid-error-msg').html(value);
		      			}
	          			
	          		});
	          		
		      	}
	  		}
	  	});
	});

  	//ajax profile update
  	$(ajaxAccountSubmitBtn).click(function(e){
  		
  		$('.alert-danger, .alert-success').html('').addClass('d-none');

    	e.preventDefault();
    	var dataString = $(editAccountFormId).serialize();
	  	
	  	$.ajax({
	      	url: SITE_URL+"user/saveProfile",
	      	method: 'post',
	        dataType: 'json',
	        headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        data: dataString,
		    success: function(result){
		    	$('input').removeClass('invalid');
		      	if(result.status=='success') {
		      		$(accountModal).modal('hide');
	            	setTimeout(function(){
	            		displayAlertBox(result.message, result.status);
	            	}, 500);
		      	} else {
		      		$.each(result.errors, function(key, value){
		      			if ($('input[name="'+key+'"]').length > 0) {
			      			$('input[name="'+key+'"]').addClass('invalid');
			      			$('input[name="'+key+'"]').next('.invalid-error-msg').html(value[0]);
		      			}
	          			
	          		});
	          		
	          		$('.alert-success').html('').addClass('d-none');
		      	}
		  	}
		});
	});

  	//ajax get profile info
	$('#account-link').click(function(e){
		$('.alert-danger').html('').addClass('d-none');

		$.ajax({
	      	url: SITE_URL+"user/getProfile",
	      	method: 'get',
	        dataType: 'json',
	        headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
		    success: function(result){
		      	if(result.status=='success') {
		      		$.each(result.user, function (key, value) {
		      			$(editAccountFormId+' input[name='+key+']').val(value);
		      		});
		      		$(accountModal).modal('show');
		      	} else {
		      		
		      	}
		  	}
		});

	});

	$(importCfgBtn).on('change', function(e) {
		e.preventDefault();
		$(triggerImportBtn).click();
	});

	//chnage config dropdown
	$(configFilesId).on('change', function(e) {
		e.preventDefault();
		var configId = $(this).val();
		$.ajax({
	      	url: SITE_URL+"user/getConfig",
	      	method: 'post',
	        dataType: 'json',
	        headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        data: {'configid':configId},
		    success: function(result){
		      	if(result.status=='success') {
		      		var data = result.data;
		      		$(numGroupsId).val(data.numgroups);
		      		//clear group list
		      		$(configGroupsId).empty();
		      		//loop data
		      		$.each(data.configgroup, function(i, configgroup) {
		      			$(configGroupsId).append(
					        $('<option></option>').val(configgroup.id).html('Group'+configgroup.groupindex)
					    );
		      		});

		      		$(configGroupsId).trigger('change');
		      	} else {
		      		displayAlertBox(result.errors[0], result.status);
		      	}
		  	}
		});
	});

	//change config group dropdwon
	$(configGroupsId).on('change', function(e) {
		e.preventDefault();
		$(configFormId).validate().cancelSubmit = true;
		var configGroupId = $(this).val();
		var configId = $(configFilesId).val();
		$(loadingImageId).show();
		$.ajax({
	      	url: SITE_URL+"user/getConfigGroup",
	      	method: 'post',
	        dataType: 'json',
	        headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        data: {'configid':configId, 'configgroupid':configGroupId},
		    success: function(result){
		      	if(result.status=='success') {
		      		disableSaveBtn = true;
	    			toggleDisableSaveBtn();

		      		var data = result.data;
		      		$(numRowsId).val(data.numrows);
		      		$(numColsId).val(data.numcols);
		      		var currentIndex,currentRow,currentRowId,currentRowNum=1,currentColNum=1,cols='',newRow='';
		      		var channelOptions =  $(channelsListId).html(); 
		      		//clear table
		      		
		      		//datatable
		      		configDataTable.clear().draw(false);

		      		//loop data
		      		$.each(data.configchannel, function(i, configchannel) {
		      			configchannel.label = (configchannel.label!=null)?configchannel.label:'';
		      			configchannel.url = (configchannel.url!=null)?configchannel.url:'';
		      			
					    

				        //datatable
				        var rowNode = configDataTable.row.add([
				            currentRowNum,
				            currentColNum,
				            '<input type="text" class="channellabel" name="channels['+currentRowNum+']['+currentColNum+'][channellabel]" placeholder="Enter Label" value="'+configchannel.label+'">',
				            '<select class="form-control con-select channel" name="channels['+currentRowNum+']['+currentColNum+'][channel]">'+channelOptions+'</select></td>',
				            '',
				            '<input type="url" class="channelurl" name="channels['+currentRowNum+']['+currentColNum+'][channelurl]" placeholder="Enter Url" value="'+configchannel.url+'" readonly="readonly">',
				            configchannel.channelid
				        ]).draw( false );
				        var selectedOption = $(configureDataTableId+' #row_'+currentRowNum+'_'+currentColNum+' .channel option[value="'+configchannel.channelid+'"]');
				        selectedOption.prop("selected", true);
				        $(configureDataTableId+' #row_'+currentRowNum+'_'+currentColNum+' td:eq(4)').text(selectedOption.attr('data-ip'));
				        

				        if (currentColNum >= data.numcols) {
				        	currentColNum = 1;
				        	currentRowNum = currentRowNum+1;
				        } else {
				        	currentColNum = currentColNum+1;
				        }
					});
		      		configDataTable.page( 'first' ).draw( 'page' );
		      	} else {
		      		displayAlertBox(result.errors[0], result.status);
		      	}
		  	},
		  	complete: function(){
	        	$(loadingImageId).hide();
	        }
		});
	});

	//prevent entering non numeric values
	$(numGroupsId).keypress(function (e) {
     	//if the letter is not digit then display error and don't type anything
     	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        	return false;
    	}
   	});

	//prevent entering >250 value
	$(numGroupsId).on('input', function () {	    
	    var value = $(this).val();	    
	    if ((value !== '') && (value.indexOf('.') === -1)) {	        
	        $(this).val(Math.max(Math.min(value, maxGroups), -Math.abs(maxGroups)));
	    }
	    
	});

	$(numGroupsId).on('focusin', function(){
	    $(this).attr('data-val', $(this).val());
	});

	//add groups by entering group number
	$(numGroupsId).on('change', function(e) {
		e.preventDefault();
		var numGroups = $(this).val();
		var prevNumGroups = $(this).attr('data-val');
		var groupDiff = numGroups - prevNumGroups;
		var configId = $(configFilesId).val();
		configId = (configId == '')? createDefaultConfig(configId):configId;


		if (groupDiff < 0) {
			groupDiff = Math.abs(groupDiff);
			bootbox.confirm({
	        	title: 'Confirm',
			    message: 'Are you sure you want to delete last '+groupDiff+' groups?',
			    buttons: {
			        confirm: {
			            label: 'Delete',
			            className: 'primary-btn'
			        },
			        cancel: {
			            label: 'Cancel',
			            className: 'cancel-btn'
			        }
			    },
			    callback: function (result) {
			    	if (result == true) {
			    		$.ajax({
					      	url: SITE_URL+"user/addRemoveConfigGroup",
					      	method: 'post',
					        dataType: 'json',
					        headers: {
					        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					        },
					        data: {'configid':configId, 'numgroups':numGroups},
						    success: function(result){
						      	if(result.status=='success') {	      		
						      		disableSaveBtn = true;
					    			toggleDisableSaveBtn();

					    			var data = result.data;

					    			$(numGroupsId).val(data.numgroups);

					    			var selConfigGroup = $(configGroupsId+' option:selected').val();
					    			//clear group list
						      		$(configGroupsId).empty();
						      		//loop data
						      		$.each(data.configgroup, function(i, configgroup) {
						      			$(configGroupsId).append(
									        $('<option></option>').val(configgroup.id).html('Group'+configgroup.groupindex)
									    );
						      		});

						      		$(configGroupsId+' option[value="'+selConfigGroup+'"]').prop("selected", true);
						      		$(configGroupsId).trigger('change');

						      		displayAlertBox(result.message, result.status);
						      	} else {
						      		displayAlertBox(result.errors[0], result.status);
						      	}
						  	}
						});
			    	} else {
			    		$(numGroupsId).val(prevNumGroups);
			    		$(numGroupsId).attr('data-val', prevNumGroups);
			    	}
			    }
			});
		} else {
			groupDiff = Math.abs(groupDiff);
			bootbox.confirm({
	        	title: 'Confirm',
			    message: 'Are you sure you want to add '+groupDiff+' more groups?',
			    buttons: {
			        confirm: {
			            label: 'Add',
			            className: 'primary-btn'
			        },
			        cancel: {
			            label: 'Cancel',
			            className: 'cancel-btn'
			        }
			    },
			    callback: function (result) {
			    	if (result == true) {
						$.ajax({
					      	url: SITE_URL+"user/addRemoveConfigGroup",
					      	method: 'post',
					        dataType: 'json',
					        headers: {
					        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					        },
					        data: {'configid':configId, 'numgroups':numGroups},
						    success: function(result){
						      	if(result.status=='success') {	      		
						      		disableSaveBtn = true;
					    			toggleDisableSaveBtn();

					    			var data = result.data;

					    			$(numGroupsId).val(data.numgroups);

					    			var selConfigGroup = $(configGroupsId+' option:selected').val();
					    			//clear group list
						      		$(configGroupsId).empty();
						      		//loop data
						      		$.each(data.configgroup, function(i, configgroup) {
						      			$(configGroupsId).append(
									        $('<option></option>').val(configgroup.id).html('Group'+configgroup.groupindex)
									    );
						      		});

						      		$(configGroupsId+' option[value="'+selConfigGroup+'"]').prop("selected", true);
						      		$(configGroupsId).trigger('change');

						      		displayAlertBox(result.message, result.status);
						      	} else {
						      		displayAlertBox(result.errors[0], result.status);
						      	}
						  	}
						});
					} else {
						$(numGroupsId).val(prevNumGroups);
			    		$(numGroupsId).attr('data-val', prevNumGroups);
					}
				}
			});
		}		

	});

	$(prevGroupBtnId).on('click', function(e) {
		e.preventDefault();
	    $(configGroupsId+' option:selected').prev().prop('selected', true);
	    $(configGroupsId).trigger('change');
	});

	$(nextGroupBtnId).on('click', function(e) {
		e.preventDefault();
	    $(configGroupsId+' option:selected').next().prop('selected', true);
	    $(configGroupsId).trigger('change');
	});

	$(createGroupBtnId).on('click', function(e) {
		e.preventDefault();
	    var configId = $(configFilesId).val();
	    configId = (configId == '')? createDefaultConfig(configId):configId;
		$.ajax({
	      	url: SITE_URL+"user/createConfigGroup",
	      	method: 'post',
	        dataType: 'json',
	        headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        data: {'configid':configId},
		    success: function(result){
		      	if(result.status=='success') {	      		
		      		disableSaveBtn = true;
	    			toggleDisableSaveBtn();

	    			var data = result.data;

	    			$(numGroupsId).val(data.numgroups);
	    			$(configGroupsId).append($("<option></option>").attr("value",data.id).text('Group'+data.groupindex));
		      		$(configGroupsId+' option[value="'+data.id+'"]').prop("selected", true);
		      		$(configGroupsId).trigger('change');
	    			
	    			displayAlertBox(result.message, result.status);
		      	} else {
		      		displayAlertBox(result.errors[0], result.status);
		      	}
		  	}
		});
	});

	$(deleteGroupBtnId).on('click', function(e) {
		e.preventDefault();
		var configId = $(configFilesId).val();
		var configGroupId = $(configGroupsId+' option:selected').val();
		var configGroupName = $(configGroupsId+' option:selected').text();

		bootbox.confirm({
        	title: 'Confirm',
		    message: 'Are you sure you want to delete '+configGroupName+'?',
		    buttons: {
		        confirm: {
		            label: 'Delete',
		            className: 'primary-btn'
		        },
		        cancel: {
		            label: 'Cancel',
		            className: 'cancel-btn'
		        }
		    },
		    callback: function (result) {
		    	if (result == true) {
		        	$.ajax({
				      	url: SITE_URL+"user/deleteConfigGroup",
				      	method: 'post',
				        dataType: 'json',
				        headers: {
				        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				        },
				        data: {'configid':configId, 'configgroupid':configGroupId},
					    success: function(result){
					      	if(result.status=='success') {
					      		disableSaveBtn = true;
				    			toggleDisableSaveBtn();

				    			var data = result.data;

				    			$(numGroupsId).val(data.numgroups);
				    			$(configGroupsId+' option[value="'+data.configGroupId+'"]').remove();
				    			if (data.numgroups == 0) {
				    				$(numRowsId).val(0);
				    				$(numColsId).val(0);
				    				$(configureDataTableId+' tbody').empty();
				    			} else {
				    				$(configGroupsId).trigger('change');
				    			}

				    			displayAlertBox(result.message, result.status);
					      	} else {
					      		displayAlertBox(result.errors[0], result.status);
					      	}
					  	}
					});
		        }
		    }
		});		

	});

	$(document.body).on('change',"select.channel",function (e) {
		disableSaveBtn = false;
	    toggleDisableSaveBtn();

		var optionSelected = $("option:selected", this);
		if (optionSelected.val() == '') {
			$(this).parents('tr').find('.channelurl').prop('readonly', true);
		} else {
			$(this).parents('tr').find('.channelurl').prop('readonly', false);
		}
    	var nodeIp = optionSelected.attr('data-ip');
    	if (typeof nodeIp == 'undefined') {
    		nodeIp = '';
    	}
    	$(this).closest('td').next('td').text(nodeIp);
    	$(this).parents('tr').addClass('edited-row');
	});

	$(document.body).on('input propertychange paste', '.group-props, .channellabel, .channelurl',function (e) {
	    disableSaveBtn = false;
	    toggleDisableSaveBtn();

	    $(this).parents('tr').addClass('edited-row');
	});

	//grid page 
	$(gridPrevGroupBtnId).on('click', function(e) {
		e.preventDefault();
	    $(gridConfigGroupsId+' option:selected').prev().prop('selected', true);
	    $(gridConfigGroupsId).trigger('change');
	});

	$(gridNextGroupBtnId).on('click', function(e) {
		e.preventDefault();
	    $(gridConfigGroupsId+' option:selected').next().prop('selected', true);
	    $(gridConfigGroupsId).trigger('change');
	});

	//change grid group dropdwon
	$(gridConfigGroupsId).on('change', function(e) {
		e.preventDefault();
		var configGroupId = $(this).val();
		var configId = $(this).attr('data-cfg');
		$(loadingImageId).show();
		$.ajax({
	      	url: SITE_URL+"user/getConfigGroup",
	      	method: 'post',
	        dataType: 'json',
	        headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        data: {'configid':configId, 'configgroupid':configGroupId},
		    success: function(result){
		      	if(result.status=='success') {
		      		
		      		var data = result.data;
		      		var numCols = data.numcols;
		      		$(videoOuterClass).html('');
		      		var videoList = $('#video-list').html();
		      		var $videoHtml,videoExt,videoType;

		      		//loop data
		      		var rowStart = 0;
		      		var cfgChannelIndex = 1;
		      		var channelsCount = data.configchannel.length;
		      		
		      		var html = '<div class="video-controller">';
		      		$.each(data.configchannel, function(i, configchannel) {
		      			configchannel.label = (configchannel.label!=null)?configchannel.label:'';
		      			configchannel.url = (configchannel.url!=null)?configchannel.url:'';
		      			rowStart = cfgChannelIndex%numCols;

		      			html += '<div class="video-wrapp">';

		      			if (configchannel.url!='') {
		      				
		      				videoExt = configchannel.url.split('.').pop();
		      				if(videoExt == 'm3u8' || videoExt == 'mp4') {
		      					videoType = (videoExt=='m3u8')? 'application/x-mpegURL':'video/mp4';
				      			$videoHtml = $(videoList);
				      			$videoHtml.find('video').attr('id', 'video-'+configchannel.id);
				      			$videoHtml.find('video').attr('data-id', configchannel.id);
				      			$videoHtml.find('video source').attr('src', configchannel.url);
				      			$videoHtml.find('video source').attr('type', videoType);
				      			$videoHtml.find('.video-bar span').text(configchannel.label);
				      			
				      			$videoHtml.find('canvas').attr('id', 'canvas-'+configchannel.id);
				      			html += $videoHtml.html();
			      			}
		      			}

		      			html += '</div>';

		      			cfgChannelIndex++;
		      			if (rowStart == 0 && cfgChannelIndex<=channelsCount) {
		      				html += '</div><div class="clear"></div><div class="video-controller">';
		      			}


					});
					$(videoOuterClass).append(html);

					initVideoJS();

					

		      	} else {
		      		displayAlertBox(result.errors[0], result.status);
		      	}
		  	},
		  	complete: function(){
	        	$(loadingImageId).hide();
	        }
		});
	});

	$(window).on('resize', function(){
	   if($(this).width() != windowWidth){
	      windowWidth = $(this).width();
	       if ($(gridPlayerModeId).length > 0) {
	       		
	    		
	       }
	   }
	});

	$(gridPlayerModeId).on('change', function(e) {
		e.preventDefault();
	    var playerMode = $(this).val();
	    //setPlayerWidth(playerMode);
	    $(gridFormId).submit();
	});

	$(gridPlayerModeDynamicId).on('change', function(e) {
		e.preventDefault();
	    var playerMode = $(this).val();
	    //$(gridPlayerModeDynamicId+' option[value="fixed"]').removeAttr('selected');
	    //$(gridPlayerModeDynamicId+' option[value="'+playerMode+'"]').attr('selected','selected');
	    setPlayerWidthDynamic(playerMode);
	});

	function createDefaultConfig(configId) {
		if (configId != '') {
			return configId;
		}
		var selConfigGroup = $(configGroupsId+' option:selected').text().match(/\d+/)[0];
    	var dataString1 = $(configFormId).serialize();
	   	var dataString2 = configDataTable.$('input, select').serialize();
	   	var dataString = dataString1 + "&" + dataString2;
    	dataString += "&name=" + defaultConfig;
    	dataString += "&groupindex=" + encodeURIComponent(selConfigGroup);
    	var resultConfigId;

	  	$.ajax({
	      	url: SITE_URL+"user/copyConfig",
	      	method: 'post',
	        dataType: 'json',
	        async: false,
	        headers: {
	        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        },
	        data: dataString,
		    success: function(result){
		    	if(result.status=='success') {
		      		var data = result.data;
		      		$(configFilesId+' option[value=""]').remove();
		      		$(configFilesId +' option:selected').prop('selected', false);
		      		$(configFilesId).append($("<option></option>").attr("value",data.id).text(data.name));
		      		$(configFilesId+' option[value="'+data.id+'"]').prop("selected", true);
		      		
		      		var configGroupText = $(configGroupsId+' option[value=""]').text();
		      		$(configGroupsId+' option[value=""]').remove();
		      		$(configGroupsId +' option:selected').prop('selected', false);
		      		$(configGroupsId).append($("<option></option>").attr("value",data.configgroupid).text(configGroupText));
		      		$(configGroupsId+' option[value="'+data.configgroupid+'"]').prop("selected", true);
		      		
		      		resultConfigId = data.id;
		      	}
		  	}
		});
		
		return resultConfigId;
	}

	function setPlayerWidth(playerMode) {
		var maxPlayerInRow = 1;
	    var videoPlayerinRow = 1;
	    var videoOuterClassWidth = $(videoOuterClass).width();
	    //autofit horizontal
	    if (playerMode == 'auto') {
	    	$('.video-controller').each(function(){
	    		videoPlayerinRow = $(this).find('.video-wrapp video').length;
	    		maxPlayerInRow = (videoPlayerinRow > maxPlayerInRow) ? videoPlayerinRow : maxPlayerInRow;
	    		if (videoPlayerinRow == 0) {
	    			$(this).find('.video-wrapp').css('border', 'none');
	    		}
	    	});
	    	var videoWidth = (videoOuterClassWidth/maxPlayerInRow);
	    	$('.video-wrapp').css('width', videoWidth+'px');
	    	videoWidth = ($(videoOuterClass).width()/maxPlayerInRow);
	    	$('.video-wrapp').attr('style', 'width: '+videoWidth+'px !important');
	    	$('.my-vu-meter').css('max-height','34px');
	    	$(videoOuterClass).css('overflow-x', 'unset');

	    } else if (playerMode == 'autofitvertical') {
	    	var rowsCount = $('.video-controller').length;
	    	var visibleHeight = $(window.top).height() - $('.header').outerHeight() - $('.link-wrapp').outerHeight() - $('.padding-15').outerHeight() - $(gridFormId).outerHeight() - $('footer').outerHeight() - ($(videoScrollId).outerHeight() - $(videoScrollId).height()) - 15;
	    	
	    	var videoWidth = visibleHeight/ rowsCount;
	    	$('.video-controller').each(function(){
	    		videoPlayerinRow = $(this).find('.video-wrapp video').length;
	    		if (videoPlayerinRow == 0) {
	    			$(this).find('.video-wrapp').css('border', 'none');
	    		}
	    	});
	    	//$('.video-wrapp').css('width', videoWidth+'px');
	    	$('.video-wrapp').css({'width':videoWidth+'px', 'height':'max-content'});
	    	$(videoOuterClass).css('overflow-x', 'unset');
	    } else {
	    	$('.video-controller').each(function(){
	    		videoPlayerinRow = $(this).find('.video-wrapp video').length;
	    		maxPlayerInRow = (videoPlayerinRow > maxPlayerInRow) ? videoPlayerinRow : maxPlayerInRow;
	    		if (videoPlayerinRow == 0) {
	    			$(this).find('.video-wrapp').css('border', 'none');
	    		}
	    	});

	    	//$('.video-wrapp').css('width', '300px');
	    	$('.video-wrapp').css({'width':'300px', 'height':'max-content'});
	    	
	    	if ((maxPlayerInRow*300) > videoOuterClassWidth) {
	    		$(videoOuterClass).css('overflow-x', 'scroll');
	    	} else {
	    		$(videoOuterClass).css('overflow-x', 'unset');
	    	}

	    }
	}

	function setVUMeterWithPlayer() {
		var audioCanvas, audioCanvasParent;
		$('.video-wrapp').css({'max-height':''});
		$('.my-vu-meter').css({'height':''});
		$('.video-bar.green').css({'max-height':'', 'padding':''});
		$('.video-js').each(function(j, obj) {
			var videoId = this.id;
			if (videoId) {
				$(this).addClass('vjs-16-9');

				idArr = videoId.split('-');
				if (idArr[1] != '') {
					var i = idArr[1];
					audioCanvas = $('#canvas-'+idArr[1]);
					audioCanvasParent = audioCanvas.parent();
					audioCanvasParent.height($('.video-js').attr('data-vumeter-height'));
					
					audioCanvas.css({'padding':'5px 5px','max-height':audioCanvasParent.height()+'px','max-width':audioCanvasParent.width()+'px'});
					if (audioCanvasParent.height() > 25) {
						audioCanvas.css('padding','10px 5px');
					}
					
					document.getElementById('canvas-'+idArr[1]).height = audioCanvasParent.height();
					document.getElementById('canvas-'+idArr[1]).width = audioCanvasParent.width();
				}
			}
		});
	}

	function setPlayerWidthDynamic(playerMode) {
		setPlayerWidth(playerMode);		
		//set web audio
		initWebAudioApi(true);
	}

	function setPlayerWidthFullScreenMode(playerMode) {
		if ($.fullScreenHelper("state")) {
	    	console.log("In fullscreen");
	    	
			var maxPlayerInRow = 0;
	    	var videoPlayerinRow = 1;
	    	var numRowsInGrid = 0;
	    	var videosInRow = 0;
	    	var videoOuterClassWidth = $(videoOuterClass).width();
	    	var videoOuterClassHeight = $(videoOuterClass).height();
	    	var screenWidth = screen.width;
	    	var screenHeight = screen.height-10;
	    	var rowMaxWidth = 0;
	    	$(videoOuterClass).css('overflow-x', 'unset');
	    	
	    	if ($('.video-js').length > 0) {
				/*if ($(gridPlayerModeId).length > 0) {
			       	var playerMode = $(gridPlayerModeId).val();*/
			    if ($(gridPlayerModeDynamicId).length > 0) {
			       	var playerMode = $(gridPlayerModeDynamicId).val();
			       	$('.video-controller').each(function(){
			    		videoPlayerinRow = $(this).find('video').length;
			    		maxPlayerInRow = (videoPlayerinRow > maxPlayerInRow) ? videoPlayerinRow : maxPlayerInRow;
			    		if (videoPlayerinRow > 0) {
			    			numRowsInGrid++;
			    		}
			    		
			    	});

			       	var videoWidth = (maxPlayerInRow>0)?(videoOuterClassWidth/maxPlayerInRow):0;
	    			if (videoWidth >= 536) {
	    				//videoWidth = 300;
	    				if (/*numRowsInGrid == 2 &&*/ maxPlayerInRow <=2) {
	    					videoWidth = 640;
	    				}
	    				if (numRowsInGrid == 1 && maxPlayerInRow == 1) {
	    					videoWidth = screenWidth-100;
	    				}
	    			}
	    			var videoHeight = (numRowsInGrid>0)?(screenHeight/numRowsInGrid):0;
	    			

	    			$('.full-screen-helper .video-wrapp').css({'width':videoWidth+'px', 'max-height':videoHeight+'px'});
	    			if (numRowsInGrid == 2 && maxPlayerInRow <= 2 ) {
	    				$('.full-screen-helper .video-wrapp').css({'width':videoWidth+'px', 'height':videoHeight+'px', 'max-height':''});
	    			}
	    			//set video size, canvas size
	    			
	    			$('.video-js').each(function() {
						var videoId = $(this).attr('id');
						if (vPlayer[videoId] != undefined) {
							if (numRowsInGrid > 2 ) {
								$(this).removeClass('vjs-16-9');
								vPlayer[videoId].dimensions(videoWidth, (videoHeight*70/100));
								$(this).parents('.video-wrapp').find('.my-vu-meter').css('height',(videoHeight*17/100));
							} else {
								vPlayer[videoId].dimensions(videoWidth);

								var vH = $('#'+videoId+'_html5_api').height();
                 				$(this).parents('.video-wrapp').find('.my-vu-meter').css('max-height',((videoHeight-vH)/2));
                 				if (numRowsInGrid == 2 && maxPlayerInRow <= 2) {
                 					$(this).parents('.video-wrapp').find('.my-vu-meter').css({'height':((videoHeight-vH)/2)+'px', 'max-height':''});
                 					
                 				}
							}
							
							$(this).parents('.video-wrapp').find('.video-bar').css('padding','inherit');
							$(this).parents('.video-wrapp').find('.video-bar').css({'max-height':(videoHeight-$('.my-vu-meter').height()-$('.video-container').height())});
							
						}
					});
	    			//set web audio
	    			initWebAudioApi(true);
	    			
	    			//make it center
	    			setTimeout(function(){ 
	    				rowMaxWidth = Math.max.apply(Math, $('.video-controller').map(function(){ return $(this).width(); }).get());
	    				var leftPos = ($(videoScrollId).outerWidth()-rowMaxWidth)/2;
	    			
	    				$('.video-outer').css('position','fixed');
	    				if (leftPos >= 1) {
	    					$('.video-outer').css("left", leftPos + "px");
	    				}
	    			
	    			//setTimeout(function(){ 
	    				var topPos = ($(videoScrollId).height() - $('.video-outer').height())/2;
	    				if (topPos >= 0) { 
	    					$('.video-outer').css("top", topPos + "px");
	    				}
	    			}, 200);
		        }
			}
	  	}
	}

} );