<?php

use http\Client\Request;


class LoginController extends Controller
{
    public function loginAction($parameters)
    {
        if (!empty($parameters['post']['login']) && $parameters['post']['login'] === 'admin' && !empty($parameters['post']['password']) && $parameters['post']['password'] == '123') {
            session_start();
            $sessionAdmin = AdminSession::firstOrNew(['session_id' => session_id(), 'login' => 'admin']);
            $sessionAdmin->update(['is_logged'=>true]);
            header('Location: /');
        } else {
            $error = empty($parameters['post']) ? false : 'You are enter wrong login or password';
            $this->view('login_form',
                array(
                    'error' => $error,
                )
            );

        }
    }

    public function logoutAction()
    {
        session_start();
        $adminSession = AdminSession::firstOrNew(['login'=>'admin','session_id'=>session_id()]);
        $adminSession->update(['is_logged'=>false]);
        session_unset();
        session_write_close();
        header('Location: /');
    }
}
