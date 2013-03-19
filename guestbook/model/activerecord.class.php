<?php
    class ActiveRecord implements ArrayAccess
    {
        protected $storage = null;
        public function setStorage($storage) {
            $this->storage = $storage;
        }
        public function getStorage()
        {
            return $this->storage;
        }

        /**
         * ArrayAccess implentation
         */

        public function offsetGet($key)
        {
            if (array_key_exists($key, $this->data)) {
                return $this->data[$key];
            } else {
                $method_name = 'get'.$key;
                if (method_exists($this, $method_name)) {
                    $this->data[$key] = $this->$method_name();
                }
                return $this->data[$key];
            }
        }

        public function offsetSet($key, $value)
        {
            $this->data[$key] = $value;
        }

        public function offsetUnset($key)
        {
            unset($this->data[$key]);
        }

        public function offsetExists($key)
        {
            $properties = array_keys($this->data);
            $properties = array_merge(array_keys($this->relations(), $properties));
            return in_array($key, $properties);
        }

        public function belongsTo($object, $options = array())
        {
            $options['class'] = $object;
            if (array_key_exists('as', $options)) {
                $field_name = $options['as'];
            } else {
                $field_name = $object;
            }

            $this->relations[$field_name] = $options;
        }

        public function hasMany($object, $options = array())
        {
            $options['class'] = $object;
            if (array_key_exists('as', $options)) {
                $field_name = $options['as'];
            } else {
                $field_name = $object;
            }

            $this->relations[$field_name] = $options;
        }
    }