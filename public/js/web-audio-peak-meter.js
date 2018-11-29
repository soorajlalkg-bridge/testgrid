//var webAudioPeakMeter = (function() {
function webAudioPeakMeter () {
  'use strict';
  var options = {
    borderSize: 2,
    fontSize: 9,
    backgroundColor: 'black',
    tickColor: '#ddd',
    gradient: ['red 1%', '#ff0 16%', 'lime 45%', '#080 100%'],
    dbRange: 48,
    dbTickSize: 6,
    maskTransition: '0.1s',
    horizontal:false,
    horizontalWidth:'',
  };
  var tickWidth;
  var elementWidth;
  var elementHeight;
  var meterHeight;
  var meterWidth;
  var meterTop;
  var vertical = true;
  var channelCount = 1;
  var channelMasks = [];
  var channelPeaks = [];
  var channelPeakLabels = [];
  var maskSizes = [];
  var textLabels = [];
  var gainNode;
  var analyser;
  var analyser2;
  var splitter;


  var getBaseLog = function(x, y) {
    return Math.log(y) / Math.log(x);
  };

  var dbFromFloat = function(floatVal) {
    return getBaseLog(10, floatVal) * 20;
  };

  var setOptions = function(userOptions) {
    for (var k in userOptions) {
      if(userOptions.hasOwnProperty(k)) {
        options[k] = userOptions[k];
      }
    }
    tickWidth = options.fontSize * 2.0;
    meterTop = options.fontSize * 1.5 + options.borderSize;
  };

  var createMeterNode = function(sourceNode, audioCtx) {
    var c = sourceNode.channelCount;
    var meterNode = audioCtx.createScriptProcessor(2048, c, c);
    sourceNode.connect(meterNode);
    meterNode.connect(audioCtx.destination);

    // connect to destination, else it isn't called  
    // setup a analyzer
    /*analyser = audioCtx.createAnalyser();
    analyser.smoothingTimeConstant = 0.3;
    analyser.fftSize = 1024;
    analyser2 = audioCtx.createAnalyser();
    analyser2.smoothingTimeConstant = 0.0;
    analyser2.fftSize = 1024;
    // create a buffer source node
    splitter = audioCtx.createChannelSplitter();
    // connect the source to the analyser and the splitter
    sourceNode.connect(splitter);    
    // connect one of the outputs from the splitter to the analyser
    splitter.connect(analyser,0,0);
    splitter.connect(analyser2,1,0);
    // connect the splitter to the javascriptnode, we use the javascript node to draw at a specific interval.
    analyser.connect(meterNode);
    // and connect to destination
    gainNode = audioCtx.createGain();
    sourceNode.connect(gainNode);
    gainNode.connect(audioCtx.destination);
    gainNode.gain.value = 0;*/

    return {
      gainNode: gainNode,
      meterNode: meterNode,
    };
    //return meterNode;
  };

  var createContainerDiv = function(parent) {
    var meterElement = document.createElement('div');
    meterElement.style.position = 'relative';
    //console.log('elementWidth: '+elementWidth);
    meterElement.style.width = elementWidth + 'px';
    meterElement.style.height = elementHeight + 'px';
    meterElement.style.backgroundColor = options.backgroundColor;
    parent.appendChild(meterElement);
    return meterElement;
  };

  var createMeter = function(domElement, meterNode, gainNode, optionsOverrides) {
    setOptions(optionsOverrides);
    //console.log('domElement: '+domElement.clientWidth);
    elementWidth = domElement.clientWidth;
    elementHeight = domElement.clientHeight;
    //custom code
    if (options.horizontal==true) {
      elementWidth = options.horizontalWidth;
    }
    var meterElement = createContainerDiv(domElement);
    if (elementWidth > elementHeight) {
      vertical = false;
    }
    meterHeight = elementHeight - meterTop - options.borderSize;
    meterWidth = elementWidth - tickWidth - options.borderSize;
    createTicks(meterElement);
    createRainbow(meterElement, meterWidth, meterHeight,
                  meterTop, tickWidth);
    channelCount = meterNode.channelCount;
    var channelWidth = meterWidth / channelCount;
    if (!vertical) {
      channelWidth = meterHeight / channelCount;
    }
    var channelLeft = tickWidth;
    if (!vertical) {
      channelLeft = meterTop;
    }
    for (var i = 0; i < channelCount; i++) {
      createChannelMask(meterElement, options.borderSize,
                        meterTop, channelLeft, false);
      channelMasks[i] = createChannelMask(meterElement, channelWidth,
                                          meterTop, channelLeft,
                                          options.maskTransition);
      channelPeaks[i] = 0.0;
      channelPeakLabels[i] = createPeakLabel(meterElement, channelWidth,
                                             channelLeft);
      channelLeft += channelWidth;
      maskSizes[i] = 0;
      textLabels[i] = '-∞';
    }
    meterNode.onaudioprocess = updateMeter;
    meterElement.addEventListener('click', function() {
      for (var i = 0; i < channelCount; i++) {
        channelPeaks[i] = 0.0;
        textLabels[i] = '-∞';
      }
    }, false);
    paintMeter();
  };

  var createTicks = function(parent) {
    var numTicks = Math.floor(options.dbRange / options.dbTickSize);
    var dbTickLabel = 0;
    if (vertical) {
      var dbTickTop = options.fontSize + options.borderSize;
      for (var i = 0; i < numTicks; i++) {
        var dbTick = document.createElement('div');
        parent.appendChild(dbTick);
        dbTick.style.width = tickWidth + 'px';
        dbTick.style.textAlign = 'right';
        dbTick.style.color = options.tickColor;
        dbTick.style.fontSize = options.fontSize + 'px';
        dbTick.style.position = 'absolute';
        dbTick.style.top = dbTickTop + 'px';
        dbTick.textContent = dbTickLabel + '';
        dbTickLabel -= options.dbTickSize;
        dbTickTop += meterHeight / numTicks;
        dbTick.className += "hidden-meter";
      }
    } else {
      tickWidth = meterWidth / numTicks;
      var dbTickRight = options.fontSize * 2;
      for (var i = 0; i < numTicks; i++) {
        var dbTick = document.createElement('div');
        parent.appendChild(dbTick);
        dbTick.style.width = tickWidth + 'px';
        dbTick.style.textAlign = 'right';
        dbTick.style.color = options.tickColor;
        dbTick.style.fontSize = options.fontSize + 'px';
        dbTick.style.position = 'absolute';
        dbTick.style.right = dbTickRight + 'px';
        dbTick.textContent = dbTickLabel + '';
        dbTickLabel -= options.dbTickSize;
        dbTickRight += tickWidth;
      }
    }
    //console.log('tickWidth: '+tickWidth);
  };

  var createRainbow = function(parent, width, height, top, left) {
    var rainbow = document.createElement('div');
    parent.appendChild(rainbow);
    //console.log('rainbow: '+width);
    rainbow.style.width = width + 'px';
    rainbow.style.height = height + 'px';
    rainbow.style.position = 'absolute';
    rainbow.style.top = top + 'px';
    if (vertical) {
      rainbow.style.left = left + 'px';
      var gradientStyle = 'linear-gradient(to bottom, ' +
        options.gradient.join(', ') + ')';
    } else {
      rainbow.style.left = options.borderSize + 'px';
      var gradientStyle = 'linear-gradient(to left, ' +
        options.gradient.join(', ') + ')';
    }
    rainbow.style.backgroundImage = gradientStyle;
    rainbow.className += "rainbow";
    return rainbow;
  };

  var createPeakLabel = function(parent, width, left) {
    var label = document.createElement('div');
    parent.appendChild(label);
    label.style.textAlign = 'center';
    label.style.color = options.tickColor;
    label.style.fontSize = options.fontSize + 'px';
    label.style.position = 'absolute';
    label.textContent = '-∞';
    if (vertical) {
      label.style.width = width + 'px';
      label.style.top = options.borderSize + 'px';
      label.style.left = left + 'px';
      label.className += "hidden-meter";
    } else {
      label.style.width = options.fontSize * 2 + 'px';
      label.style.right = options.borderSize + 'px';
      label.style.top = (width * 0.25) + left + 'px';
    }
    //console.log('label.style.width: '+label.style.width);
    return label;
  };

  var createChannelMask = function(parent, width, top, left, transition) {
    var channelMask = document.createElement('div');
    parent.appendChild(channelMask);
    channelMask.style.position = 'absolute';
    if (vertical) {
      //console.log('width: '+width);
      channelMask.style.width = width + 'px';
      channelMask.style.height = meterHeight + 'px';
      channelMask.style.top = top + 'px';
      channelMask.style.left = left + 'px';
    } else {
      //console.log('meterWidth: '+meterWidth);
      channelMask.style.width = meterWidth + 'px';
      channelMask.style.height = width + 'px';
      channelMask.style.top = left + 'px';
      channelMask.style.right = options.fontSize * 2 + 'px';
    }
    channelMask.style.backgroundColor = options.backgroundColor;
    if (transition) {
      //console.log('maskTransition: '+options.maskTransition);
      if (vertical) {
        channelMask.style.transition = 'height ' + options.maskTransition;
      } else {
        channelMask.style.transition = 'width ' + options.maskTransition;
      }
    }
    return channelMask;
  };

  var maskSize = function(floatVal) {
    var meterDimension = vertical ? meterHeight : meterWidth;
    if (floatVal === 0.0) {
      return meterDimension;
    } else {
      var d = options.dbRange * -1;
      var returnVal = Math.floor(dbFromFloat(floatVal) * meterDimension / d);
      if (returnVal > meterDimension) {
        return meterDimension;
      } else {
        return returnVal;
      }
    }
  };

  var updateMeter = function(audioProcessingEvent) {
    var inputBuffer = audioProcessingEvent.inputBuffer;
    var i;
    var channelData = [];
    var channelMaxes = [];
    for (i = 0; i < channelCount; i++) {
      channelData[i] = inputBuffer.getChannelData(i);
      channelMaxes[i] = 0.0;
    }
    for (var sample = 0; sample < inputBuffer.length; sample++) {
      for (i = 0; i < channelCount; i++) {
        if (Math.abs(channelData[i][sample]) > channelMaxes[i]) {
          channelMaxes[i] = Math.abs(channelData[i][sample]);
        }
      }
    }
    for (i = 0; i < channelCount; i++) {
      maskSizes[i] = maskSize(channelMaxes[i], meterHeight);
      if (channelMaxes[i] > channelPeaks[i]) {
        channelPeaks[i] = channelMaxes[i];
        textLabels[i] = dbFromFloat(channelPeaks[i]).toFixed(1);
      }
    }
  };

  var paintMeter = function() {
    for (var i = 0; i < channelCount; i++) {
      if (vertical) {
        channelMasks[i].style.height = maskSizes[i] + 'px';
      } else {
        channelMasks[i].style.width = maskSizes[i] + 'px';
      }
      channelPeakLabels[i].textContent = textLabels[i];
    }
    window.requestAnimationFrame(paintMeter);
  };

  return {
    createMeterNode: createMeterNode,
    createMeter: createMeter,
  };
//})();
};

//module.exports = webAudioPeakMeter;
