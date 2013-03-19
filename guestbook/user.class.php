<?php
    class User
    {
        public function isAuthenticated() {
            return array_key_exists('user', $_SESSION) && !empty($_SESSION['user']);
        }

        public function authenticate($username) {
            session_regenerate_id();
            $_SESSION['user'] = $username;
        }

        public function getGuestbookUser()
        {
            $storage = ServiceLocator::getInstance()->buildStorage();
            return $storage->findBy('GuestbookUser', 'username', $_SESSION['user']);
        }

        public function getName() {
            $user = $this->getGuestbookUser();
            return $user['name'];
        }

        public function logout() {
            unset($_SESSION['user']);
        }

        public function setFlash($type, $message)
        {
            $_SESSION["flash"] = array('type' => $type, 'message' => $message);
        }

        public function getFlash() {
            return $_SESSION['flash'];
        }

        public function hasFlash() {
            $result = array_key_exists('flash', $_SESSION);
            return $result;
        }

        public function clearFlash()
        {
            unset($_SESSION['flash']);
        }
    }