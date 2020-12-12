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
        $query = $this->pdo->prepare('INSERT INTO message (author, message, createdAt) 
            VALUES (:author, :message, NOW())');
        $query->bindValue(':author', $message->getAuthor(), PDO::PARAM_STR);
        $query->bindValue(':message', $message->getMessage(), PDO::PARAM_STR);

        $query->execute();
    }

    protected function update(MessageEntity $message): void
    {
        $query = $this->pdo->prepare('UPDATE message SET author = :author, message = :message,
                 createdAt = NOW() WHERE id=:id');
        $query->bindValue(':id', $message->getId(), PDO::PARAM_INT);
        $query->bindValue(':author', $message->getAuthor(), PDO::PARAM_STR);
        $query->bindValue(':message', $message->getMessage(), PDO::PARAM_STR);

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
        var_dump($limit,$start);
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
}