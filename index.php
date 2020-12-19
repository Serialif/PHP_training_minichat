<?php

function autoload(string $className)
{
    require('src/class/' . ucfirst($className) . '.php');
}

spl_autoload_register('autoload');

DBFactory::createTableIfNotExistsWithPDO();

$pdo = DBFactory::getMysqlConnexionWithPDO();

$manager = new MessageManagerPDO($pdo);

$pseudo = '';
$content = '';
$success = '';
$warning = '';
$error = '';
$captchaSuccess = true;

$captcha = [
    ['<span class="captcha-word">rouge</span> en majuscules', 'ROUGE'],
    ['<span class="captcha-word">vingt et un</span> en chiffre', '21'],
    ['<span class="captcha-word">soixante douze</span> en chiffre', '72'],
    ['<span class="captcha-word">trente trois</span> en chiffre', '33'],
    ['<span class="captcha-word">cent</span> en chiffre', '100'],
    ['le résultat de <span class="captcha-word">1+2</span> en lettres minuscules', 'trois'],
    ['le résultat de <span class="captcha-word">2+3</span> en lettres majuscules', 'CINQ'],
    ['le résultat de <span class="captcha-word">99+1</span> en lettres minuscules', 'cent'],
    ['<span class="captcha-word">blanc</span> au féminin en minuscules', 'blanche'],
    ['<span class="captcha-word">+</span> en lettres majuscules', 'PLUS'],
    ['<span class="captcha-word">non</span> à l\'envers', 'non'],
    ['<span class="captcha-word">ko</span> à l\'envers', 'ok'],
    ['<span class="captcha-word">non</span> à l\'envers', 'non']
];

// If you want to add a determined number of false messages,
// uncomment the line below and you can pass the desired number of messages in parameter (otherwise it's 10)
//$manager->addFakeMessagesWithPDO();


if (isset($_POST['pseudo']) && isset($_POST['content'])) {
    if (isset($_POST['captcha']) && $captcha[$_POST['captchaId']][1] === $_POST['captcha']) {
        if (strlen(htmlentities($_POST['pseudo'])) > 30) {
            $warning = 'Le pseudo est trop long, il a été tronqué.';
        }
        if (strlen(htmlentities($_POST['content'])) > 255) {
            $warning = $warning === '' ? 'Le message est trop long, il a été tronqué.' : $warning .
                '<br>Le message est trop long, il a été tronqué.';
        }
        $message = new MessageEntity([
            'pseudo' => substr(htmlentities($_POST['pseudo']), 0, 30),
            'content' => str_replace(PHP_EOL, '<br>', substr(htmlentities($_POST['content']), 0, 255))
        ]);
        if ($manager->count() < 100) {
            $manager->save($message);
            $success = 'Le message a été ajouté.';
        } else {
            $error = 'Erreur : Le nombre maximum d\'enregistrements est atteint. <br>
                    Si vous voulez enregistrer un nouveau message, supprimer tous les messages avant.';
        }
    } else {
        $pseudo = $_POST['pseudo'];
        $content = $_POST['content'];
        $captchaSuccess = false;
        $error = 'Vous n\'avez pas prouvé que vous êtes un être humain, réessayez...';
    }
}

if (isset($_GET['add'])) {
    if ($manager->count() < 100) {
        $manager->addFakeMessagesWithPDO();
    } else {
        $error = 'Erreur : Le nombre maximum d\'enregistrements est atteint. <br>
                Si vous voulez enregistrer un nouveau message, supprimer tous les messages avant.';
    }
}

if (isset($_GET['delAll'])) {
    $manager->truncateTable();
}

include 'component/header.php';

?>
    <h1>Mini système de chat</h1>
    <main>
        <?php
        $i = rand(0, count($captcha) - 1);
        $captchaText = $captcha[$i][0];
        $captchaId = $i;
        include 'component/form.php'
        ?>
        <?php include 'component/error.php' ?>
        <?php include 'component/messageList.php' ?>
    </main>

<?php include 'component/footer.php' ?>