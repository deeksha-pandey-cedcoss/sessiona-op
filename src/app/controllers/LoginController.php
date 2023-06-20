<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
// login controller
class LoginController extends Controller
{
    public function indexAction()
    {
        // default login view
    }
    // login action page
    public function loginAction()
    {
        
        $collection = $this->mongo->Users;
        $m = $collection->findOne(["email" => $_POST['email'], "password" => $_POST['password']]);
        if ($m->email!==null) {
            $this->view->message = "LOGIN SUCCESSFULLY";
            $this->response->redirect('dashboard/index');
        } else {
            $response = new Response();

            $response->setStatusCode(403, 'Not Found');
            $response->setContent("Sorry, Credentials does not match");
            $this->view->message = "Not Login succesfully ";

        }

    }
}
