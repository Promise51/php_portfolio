<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Sign Up</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="error">Username or Email already exists!</div>
        <?php endif; ?>
        <form action="sever.php" method="POST" id="signupForm" novalidate>
            <input type="text" name="username" id="username" placeholder="Username" required>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <button type="submit" name="signup">Sign Up</button>
        </form>
        <script>
        const form = document.getElementById('signupForm');
        const email = document.getElementById('email');
        const username = document.getElementById('username');
        const password = document.getElementById('password');

        function setError(input, message) {
            input.style.borderColor = 'red';
            if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('input-error')) {
                const error = document.createElement('div');
                error.className = 'input-error';
                error.style.color = 'red';
                error.style.fontSize = '13px';
                error.innerText = message;
                input.parentNode.insertBefore(error, input.nextSibling);
            } else {
                input.nextElementSibling.innerText = message;
            }
        }
        function clearError(input) {
            input.style.borderColor = '';
            if (input.nextElementSibling && input.nextElementSibling.classList.contains('input-error')) {
                input.nextElementSibling.remove();
            }
        }
        form.addEventListener('submit', function(e) {
            let valid = true;
            clearError(username);
            clearError(email);
            clearError(password);
            if (!username.value.trim()) {
                setError(username, 'Username is required');
                valid = false;
            }
            if (!email.value.trim()) {
                setError(email, 'Email is required');
                valid = false;
            } else if (!/^\S+@\S+\.\S+$/.test(email.value)) {
                setError(email, 'Enter a valid email');
                valid = false;
            }
            if (!password.value.trim()) {
                setError(password, 'Password is required');
                valid = false;
            }
            if (!valid) e.preventDefault();
        });
        [username, email, password].forEach(input => {
            input.addEventListener('input', () => clearError(input));
        });
        </script>
        <p>Already have an account? <a href="sign_in.php">Sign In</a></p>
    </div>
</body>
</html>