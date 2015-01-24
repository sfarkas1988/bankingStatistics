<?php
/**
 * Created by PhpStorm.
 * User: farkas-berlin
 * Date: 06.12.14
 * Time: 22:08
 */

namespace AppBundle\Service;

class Filter
{
    private $keys = array();

    public function __construct($keys)
    {
        $this->keys = $keys;
    }


    /**
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }


}