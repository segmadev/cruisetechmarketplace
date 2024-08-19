    // Calculate remaining time and return minutes and seconds
    function calculateTimeRemaining(endTime, now) {
        const remainingTime = endTime - now;
        const minutes = Math.floor(remainingTime / (1000 * 60));
        const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
        return { minutes, seconds, remainingTime };
    }

    // Display the countdown or message
    function displayMessage(element, message) {
        element.innerHTML = message;
    }

    // Handle countdown logic
    function handleCountdown(countdownInSec, element) {
        const now = moment().valueOf(); // Get current time in milliseconds
        const endTime = now + countdownInSec * 1000; // Calculate end time in milliseconds

        if (countdownInSec <= 0) {
            displayMessage(element, badge("Expired"));
            return;
        }

        const interval = setInterval(() => {
            const now = moment().valueOf(); // Update current time

            const { minutes, seconds, remainingTime } = calculateTimeRemaining(endTime, now);

            if (remainingTime <= 0) {
                displayMessage(element, badge("Expired"));
                clearInterval(interval);
            } else {
                displayMessage(element, badge(`${minutes}m ${seconds}s`));
            }
        }, 1000);
    }

    // Initialize countdowns for elements with data-countdown-insec attribute
    function initializeCountdowns() {
        const countdownElements = document.querySelectorAll('[data-countdown-insec]');
        countdownElements.forEach(element => {
            var countdownInSec = parseInt(element.getAttribute('data-countdown-insec'), 10);
            var countdownInDuration = parseInt(element.getAttribute('data-countdown-duration')) ?? 5;
            if(countdownInSec < countdownInDuration * 60) {
                countdownInSec = (countdownInDuration * 60) - countdownInSec;
                handleCountdown(countdownInSec, element);
            }else {
                displayMessage(element, badge("Expired"));
            }
        });
    }

    initializeCountdowns();


    function badge(data) {
        data = data.charAt(0).toUpperCase() + data.slice(1);
        
        if (data === "1") data = "Active";
        if (data === "0") data = "Expired";
        
        let info = `<span class='badge-sm bg-light-primary text-primary fw-semibold fs-2 p-2'>${data}</span>`;
        
        try {
            switch (data) {
                case 'Active':
                case 'Approved':
                case 'Success':
                case 'Successful':
                case 'Allocated':
                case 'Completed':
                    return `<span class='badge-sm bg-light-success text-success fw-semibold fs-2'>${data}</span>`;
                case 'Disable':
                case 'Expired':
                case 'Reject':
                case 'Rejected':
                    return `<span class='badge-sm bg-light-danger text-danger fw-semibold fs-2'>${data}</span>`;
                case 'initiate':
                case 'Pending':
                    return `<span class='badge-sm bg-light-warning text-warning fw-semibold fs-2'>${data}</span>`;
                case '':
                case 'Bot':
                    return `<span class='badge-sm bg-light-primary text-primary fw-semibold fs-2'>${data}</span>`;
                default:
                    return info;
            }
        } catch (error) {
            return info;
        }
    }