function webAudioVUMeter () {
  'use strict';

  var options = {
    borderSize: 2,
    horizontal:true,
    canvasHeight:15,
    canvasWidth:300,
    showAverage:false
  };
  var gainNode;
  var gradient;

  var setOptions = function(userOptions) {
    for (var k in userOptions) {
      if(userOptions.hasOwnProperty(k)) {
        options[k] = userOptions[k];
      }
    }
  };

  var createMeterCanvas = function(ctx, optionsOverrides) {
    //console.log(optionsOverrides);
    setOptions(optionsOverrides);
    //ctx.clearRect(0, 0, options.canvasWidth, options.canvasHeight);
    gradient = ctx.createLinearGradient(0, 0, options.canvasWidth, 0);
    gradient.addColorStop(1,'#F70700');
    gradient.addColorStop(0.75,'#00FF01');
    gradient.addColorStop(0.25,'#00FF01');
    gradient.addColorStop(0,'#00FF01');
    return {
      gradient: gradient,
    };

  }

  var createMeter = function(ctx, context, sourceNode, optionsOverrides) {
    /*setOptions(optionsOverrides);
    gradient = ctx.createLinearGradient(0, 0, options.canvasWidth, 0);
    //gradient = ctx.createLinearGradient(0, 0, 130, 0);
    gradient.addColorStop(1,'#F70700');
    gradient.addColorStop(0.75,'#00FF01');
    gradient.addColorStop(0.25,'#00FF01');
    gradient.addColorStop(0,'#00FF01');

    gainNode = context.createGain();
    //return gainNode;
    return {
	    gainNode: gainNode,
	    gradient: gradient,
	};*/
    setOptions(optionsOverrides);
    gainNode = context.createGain();
    return {
      gainNode: gainNode
    };
  };

  var createMeterNode = function(ctx, context, sourceNode, splitter, analyser, analyser2, javascriptNode, gainNode, gradient) {
  	//setOptions(optionsOverrides);

  	// setup a javascript node
    javascriptNode = context.createScriptProcessor(2048, 1, 1);
    // connect to destination, else it isn't called
    javascriptNode.connect(context.destination);        
    // setup a analyzer
    analyser = context.createAnalyser();
    analyser.smoothingTimeConstant = 0.3;
    analyser.fftSize = 1024;

    analyser2 = context.createAnalyser();
    analyser2.smoothingTimeConstant = 0.0;
    analyser2.fftSize = 1024;

    // create a buffer source node
    splitter = context.createChannelSplitter();
    // connect the source to the analyser and the splitter
    sourceNode.connect(splitter);
    
    // connect one of the outputs from the splitter to the analyser
    splitter.connect(analyser,0,0);
    splitter.connect(analyser2,1,0);
    // connect the splitter to the javascriptnode, we use the javascript node to draw at a specific interval.
    analyser.connect(javascriptNode);
    // and connect to destination
    sourceNode.connect(gainNode);
    gainNode.connect(context.destination);
    gainNode.gain.value = 0; 

    javascriptNode.onaudioprocess = function() {
        // get the average for the first channel
        var array =  new Uint8Array(analyser.frequencyBinCount);
        analyser.getByteFrequencyData(array);
        var average = getAverageVolumeChannel(array);
        //if (options.showAverage == true) {
          //console.log('analyser:'+analyser.frequencyBinCount);
        //  console.log(array);
        //}

        // get the average for the second channel
        var array2 =  new Uint8Array(analyser2.frequencyBinCount);
        analyser2.getByteFrequencyData(array2);
        var average2 = getAverageVolumeChannel(array2);

        //if (options.showAverage == true) {
          //console.log('analyser2:'+analyser2.frequencyBinCount);
        //  console.log(array2);
        //}

        // clear the current state
        //ctx.clearRect(0, 0, 130, 15);
        ctx.clearRect(0, 0, options.canvasWidth, options.canvasHeight);
        // set the fill style
        ctx.fillStyle=gradient;
        //if (options.showAverage == true) {
        //  console.log(average+':'+average2);
        //}
        var averageInCanvas = (average*options.canvasWidth)/255;
        var average2InCanvas = (average2*options.canvasWidth)/255;

        // create the meters
        var secondChannelY = options.canvasHeight-6;
        //ctx.fillRect(0,0,average,5);
        ctx.fillRect(0,0,averageInCanvas,5);
        //ctx.fillRect(0,10,average2,5);
        //ctx.fillRect(0,secondChannelY,average2,5);
        ctx.fillRect(0,secondChannelY,average2InCanvas,5);
    }

  };

  var getAverageVolumeChannel = function(array) {
    var values = 0;
    var average;

    var length = array.length;
    if (options.showAverage == true) {
        console.log('length:'+length);
    }
    // get all the frequency amplitudes
    for (var i = 0; i < length; i++) {
        values += array[i];
    }

    average = values / length;
    return average;
  };

  return {
    createMeterNode: createMeterNode,
    createMeter: createMeter,
    createMeterCanvas: createMeterCanvas
  };
};