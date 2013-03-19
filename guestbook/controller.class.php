<?php
    class Controller
    {
        private static $instance;
        private function __construct() {}
        public static function getInstance() {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function getUser() {
            return new User();
        }

        private $service_locator;
        public function setServiceLocator($service_locator)
        {
            $this->service_locator = $service_locator;
        }
        public function getServiceLocator() {
            return $this->service_locator;
        }

        public function getViewManager()
        {
            return $this->getServiceLocator()->buildViewManager();
        }

        public function getView($view) {
            return $this->getViewManager()->getView($view);
        }

        public function getStorage() {
            return $this->getServiceLocator()->buildStorage();
        }

        public function matchRequest() {
            if (array_key_exists('action', $_GET)) {
                switch ($_GET['action']) {
                    case 'post':
                        return 'AddPost';
                    case 'logout':
                        return 'Logout';
                    case 'login':
                        return 'Login';
                    case 'register':
                        return 'Register';
                    case 'by_user':
                        return 'ByUser';

                }
            }
        }

        public function handle()
        {
            $action = $this->matchRequest();

            $method_name = "execute$action";
            if (method_exists($this, $method_name)) {
                try {
                    $this->$method_name();
                } catch (Exception $e) {
                    $this->displayErrorPage($e);
                }
            } else {
                $this->executeHomepage();
            }
        }

        public function displayErrorPage($e) {
            header('HTTP/1.0 500 Internal Server Error');
            $view = $this->getView('error');

            if ($e instanceof StorageException) {
                $error = "Storage problem:\n" . $e->getMessage();
            } else {
                $error = $e->__toString();
            }

            $view->display(
                array('error_details' => $error),
                array('layout' => false));
        }

        public function executeAddPost()
        {
            if ($this->getUser()->isAuthenticated()) {
                $storage = $this->getStorage();

                $post = $this->getUser()->getGuestbookUser()->createPost();
                $post_form = new GuestbookPostForm($post);
                $post_form->bind($_POST['post']);

                if ($post_form->isValid()) {
                    $post_form->save();
                    $this->getUser()->setFlash('success', 'Post added');
                    $this->redirect('homepage');
                } else {
                    $view = $this->getView('homepage');
                    echo $view->render(array('post_form' => $post_form));
                }
            } else {
                $post_form = new GuestbookPostForm();
                $view = $this->getView('homepage');
                $this->getUser()->setFlash('error', 'You must be logged in to post');
                $view->display(array(
                    'form' => $post_form
                ));
            }

        }

        public function executeLogin()
        {
            $login_form = new LoginForm();
            if ('post' === strtolower($_SERVER['REQUEST_METHOD'])) {
                // either login or display error message
                $login = $_POST['login'];

                $login_form->bind($_POST['login']);
                if ($login_form->isValid()) {
                    $this->getUser()->authenticate($login['username']);

                    $this->getUser()->setFlash('success', 'Logged in succesfully');
                    $this->redirect('homepage');
                } else {
                    $view = $this->getView("login");
                    $view->display(array('form' => $login_form));
                }
            } else {
                // display login form
                $view = $this->getView('login');
                $view->display(array('form' => $login_form));
            }
        }

        public function executeRegister()
        {
            if ('post' === strtolower($_SERVER['REQUEST_METHOD'])) {
                // attempt to register user
                $storage = $this->getStorage();
                $user = $storage->create('GuestbookUser');
                $user_form = new GuestbookUserForm($user);
                $user_form->bind($_POST['register']);
                if ($user_form->isValid()) {
                    $user = $user_form->save();

                    $this->getUser()->authenticate($user['username']);
                    $this->getUser()->setFlash('notice', "You've been automatically logged in");
                    $this->redirect('homepage');
                } else {
                    $view = $this->getView('register');
                    $view->display(array('form' => $user_form));
                }
            } else {
                $view = $this->getView('register');
                $user_form = new GuestbookUserForm($this->getStorage()->create('GuestbookUser'));
                $view->display(array('form' => $user_form));
                // display register form
            }
        }

        public function executeByUser() {
            $storage = $this->getStorage();
            $username = $_GET['author'];

            $user = $storage->findBy('GuestbookUser', 'username', $username);

            $view = $this->getView('default');
            $view->display(array('posts' => $user->getPosts(), 'sub_title' => 'Posts by ' . $user['name']));
        }

        public function executeLogout()
        {
            $this->getUser()->logout();
            $this->redirect('homepage');
        }

        public function redirect($route)
        {
            switch ($route) {
                case 'login':
                    $url = '?action=login';
                    break;
                case 'homepage':
                default:
                    $url = 'index.php';
                    break;
            }

            header('Location: ' . $url);
            die();
        }

        public function executeHomepage()
        {
            $storage = $this->getStorage();

            $storage->findAll('GuestbookPost');

            $view = $this->getView('homepage');
            $view->display(array('form' => new GuestbookPostForm()));
        }

        public function getComponent($name, $params = array())
        {
            if (method_exists($this, 'component'.$name)) {
                $method_name = "component$name";
                return $this->$method_name($params);
            } else {
                return '';
            }
        }

        public function componentGuestbook($params)
        {
            if ($params['posts'] !== null) {
                $posts = $params['posts'];
            } else {
                $posts = $this->getStorage()->findAll('GuestbookPost');
            }

            $view = $this->getViewManager()->getPartial('guestbook');
            return $view->render(array('posts' => $posts, 'sub_title' => $params['sub_title']));
        }

        public function componentForm()
        {
            $view = $this->getViewManager()->getPartial('form');
            return $view->render(array('post' => new Post()));
        }

        public function componentUser_Flash()
        {
            $view = $this->getViewManager()->getPartial('user_flash');
            $flash = $this->getUser()->getFlash();
            $this->getUser()->clearFlash();
            return $view->render(array('flash' => $flash));
        }

        public function componentPost_Form($params = array())
        {
            $view = $this->getViewManager()->getPartial('post_form');

            $post = $this->getStorage()->create('GuestbookPost');
            $post_form = new GuestbookPostForm($post);
            if (array_key_exists('form', $params) && !empty($params['form'])) {
                $post_form = $params['form'];
            }
            return $view->render(array('form' => $post_form));
        }
    }