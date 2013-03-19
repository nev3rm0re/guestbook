<?php
    /**
     * This file contains helper functions for use inside View templates.
     *
     *
     */

    function url_for($url, $params = array()) {
        switch ($url) {
            case 'post/add':
                $params['action'] = 'post';
                break;
            case 'post_by_user':
                $params['action'] = 'by_user';
                break;
                break;
            case 'logout':
                $params['action'] = 'logout';
                break;
            case 'login':
                $params['action'] = 'login';
                break;
            case 'register':
                $params['action'] = 'register';
                break;
            case 'homepage':
                break;
            default:
                throw new Exception('No route for that name: ' . $url);
                break;
        }

        return 'index.php?'.http_build_query($params);
    }

    function include_component($component_name, $options = array()) {
        $controller = ServiceLocator::getInstance()->getController();
        echo $controller->getComponent($component_name, $options);
    }

    function user_is_logged_in() {
        return ServiceLocator::getInstance()->getController()->getUser()->isAuthenticated();
    }

    function get_user() {
        return ServiceLocator::getInstance()->getController()->getUser();
    }

    function safe_html($string) {
        return htmlentities($string, ENT_COMPAT, 'utf-8');
    }