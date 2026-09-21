<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/user.php';

/**
 * Auth controller.
 * Handles registration, login, logout and the session/cookie work that
 * goes with them.
 */
class AuthController extends Controller
{
    /**
     * Show the registration form.
     * $errors and $old let us re-show the form with messages + typed values.
     */
    public function showRegister($errors = [], $old = [])
    {
        $this->view('auth/register', [
            'errors' => $errors,
            'old' => $old
        ]);
    }

    /**
     * Validate the register form and create the user.
     * On error the form is shown again with the messages.
     * On success we go to the login page.
     */
    public function register($data)
    {
        $firstName = trim($data['firstName'] ?? '');
        $lastName = trim($data['lastName'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $location = trim($data['location'] ?? '');
        $description = trim($data['description'] ?? '');
        $occupation = trim($data['occupation'] ?? '');

        // ---- server side validation ----
        $errors = [];

        // names: required, letters (and spaces) only, max 50
        if (!preg_match('/^[A-Za-z ]{1,50}$/', $firstName)) {
            $errors[] = "First name is required and must be letters only (max 50).";
        }
        if (!preg_match('/^[A-Za-z ]{1,50}$/', $lastName)) {
            $errors[] = "Last name is required and must be letters only (max 50).";
        }

        // email: required, valid format, max 100
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
            $errors[] = "Please enter a valid email address.";
        }

        // password: at least 6 characters
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }

        $userModel = new User();

        // email must be unique
        if (empty($errors) && $userModel->findByEmail($email)) {
            $errors[] = "This email is already registered.";
        }

        // keep what the user typed so we can refill the form (never the password)
        $old = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'location' => $location,
            'description' => $description,
            'occupation' => $occupation
        ];

        if (!empty($errors)) {
            $this->showRegister($errors, $old);
            return;
        }

        // hash the password so the raw value is never stored
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $created = $userModel->create(
            $firstName,
            $lastName,
            $email,
            $hashedPassword,
            $location !== '' ? $location : null,
            $description !== '' ? $description : null,
            $occupation !== '' ? $occupation : null
        );

        if ($created) {
            $_SESSION['flash'] = "Registration successful. You can log in now.";
            $this->redirect('/login');
        } else {
            $this->showRegister(["Something went wrong, please try again."], $old);
        }
    }

    /**
     * Show the login form.
     */
    public function showLogin($errors = [], $old = [])
    {
        $this->view('auth/login', [
            'errors' => $errors,
            'old' => $old
        ]);
    }

    /**
     * Check the login details. If they are correct we start the session,
     * write the "last login" cookie and go to the home page. Otherwise we
     * show the form again with an error.
     */
    public function login($data)
    {
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        $errors = [];
        if ($email === '' || $password === '') {
            $errors[] = "Please enter your email and password.";
            $this->showLogin($errors, ['email' => $email]);
            return;
        }

        $userModel = new User();
        $foundUser = $userModel->findByEmail($email);

        // one generic message so we don't reveal which part was wrong
        if (!$foundUser || !password_verify($password, $foundUser['password'])) {
            $this->showLogin(["Invalid email or password."], ['email' => $email]);
            return;
        }

        $_SESSION['user_id'] = $foundUser['id'];

        // remember the last login time on this browser for 7 days
        setcookie(
            'last_login',
            date('Y-m-d H:i:s'),
            time() + (7 * 24 * 60 * 60),
            '/'
        );

        $this->redirect('/');
    }

    /**
     * Log the user out and clear the session.
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('/');
    }
}
