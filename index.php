<?php

function autoload(string $className)
{
    require('src/class/' . ucfirst($className) . '.php');
}

spl_autoload_register('autoload');

DBFactory::createTableIfNotExistsWithPDO();

$pdo = DBFactory::getMysqlConnexionWithPDO();

$manager = new MessageManagerPDO($pdo);

$warning = '';
$error = '';

// If you want to add a determined number of false messages,
// uncomment the line below and you can pass the desired number of messages in parameter (otherwise it's 10)
//$manager->addFakeMessagesWithPDO();


if (isset($_GET['pseudo']) && isset($_GET['content'])) {
    if (strlen(htmlentities($_GET['pseudo'])) > 30) {
        $warning = 'Le pseudo est trop long, il a été tronqué.';
    }
    if (strlen(htmlentities($_GET['content'])) > 255) {
        $warning = $warning === '' ? 'Le message est trop long, il a été tronqué.' : $warning .
            '<br>Le message est trop long, il a été tronqué.';
    }
    $message = new MessageEntity([
        'pseudo' => substr(htmlentities($_GET['pseudo']), 0, 30),
        'content' => str_replace(PHP_EOL,'<br>',substr(htmlentities($_GET['content']), 0, 255))
    ]);
    if ($manager->count() < 100) {
        $manager->save($message);
    } else {
        $error = 'Erreur : Le nombre maximum d\'enregistrements est atteint. <br>
                Si vous voulez enregistrer un nouveau message, supprimer tous les messages avant.';
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
        <?php include 'component/form.php' ?>
        <?php include 'component/error.php' ?>
        <?php include 'component/messageList.php' ?>
    </main>

<?php include 'component/footer.php' ?>