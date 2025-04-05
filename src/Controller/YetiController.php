<?php

namespace App\Controller;
use App\Repository\AddressRepository;
use App\Repository\ScoreRepository;
use App\Repository\YetiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class YetiController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->redirectToRoute('match-yeti');
    }

    #[Route('/add-yeti', name: 'add-yeti')]
    public function addYeti(SessionInterface $session): Response
    {
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('login');
        }
        return $this->render('addYeti.html.twig');
    }

    #[Route('/add-yeti-submit', name: 'add-yeti-submit', methods: ['POST'])]
    public function addYetiSubmit(Request $request, SessionInterface $session, YetiRepository $yetiRepository, AddressRepository $addressRepository): Response
    {
        $errors = [];
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('login');
        }

        $town = $request->request->get('town');
        $street = $request->request->get('street');
        if (!preg_match('/^[\p{L}\s]+$/u', $town)) {
            $errors[] = "City can only contain letters and spaces.";
        }
        if (!preg_match('/^[\p{L}\s]+(?:\s\d{1,3})?$/u', $street)) {
            $errors[] = "The street must contain only letters and can end with a three-digit number.";
        }

        $weight = $request->request->get('weight');
        $height = $request->request->get('height');
        $specialAbility = $request->request->get('specialAbility');
        $username = $request->request->get('username');

        if (!preg_match('/^(?:\d{1,2}(\.\d{1,2})?|[1-3]\d{2}(\.\d{1,2})?|400(\.0{1,2})?)$/', $weight)) {
            $errors[] = "Weight must be a number between 0-400 with a maximum of one decimal place.";
        }

        if (!preg_match('/^(?:\d{1,2}(\.\d{1})?|[1-3]\d{2}(\.\d{1})?|400(\.0)?)$/', $height)) {
            $errors[] = "Height must be a number between 0-400 with a maximum of one decimal place.";
        }

        if (!preg_match('/^[\p{L}\s]+$/u', $specialAbility)) {
            $errors[] = "The special ability can only contain letters and spaces.";
        }

        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            $errors[] = "The username can only contain letters, numbers, and the underscore and must be 3-20 characters long.";
        }

        $yeti = $yetiRepository->findByUsername($username);
        if (!empty($yeti)) {
            $yeti = [];
            $yeti['username'] = "";
            $yeti['id'] = "";
        }

        if($username === $yeti['username']) {
            $errors[] = "The yeti with this username already exists.";
        }

        if (count($errors) > 0) {
            $string = "";
            foreach ($errors as $error) {
                $string .= $error . "\n";
            }

            $this->addFlash('errors', $string);
            return $this->redirectToRoute('add-yeti');
        }else{
            $imagePath = $request->files->get('profileImage');
            $newFileName = "";
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                     error_log("Chyba při nahrávání souboru: " . $e->getMessage());
                }
            }
            $yetiRepository->createYeti($username, $weight, $height, $specialAbility, '/uploads/' . $newFileName);
            if ($yeti['id'] !==""){
                $addressRepository->createAddress($town,$street, $yeti['id']);
            }
        }



        return $this->redirectToRoute('top-yeti');
    }

    #[Route('/top-yeti', name: 'top-yeti')]
    public function topYeti(YetiRepository $yetiRepository): Response
    {
        $topYeti = $yetiRepository->selectTop10();
        return $this->render('topYeti.html.twig',[
            'topYeti' => $topYeti
        ]);
    }


    #[Route('/match-yeti', name: 'match-yeti')]
    public function matchYeti(
        YetiRepository $yetiRepository,
        SessionInterface $session,
        AddressRepository $addressRepository,
        ScoreRepository $scoreRepository
    ): Response {
        $userId = $session->get('user_id');
        $allYeti = $yetiRepository->findAllYeti();
        $yeti = null;

        if ($userId) {
            $yetiGroup = $yetiRepository->findByUserId($userId);
            $minDifference = 10;
            $bestMatches = [];

            if (!empty($yetiGroup)) {
                foreach ($yetiGroup as $userYeti) {
                    $userYetiAddress = $addressRepository->findByYetiId($userYeti['id']);
                    $userYetiScore = $scoreRepository->avgRateByYetiId($userYeti['id'])[0]['avg'] ?? 0;

                    foreach ($allYeti as $compareYeti) {
                        if ($compareYeti['id'] == $userYeti['id']) {
                            continue;
                        }
                        $compareYetiAddress = $addressRepository->findByYetiId($compareYeti['id']);
                        $compareYetiScore = $scoreRepository->avgRateByYetiId($compareYeti['id'])[0]['avg'] ?? 0;

                        $heightDifference = abs($userYeti['vyska'] - $compareYeti['vyska']);
                        $weightDifference = abs($userYeti['vaha'] - $compareYeti['vaha']);
                        $scoreDifference = abs($userYetiScore - $compareYetiScore);
                        $totalDifference = $heightDifference + $weightDifference + $scoreDifference;

                        if ($compareYeti['specialni_schopnost'] === $userYeti['specialni_schopnost']) {
                            $totalDifference -= 5;
                        }
                        if ($compareYetiAddress['mesto'] === $userYetiAddress['mesto']) {
                            $totalDifference -= 5;
                        }
                        if ($compareYetiAddress['ulice'] === $userYetiAddress['ulice']) {
                            $totalDifference -= 5;
                        }

                        if ($totalDifference < $minDifference) {
                            $alreadyAdded = false;
                            foreach ($bestMatches as $bm) {
                                if ($bm['id'] === $compareYeti['id']) {
                                    $alreadyAdded = true;
                                    break;
                                }
                            }
                            if (!$alreadyAdded) {
                                $bestMatches[] = $compareYeti;
                            }
                        }
                    }
                }

                if (!empty($bestMatches)) {
                    $yeti = $bestMatches[array_rand($bestMatches)];
                } else {
                    $yeti = $allYeti[array_rand($allYeti)];
                }
            } else {
                $yeti = $allYeti[array_rand($allYeti)];
            }
        } else {
            $yeti = $allYeti[array_rand($allYeti)];
        }

        $AddressYeti = $addressRepository->findByYetiId($yeti['id']);

        return $this->render('mainPage.html.twig', [
            'yeti' => $yeti,
            'address' => $AddressYeti
        ]);
    }


    #[Route('/reload-match-yeti', name: 'reload-match-yeti')]
    public function reloadMatch(): Response
    {
        return $this->redirectToRoute('match-yeti');
    }
}
