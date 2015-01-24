<?php
/**
 * Created by PhpStorm.
 * User: farkas-berlin
 * Date: 10.12.14
 * Time: 00:25
 */

namespace AppBundle\Service\Charts;


class Doughnut extends AbstractChart
{
    public function generate(array $rows, $options = array())
    {

        $this->dataSets = array(
            array('value' => 188, 'color' => "#F7464A", 'highlight' => "#FF5A5E", 'label' => "Red"),
            array('value' => 50, 'color' => "#46BFBD", 'highlight' => "#5AD3D1", 'label' => "Green"),
            array('value' => 100, 'color' => "#FDB45C", 'highlight' => "#FFC870", 'label' => "Yellow")
        );

    }
}