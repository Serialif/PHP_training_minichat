<?php

function autoload(string $className)
{
    require ('src/class/' . ucfirst($className) . '.php');
}

spl_autoload_register('autoload');

DBFactory::createTableIfNotExistsWithPDO();

$pdo = DBFactory::getMysqlConnexionWithPDO();

$manager = new MessageManagerPDO($pdo);

// If you want to add a determined number of false messages,
// uncomment the line below and you can pass the desired number of messages in parameter (otherwise it's 10)
//$manager->addFakeMessagesWithPDO();


if(isset($_GET['pseudo']) && isset($_GET['content'])){
    $message = new MessageEntity([
            'pseudo'=>$_GET['pseudo'],
            'content'=>$_GET['content']
    ]);
    $manager->save($message);
}

if(isset($_GET['add'])){
    $manager->addFakeMessagesWithPDO();
}

if(isset($_GET['delAll'])){
    $manager->truncateTable();
}

include 'component/header.php';

?>
<h1>Mini système de chat</h1>
<main>
    <?php include 'component/form.php' ?>
    <?php include 'component/messageList.php' ?>
</main>

<?php include 'component/footer.php' ?>