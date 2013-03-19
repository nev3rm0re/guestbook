<?php
    class Form implements ArrayAccess
    {
        public function getCSRFToken()
        {
            return md5(get_class().session_id());
        }

        protected $errors = array();

        public function isValid() {
            $this->validate();
            return $this->hasErrors() != true;
        }

        public function hasErrors()
        {
            return count($this->errors) > 0;
        }

        public function getErrors()
        {
            return $this->errors;
        }

        protected $data = array();
        public function bind($data) {
            $this->data = $data;
        }

        public function offsetGet($key) {
            return $this->data[$key];
        }

        public function offsetSet($key, $value)
        {
            $this->data[$key] = $value;
        }

        public function offsetUnset($key) {
            unset($this->data[$key]);
        }

        public function offsetExists($key)
        {
            return array_key_exists($key, $this->data);
        }
    }