document.addEventListener('DOMContentLoaded', function() {
    const signinForm = document.getElementById('signin')?.querySelector('form');
    const signupForm = document.getElementById('signup')?.querySelector('form');
    const signinErrorText = document.querySelector('#signin .error-text');
    const signupErrorText = document.querySelector('#signup .error-text');
    const passwordInputs = document.querySelectorAll('input[type="password"]'); // Target all password fields
    const togglePasswordIcons = document.querySelectorAll('.password-field i'); // Target all eye icons
    const toggleLoginLink = document.getElementById('toggleLogin');
    const toggleSignLink = document.getElementById('toggleSign');
    const signinDiv = document.getElementById('signin');
    const signupDiv = document.getElementById('signup');
    var myID=0;

    // --- Utility function for toggling password visibility ---
    const setupPasswordToggle = (icon, input) => {
        icon?.addEventListener('click', function() {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    };

    // --- Set up password visibility toggles ---
    passwordInputs.forEach(input => {
        const siblingIcon = input.nextElementSibling; // Assuming the icon is immediately after the input
        if (siblingIcon?.classList.contains('fa-eye')) {
            setupPasswordToggle(siblingIcon, input);
        }
    });

    // --- Event listener for the Sign-in form ---
    signinForm?.addEventListener('submit', function(event) {
        event.preventDefault();
        handleSubmit(this, 'backend/main.php', signinErrorText, 'Login successful','navbar/home.html', () => this.reset()); // Reusable submit function
    });
    signupForm?.addEventListener('submit', function(event) {
        event.preventDefault();

        const passwordInput = this.querySelector('[name="password"]');
        const confirmPasswordInput = this.querySelector('[name="confirm_password"]');

        if (passwordInput && confirmPasswordInput && passwordInput.value !== confirmPasswordInput.value) {
            displayError(signupErrorText, 'Passwords do not match!');
            return;
        } else {
            clearError(signupErrorText);
        }

        handleSubmit(this, 'backend/main.php', signupErrorText, 'Account created successfully!', () => this.reset()); // Reusable submit function
    });
    // --- Event listener for the Sign-up form ---
    async function handleSubmit(form, url, errorElement, successMessage, redirectUrl = null, successCallback = null) {
        try {
            const formData = new FormData(form);
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                const errorMessage = await response.text();
                throw new Error(`HTTP error! status: ${response.status}, message: ${errorMessage}`);
            }

            const data = await response.text();
            console.log('Server response:', data);
            redirectUrl=data ;
            if (data.includes(successMessage)) {
                displaySuccess(errorElement, data);
                if (successCallback) {
                    if (typeof successCallback === 'function') {
                        const callbackResult = successCallback(data);
                        if (redirectUrl && callbackResult !== false) {
                            window.location.href = redirectUrl;
                            console.log('Server first response:', data);
                        }
                    } else if (redirectUrl) {
                        window.location.href = redirectUrl;
                        console.log('Server else if response:', data);
                    }
                } else if (redirectUrl) {
                    window.location.href = redirectUrl;
                    console.log('Server last response:', data);
                }
            } else {
                displayError(errorElement, data); // Display server's error message for login
                if (successCallback && typeof successCallback === 'function') {
                    successCallback(data); // Still call the callback for potential custom error handling
                }
                
                window.location.href = redirectUrl;
            }

        } catch (error) {
            console.error('Submission failed:', error);
            displayError(errorElement, `${error} Please try again.`);
        }
        clearError(errorElement); 
    }

    // --- Utility functions for displaying and clearing errors/success messages ---
    function displayError(element, message) {
        if (element) {
            element.style.display = 'block';
            element.classList.remove('success');
            element.classList.add('error');
            element.textContent = message;
        }
    }

    function displaySuccess(element, message) {
        if (element) {
            element.style.display = 'block';
            element.classList.remove('error');
            element.classList.add('success');
            element.textContent = message;
        }
    }

    function clearError(element) {
        if (element) {
            element.style.display = 'none';
            element.textContent = '';
            element.classList.remove('success', 'error');
        }
    }

    // --- (Optional) JavaScript for toggling between login and signup forms ---
    if (toggleLoginLink && toggleSignLink && signinDiv && signupDiv) {
        toggleLoginLink.addEventListener('click', (event) => {
            event.preventDefault();
            if (signinDiv.style.display === 'none') {
                signinDiv.style.display = 'block';
                signupDiv.style.display = 'none';
                clearError(signinErrorText);
                clearError(signupErrorText);
            }
        });

        toggleSignLink.addEventListener('click', (event) => {
            event.preventDefault();
            if (signupDiv.style.display === 'none') {
                signupDiv.style.display = 'block';
                signinDiv.style.display = 'none';
                clearError(signinErrorText);
                clearError(signupErrorText);
            }
        });

        // Initially show only the sign-in form
        if (signinDiv && signupDiv) {
            signupDiv.style.display = 'none';
        }
    }
});
//

function getSessionData() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../backend/main.php', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Important for identifying AJAX request

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                var response = JSON.parse(xhr.responseText);
                myID=response.session_id;
                document.getElementById('username').innerHTML = response.session_value ;
                document.getElementById('UserID').innerText=response.session_id;
            } catch (e) {
                console.error("Error parsing JSON", e);
                document.getElementById('username').innerHTML = "Error: Invalid JSON";
            }
        } else {
            document.getElementById('username').innerHTML = "Error: " + xhr.status;
        }
    };

    xhr.onerror = function() {
        document.getElementById('username').innerHTML = "Request failed";
    };

    xhr.send();
}



