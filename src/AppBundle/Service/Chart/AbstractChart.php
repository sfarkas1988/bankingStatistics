<?php
/**
 * Created by PhpStorm.
 * User: farkas-berlin
 * Date: 06.12.14
 * Time: 20:56
 */

namespace AppBundle\Service\Chart;


use AppBundle\Helper\StatisticDate;
use AppBundle\Service\CsvReader;
use AppBundle\Service\Filter;

/**
 * Class AbstractChart
 * @package AppBundle\Service\Chart
 */
abstract class AbstractChart
{

    const TYPE_INCOME = 'income';
    const TYPE_OUTCOME = 'outcome';

    /**
     * @var CsvReader
     */
    protected $csvReader;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param CsvReader $csvReader
     * @param Filter $filter
     */
    public function __construct(
        CsvReader $csvReader,
        Filter $filter
    )
    {
        $this->csvReader = $csvReader;
        $this->filter = $filter;
    }

    /**
     * @param StatisticDate $statisticDate
     * @return mixed
     */
    abstract protected function preProcess(StatisticDate $statisticDate);

    /**
     * @param String $arrayKey
     */
    protected function postProcess($arrayKey)
    {
        return;
    }

    /**
     * @param $value
     * @param \DateTime $date
     * @param $category
     * @param $arrayKey
     * @return mixed
     */
    abstract protected function setRow($value, \DateTime $date, $category, $arrayKey);


    /**
     * @return array
     */
    public function getData(StatisticDate $statisticDate)
    {
        $arrayKey = (String)$statisticDate;
        if (isset($this->data[$arrayKey])) {
            return $this->data[$arrayKey];
        }

        $rows = $this->csvReader->getRows();
        $filterKeys = $this->filter->getKeys();

        $this->preProcess($statisticDate);
        foreach ($rows as $row) {
            $category = $category = !empty($row[2]) ? $row[2] : 'undefined';
            $value = $row[0];
            $date = new \DateTime($row[1]);

            if (
                in_array($category, $filterKeys) ||
                !$statisticDate->isDateBetweenStartAndEnd($date)
            ) {
                continue;
            }

            $this->setRow($value, $date, $category, $arrayKey);
        }
        $this->postProcess($arrayKey);

        return $this->data[$arrayKey];
    }
}