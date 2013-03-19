<?php
    class ServiceLocator {
        private static $instance;
        public static function getInstance($config_file = null) {
            if (self::$instance === null) {
                self::$instance = new self($config_file);
            }

            return self::$instance;
        }

        private static $services = array();

        public function getController() {
            $controller_class = $this->config['controller']['class'];
            $controller = $controller_class::getInstance();
            $controller->setServiceLocator($this);
            return $controller;
        }

        public function buildViewManager() {
            $view_manager_class = $this->config['viewManager']['class'];
            return $view_manager_class::getInstance();
        }

        private $config;
        protected function __construct($config_file = null)
        {
            $default_config = array(
                'controller' =>
                    array(
                        'class' => 'Controller'
                    ),
                'viewManager' =>
                    array(
                        'class' => 'ViewManager'
                    ),
                'storage' =>
                    array(
                        'class' => 'GuestbookPDO',
                        'options' => array(
                            'dsn' => 'mysql:host=localhost;dbname=guestbook',
                            'username' => 'root',
                            'password' => 'QPwoei10'
                        )
                    )
            );
            if ($config_file !== null) {
                include($config_file);
                $this->config = $this->merge($default_config, $config);
            } else {
                $this->config = $default_config;
            }
        }
        /**
         * Recursively merge arrays.
         *
         * array_merge_recursive() creates duplicates when both arrays have the same key.
         * This method overwrites duplicate keys with values from second array.
         *
         * @param  array $array1
         * @param  array $array2
         * @return array
         */
        private function merge($array1, $array2) {
            $merged = $array1;
            foreach ($array2 as $key => $value) {
                if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                    $merged[$key] = $this->merge($merged[$key], $value);
                } else {
                    $merged[$key] = $value;
                }
            }
            return $merged;
        }

        public function buildStorage()
        {
            $class = $this->config['storage']['class'];
            return new $class($this->config['storage']['options']);
        }
    }