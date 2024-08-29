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
            var countdownInDuration = parseInt(element.getAttribute('data-countdown-duration'));
            var countdownStatus = parseInt(element.getAttribute('data-status')) ?? 1;
            if(countdownStatus == 0) {
                displayMessage(element, badge("Expired"))
            }else if(countdownStatus == 2){
                displayMessage(element, badge("Closed"))
            }else{
                if(countdownInSec < countdownInDuration) {
                    countdownInSec = countdownInDuration  - countdownInSec;
                    handleCountdown(countdownInSec, element);
                }else {
                    displayMessage(element, badge("Expired"));
                }
            }
            
        });
    }

    initializeCountdowns();


    function badge(data) {
        data = data.charAt(0).toUpperCase() + data.slice(1);
        
        if (data === "1") data = "Active";
        if (data === "0") data = "Expired";
        if (data === "2") data = "Closed";
        
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
                case 'Bot', 'Closed':
                    return `<span class='badge-sm bg-dark text-white fw-semibold fs-2 p-1'>${data}</span>`;
                default:
                    return info;
            }
        } catch (error) {
            return info;
        }
    }