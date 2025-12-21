document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('fileUploadForm').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the page from refreshing

        const userId = document.getElementById('UserID').innerText; // Get the User ID
        const uploadedFile = document.getElementById('actual-file-input').files[0]; // Get the file

        if (userId && uploadedFile) {
            submitFile(userId, uploadedFile); // Call Ajax function
        } else {
            alert('Please provide a file ID and upload a file.');
        }
    });
});

function submitFile(userId, file) {
    const formData = new FormData();
    formData.append('compare_files', true);
    formData.append('user_id', userId);
    formData.append('uploaded_file', file);

    const msgBox = document.getElementById('error');

    fetch('../backend/hello.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log(data); // Debug response
        if (data.status === 'success') {
            msgBox.classList.remove('error');
            msgBox.classList.add('success');
            msgBox.innerText = data.message ;
        } else {
            msgBox.classList.remove('success');
            msgBox.classList.add('error');
            msgBox.innerHTML = data.message ;
        }
    })
    .catch(error => {
        console.error('There was a network error:', error);
        msgBox.classList.remove('success');
        msgBox.classList.add('error');
        msgBox.innerText = 'Failed to connect to the server or process the response.' +error;
    });
}
document.getElementById('logout').addEventListener('click',logout);
function logout() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../../backend/hello.php', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Important for identifying AJAX request

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                var response = JSON.parse(xhr.responseText);
                rwindow.location.href='../index.html';
            } catch (e) {
                console.error("Error parsing JSON", e);
                document.getElementById('username').innerHTML = "Error: Invalid JSON";
            }
        } else {
            window.location.href='../index.html';
        }
    };

    xhr.onerror = function() {
        window.location.href='../index.html';
    };

    xhr.send();
}