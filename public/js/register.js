const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');
const registerForm = document.getElementById('registerForm');
const loginForm = document.getElementById('loginForm');

// Handle panel switching
registerBtn.addEventListener('click', () => {
    container.classList.add('active');
});

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});

// Show flash message function
const showFlashMessage = (message, isError = false) => {
    const flashDiv = document.createElement('div');
    flashDiv.className = `flash-message${isError ? ' error' : ''}`;
    flashDiv.textContent = message;
    document.body.appendChild(flashDiv);

    setTimeout(() => {
        flashDiv.remove();
    }, 11000);  // 10.5s for animation + 0.5s buffer
};

// Handle form submissions
registerForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(registerForm);

    try {
        const response = await fetch(registerForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();
        console.log('Registration response:', data);

        if (response.ok) {
            localStorage.setItem('token', data.token);
            showFlashMessage(data.message);
            window.location.href = '/products';
        } else {
            const errorMessage = data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'Registration failed');
            showFlashMessage(errorMessage, true);
        }
    } catch (error) {
        console.error('Registration error:', error);
        showFlashMessage('An error occurred during registration', true);
    }
});

loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(loginForm);

    try {
        const response = await fetch(loginForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();
        console.log('Login response:', data);

        if (response.ok) {
            localStorage.setItem('token', data.token);
            showFlashMessage(data.message);
            window.location.href = '/products';
        } else {
            const errorMessage = data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'Login failed');
            showFlashMessage(errorMessage, true);
        }
    } catch (error) {
        console.error('Login error:', error);
        showFlashMessage('An error occurred during login', true);
    }
});