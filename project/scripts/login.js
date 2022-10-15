function submitFormHandler(event) {
    event.preventDefault();
    
    const formElement = event.target;
    const formData = {
        username: formElement.querySelector('input[name="username"]').value,
        password: formElement.querySelector('input[name="password"]').value
    };

    fetch(formElement.action, {
        method: 'POST',
        body: JSON.stringify(formData),
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            document.location = 'homepage.html';
        } else if (result.error) {
            let message = document.getElementById('message');
            message.innerHTML = result.error;
            message.style.color = "red";
        }
    });
}

const form = document.querySelector('form');
form.addEventListener('submit', submitFormHandler);