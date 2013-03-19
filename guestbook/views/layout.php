<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guestbook</title>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <style>
    body {
        padding-top: 50px;
    }
    </style>
</head>
<body>
    <div class="container">
        <?php if (get_user()->hasFlash()): ?>
            <?php include_component('user_flash');?>
        <?php endif;?>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a href="<?= url_for('homepage')?>" class="brand">Guestbook</a>
                    <?php if (user_is_logged_in() === false): ?>
                        <ul class="nav pull-right">
                            <li><a href="<?= url_for('login') ?>">Login</a></li>
                            <li class="divider-vertical"></li>
                            <li><a href="<?= url_for('register')?>">Register</a></li>
                        </ul>
                    <?php else: ?>
                        <ul class="nav pull-right">
                            <li class="navbar-text">
                                Logged in as <?= get_user()->getName(); ?>
                            </li>
                            <li><a href="<?= url_for('logout')?>">Logout</a></li>
                        </ul>
                    <?php endif;?>
                </div>
            </div>
        </div>
        <?= $content ?>
    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>