document.addEventListener('DOMContentLoaded', function() {
    const dataContainer = document.getElementById('data-container');

    // File comparison
    const compareFileButton = document.getElementById('compare-file-button');
    if (compareFileButton) {
        compareFileButton.addEventListener('click', function() {
            const fileInput = document.getElementById('file-input');
            const userIdInput = document.getElementById('user-id');
            const file = fileInput.files[0];
            const userId = userIdInput.value;

            if (!file || !userId) {
                dataContainer.innerHTML = 'Please select a file and enter User ID.';
                return;
            }

            const formData = new FormData();
            formData.append('uploaded_file', file);
            formData.append('user_id', userId);
            formData.append('compare_files', true); //  Key for compare

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'main.php', true);

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        dataContainer.innerHTML = response.message;
                         if (response.status === 'success') {
                            console.log("Files are the same")
                        }
                        else{
                            console.log("Files are different")
                        }
                    } catch (error) {
                        console.error('Error parsing JSON:', error);
                        dataContainer.innerHTML = 'Error parsing server response.';
                    }
                } else {
                    console.error('Error comparing files. Status:', xhr.status, xhr.statusText);
                    dataContainer.innerHTML = 'Error: ' + xhr.status + ' - ' + xhr.statusText;
                }
            };

            xhr.onerror = function() {
                console.error('Network error occurred.');
                dataContainer.innerHTML = 'Network error occurred.';
            };

            xhr.send(formData);
        });
    }



    // File storage
    const storeFileButton = document.getElementById('store-file-button'); // Get the new button
    if (storeFileButton) {
        storeFileButton.addEventListener('click', function() {
            const fileInput = document.getElementById('file-input');
            const userIdInput = document.getElementById('user-id');
            const file = fileInput.files[0];
            const userId = userIdInput.value;

            if (!file || !userId) {
                dataContainer.innerHTML = 'Please select a file and enter User ID.';
                return;
            }

            const formData = new FormData();
            formData.append('uploaded_file', file);
            formData.append('user_id', userId);
            formData.append('store_file', true); // Key for store

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'main.php', true);

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        dataContainer.innerHTML = response.message;
                         if (response.status === 'success') {
                            console.log("File stored successfully")
                        }
                        else{
                            console.log("File storage failed")
                        }
                    } catch (error) {
                        console.error('Error parsing JSON:', error);
                        dataContainer.innerHTML = 'Error parsing server response.';
                    }
                } else {
                    console.error('Error storing file. Status:', xhr.status, xhr.statusText);
                    dataContainer.innerHTML = 'Error: ' + xhr.status + ' - ' + xhr.statusText;
                }
            };

            xhr.onerror = function() {
                console.error('Network error occurred.');
                dataContainer.innerHTML = 'Network error occurred.';
            };

            xhr.send(formData);
        });
    }
});