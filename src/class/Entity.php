<?php


abstract class Entity
{
    protected int $id;

    /**
     * Entity constructor.
     * @param array $datas values to assign to the corresponding attributes.
     */
    public function __construct(array $datas)
    {
        if (!empty($datas)) {
            $this->hydrate($datas);
        }
    }

    /**
     * Assigns the specified values to the corresponding attributes.
     * @param array $datas values to assign 
     */
    public function hydrate(array $datas): void
    {
        foreach ($datas as $property => $value) {
            $method = 'set' . ucfirst($property);
            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }

    /** Checks if the entity is valid
     * @return bool
     */
    abstract public function isValid(): bool;

    /**
     * Checks if the entity exists
     * @return bool
     */
    public function isExist(): bool
    {
        return !empty($this->id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}