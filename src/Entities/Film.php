<?php

require('./src/EntityManager.php');

class Film extends Entity
{
    private $id;
    private $title;
    private $release_date;
    private $duration;

    public function getId()
    {
        return $this->id;
    }


    public function getReleaseDate()
    {
        return $this->release_date;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    public function setReleaseDate($release_date)
    {
        $this->release_date = $release_date;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

}