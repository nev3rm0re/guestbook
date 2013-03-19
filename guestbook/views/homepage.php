<?php include_component('guestbook', array('posts' => isset($posts) ? $posts: null, 'sub_title' => isset($sub_title) ? $sub_title : null)); ?>
<?php include_component('post_form', array('form' => isset($post_form) ? $post_form : null)); ?>
