<?php

namespace WCS\CantineBundle\Entity;

/**
 * Lunch
 */
class Lunch extends ActivityBase
{

    public function __toString()
    {
        return (string) $this->getEleve();

    }

    public function getStringDate()
    {
        return $this->date->format('Y-m-d');
    }
}
