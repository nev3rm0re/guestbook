<?php
switch($flash['type']) {
    case 'error':
        $alert_class = 'alert-error';
        break;
    case 'notice':
        $alert_class = 'alert-notice';
        break;
    case 'success':
        $alert_class = 'alert-success';
        break;
    default:
        $alert_class = 'alert-info';
}
?>
<div class="alert <?= $alert_class?>">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?= $flash['message'] ?>
</div>