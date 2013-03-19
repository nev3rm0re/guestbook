<?php
    require_once('helpers.php');
    class View
    {
        public function __construct($template)
        {
            $this->template = $template;
        }

        public function render($variables = array(), $options = array()) {
            ob_start();
            extract($variables);
            try {
                include($this->template.'.php');

            } catch (Exception $e) {
                ob_end_clean();
                throw $e;
            }
            $content = ob_get_clean();

            if (array_key_exists('layout', $options)) {
                $this->layout = $options['layout'];
            }

            if ($this->layout) {
                $view = new View($this->layout);
                return $view->render(array('content' => $content));
            } else {
                return $content;
            }
        }

        public function display($variables = array(), $options = array()) {
            echo $this->render($variables, $options);
        }

        private $layout = null;
        public function setLayout($layout) {
            $this->layout = $layout;
        }
    }