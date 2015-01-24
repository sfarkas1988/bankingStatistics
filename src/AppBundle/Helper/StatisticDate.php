<?php
/**
 * Created by PhpStorm.
 * User: farkas-berlin
 * Date: 17.01.15
 * Time: 23:39
 */

namespace AppBundle\Helper;


/**
 * Class StatisticDate
 * @package AppBundle\Service
 */
class StatisticDate
{
    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @param $start
     * @param $end
     */
    public function __construct($start, $end)
    {
        $this->startDate = new \DateTime();
        $this->startDate->setTimestamp($start);
        $this->endDate = new \DateTime();
        $this->endDate->setTimestamp($end);
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    public function isDateBetweenStartAndEnd(\DateTime $date)
    {
        return $date >= $this->startDate && $date <= $this->endDate;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->startDate->getTimestamp().'_'.$this->endDate->getTimestamp();
    }
}