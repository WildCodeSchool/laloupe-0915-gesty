<?php

namespace Scheduler\Tests\Unit\Component\DateContainer;


use Scheduler\Component\DateContainer\PeriodInterface;

class PeriodOneMonthMock implements PeriodInterface
{
    function __construct(
        \DateTimeInterface $firstDate,
        \DateTimeInterface $lastDate,
        $description=''
    )
    {}

    function getFirstDate()
    {
        return new \DateTimeImmutable('2016-01-01');
    }

    function getLastDate()
    {
        return new \DateTimeImmutable('2016-01-31');
    }

    function getDescription()
    {
        return '';
    }

    function isIncluded(\DateTimeInterface $date)
    {
        return false;
    }

    function getIterator(\DateInterval $increment)
    {
        return null;
    }

}
