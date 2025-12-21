
    const fetchDataButton = document.getElementById('fetch-data-button');
    const dataContainer = document.getElementById('user_contain');

     function Fetcher() {
        // 1. Create XMLHttpRequest Object
        const xhr = new XMLHttpRequest();

        // 2. Configure the Request
        xhr.open('GET', '../main.php', true); //  Adjust the URL if needed
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        // 3. Set up the Callback Function
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const response = JSON.parse(xhr.responseText); // Parse the JSON response

                    if (response.status === "success") {
                        if (response.users) {
                            displayUsers(response.users); // Call function to display users
                        } else if (response.user_level) { //check the user level
                            if(response.user_level === 'Admin'){
                                window.location.href = 'backend/admin/admin.html';
                            }
                            else{
                                window.location.href = 'navbar/home.html';
                            }

                        }
                         else if (response.message){
                            dataContainer.innerHTML = response.message;
                        }
                        else {
                            dataContainer.innerHTML = 'No data received.';
                        }
                    } else if (response.status === "error") {
                        dataContainer.innerHTML = 'Error: ' + response.message;
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    dataContainer.innerHTML = 'Error parsing data from server.'+error;
                }
            } else {
                // Handle HTTP errors
                console.error('Error fetching data. Status:', xhr.status, xhr.statusText);
                dataContainer.innerHTML = 'Error: ' + xhr.status + ' - ' + xhr.statusText;
            }
        };

        // 4. Handle network errors
        xhr.onerror = function() {
            console.error('Network error occurred.');
            dataContainer.innerHTML = 'Network error occurred.';
        };

        // 5. Send the Request
        xhr.send();
    }

    function displayUsers(users) {
        
        dataContainer.innerHTML = ''; // Clear previous content
        if (users.length > 0) {
            users.forEach(user => {
                const p = document.createElement('p');
                if(user.status == 1){
                    p.classList.add('winner');
                }
                p.textContent = ` ${user.user_id}  ${user.username} `; // Adjust properties as needed
                dataContainer.appendChild(p);
            });
        } else {
            dataContainer.textContent = 'No users found.';
        }
    }
    function getSessionData() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../main.php', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Important for identifying AJAX request
    
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    document.getElementById('username').innerHTML = response.session_value ;
                    document.getElementById('user_id').innerHTML =  response.session_id;
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
    
    
document.addEventListener('DOMContentLoaded',Fetcher);
document.getElementById('logout').addEventListener('click',logout);
function logout() {
    window.location.href='../../index.html';
    // var xhr = new XMLHttpRequest();
    // xhr.open('GET', '../hello.php', true);
    // xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Important for identifying AJAX request

    // xhr.onload = function() {
    //     if (xhr.status >= 200 && xhr.status < 300) {
    //         try {
    //             var response = JSON.parse(xhr.responseText);
    //             window.location.href='../../index.html';
    //         } catch (e) {
    //             console.error("Error parsing JSON", e);
    //             document.getElementById('username').innerHTML = "Error: Invalid JSON";
    //         }
    //     } else {
    //         window.location.href='../../index.html';
    //     }
    // };

    // xhr.onerror = function() {
    //     window.location.href='../../index.html';
    // };

    // xhr.send();
}