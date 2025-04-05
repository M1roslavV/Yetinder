<?php

namespace App\Controller;

use App\Repository\ScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ScoreController extends AbstractController
{
    #[Route('/statistics-yeti-your', name: 'statistics-yeti')]
    public function statisticsYeti(ScoreRepository $scoreRepository, SessionInterface $session): Response
    {
        $userId = $session->get('user_id');
        if (!$userId) {
            return $this->redirectToRoute('login');
        }

        $dayData = $scoreRepository->getAverageRatingByDay($userId) ?? [];
        $monthData = $scoreRepository->getAverageRatingByMonth($userId) ?? [];
        $yearData = $scoreRepository->getAverageRatingByYear($userId) ?? [];

        return $this->extracted($dayData, $monthData, $yearData);
    }


    #[Route('/statistics-yeti-all', name: 'statistics-yeti-all')]
    public function statisticsYetiAll(ScoreRepository $scoreRepository): Response
    {
        $dayData = $scoreRepository->getAverageRatingByDay(null) ??[];
        $monthData = $scoreRepository->getAverageRatingByMonth(null) ??[];
        $yearData = $scoreRepository->getAverageRatingByYear(null) ??[];

        return $this->extracted($dayData, $monthData, $yearData);
    }


    public function extracted(?array $dayData, ?array $monthData, ?array $yearData): Response
    {
        $dayLabels = $dayData ? array_column($dayData, 'day') : [];
        $dayRatings = $dayData ? array_column($dayData, 'avg_rating') : [];
        $filteredRatingsD = array_filter($dayRatings, fn($value) => $value !== null);
        $avgDay = !empty($filteredRatingsD) ? round(array_sum($filteredRatingsD) / count($filteredRatingsD), 2) : null;

        $monthLabels = $monthData ? array_column($monthData, 'month') : [];
        $monthRatings = $monthData ? array_column($monthData, 'avg_rating') : [];
        $filteredRatingsM = array_filter($monthRatings, fn($value) => $value !== null);
        $avgMonth = !empty($filteredRatingsM) ? round(array_sum($filteredRatingsM) / count($filteredRatingsM), 2) : null;

        $yearLabels = $yearData ? array_column($yearData, 'year') : [];
        $yearRatings = $yearData ? array_column($yearData, 'avg_rating') : [];
        $filteredRatingsY = array_filter($yearRatings, fn($value) => $value !== null);
        $avgYear = !empty($filteredRatingsY) ? round(array_sum($filteredRatingsY) / count($filteredRatingsY), 2) : null;

        return $this->render('statistic.html.twig', [
            'dayData' => $dayData,
            'dayLabels' => $dayLabels,
            'dayRatings' => $dayRatings,
            'avgDay' => $avgDay,
            'monthData' => $monthData,
            'monthLabels' => $monthLabels,
            'monthRatings' => $monthRatings,
            'avgMonth' => $avgMonth,
            'yearData' => $yearData,
            'yearLabels' => $yearLabels,
            'yearRatings' => $yearRatings,
            'avgYear' => $avgYear,
        ]);
    }


    #[Route('/match-yeti-process/{id}', name: 'match-yeti-process', methods: ['POST'])]
    public function matchYetiProcess(SessionInterface $session, $id, ScoreRepository $scoreRepository, Request $request): Response
    {
        $userId = $session->get('user_id');

        $score = $request->request->get('score');
        $currentDate = new \DateTime();
        $formattedDate = $currentDate->format('Y-m-d H:i:s');
        if ($score !== null) {
            $scoreRepository->insert($score, $userId, $id, $formattedDate);
        }
        return $this->redirectToRoute('match-yeti');
    }


}
