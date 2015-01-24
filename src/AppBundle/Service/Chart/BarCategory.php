<?php
/**
 * Created by PhpStorm.
 * User: farkas-berlin
 * Date: 06.12.14
 * Time: 20:55
 */

namespace AppBundle\Service\Chart;


use AppBundle\Helper\StatisticDate;

class BarCategory extends AbstractChart
{
    /**
     * {@inheritdoc}
     */
    protected function preProcess(StatisticDate $statisticDate)
    {
        $arrayKey = (String)$statisticDate;
        $this->data[$arrayKey] = array(static::TYPE_INCOME => array(), static::TYPE_OUTCOME => array());
    }

    /**
     * {@inheritdoc}
     */
    protected function setRow($value, \DateTime $date, $category, $arrayKey)
    {
        $type = $value > 0 ? static::TYPE_INCOME : static::TYPE_OUTCOME;
        if (!isset($this->data[$arrayKey][$type][$category])) {
            $this->data[$arrayKey][$type][$category] = 0;
        }

        $this->data[$arrayKey][$type][$category] += $value < 0 ? abs($value) : $value;
    }

    /**
     * {@inheritdoc}
     */
    protected function postProcess($arrayKey)
    {
        $tmp = array();
        foreach ($this->data[$arrayKey] as $type => $categories) {

            foreach ($categories as $category => $sum) {
                if (!isset($tmp[$type]['others'])) {
                    $tmp[$type]['others'] = array('x' => 'others', 'y' => 0);
                }

                if ($sum < 50) {
                    $tmp[$type]['others']['y'] += $sum;
                } else {
                    $tmp[$type][] = array('x' => $category, 'y' => $sum);
                }
            }
        }

        $this->data = array();
        $types = array(static::TYPE_INCOME, static::TYPE_OUTCOME);
        foreach ($types as $type) {
            if (isset($tmp[$type])) {
                $this->data[$arrayKey][$type] = array(
                    'xkey' => 'x',
                    'ykeys' => ['y'],
                    'labels' => $this->getLabels(),
                    'data' => array_values($tmp[$type]),
                    'stacked' => true
                );
            }
        }
    }


    protected function getLabels()
    {
        return ['Betrag'];//array_keys($this->data);
    }
}