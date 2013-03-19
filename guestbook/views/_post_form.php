<form action="<?= url_for('post/add')?>" method="post">
    <fieldset>
        <legend>Leave a message</legend>

        <?php if ($form->hasErrors()): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($form->getErrors() as $field => $error): ?>
                <li><?= $error?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif;?>

        <div class="control-group">
            <label for="post_message">Message</label>
            <textarea name="post[message]" placeholder="Leave a message" rows="4" class="span6"><?= $form['message']?></textarea>
        </div>

        <div class="form-actions">
            <input type="hidden" name="post[csrf]" value="<?= $form['csrf']?>">
            <input type="submit" class="btn btn-primary" value="Post">
        </div>
    </fieldset>
</form>