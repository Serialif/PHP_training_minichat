<?php


abstract class MessageManager
{
    public function save(MessageEntity $message){
        if ($message->isValid()) {
            if ($message->isExist()) {
                $this->update($message);
            } else {
                $this->add($message);
            }
        }
    }

    abstract protected function add(MessageEntity $message);
    abstract protected function update(MessageEntity $message);
    abstract public function delete(int $id);
    abstract public function getSingle($id);
    abstract public function getList(?int $start, ?int $limit);
    abstract public function count();
}