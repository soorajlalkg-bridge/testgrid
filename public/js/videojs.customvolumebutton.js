//custom volume buttom code starts here
var VjsButton = videojs.getComponent('Button');
var CVButton = videojs.extend(VjsButton, {
    constructor: function(player, options) {
        VjsButton.call(this, player, options);
        this.player = player;
        this.on('click', this.onClick);
    },
    onClick: function() {
        
    },
});

function customvolumebutton(options) {
    var player = this;

    player.ready(function() {
        player.controlBar.addChild(
            new CVButton(player, {
                el: videojs.createEl(
                    'div',
                    {
                        className: 'vjs-custom-volume-button vjs-control vjs-vol-off',
                        innerHTML: '<div class="vjs-control-content" style="font-size: 11px; line-height: 28px;"><span class="vjs-control-text custom-vjs-control-text vjs-custom-volume-mute-control"><i class="material-icons">volume_off</i></span><div class="slidecontainer"><input type="range" min="0" max="100" value="0" class="slider vjs-custom-volume-level" data-vol="0"></div>'
                    },
                    {
                        role: 'button'
                    }
                )
            }),
        {}, 2);

    });
}
videojs.plugin('customvolumebutton', customvolumebutton);


$('body').on('input', '.vjs-custom-volume-level', function() {
    var volumeLevel = $(this).val();
    var customVolText = $(this).parents('.vjs-custom-volume-button').find('.custom-vjs-control-text');
    var customVolBtn = $(this).parents('.vjs-custom-volume-button');

    customVolBtn.removeClass('vjs-vol-up vjs-vol-down vjs-vol-off');

    if (volumeLevel > 50) {
        customVolBtn.addClass('vjs-vol-up');
        customVolText.html('<i class="material-icons">volume_up</i>');
    } else if(volumeLevel >1) {
        customVolBtn.addClass('vjs-vol-down');
        customVolText.html('<i class="material-icons">volume_down</i>');
    } else {
        customVolBtn.addClass('vjs-vol-off');
        customVolText.html('<i class="material-icons">volume_off</i>');
    }
    $(this).attr('data-vol', volumeLevel);
});

$('body').on('click', '.vjs-custom-volume-mute-control', function() {
    var volumeLevel = $(this).parents('.video-js').find('.vjs-custom-volume-level').val();
    var customVolText = $(this);
    var customVolBtn = $(this).parents('.video-js').find('.vjs-custom-volume-button');
    var prevVolumeLevel = $(this).parents('.video-js').find('.vjs-custom-volume-level').attr('data-vol');
    
    if (customVolBtn.hasClass('vjs-vol-off')) {
        volumeLevel = prevVolumeLevel;
        if (volumeLevel > 50) {
            customVolBtn.removeClass('vjs-vol-off').addClass('vjs-vol-up');
            customVolText.html('<i class="material-icons">volume_up</i>');
        } else if(volumeLevel >1) {
            customVolBtn.removeClass('vjs-vol-off').addClass('vjs-vol-down');
            customVolText.html('<i class="material-icons">volume_down</i>');
        }
    } else {
        volumeLevel = 0;
        customVolBtn.removeClass('vjs-vol-up vjs-vol-down').addClass('vjs-vol-off');
        customVolText.html('<i class="material-icons">volume_off</i>');
    }
    $(this).parents('.video-js').find('.vjs-custom-volume-level').val(volumeLevel);
    $(this).parents('.video-js').find('.vjs-custom-volume-level').attr('data-vol', prevVolumeLevel);
    //console.log(prevVolumeLevel);

});
//custom volume buttom code ends here