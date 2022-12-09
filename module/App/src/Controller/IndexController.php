<?php

namespace App\Controller;

/**
 * Class IndexController
 *
 * @package App\Controller
 */
class IndexController extends Controller
{

    public function indexAction()
    {
        $options = [];
        $date    = new \DateTime();
        for ($month = 0; $month < 6; $month++) {
            $options[] = $date->format('F, Y');
            $date->modify('-1 month');
        }

        return $this->render('homepage', ['options' => $options]);
    }
}
