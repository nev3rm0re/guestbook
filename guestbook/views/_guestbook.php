<h1>Guestbook
    <?php if (!empty($sub_title)): ?>
    <small><?= safe_html($sub_title) ?></small>
    <?php endif;?>
</h1>
<?php foreach ($posts as $post): ?>
    <div class="row">
        <div class="span3">
            <strong><?= safe_html($post['author'])?></strong>
            <small><a href="<?= url_for('post_by_user', array('author' => $post['author']['username']))?>">
                other posts by <?= safe_html($post['author'])?></a></small>
        </div>
        <div class="span9" style="text-align:right">
            Posted: <?= date('d.m.Y H:i:s', strtotime($post['created_at']))?>
        </div>
    </div>
    <div class="row">
        <div class="span12">
            <div class="alert alert-info"><?= nl2br(safe_html($post['message'])); ?></div>
        </div>
    </div>
<?php endforeach; ?>