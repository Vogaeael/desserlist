<?php

namespace App\Entity;

class Workday
{
    private $id;
    private $date;
    private $meal;
    private $entries = [];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getMeal()
    {
        return $this->meal;
    }

    /**
     * @param mixed $meal
     */
    public function setMeal($meal): void
    {
        $this->meal = $meal;
    }

    /**
     * @return array
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * @param array $entries
     */
    public function setEntries(array $entries): void
    {
        $this->entries = $entries;
    }
}
