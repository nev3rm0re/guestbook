<?php
    class ViewManager
    {
        private function __construct(){}
        private static $instance;
        public static function getInstance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function getView($view)
        {
            $view = new View($view);
            $view->setLayout('layout');

            return $view;
        }

        public function getPartial($view) {
            $view = new View('_'.$view);
            return $view;
        }
    }