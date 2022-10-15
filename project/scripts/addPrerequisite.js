function subjectLoader() {
    fetch('addPrerequisite.php', {
        method: 'GET',
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            const subjectSelect = document.querySelector('select[name="subject"]');
            const prerequisiteSelect = document.querySelector('select[name="prerequisite"]');

            for (let name of result.names) {
                let option1 = document.createElement('option');
                option1.text = name;
                subjectSelect.add(option1);

                let option2 = document.createElement('option');
                option2.text = name;
                prerequisiteSelect.add(option2);
            }
        } else if (result.error) {
            console.error(result.error);
        }
    });
}

function submitFormHandler(event) {
    event.preventDefault();

    const formElement = event.target;
    const formData = {
        subject: formElement.querySelector('select[name="subject"]').value,
        prerequisite: formElement.querySelector('select[name="prerequisite"]').value,
    };

    fetch(formElement.action, {
        method: 'POST',
        body: JSON.stringify(formData),
    })
    .then(response => response.json())
    .then(result => {
        let message = document.getElementById('message');

        if (result.success) {
            message.innerHTML = result.message
            message.style.color = "green";
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
