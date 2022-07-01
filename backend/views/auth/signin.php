<?php
use yii\helpers\Url;

$this->title = 'Sign In';
$this->registerCss("body {text-align: center}");
?>
<script src="https://accounts.google.com/gsi/client" async defer></script>

<div id="g_id_onload"
    data-client_id="<?= CLIENT_ID ?>"
    data-login_uri="<?= SIGNIN_CALLBACK_URL ?>"
    data-auto_prompt="false"
    data-ux_mode="redirect">
</div>
<div class="g_id_signin"
    data-type="standard"
    data-size="large"
    data-theme="outline"
    data-text="sign_in_with"
    data-shape="rectangular"
    data-logo_alignment="center"
    style="display: flex; justify-content: center">
</div>