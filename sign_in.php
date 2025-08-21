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
    <title>Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Sign In</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="error">Invalid username or password!</div>
        <?php endif; ?>
        <?php if (isset($_GET['signup'])): ?>
            <div class="success">Signup successful! Please sign in.</div>
        <?php endif; ?>
        <form action="sever.php" method="POST" id="signinForm" novalidate>
            <input type="text" name="username" id="signin-username" placeholder="Username" required>
            <input type="password" name="password" id="signin-password" placeholder="Password" required>
            <button type="submit" name="signin">Sign In</button>
        </form>
        <script>
        const signinForm = document.getElementById('signinForm');
        const signinUsername = document.getElementById('signin-username');
        const signinPassword = document.getElementById('signin-password');

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
        signinForm.addEventListener('submit', function(e) {
            let valid = true;
            clearError(signinUsername);
            clearError(signinPassword);
            if (!signinUsername.value.trim()) {
                setError(signinUsername, 'Username is required');
                valid = false;
            }
            if (!signinPassword.value.trim()) {
                setError(signinPassword, 'Password is required');
                valid = false;
            }
            if (!valid) e.preventDefault();
        });
        [signinUsername, signinPassword].forEach(input => {
            input.addEventListener('input', () => clearError(input));
        });
        </script>
        <p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>
    </div>
</body>
</html>