<?php
    /**
     * This class represents Web-site user
     */
    class User
    {
        /**
         * Returns `true` if the user is logged in
         * @return boolean
         */
        public function isAuthenticated() {
            return array_key_exists('user', $_SESSION) && !empty($_SESSION['user']);
        }

        /**
         * Logs in user with username $username
         * @param  string $username
         * @return
         */
        public function authenticate($username)
        {
            // refresh session id
            session_regenerate_id();
            $_SESSION['user'] = $username;
        }

        /**
         * Returns underlying GuestbookUser object
         *
         * @return GuestbookUser
         */
        public function getGuestbookUser()
        {
            $storage = ServiceLocator::getInstance()->buildStorage();
            return $storage->findBy('GuestbookUser', 'username', $_SESSION['user']);
        }

        /**
         * Proxy method to access name of the GuestbookUser object
         * @return string
         */
        public function getName() {
            $user = $this->getGuestbookUser();
            return $user['name'];
        }

        /**
         * Logs out user
         */
        public function logout() {
            unset($_SESSION['user']);
        }

        /**
         * Set user message to be shown after redirect
         *
         * @param string $type    error|success|info|notice
         * @param string $message
         */
        public function setFlash($type, $message)
        {
            $_SESSION["flash"] = array('type' => $type, 'message' => $message);
        }

        /**
         * Returns user message set previously
         * @return array
         */
        public function getFlash()
        {
            return $_SESSION['flash'];
        }

        /**
         * Returns `true` if user message exists
         * @return boolean [description]
         */
        public function hasFlash()
        {
            $result = array_key_exists('flash', $_SESSION);
            return $result;
        }

        /**
         * Clear user message
         */
        public function clearFlash()
        {
            unset($_SESSION['flash']);
        }
    }