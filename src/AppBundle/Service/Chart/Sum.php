<?php
/**
 * Created by PhpStorm.
 * User: farkas-berlin
 * Date: 17.01.15
 * Time: 23:14
 */

namespace AppBundle\Service\Chart;


use AppBundle\Helper\StatisticDate;

/**
 * Class SumCalculator
 * @package AppBundle\Service
 */
class Sum extends AbstractChart
{

    /**
     * {@inheritdoc}
     */
    protected function preProcess(StatisticDate $statisticDate)
    {
        $arrayKey = (String)$statisticDate;
        $this->data[$arrayKey][static::TYPE_INCOME] = array('sum' => 0, 'countRows' => 0);
        $this->data[$arrayKey][static::TYPE_OUTCOME] = array('sum' => 0, 'countRows' => 0);
    }


    /**
     * {@inheritdoc}
     */
    protected function setRow($value, \DateTime $date, $category, $arrayKey)
    {
        $type = $value > 0 ? static::TYPE_INCOME : static::TYPE_OUTCOME;
        $this->data[$arrayKey][$type]['sum'] += $type == static::TYPE_INCOME ? $value : abs($value);
        $this->data[$arrayKey][$type]['countRows'] += 1;
    }
}