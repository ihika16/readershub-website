/**
 * BookShare Authentication Page JavaScript
 * Handles all interactive functionality for the sign-in and sign-up forms
 */

document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const loginTab = document.getElementById('login-tab');
    const signupTab = document.getElementById('signup-tab');
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    const passwordInput = document.getElementById('signup-password');
    const strengthBar = document.querySelector('.strength-bar');
    const strengthText = document.querySelector('.strength-text');
    const confirmPassword = document.getElementById('confirm-password');
    const loginButton = document.querySelector('#login-form button[type="submit"]');
    const signupButton = document.querySelector('#signup-form button[type="submit"]');
    const header = document.getElementById('main-header');
    
    /**
     * Tab switching functionality
     */
    function initTabs() {
        loginTab.addEventListener('click', function() {
            loginTab.classList.add('active');
            signupTab.classList.remove('active');
            loginForm.classList.add('active');
            signupForm.classList.remove('active');
        });
        
        signupTab.addEventListener('click', function() {
            signupTab.classList.add('active');
            loginTab.classList.remove('active');
            signupForm.classList.add('active');
            loginForm.classList.remove('active');
        });
    }
    
    /**
     * Toggle password visibility functionality
     */
    function initPasswordToggles() {
        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                if (input.type === 'password') {
                    input.type = 'text';
                    this.textContent = '🔒';
                } else {
                    input.type = 'password';
                    this.textContent = '👁️';
                }
            });
        });
    }
    
    /**
     * Password strength meter
     */
    function initPasswordStrengthMeter() {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let feedback = '';
            
            // Length check
            if (password.length >= 8) {
                strength += 25;
            }
            
            // Uppercase letter check
            if (password.match(/[A-Z]/)) {
                strength += 25;
            }
            
            // Number check
            if (password.match(/[0-9]/)) {
                strength += 25;
            }
            
            // Special character check
            if (password.match(/[^A-Za-z0-9]/)) {
                strength += 25;
            }
            
            // Update UI
            strengthBar.style.width = strength + '%';
            
            // Set color and text based on strength
            if (strength <= 25) {
                strengthBar.style.backgroundColor = '#ff4d4d';
                strengthText.textContent = 'Weak password';
            } else if (strength <= 50) {
                strengthBar.style.backgroundColor = '#ffa64d';
                strengthText.textContent = 'Fair password';
            } else if (strength <= 75) {
                strengthBar.style.backgroundColor = '#ffff4d';
                strengthText.textContent = 'Good password';
            } else {
                strengthBar.style.backgroundColor = '#4dff4d';
                strengthText.textContent = 'Strong password';
            }
            
            // Check confirm password match if it has content
            if (confirmPassword.value) {
                checkPasswordMatch();
            }
        });
    }
    
    /**
     * Password match validation
     */
    function initPasswordMatchValidation() {
        confirmPassword.addEventListener('input', checkPasswordMatch);
    }
    
    function checkPasswordMatch() {
        if (confirmPassword.value !== passwordInput.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
            // Add visual indication
            confirmPassword.classList.add('invalid');
        } else {
            confirmPassword.setCustomValidity('');
            // Remove visual indication
            confirmPassword.classList.remove('invalid');
        }
    }
    
    /**
     * Header scroll effect
     */
    function initHeaderScrollEffect() {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
    
    /**
     * Form submission handling
     */
    function initFormSubmission() {
        // Login form submission
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            const remember = document.getElementById('remember').checked;
            
            // Here you would normally send this data to your server
            console.log('Login attempt:', { email, password, remember });
            
            // Simulate login (replace with actual API call)
            simulateApiCall({ email, password, remember })
                .then(response => {
                    if (response.success) {
                        showNotification('Login successful! Redirecting...', 'success');
                        setTimeout(() => {
                            window.location.href = 'dashboard.html';
                        }, 1500);
                    } else {
                        showNotification('Invalid email or password', 'error');
                    
                        // Highlight input fields
                        document.getElementById('login-email').classList.add('invalid');
                        document.getElementById('login-password').classList.add('invalid');
                    }
                    
                })
                .catch(error => {
                    showNotification('An error occurred. Please try again.', 'error');
                });
        });
        
        // Signup form submission
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const firstName = document.getElementById('first-name').value;
            const lastName = document.getElementById('last-name').value;
            const email = document.getElementById('signup-email').value;
            const password = document.getElementById('signup-password').value;
            const termsAccepted = document.getElementById('terms').checked;
            
            if (!termsAccepted) {
                showNotification('You must accept the Terms of Service', 'error');
                return;
            }
            
            // Here you would normally send this data to your server
            console.log('Registration attempt:', { firstName, lastName, email, password });
            
            // Simulate registration (replace with actual API call)
            simulateApiCall({ firstName, lastName, email, password })
                .then(response => {
                    if (response.success) {
                        showNotification('Account created successfully! Redirecting...', 'success');
                        setTimeout(() => {
                            window.location.href = 'verification.html';
                        }, 1500);
                    } else {
                        showNotification('Registration failed. Email might be already in use.', 'error');
                    }
                })
                .catch(error => {
                    showNotification('An error occurred. Please try again.', 'error');
                });
        });
    }
    
    /**
     * Show notification message
     */
    function showNotification(message, type) {
        // Check if notification container exists, create if not
        let notificationContainer = document.querySelector('.notification-container');
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.className = 'notification-container';
            document.body.appendChild(notificationContainer);
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        
        // Add to container
        notificationContainer.appendChild(notification);
        
        // Auto remove after delay
        setTimeout(() => {
            notification.classList.add('hide');
            setTimeout(() => {
                notificationContainer.removeChild(notification);
            }, 500);
        }, 3000);
    }
    
    /**
     * Simulate API call (for demo purposes)
     * In a real application, replace with actual fetch/axios calls
     */
    function simulateApiCall(data) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                // Simulate successful response
                resolve({ success: true, data: { userId: 'user123' } });
                
                // Uncomment to simulate error
                // reject(new Error('Network error'));
            }, 1000);
        });
    }
    
    /**
     * Initialize social login buttons
     */
    function initSocialLogin() {
        const socialButtons = document.querySelectorAll('.btn-social');
        
        socialButtons.forEach(button => {
            button.addEventListener('click', function() {
                const provider = this.classList.contains('google') ? 'Google' : 
                                this.classList.contains('facebook') ? 'Facebook' : 'Twitter';
                                
                console.log(`Attempting to login with ${provider}`);
                showNotification(`${provider} login initiated...`, 'info');
                
                // In a real app, you would redirect to OAuth provider or use a library
                // window.location.href = `/auth/${provider.toLowerCase()}`;
            });
        });
    }
    
    // Initialize all functionality
    initTabs();
    initPasswordToggles();
    initPasswordStrengthMeter();
    initPasswordMatchValidation();
    initHeaderScrollEffect();
    initFormSubmission();
    initSocialLogin();
    
    // Add additional CSS for notifications
    addNotificationStyles();
    
    function addNotificationStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .notification-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
            }
            
            .notification {
                padding: 15px 20px;
                margin-bottom: 10px;
                border-radius: 4px;
                color: white;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                opacity: 1;
                transition: opacity 0.3s, transform 0.3s;
                transform: translateX(0);
            }
            
            .notification.hide {
                opacity: 0;
                transform: translateX(100%);
            }
            
            .notification.success {
                background-color: #2ecc71;
            }
            
            .notification.error {
                background-color: #e74c3c;
            }
            
            .notification.info {
                background-color: #3498db;
            }
            
            input.invalid {
                border-color: #e74c3c !important;
                box-shadow: 0 0 0 2px rgba(231, 76, 60, 0.2) !important;
            }
        `;
        document.head.appendChild(style);
    }
});
