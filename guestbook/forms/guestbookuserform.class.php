<?php
    class GuestbookUserForm extends Form
    {
        public function __construct($object = null)
        {
            $this->object = $object;
            $this->data = array(
                'csrf' => $this->getCSRFToken(),
                'username' => '',
                'name' => '',
                'password' => '',
                'password_repeat' => '',
            );
        }

        public function validate()
        {
            $errors = array();

            if (!array_key_exists('csrf', $this->data)
                || $this->data['csrf'] != $this->getCSRFToken()) {
                $errors[] = 'Cross-site request forgery detected';
            }

            if (!array_key_exists('username', $this->data)
                || trim($this->data['username']) == '') {
                $errors['username'] = 'Username is missing';
            }

            if (!array_key_exists('name', $this->data)
                || trim($this->data['name'] == '')) {
                $errors['name'] = 'Name is missing';
            }

            if (!array_key_exists('password', $this->data)
                || trim($this->data['password'] == '')) {
                $errors['password'] = 'Password is missing';
            }

            if (!array_key_exists('password_repeat', $this->data) ||
                $this->data['password'] != $this->data['password_repeat']) {
                $errors['password_repeat'] = 'Passwords do not match';
            }

            if (count($errors) == 0) {
                $storage = $this->object->getStorage();
                $user = $storage->findBy('GuestbookUser', 'username', $this->data['username']);
                if ($user !== false) {
                    $errors[] = 'Username already taken';
                }
            }

            $this->errors = $errors;
        }

        public function save() {
            $clean_data = array(
                'username' => $this->data['username'],
                'name'     => $this->data['name'],
                'password' => crypt($this->data['password']),
            );

            $this->object->import($clean_data);
            return $this->object->save();
        }
    }