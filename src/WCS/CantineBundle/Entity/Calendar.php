<?php

namespace WCS\CantineBundle\Entity;

/**
 * Calendar
 */
class Calendar
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $period;

    /**
     * @var string
     */
    private $start;

    /**
     * @var string
     */
    private $end;

    /**
     * @var string
     */
    private $vacancesToussaintStart;

    /**
     * @var string
     */
    private $vacancesToussaintEnd;

    /**
     * @var string
     */
    private $vacancesNoelStart;

    /**
     * @var string
     */
    private $vacancesNoelEnd;

    /**
     * @var string
     */
    private $vacancesHiverStart;

    /**
     * @var string
     */
    private $vacancesHiverEnd;

    /**
     * @var string
     */
    private $vacancesPrintempsStart;

    /**
     * @var string
     */
    private $vacancesPrintempsEnd;

    /**
     * @var string
     */
    private $vacancesEte;

    /**
     * @var string
     */
    private $feriePaques;

    /**
     * @var string
     */
    private $feriePentecote;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set period
     *
     * @param string $period
     *
     * @return Calendar
     */
    public function setPeriod($period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return string
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set start
     *
     * @param string $start
     *
     * @return Calendar
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param string $end
     *
     * @return Calendar
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return string
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set vacancesToussaintStart
     *
     * @param string $vacancesToussaintStart
     *
     * @return Calendar
     */
    public function setVacancesToussaintStart($vacancesToussaintStart)
    {
        $this->vacancesToussaintStart = $vacancesToussaintStart;

        return $this;
    }

    /**
     * Get vacancesToussaintStart
     *
     * @return string
     */
    public function getVacancesToussaintStart()
    {
        return $this->vacancesToussaintStart;
    }

    /**
     * Set vacancesToussaintEnd
     *
     * @param string $vacancesToussaintEnd
     *
     * @return Calendar
     */
    public function setVacancesToussaintEnd($vacancesToussaintEnd)
    {
        $this->vacancesToussaintEnd = $vacancesToussaintEnd;

        return $this;
    }

    /**
     * Get vacancesToussaintEnd
     *
     * @return string
     */
    public function getVacancesToussaintEnd()
    {
        return $this->vacancesToussaintEnd;
    }

    /**
     * Set vacancesNoelStart
     *
     * @param string $vacancesNoelStart
     *
     * @return Calendar
     */
    public function setVacancesNoelStart($vacancesNoelStart)
    {
        $this->vacancesNoelStart = $vacancesNoelStart;

        return $this;
    }

    /**
     * Get vacancesNoelStart
     *
     * @return string
     */
    public function getVacancesNoelStart()
    {
        return $this->vacancesNoelStart;
    }

    /**
     * Set vacancesNoelEnd
     *
     * @param string $vacancesNoelEnd
     *
     * @return Calendar
     */
    public function setVacancesNoelEnd($vacancesNoelEnd)
    {
        $this->vacancesNoelEnd = $vacancesNoelEnd;

        return $this;
    }

    /**
     * Get vacancesNoelEnd
     *
     * @return string
     */
    public function getVacancesNoelEnd()
    {
        return $this->vacancesNoelEnd;
    }

    /**
     * Set vacancesHiverStart
     *
     * @param string $vacancesHiverStart
     *
     * @return Calendar
     */
    public function setVacancesHiverStart($vacancesHiverStart)
    {
        $this->vacancesHiverStart = $vacancesHiverStart;

        return $this;
    }

    /**
     * Get vacancesHiverStart
     *
     * @return string
     */
    public function getVacancesHiverStart()
    {
        return $this->vacancesHiverStart;
    }

    /**
     * Set vacancesHiverEnd
     *
     * @param string $vacancesHiverEnd
     *
     * @return Calendar
     */
    public function setVacancesHiverEnd($vacancesHiverEnd)
    {
        $this->vacancesHiverEnd = $vacancesHiverEnd;

        return $this;
    }

    /**
     * Get vacancesHiverEnd
     *
     * @return string
     */
    public function getVacancesHiverEnd()
    {
        return $this->vacancesHiverEnd;
    }

    /**
     * Set vacancesPrintempsStart
     *
     * @param string $vacancesPrintempsStart
     *
     * @return Calendar
     */
    public function setVacancesPrintempsStart($vacancesPrintempsStart)
    {
        $this->vacancesPrintempsStart = $vacancesPrintempsStart;

        return $this;
    }

    /**
     * Get vacancesPrintempsStart
     *
     * @return string
     */
    public function getVacancesPrintempsStart()
    {
        return $this->vacancesPrintempsStart;
    }

    /**
     * Set vacancesPrintempsEnd
     *
     * @param string $vacancesPrintempsEnd
     *
     * @return Calendar
     */
    public function setVacancesPrintempsEnd($vacancesPrintempsEnd)
    {
        $this->vacancesPrintempsEnd = $vacancesPrintempsEnd;

        return $this;
    }

    /**
     * Get vacancesPrintempsEnd
     *
     * @return string
     */
    public function getVacancesPrintempsEnd()
    {
        return $this->vacancesPrintempsEnd;
    }

    /**
     * Set vacancesEte
     *
     * @param string $vacancesEte
     *
     * @return Calendar
     */
    public function setVacancesEte($vacancesEte)
    {
        $this->vacancesEte = $vacancesEte;

        return $this;
    }

    /**
     * Get vacancesEte
     *
     * @return string
     */
    public function getVacancesEte()
    {
        return $this->vacancesEte;
    }

    /**
     * Set feriePaques
     *
     * @param string $feriePaques
     *
     * @return Calendar
     */
    public function setFeriePaques($feriePaques)
    {
        $this->feriePaques = $feriePaques;

        return $this;
    }

    /**
     * Get feriePaques
     *
     * @return string
     */
    public function getFeriePaques()
    {
        return $this->feriePaques;
    }

    /**
     * Set feriePentecote
     *
     * @param string $feriePentecote
     *
     * @return Calendar
     */
    public function setFeriePentecote($feriePentecote)
    {
        $this->feriePentecote = $feriePentecote;

        return $this;
    }

    /**
     * Get feriePentecote
     *
     * @return string
     */
    public function getFeriePentecote()
    {
        return $this->feriePentecote;
    }
}

