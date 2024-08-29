// Calculate remaining time and return all time components
function calculateTimeRemaining(endTime) {
    const now = moment().valueOf(); // Current time in milliseconds
    const remainingTime = endTime - now;

    const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
    const minutes = Math.floor((remainingTime / (1000 * 60)) % 60);
    const hours = Math.floor((remainingTime / (1000 * 60 * 60)) % 24);
    const days = Math.floor((remainingTime / (1000 * 60 * 60 * 24)) % 7);
    const weeks = Math.floor((remainingTime / (1000 * 60 * 60 * 24 * 7)) % 4.345);
    const months = Math.floor((remainingTime / (1000 * 60 * 60 * 24 * 30.44)) % 12);
    const years = Math.floor(remainingTime / (1000 * 60 * 60 * 24 * 365.25));

    return { years, months, weeks, days, hours, minutes, seconds, remainingTime };
}

// Display the countdown or message
function displayMessage(element, message) {
    element.innerHTML = message;
}

// Handle countdown logic based on input type
function handleCountdown(countdownInput, element) {
    let endTime;
    
    // Determine if the input is a date or seconds
    if (typeof countdownInput === 'number') {
        // Input is in seconds
        endTime = moment().add(countdownInput, 'seconds').valueOf();
    } else if (typeof countdownInput === 'string') {
        // Input is a date string
        endTime = moment(countdownInput).valueOf();
    } else {
        throw new Error('Invalid countdown input. Must be a number (seconds) or a string (date).');
    }

    const interval = setInterval(() => {
        const { years, months, weeks, days, hours, minutes, seconds, remainingTime } = calculateTimeRemaining(endTime);

        if (remainingTime <= 0) {
            displayMessage(element, badge("Expired"));
            clearInterval(interval);
        } else {
            let displayString = '';
            if (years > 0) displayString += `${years}y `;
            if (months > 0) displayString += `${months}mo `;
            if (weeks > 0) displayString += `${weeks}w `;
            if (days > 0) displayString += `${days}d `;
            if (hours > 0) displayString += `${hours}h `;
            if (minutes > 0) displayString += `${minutes}m `;
            if (seconds > 0) displayString += `${seconds}s`;

            displayMessage(element, badge(displayString.trim()));
        }
    }, 1000);
}

// Initialize countdowns for elements with data-countdown attribute
function initializeCountdowns() {
    const countdownElements = document.querySelectorAll('[data-countdown]');
    countdownElements.forEach(element => {
        const countdownInput = element.getAttribute('data-countdown');
        const countdownStatus = parseInt(element.getAttribute('data-status')) ?? 1;

        if(countdownStatus === 0) {
            displayMessage(element, badge("Expired"));
        } else if(countdownStatus === 2) {
            displayMessage(element, badge("Closed"));
        } else {
            // Check if countdownInput is a number or a valid date string
            if (!isNaN(countdownInput)) {
                // If countdownInput is a number, treat it as seconds
                handleCountdown(parseInt(countdownInput, 10), element);
            } else {
                // Otherwise, treat it as a date string
                handleCountdown(countdownInput, element);
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
            case 'Bot':
            case 'Closed':
                return `<span class='badge-sm bg-dark text-white fw-semibold fs-2 p-1'>${data}</span>`;
            default:
                return info;
        }
    } catch (error) {
        return info;
    }
}
