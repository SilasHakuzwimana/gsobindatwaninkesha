var signUpBtn=document.getElementById('toggleSign');
var loginBtn=document.getElementById('toggleLogin');
var loginDiv=document.getElementById('signin');
var SignUpDiv=document.getElementById('signup');

const daysDisplay = document.getElementById('days');
const hoursDisplay = document.getElementById('hours');
const minutesDisplay = document.getElementById('minutes');
const secondsDisplay = document.getElementById('seconds');
const messageDisplay = document.getElementById('message');

let countdownInterval;

signUpBtn.addEventListener('click', ()=>{
    loginDiv.style.animation='slide_down 2s 1 forwards ';
    SignUpDiv.style.display='block';
    SignUpDiv.style.animation='slide_down_up 2s 1s 1 forwards ';
    loginDiv.addEventListener('animationend',()=>{
        loginDiv.style.display='none';
        loginDiv.style.transform='translateY(-200%)';
    }, { once: true }); // Use { once: true } to remove the listener after it fires once
});

loginBtn.addEventListener('click',()=>{
    SignUpDiv.style.animation='slide_down 2s 1 forwards ';
    loginDiv.style.display='block';
    loginDiv.style.animation='slide_down_up 2s  1s 1 forwards ';
    // Immediately hide SignUpDiv
    loginDiv.addEventListener('animationend', ()=>{
        SignUpDiv.style.display='none'; 
        // No need to change loginDiv's display here, it should remain 'block'
    }, { once: true }); // Use { once: true } for good practice
});
function startTimer(targetDate) {
    clearInterval(countdownInterval); // Clear any existing interval
    countdownInterval = setInterval(() => {
        const now = new Date();
        const timeRemaining = targetDate.getTime() - now.getTime();

        if (timeRemaining <= 0) {
            clearInterval(countdownInterval);
            daysDisplay.textContent = '00';
            hoursDisplay.textContent = '00';
            minutesDisplay.textContent = '00';
            secondsDisplay.textContent = '00';
            messageDisplay.textContent = "Countdown has finished!";
        } else {
            const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

            daysDisplay.textContent = days.toString().padStart(2, '0');
            hoursDisplay.textContent = hours.toString().padStart(2, '0');
            minutesDisplay.textContent = minutes.toString().padStart(2, '0');
            secondsDisplay.textContent = seconds.toString().padStart(2, '0');
        }
    }, 1000);
}

function fetchEndDate() {
    // Use AJAX to fetch the data from the backend
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../backend/fetchEndDate.php', true); // Replace with your actual PHP endpoint
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Parse the response JSON
            const response = JSON.parse(xhr.responseText);

            if (response && response.end_date) {
                // Update the target date in the timer function dynamically
                const targetDate = new Date(response.end_date);
                startTimer(targetDate);
            } else {
                console.error('End date not found in response');
            }
        } else {
            console.error('Failed to fetch end date:', xhr.statusText);
        }
    };

    xhr.onerror = function() {
        console.error('An error occurred during the AJAX request');
    };

    xhr.send();
}
