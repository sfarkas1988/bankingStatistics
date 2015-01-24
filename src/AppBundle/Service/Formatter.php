<?php
/**
 * Created by PhpStorm.
 * User: farkas-berlin
 * Date: 06.12.14
 * Time: 22:15
 */

namespace AppBundle\Service;


class Formatter
{

    public function formatCurrency($value, $locale = 'de')
    {
        $formatter = \NumberFormatter::create($locale, \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($value, 'EUR');
    }
}