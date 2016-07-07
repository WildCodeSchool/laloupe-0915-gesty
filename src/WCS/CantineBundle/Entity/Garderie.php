<?php

namespace WCS\CantineBundle\Entity;

/**
 * Garderie
 */
class Garderie extends ActivityBase
{
    /**
     * @var bool
     */
    private $enable_morning;

    /**
     * @var bool
     */
    private $enable_evening;

    /**
     * Garderie constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->enable_evening = false;
        $this->enable_morning = false;
    }

    /**
     * @return boolean
     */
    public function isEnableEvening()
    {
        return $this->enable_evening;
    }

    /**
     * @return boolean
     */
    public function isEnableMorning()
    {
        return $this->enable_morning;
    }

    /**
     * @param boolean $enable_evening
     * @return $this
     */
    public function setEnableEvening($enable_evening)
    {
        $this->enable_evening = $enable_evening;
        return $this;
    }

    /**
     * @param boolean $enable_morning
     * @return $this
     */
    public function setEnableMorning($enable_morning)
    {
        $this->enable_morning = $enable_morning;
        return $this;
    }
}
