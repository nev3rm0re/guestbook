<?php
    class LoginForm extends Form
    {
        public function __construct() {
            $this->data = array(
                'csrf' => $this->getCSRFToken(),
                'username' => '',
                'password' => '',
            );
        }

        public function validate()
        {
            $errors = array();

            if (!array_key_exists('csrf', $this->data) ||
                $this->data['csrf'] != $this->getCSRFToken()) {
                $errors[] = 'Cross-site request forgery detected';
            }

            if (!array_key_exists('username', $this->data)) {
                $errors['username'] = 'Username is missing';
            }

            if (!array_key_exists('password', $this->data)) {
                $errors['password'] = 'Password is missing';
            }

            if (count($errors) == 0) {
                $storage = ServiceLocator::getInstance()->buildStorage();
                $user = $storage->findBy('GuestbookUser', 'username', $this->data['username']);

                if ($user === false) {
                    $errors[] = 'No user by that username found';
                } else {
                    if (crypt($this->data['password'], $user['password']) != $user['password']) {
                        $errors[] = "Wrong password";
                    }
                }
            }

            $this->errors = $errors;
        }
    }