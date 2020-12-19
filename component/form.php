<div class="form">
    <h2 class="form-title">Nouveau message</h2>
    <form action="" method="post" class="form-fields">
        <label for="pseudo">
            <div>Pseudo <span class="form-char-number">(30 caractères maximum)</span></div>
            <input type="text" name="pseudo" id="pseudo" value="<?= $pseudo ?>">
        </label>
        <label for="content">
            <div>Message <span class="form-char-number">(255 caractères maximum)</span></div>
            <textarea name="content" id="content" rows="5" cols="100"><?= $content ?></textarea>
        </label>
        <label for="captcha">
            <div>Pour vérifier que vous êtes un être humain, écrivez <?= $captchaText ?></div>
            <input type="text" name="captcha" <?= !$captchaSuccess ? 'class="content-error"' : '' ?>>
            <input type="hidden" name="captchaId" value="<?= $captchaId ?>">
        </label>
        <button type="submit">Ajouter le message</button>
    </form>
</div>

<script>
    let contentError = document.getElementsByClassName('content-error');
    for (let i = 0; i < contentError.length; i++) {
        contentError[i].style.border = '2px solid #c10000';
        contentError[i].style.background = '#ddb6b6';

        contentError[i].addEventListener('keyup', function () {
            contentError[i].style.border = '1px solid #000';
            contentError[i].style.background = '#ffffff';
        })
    }
</script>