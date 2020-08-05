<?php

class Item
{
    private $name;
    private $weight;
    private $chance;

    public function __construct(string $name, int $chance, int $weight)
    {
        $this->name = $name;
        $this->chance = $chance;
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @return int
     */
    public function getChance(): int
    {
        return $this->chance;
    }
}
