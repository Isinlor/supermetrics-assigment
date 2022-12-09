<?php

namespace App\Controller;

/**
 * Class ErrorController
 *
 * @package App\Controller
 */
class ErrorController extends Controller
{

    public function notFoundAction()
    {
        return $this->render('error', ['message' => 'Page not found']);
    }
}
