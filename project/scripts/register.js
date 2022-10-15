function submitFormHandler(event) {
    event.preventDefault();

    const formElement = event.target;
    const formData = {
        username: formElement.querySelector('input[name="username"]').value,
        email: formElement.querySelector('input[name="email"]').value,
        password: formElement.querySelector('input[name="password"]').value,
        confirm: formElement.querySelector('input[name="confirm"]').value,
    };

    fetch(formElement.action, {
        method: 'POST',
        body: JSON.stringify(formData),
    })
    .then(response => response.json())
    .then(result => {
        let message = document.getElementById('message');

        if (result.success) {
            document.location = 'homepage.html';
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