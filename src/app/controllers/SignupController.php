<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{

    public function IndexAction()
    {
        // defalut action
       
        
    }

    public function registerAction()
    {
        $payload = [
            "name" => $_POST['name'],
            "email" => $_POST['email'],
            "password" => $_POST['password'],
        ];
        $collection = $this->mongo->Users;
        $status = $collection->insertOne($payload);
    
        $this->session->set('name', $payload['name']);
        $this->session->set('email',$payload['email']);
        $this->session->set('password', $payload['password']);

    

        if (isset($_POST['remember'])=="on") {
        $this->cookies->set('email', $this->session->get('email'), time() + 15 * 86400);
        $this->cookies->set('pass', $this->session->get('password'), time() + 15 * 86400);
        }
        else{
            $this->cookies->set('email', $this->session->get('email'), time() - 15 * 86400);
            $this->cookies->set('pass', $this->session->get('password'), time() - 15 * 86400);
        }

      
        if ($status->getInsertedCount()>1) {
            $this->view->message = "Register succesfully";
        } else {
            $this->view->message = "Not Register due to following reason: <br>";
        }
        $this->response->redirect('login/index');
    }
    }
   
