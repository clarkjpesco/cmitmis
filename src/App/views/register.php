<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up - TCMPrint</title>
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
                Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            min-height: 100vh;
            line-height: 1.6;
        }

        /* Navigation */
        .nav {
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 0 20px;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 64px;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-text {
            color: #64748b;
        }

        .nav-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: #4f46e5;
        }

        /* Main Content */
        .container {
            max-width: 480px;
            margin: 0 auto;
            padding: 48px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .title {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #64748b;
            font-size: 16px;
        }

        /* Form Styles */
        .form-card {
            background: white;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-input,
        .form-textarea,
        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.2s ease;
            background: white;
        }

        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: #374151;
        }

        /* Checkbox */
        .checkbox-group-column {
            display: flex;
            flex-direction: column;
            margin-bottom: 24px;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 12px;

        }

        .checkbox {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            accent-color: #2563eb;
        }

        .checkbox-label {
            color: #64748b;
            font-size: 14px;
            line-height: 1.5;
            flex: 1;
        }

        .checkbox-label a {
            color: #2563eb;
            text-decoration: none;
        }

        .checkbox-label a:hover {
            text-decoration: underline;
        }

        /* Buttons */
        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 16px;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: #64748b;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .divider-text {
            padding: 0 16px;
        }

        /* Social Button */
        .btn-google {
            width: 100%;
            background: white;
            color: #374151;
            border: 2px solid #e5e7eb;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .btn-google:hover {
            border-color: #d1d5db;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .google-icon {
            width: 20px;
            height: 20px;
        }

        /* Footer Text */
        .footer-text {
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        .footer-text a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        /* Success Message */
        .success-message {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: none;
        }

        /* Error Message */
        .error-message {

            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: block;

            /* background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: block; */
        }

        /* Responsive */
        @media (max-width: 640px) {
            .nav-container {
                padding: 0 16px;
            }

            .nav-links {
                flex-direction: column;
                gap: 8px;
            }

            .nav-text {
                font-size: 14px;
            }

            .container {
                padding: 24px 16px;
            }

            .form-card {
                padding: 24px;
            }

            .title {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="nav">
        <div class="nav-container">
            <a href="/" class="logo">TCMPrint</a>
            <div class="nav-links">
                <span class="nav-text">Already have an account?</span>
                <a href="/login" class="nav-link">Sign In</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="header">
            <h1 class="title">Create Account</h1>
            <p class="subtitle">Join TCMPrint for seamless printing services</p>
        </div>

        <div class="form-card">
            <!-- Success/Error Messages -->
            <!-- <div id="successMessage" class="success-message">
                <i class="fas fa-check-circle"></i> Account created successfully!
            </div>
            <div id="errorMessage" class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span id="errorText">Please fill in all required fields.</span>
            </div> -->

            <!-- Sign Up Form -->
            <form method="POST" id="signupForm">
                <?php include $this->resolve("partials/_csrf.php"); ?>
                <div class="form-group">
                    <label for="fullName" class="form-label">Full Name *</label>
                    <input
                        type="text"
                        id="fullName"
                        name="fullName"
                        value="<?php echo e($oldFormData['fullName'] ?? ''); ?>"
                        class="form-input"

                        placeholder="Enter your full name" />
                    <?php if (array_key_exists('fullName', $errors)) : ?>
                        <div class="error-message">
                            <?php echo e($errors['fullName'][0]); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="username" class="form-label">Username *</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?php echo e($oldFormData['username'] ?? ''); ?>"
                        class="form-input"
                        placeholder="Choose a username" />
                    <?php if (array_key_exists('username', $errors)) : ?>
                        <div class="error-message">
                            <?php echo e($errors['username'][0]); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address *</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo e($oldFormData['email'] ?? ''); ?>"
                        class="form-input"
                        placeholder="Enter your email" />
                    <?php if (array_key_exists('email', $errors)) : ?>
                        <div class="error-message">
                            <?php echo e($errors['email'][0]); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number *</label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="<?php echo e($oldFormData['phone'] ?? ''); ?>"
                        class="form-input"
                        placeholder="Enter your phone number" />
                    <?php if (array_key_exists('phone', $errors)) : ?>
                        <div class="error-message">
                            <?php echo e($errors['phone'][0]); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Address *</label>
                    <textarea
                        id="address"
                        name="address"
                        class="form-textarea"
                        placeholder="Enter your complete address">
                    <?php echo e($oldFormData['address'] ?? ''); ?>
                    </textarea>
                    <?php if (array_key_exists('address', $errors)) : ?>
                        <div class="error-message">
                            <?php echo e($errors['address'][0]); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password *</label>
                    <div class="password-container">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Create a strong password" />
                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword('password')">
                            <i id="passwordIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php if (array_key_exists('password', $errors)) : ?>
                        <div class="error-message">
                            <?php echo e($errors['password'][0]); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="confirmPassword" class="form-label">Confirm Password *</label>
                    <div class="password-container">
                        <input
                            type="password"
                            id="confirmPassword"
                            name="confirmPassword"
                            class="form-input"
                            placeholder="Confirm your password" />
                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword('confirmPassword')">
                            <i id="confirmPasswordIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php if (array_key_exists('confirmPassword', $errors)) : ?>
                        <div class="error-message">
                            <?php echo e($errors['confirmPassword'][0]); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="checkbox-group-column">
                    <div class="checkbox-group">
                        <input
                            name="tos"
                            <?php echo $oldFormData['tos'] ?? false ? 'checked' : ''; ?>
                            type="checkbox"
                            id="terms"
                            class="checkbox" />
                        <label for="terms" class="checkbox-label">
                            I agree to the
                            <a href="terms.html" target="_blank">Terms of Service</a> and
                            <a href="privacy.html" target="_blank">Privacy Policy</a>
                        </label>

                    </div>
                    <?php if (array_key_exists('tos', $errors)) : ?>
                        <div class="error-message">
                            <?php echo e($errors['tos'][0]); ?>
                        </div>
                    <?php endif; ?>

                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <div class="footer-text">
                Already have an account? <a href="/login">Sign in here</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
    </script>
</body>

</html>