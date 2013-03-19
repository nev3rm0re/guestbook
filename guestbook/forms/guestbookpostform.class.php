<?php
    class GuestbookPostForm extends Form
    {
        public function __construct($object = null) {
            $this->object = $object;
            $this->data = array(
                'csrf' => $this->getCSRFToken(),
                'message' => '',
            );
        }

        public function validate() {
            $errors = array();

            if ($this->data['csrf'] != $this->getCSRFToken()) {
                $errors[] = 'Cross-site request forgery detected';
            }

            if (trim($this->data['message']) == '') {
                $errors['message'] = 'Message is empty';
            }

            $this->errors = $errors;
        }

        public function save() {
            $clean_data = array();
            $clean_data['message'] = $this->data['message'];

            $this->object->import($clean_data);
            return $this->object->save();
        }
    }