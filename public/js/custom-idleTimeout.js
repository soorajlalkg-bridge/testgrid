$(document).ready(function() {
	$(document).idleTimeout({
        redirectUrl:  SITE_URL+'user/logoutAccount', // redirect to this url. Set this value to YOUR site's logout page.
        // idle settings
        idleTimeLimit: 604800, // 'No activity' time limit in seconds. 604800 = 10080 Minutes = 168 hrs = one week
        idleCheckHeartbeat: 2, // Frequency to check for idle timeouts in seconds
        enableDialog: true, // set to false for logout without warning dialog
        dialogDisplayLimit: 20, // 20 seconds for testing. Time to display the warning dialog before logout (and optional callback) in seconds. 180 = 3 Minutes
        dialogTitle: 'Session Expiration Warning', // also displays on browser title bar
        dialogText: 'Because you have been inactive, your session is about to expire.',
        dialogTimeRemaining: 'Time remaining',
        dialogStayLoggedInButton: 'Stay Logged In',
        dialogLogOutNowButton: 'Log Out Now',
    });

    var ajaxUpdateLastActivity = function() {
        $.ajax({
            url: SITE_URL+"user/lastActivity",
            method: 'post',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {},
            statusCode: {
                401: function() {
                    clearInterval(ajaxUpdateLastActivity);
                    window.location.reload();
                },
                419: function() {
                    window.location.reload();
                }
            },
            success: function(result){
                if (result.status == 'failure') {
                    clearInterval(ajaxUpdateLastActivity);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                
            },
            complete: function() {
                
            }
        });
    };

    var interval = 5000;//1000 * 60 * 1; // where X is your every X minutes
    setInterval(ajaxUpdateLastActivity, interval);

} );