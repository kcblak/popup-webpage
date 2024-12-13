jQuery(document).ready(function($) {
    console.log('JavaScript is running');

    // Trigger the custom JavaScript event
    function triggerPopupWatchedEvent() {
        console.log('Triggering popupWatched event');
        var event = new Event('popupWatched');
        document.dispatchEvent(event);
    }

    function triggerPopupClosedEarlyEvent() {
        console.log('Triggering popupClosedEarly event');
        var event = new Event('popupClosedEarly');
        document.dispatchEvent(event);
    }

    // Open popup
    $(document).on('click', '.popup-webpage-button', function(e) {
        e.preventDefault();
        console.log('Button clicked');
        var popupURL = $(this).data('url');
        var popupWindow = window.open(popupURL, 'popupWindow', 'width=600,height=400,scrollbars=yes');

        if (popupWindow) {
            console.log('Popup opened');

            var minTime = 60000; // 1 minute
            var maxTime = 180000; // 3 minutes
            var randomTime = Math.floor(Math.random() * (maxTime - minTime + 1)) + minTime;

            // Delay before countdown starts (12 seconds)
            var delayTime = 12000;

            // Countdown popup
            var countdownPopup = window.open('', 'CountdownPopup', 'width=300,height=200,left=650,top=50');

            if (countdownPopup) {
                console.log('Countdown popup opened');

                // Ensure the countdown popup has a document and body
                countdownPopup.document.write('<html><head><title>Countdown Timer</title></head><body></body></html>');

                // Function to update the countdown timer
                var updateCountdown = function(timeLeft) {
                    var seconds = Math.floor(timeLeft / 1000);
                    countdownPopup.document.body.innerHTML = 'Time left: ' + seconds + 's';
                    console.log('Time left:', seconds, 's');
                };

                // Initial message before countdown starts
                countdownPopup.document.body.innerHTML = 'Loading, please wait...';

                // Delay before starting the countdown
                setTimeout(function() {
                    console.log('Starting countdown');
                    updateCountdown(randomTime);

                    // Countdown interval
                    var countdownInterval = setInterval(function() {
                        randomTime -= 1000;
                        updateCountdown(randomTime);

                        if (randomTime <= 0) {
                            clearInterval(countdownInterval);
                            countdownPopup.document.body.innerHTML = 'Thanks for watching!';
                            setTimeout(function() {
                                console.log('Countdown finished, closing popups');
                                popupWindow.close();
                                countdownPopup.close();
                                // Trigger the custom JavaScript event
                                triggerPopupWatchedEvent();
                            }, 3000); // Show the message for 3 seconds
                        }
                    }, 1000);

                }, delayTime);

                // Monitor popup window closure using event listener
                var popupClosedManually = false;

                var popupClosedListener = function() {
                    if (popupClosedManually) {
                        console.log('Popup window closed manually');
                    } else {
                        console.log('Popup window closed early');
                        clearInterval(countdownInterval);
                        countdownPopup.close();
                        triggerPopupClosedEarlyEvent();
                    }
                };

                // Listen for popup window closure
                popupWindow.addEventListener('unload', popupClosedListener);

                // Clean up event listener when popup closes normally
                popupWindow.addEventListener('beforeunload', function() {
                    popupWindow.removeEventListener('unload', popupClosedListener);
                });

                // Set flag when popup is closed manually
                window.addEventListener('beforeunload', function() {
                    popupClosedManually = true;
                });

            } else {
                console.log('Countdown popup blocked');
                alert('Countdown popup blocked. Please allow popups for this site.');
            }

        } else {
            console.log('Popup blocked');
            alert('Popup blocked. Please allow popups for this site.');
        }
    });

    // Listen for the custom event and send AJAX request to award points
    document.addEventListener('popupWatched', function() {
        console.log('Popup watched event triggered');
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'award_gamipress_points'
            },
            success: function(response) {
                if (response.success) {
                    console.log('Points awarded successfully');
                } else {
                    console.log('Error awarding points:', response.data);
                }
            },
            error: function(error) {
                console.log('AJAX error:', error);
            }
        });
    });

    // Listen for the custom event when popup closes early
    document.addEventListener('popupClosedEarly', function() {
        console.log('Popup closed early, no points awarded');
        alert('Popup closed too early, no points awarded');
    });
});
