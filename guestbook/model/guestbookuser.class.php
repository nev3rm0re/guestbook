<?php
    class GuestbookUser extends ActiveRecord
    {
        public function __construct() {
            $this->fields = array('id', 'name', 'username', 'password');
        }

        public function __toString() {
            return $this->data['name'];
        }

        public function import($array) {
            $this->data = $array;
        }

        public function save() {
            return $this->storage->save($this);
        }

        public function toArray() {
            return $this->data;
        }

        public function hydrate($data) {
            $this->data = $data;
        }

        public function createPost()
        {
            $post = $this->storage->create('GuestbookPost');
            $post->setAuthor($this);

            return $post;
        }

        public function getPosts()
        {
            return $this->storage->findAllBy('GuestbookPost', 'user_id', $this['id']);
        }
    }