<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    #[Route('/register', name: 'register')]
    public function register(): Response
    {
        return $this->render('register.html.twig');
    }

    #[Route('/registerSubmit', name: 'registerSubmit', methods: ['POST'])]
    public function registerSubmit(Request $request, UserRepository $userRepository): Response
    {
        $passwordValid = [];

        if ($request->isMethod('POST')) {
            $errors = [];

            $email = $request->request->get('email');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email';
            }

            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');
            if ($password !== $confirmPassword) {
                $errors[] = 'Passwords do not match';
            }

            if (strlen($password) < 6) {
                $passwordValid[] = "6 characters";
            }

            if (!preg_match('/[a-z]/', $password)) {
                $passwordValid[] = "one lowercase letter";
            }

            if (!preg_match('/[A-Z]/', $password)) {
                $passwordValid[] = "one uppercase letter";
            }

            if (!preg_match('/\d/', $password)) {
                $passwordValid[] = "one digit";
            }

            if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
                $passwordValid[] = "one special character";
            }

            if(count($passwordValid) > 0){

                $passwordValidString = "Password must at least " . implode(", ", $passwordValid);
                $errors[] = $passwordValidString;
            }

            if (count($errors) > 0) {
                $string = "";
                foreach ($errors as $error) {
                    $string .= $error . "\n";
                }

                $this->addFlash('errors', $string);
                return $this->redirectToRoute('register');
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $userRepository->createUser($email, $hashedPassword);
        }
        return $this->redirectToRoute('login');
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }


    #[Route('/loginSubmit', name: 'loginSubmit', methods: ['POST'])]
    public function loginSubmit(Request $request, UserRepository $userRepository, SessionInterface $session): Response
    {
        $errors = [];
        $email = $request->request->get('email');
        $user = null;
        if($email == ""){
            $errors[] = 'Email is required';
        }else{
            $password = $request->request->get('password');

            $user = $userRepository->findByEmail($email);

            if ($user === null){
                $errors[] = 'Email not found';
            }else{
                if (!password_verify($password, $user['password'])) {
                    $errors[] = 'Wrong password';
                }
            }
        }

        if (count($errors) > 0) {
            $string = "";
            foreach ($errors as $error) {
                $string .= $error . "\n";
            }

            $this->addFlash('errors', $string);
            return $this->redirectToRoute('login');
        }

        $session->set('user_id', $user['id']);
        return $this->redirectToRoute('top-yeti');
    }

    #[Route('/logout', name: 'logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('login');
    }
}
