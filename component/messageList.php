<?php

$count = $manager->count();

$messages = $manager->getList();

$html = '<div class="messages">';
$html .= '<div class="counter">Il y a actuellement ' . $count . ' message' . ($count > 0 ? 's' : '') . '</div>';
$html .= '<div class="buttons"><a href="?add">Ajouter 10 messages</a><a href="?delAll">Supprimer tous les messages</a></div>';
foreach ($messages as $message) {
    $pseudo = $message->getPseudo();
    $content = $message->getContent();
    $createdAt = date('d/m/y à H\hi', strtotime($message->getCreatedAt()));
    $html .= <<<HTML
        <div class="message">
            <div class="pseudo">$pseudo</div>
            <div class="content">$content</div>
            <div class="created-at">envoyé le $createdAt</div>
        </div>
    HTML;

}
$html .= '</div>';

echo $html;