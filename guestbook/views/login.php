<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <form action="<?= url_for('login')?>" method="post">
            <fieldset>
                <legend>Login</legend>
                <?php if ($form->hasErrors()): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($form->getErrors() as $error): ?>
                        <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif;?>
                <label for="login_username">Username</label>
                <input type="text" name="login[username]" id="login_username">

                <label for="login_password">Password</label>
                <input type="password" name="login[password]" id="login_password">

                <div class="form-actions">
                    <input type="hidden" name="login[csrf]" value="<?= $form['csrf']?>">
                    <input type="submit" value="Login" class="btn btn-primary">
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>