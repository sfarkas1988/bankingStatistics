<?php
/**
 * Created by PhpStorm.
 * User: farkas-berlin
 * Date: 09.12.14
 * Time: 22:47
 */

namespace AppBundle\Service\Charts;


use AppBundle\Service\Charts\Helper\Filter;

class LineSaving extends AbstractChart
{
    public function generate(array $rows, $options = array())
    {

        $filter = new Filter();
        $filter->setKeys(array('Tagesgeld'));

        $savingCalc = array();
        foreach ($rows as $row) {

            if (in_array($row[2], $filter->getKeys())) {
                continue;
            }

            $date = new \DateTime($row[1]);
            if (!isset($savingCalc[$date->format('Y-m')])) {
                $savingCalc[$date->format('Y-m')] = array(0,0);
            }

            if ($row[0] > 0) {
                $savingCalc[$date->format('Y-m')][0] += $row[0];
            } else {
                $savingCalc[$date->format('Y-m')][1] += $row[0];
            }
        }

        ksort($savingCalc);
        $savings = array();
        foreach ($savingCalc as $key => $saving) {
            $savings[$key] = round($saving[0]+$saving[1], 2);
        }



        $this->labels = array_keys($savings);
        $this->dataSets = array_values($savings);
    }
}