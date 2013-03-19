<?php
    class GuestbookPost extends ActiveRecord
    {
        public function __construct() {
            $this->fields = array('user_id', 'message', 'created_at');
            $this->belongsTo('GuestbookUser', array('as' => 'author', 'key' => 'user_id'));
        }

        public function getAuthor() {
            return $this->storage->load('GuestbookUser', $this['user_id']);
        }

        public function setAuthor(GuestbookUser $author) {
            $this['user_id'] = $author['id'];
        }

        public function fromArray($array)
        {
            $this->data = array_merge($this->data, array_intersect_key($array, $this->data));
        }

        public function import($array) {
            $this->data = array_merge($this->data, $array);
        }

        public function toArray() {
            $export = array();
            foreach ($this->fields as $field) {
                if (array_key_exists($field, $this->data)) {
                    $export[$field] = $this->data[$field];
                } else {
                    $export[$field] = null;
                }
            }
            return $export;
        }

        public function hydrate($array)
        {
            $this->data = $array;
        }

        public function save()
        {
            $this['created_at'] = date('Y-m-d H:i:s', time());
            return $this->storage->save($this);
        }

    }