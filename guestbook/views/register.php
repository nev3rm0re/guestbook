<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <form action="<?= url_for('register')?>" method="post">
            <fieldset>
                <legend>Register</legend>
                <?php if ($form->hasErrors()): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($form->getErrors() as $error): ?>
                        <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif;?>
                <label for="register_name">Your name</label>
                <input type="text" name="register[name]" id="register_name" value="<?= safe_html($form['name'])?>">

                <label for="register_username">Username</label>
                <input type="text" name="register[username]" id="" value="<?= safe_html($form['username'])?>">

                <label for="register_password">Password</label>
                <input type="password" name="register[password]" id="register_password">

                <label for="register_password_repeat">Password (repeat)</label>
                <input type="password" name="register[password_repeat]" id="register_password_repeat">

                <div class="form-actions">
                    <input type="hidden" name="register[csrf]" value="<?= $form['csrf']?>">
                    <input type="submit" value="Register" class="btn btn-primary">
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>