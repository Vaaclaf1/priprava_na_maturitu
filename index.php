<form action="register.php" method="POST">
    <div class="form-main">
        <div class="form-main-inputs">
            <div class="form-main-inputs-label">
                <label>Username</label>
            </div>
            <input type="text" name="username" placeholder="Username (alphanumeric only)" required>
        </div>

        <div class="form-main-inputs">
            <div class="form-main-inputs-label">
                <label>Password</label>
            </div>
            <input type="password" name="password" placeholder="Enter your password (min 6 characters)" required>
        </div>

        <div class="form-main-inputs">
            <div class="form-main-inputs-label">
                <label>Confirm Password</label>
            </div>
            <input type="password" name="password2" placeholder="Confirm your password" required>
        </div>
    </div>

    <div class="form-control">
        <input type="checkbox" name="terms" required>
        <label>I agree with <a href="#">terms of service</a></label>
    </div>

    <div class="form-buttons">
        <button type="submit">Create Account</button>
    </div>       
</form>
