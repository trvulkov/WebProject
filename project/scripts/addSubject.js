function submitFormHandler(event) {
    event.preventDefault();

    const formElement = event.target;
    const formData = {
        name: formElement.querySelector('input[name="name"]').value,
        lecturer: formElement.querySelector('input[name="lecturer"]').value,
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

const form = document.querySelector('form');
form.addEventListener('submit', submitFormHandler);

fetch('./checkLogin.php')
    .then(response => response.json())
    .then(isLoggedResponse => {
        if (!isLoggedResponse.logged) {
            document.location = './index.html';
        }
    });