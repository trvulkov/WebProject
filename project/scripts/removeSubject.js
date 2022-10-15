function subjectLoader() {
    fetch('removeSubject.php', {
        method: 'GET',
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            const selectElement = document.querySelector('select');

            for (let name of result.names) {
                let option = document.createElement('option');
                option.text = name;
                selectElement.add(option);
            }

        } else if (result.error) {
            let message = document.getElementById('message');

            message.innerHTML = result.error;
            message.style.color = "red";
        }
    });
}

function submitFormHandler(event) {
    event.preventDefault();

    const formElement = event.target;
    const formData = {
        name: formElement.querySelector('select[name="name"]').value,
    };

    fetch(formElement.action, {
        method: 'POST',
        body: JSON.stringify(formData),
    })
    .then(response => response.json())
    .then(result => {
        let message = document.getElementById('message');

        if (result.success) {
            console.log(result.message);
            document.location = 'removeSubject.html';
        } else if (result.error) {
            message.innerHTML = result.error;
            message.style.color = "red";
        } else if (result.errors) {
            message.innerHTML = JSON.stringify(result.errors);
            message.style.color = "red";
        }
    });

}

window.addEventListener('DOMContentLoaded', subjectLoader);

const form = document.querySelector('form');
form.addEventListener('submit', submitFormHandler);

fetch('./checkLogin.php')
    .then(response => response.json())
    .then(isLoggedResponse => {
        if (!isLoggedResponse.logged) {
            document.location = './index.html';
        }
    });