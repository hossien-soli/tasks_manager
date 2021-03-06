<?php

namespace App\Controllers;

use App\Util\Validator;
use App\Models\User;
use App\Util\Hash;
use App\Models\Task;
use App\Util\Mailer;

class AuthController extends Controller
{
    public function registerGET ($request,$response)
    {
        return $this->view->render($response,'auth/register.twig',[
            'title' => 'Register',
        ]);
    }

    public function registerPOST ($request,$response)
    {
        $form_data = $request->getParsedBody();
        $validator = new Validator;
        $validation = $validator->make($form_data,[
            'email' => 'required|email',
            'username' => 'required|min:4',
            'password' => 'required|min:6',
            'password_confirm' => 'required|same:password',
        ]);
        $validation->validate();
        if ($validation->fails()) {
            $errors = $validation->errors();
            $_SESSION['errors'] = $errors;
            return $response->withRedirect($this->router->pathFor('auth.register'));
        }
        else {
            // unique email and username
            if (!$this->gate->canUseEmail($form_data['email'])) {
                $this->flash->addMessage('error','This email is already taken !');
                return $response->withRedirect($this->router->pathFor('auth.register'));
            }
            else if (!$this->gate->canUseUsername($form_data['username'])) {
                $this->flash->addMessage('error','This username is already taken !');
                return $response->withRedirect($this->router->pathFor('auth.register'));
            }
            else {
                // upload image if exists
                $image_to_upload = $request->getUploadedFiles()['profile_picture'];
                if ($image_to_upload->getSize() != 0 && $image_to_upload->getError() == 0) {
                    $client_filename = $image_to_upload->getClientFilename();
                    $basename = pathinfo($client_filename)['filename'];
                    $extension_name = pathinfo($client_filename)['extension'];
                    $new_basename = bin2hex(random_bytes(strlen($basename))) . uniqid();
                    $new_basename = str_shuffle($new_basename);
                    $new_name = $new_basename . '.' . $extension_name;
                    $upload_directory = $this->container->get('users_profile_picture_directory')['local'];
                    $final_path = $upload_directory . DIRECTORY_SEPARATOR . $new_name;
                    $image_to_upload->moveTo($final_path);
                    $public_dir = $this->container->get('users_profile_picture_directory')['public'];
                    $final_path_public = $public_dir . '/' . $new_name;
                    $form_data = array_merge($form_data,['imagepath' => $final_path_public]);
                }
                $hash = new Hash ($this->container->get('config'));
                $form_data['password'] = $hash->password($form_data['password']);
                $active_hash = bin2hex(random_bytes(25));
                $form_data = array_merge($form_data,['activehash' => $active_hash]);

                User::create($form_data);
                // send activation mail
                $mailer = new Mailer ($this->container->get('config'));
                $activation_link = $this->config->get('app.url') . $this->router->pathFor('auth.activate_account');
                $activation_link .= sprintf('?email=%s&activehash=%s',$form_data['email'],$form_data['activehash']);              
                $mail_subject = "Tasks Manager | Thanks for registering";
                $mail_body = sprintf('
                    <h1>Tasks Manager</h1>
                    <h2>Your account has been created !</h2>
                    <h2>For activate your account please <a href="%s" >Click Here.</a></h2>
                ',$activation_link);
                $altbody = sprintf('Tasks Manager\nFor activate your account please open following link on your browser\n%s',$activation_link);
                $mailer->readyForSend($form_data['email'],$mail_subject,$mail_body,$altbody);
                $mailer->AttemptForSend();

                $this->flash->addMessage('info','You account hsa been created !<br/>Please check your email for activate your account.');
                return $response->withRedirect($this->router->pathFor('auth.login'));
            }
        }
    }

    public function activateAccount ($request,$response)
    {
        $email = $request->getQueryParam('email');
        $active_hash = $request->getQueryParam('activehash');
        $user = User::where('email',$email)
                    ->where('activehash',$active_hash)
                    ->first();
        if (is_null($user)) {
            return $response->withStatus(404)
                            ->withHeader('Content-Type','text/plain')
                            ->write('404 | Page not Found !');
        }
        else {
            $user->activehash = NULL;
            $user->isactive = 1;
            $user->save();
            $this->flash->addMessage('info','Your account has been successfully activated !');
            return $response->withRedirect($this->router->pathFor('auth.login'));
        }
    }

    public function loginGET ($request,$response)
    {
        return $this->view->render($response,'auth/login.twig',[
            'title' => 'Account | Login',
        ]);
    }

    public function loginPOST ($request,$response)
    {
        $form_data = $request->getParsedBody();
        $validator = new Validator;
        $validation = $validator->make($form_data,[
            'username' => 'required|min:4',
            'password' => 'required|min:6',
        ]);
        $validation->validate();
        if ($validation->fails()) {
            $_SESSION['errors'] = $validation->errors();
            return $response->withRedirect($this->router->pathFor('auth.login'));
        }
        else {
            $res = $this->auth->attempt($form_data['username'],$form_data['password']);
            if (!$res) {
                $this->flash->addMessage('error','Incorrect username or password !');
                return $response->withRedirect($this->router->pathFor('auth.login'));
            }
            else {
                if ($this->gate->accountIsActive($form_data['username'])) {
                    $this->flash->addMessage('info',"You're logged in !");
                    return $response->withRedirect($this->router->pathFor('auth.dashboard'));
                }
                else {
                    $this->flash->addMessage('error','Your account has not been activated!<br/>Please check your email for activate your account.');
                    return $response->withRedirect($this->router->pathFor('auth.login'));
                }
            }
        }
    }

    public function dashboardGET ($request,$response)
    {
        $tasks = $this->auth->user()->tasks;
        return $this->view->render($response,'auth/dashboard.twig',[
            'title' => 'Account | Dashboard',
            'tasks' => $tasks,
        ]);
    }

    public function logout ($request,$response)
    {
        $this->auth->logout();
        $this->flash->addMessage('info','Successfully logged out!');
        return $response->withRedirect($this->router->pathFor('main.home'));
    }
}