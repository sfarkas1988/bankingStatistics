<?php
/**
 * Created by PhpStorm.
 * User: farkas-berlin
 * Date: 16.01.15
 * Time: 23:43
 */

namespace AppBundle\Controller;


use AppBundle\Helper\StatisticDate;
use AppBundle\Service\Chart\AbstractChart;
use AppBundle\Service\SumCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MonthlyController extends Controller
{

    /**
     * @Route("/", name="monthly")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $firstDay = new \DateTime();
        $firstDay->modify('first day of this month');
        $lastDay = new \DateTime();
        $lastDay->modify('last day of this month');
        return array(
            'first_day' => $firstDay,
            'last_day' => $lastDay
        );
    }


    /**
     * @Route("/monthly/data.json", name="monthly.data", methods={"POST"})
     */
    public function dataAction(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $statisticDate = new StatisticDate($content['start'], $content['end']);
        $sumCalculator = $this->get('app.chart.sum');
        $formatter = $this->get('app.formatter');

//        $lineSaving = $this->container->get('app.charts.line_saving');
//        $lineSaving->generate($rows);
//
//        $doughnut = $this->container->get('app.charts.doughnut');
//        $doughnut->generate($rows);
//
//        return array(
//            'chartData' => array(
//                'lineSaving' => array($lineSaving->getDataSets(), $lineSaving->getLabels()),
//                'doughnutOutgoing' => $doughnut->getDataSets()
//            )
//        );


        $sum = $sumCalculator->getData($statisticDate);
        $barCategoryGenerator = $this->get('app.charts.bar_category');
        $categoryChart = $barCategoryGenerator->getData($statisticDate);


        return new JsonResponse(array(
            'incomeSum' => $formatter->formatCurrency($sum[AbstractChart::TYPE_INCOME]['sum']),
            'incomeCountRows' => $sum[AbstractChart::TYPE_INCOME]['countRows'],
            'outcomeSum' => $formatter->formatCurrency($sum[AbstractChart::TYPE_OUTCOME]['sum']),
            'outcomeCountRows' => $sum[AbstractChart::TYPE_OUTCOME]['countRows'],
            'balance' => $formatter->formatCurrency(
                $sum[AbstractChart::TYPE_INCOME]['sum']-$sum[AbstractChart::TYPE_OUTCOME]['sum']
            ),
            'categoryChart' => $categoryChart
        ));
    }
}