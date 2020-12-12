<?php


class MessageManagerPDO extends MessageManager
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function add(MessageEntity $message): void
    {
        $query = $this->pdo->prepare('INSERT INTO message (pseudo, content, createdAt) 
            VALUES (:pseudo, :content, NOW())');
        $query->bindValue(':pseudo', $message->getPseudo(), PDO::PARAM_STR);
        $query->bindValue(':content', $message->getContent(), PDO::PARAM_STR);

        $query->execute();
    }

    protected function update(MessageEntity $message): void
    {
        $query = $this->pdo->prepare('UPDATE message SET pseudo = :pseudo, content = :content,
                 createdAt = NOW() WHERE id=:id');
        $query->bindValue(':id', $message->getId(), PDO::PARAM_INT);
        $query->bindValue(':pseudo', $message->getPseudo(), PDO::PARAM_STR);
        $query->bindValue(':content', $message->getContent(), PDO::PARAM_STR);

        $query->execute();
    }

    public function delete(int $id): void
    {
        $query = $this->pdo->prepare('DELETE FROM message WHERE id=:id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);

        $query->execute();
    }

    public function getSingle($id)
    {
        $query = $this->pdo->prepare('SELECT * FROM message WHERE id=:id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);

        $query->execute();

        $query->setFetchMode(PDO::FETCH_CLASS, MessageEntity::class);

        return $query->fetch();
    }

    public function getList(?int $start = null, ?int $limit = null, string $order = 'DESC')
    {
        if (!is_null($start) && !is_null($limit)) {
            $query = $this->pdo->prepare('SELECT * FROM message ORDER BY id ' . $order . ' LIMIT :limit OFFSET :start');
            $query->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query->bindValue(':start', $start, PDO::PARAM_INT);
        } else {
            $query = $this->pdo->prepare('SELECT * FROM message ORDER BY id ' . $order);
        }

        $query->execute();

        $query->setFetchMode(PDO::FETCH_CLASS, MessageEntity::class);

        return $query->fetchAll();
    }

    public function count(): int
    {
        return $this->pdo->query('SELECT COUNT(*) FROM message')->fetchColumn();
    }

    public function truncateTable(){
        $this->pdo->query('TRUNCATE TABLE message');
    }

    public function addFakeMessagesWithPDO(int $number = 10)
    {
        for ($i = 0; $i < $number; $i++) {
            $nb = rand(5, 15);
            $pseudo = '';
            $content = '';
            for ($j = 0; $j < $nb; $j++) {
                $pseudo .= rand(1, 10) === 10 ? ' ' : '';
                $pseudo .= chr(floor(rand(0, 25) + 97));
            }
            $nb = rand(50, 100);
            for ($j = 0; $j < $nb; $j++) {
                $pseudo .= rand(1, 10) === 10 ? ' ' : '';
                $content .= chr(floor(rand(0, 25) + 97));
            }
            $query = $this->pdo->prepare('INSERT INTO message (pseudo, content, createdAt) 
            VALUES (:pseudo, :content, NOW())');
            $query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
            $query->bindValue(':content', $content, PDO::PARAM_STR);

            $query->execute();
        }
    }
}