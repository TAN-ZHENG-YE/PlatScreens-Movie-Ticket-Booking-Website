document.addEventListener('DOMContentLoaded', (event) => {
    // Attach input event listeners to each field
    document.getElementById("name").addEventListener("input", validateName);
    document.getElementById("email").addEventListener("input", validateEmailField);
    document.getElementById("phone").addEventListener("input", validatePhone);
    document.getElementById("subject").addEventListener("input", validateSubject);
    document.getElementById("message").addEventListener("input", validateMessage);
});

function sendMail(event) {
    event.preventDefault();

    if (!validateForm()) {
        alert("Please fill out all fields correctly.");
        return;
    }

    var params = {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        phone: document.getElementById("phone").value,
        subject: document.getElementById("subject").value,
        message: document.getElementById("message").value,
    };

    const serviceID = "service_iizee44";
    const templateID = "template_22dpi7q";

    emailjs.send(serviceID, templateID, params)
        .then(res => {
            document.getElementById("contact-form").reset();
            alert("Your message sent successfully!!");
        })
        .catch(err => console.log(err));
}

function validateForm() {
    let isValid = true;

    if (!validateName()) isValid = false;
    if (!validateEmailField()) isValid = false;
    if (!validatePhone()) isValid = false;
    if (!validateSubject()) isValid = false;
    if (!validateMessage()) isValid = false;

    return isValid;
}

function validateName() {
    const name = document.getElementById("name").value;
    if (!name) {
        showError("name", "Full name must be filled out!");
        return false;
    } else {
        hideError("name");
        return true;
    }
}

function validateEmailField() {
    const email = document.getElementById("email").value;
    if (!email) {
        showError("email", "Email Address can't be blank!");
        return false;
    } else if (!validateEmail(email)) {
        showError("email", "Please enter a valid email address.");
        return false;
    } else {
        hideError("email");
        return true;
    }
}

function validatePhone() {
    const phone = document.getElementById("phone").value;
    if (!phone) {
        showError("phone", "Phone Number must be filled out!");
        return false;
    } else {
        hideError("phone");
        return true;
    }
}

function validateSubject() {
    const subject = document.getElementById("subject").value;
    if (!subject) {
        showError("subject", "Subject must be filled out!");
        return false;
    } else {
        hideError("subject");
        return true;
    }
}

function validateMessage() {
    const message = document.getElementById("message").value;
    if (!message) {
        showError("message", "Message can't be blank!");
        return false;
    } else {
        hideError("message");
        return true;
    }
}

function showError(fieldId, message) {
    var field = document.getElementById(fieldId).parentElement;
    field.classList.add("error");
    field.querySelector(".error-txt").textContent = message;
}

function hideError(fieldId) {
    var field = document.getElementById(fieldId).parentElement;
    field.classList.remove("error");
}

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@(([^<>()[\]\\.,;:\s@"]+\.[^<>()[\]\\.,;:\s@"]{2,}))$/i;
    return re.test(String(email).toLowerCase());
}


//dark light mode

const ball = document.querySelector(".toggle-ball");
const items = document.querySelectorAll(
  ".container,.navbar-container,.left-menu-icon,.menu-list-item a,.toggle"
);

ball.addEventListener("click", () => {
  items.forEach((item) => {
    item.classList.toggle("active");
  });
  ball.classList.toggle("active");
});


