<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Countdown Timer</title>
</head>
<body>

<div data-countdown="2024-08-17 14:35:00"></div>
<div data-countdown="2024-08-17 13:36:00"></div>
<div data-countdown="2024-08-17 13:37:00"></div>

<script>
    // Convert a date string to a timestamp
    function toTimestamp(dateString) {
        return new Date(dateString).getTime();
    }

    // Calculate remaining time and return minutes and seconds
    function calculateTimeRemaining(mainTime, now) {
        const remainingTime = mainTime - now;
        const minutes = Math.floor(remainingTime / (1000 * 60));
        const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
        return { minutes, seconds, remainingTime };
    }

    // Display the countdown or message
    function displayMessage(element, message) {
        element.innerHTML = message;
    }

    // Handle countdown logic
    function handleCountdown(targetTime, element) {
        const targetDate = toTimestamp(targetTime);
        const mainTime = targetDate + 5 * 60 * 1000;
        const now = new Date().getTime();

        if (mainTime <= now) {
            displayMessage(element, "Expired");
            return;
        }

        if (mainTime - now > 5 * 60 * 1000) {
            displayMessage(element, "Invalid");
            return;
        }

        const interval = setInterval(() => {
            const now = new Date().getTime(); // Update current time
            const { minutes, seconds, remainingTime } = calculateTimeRemaining(mainTime, now);

            if (remainingTime <= 0) {
                displayMessage(element, "Expired");
                clearInterval(interval);
            } else {
                displayMessage(element, `Time remaining: ${minutes}m ${seconds}s`);
            }
        }, 1000);
    }

    // Initialize countdown for all elements with data-countdown attribute
    function initializeCountdowns() {
        const countdownElements = document.querySelectorAll('[data-countdown]');
        countdownElements.forEach(element => {
            const targetTime = element.getAttribute('data-countdown');
            handleCountdown(targetTime, element);
        });
    }

    // Run countdown initialization
    initializeCountdowns();
</script>

</body>
</html>
