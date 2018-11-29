//custom fullscreen buttom code starts here
var VjsFSButton = videojs.getComponent('Button');
var CFSButton = videojs.extend(VjsFSButton, {
    constructor: function(player, options) {
        VjsFSButton.call(this, player, options);
        this.player = player;
        this.on('click', this.onClick);
    },
    onClick: function() {
        
    },
});

function customfullscreenbutton(options) {
    var player = this;

    player.ready(function() {
        player.controlBar.addChild(
            new CFSButton(player, {
                el: videojs.createEl(
                    'div',
                    {
                        className: 'vjs-custom-fullscreen-control vjs-control vjs-button',
                        innerHTML: '<span class="vjs-control-text custom-vjs-control-text"><i class="material-icons">fullscreen</i></span>'
                    },
                    {
                        role: 'button'
                    }
                )
            }),
        {}, 50);

    });
}

$('body').on('click', '.vjs-custom-fullscreen-control', function() {
  $(this).toggleClass('fullscreen-mode');
  
  if ($(this).hasClass('fullscreen-mode')) {
    $(this).children('span').html('<i class="material-icons">fullscreen_exit</i>');
  } else {
    $(this).children('span').html('<i class="material-icons">fullscreen</i>');
  }
});
videojs.plugin('customfullscreenbutton', customfullscreenbutton);
//custom fullscreen buttom code ends here