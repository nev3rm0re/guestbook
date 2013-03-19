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
            $controller = Controller::getInstance();
            $controller->setServiceLocator($this);
            return $controller;
        }

        public function buildViewManager() {
            return ViewManager::getInstance();
        }

        private $config;
        protected function __construct($config_file = null)
        {
            $default_config = array(
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
                $this->config = $config;
            } else {
                $this->config = $default_config;
            }
        }

        public function buildStorage()
        {
            $class = $this->config['storage']['class'];
            return new $class($this->config['storage']['options']);
        }
    }