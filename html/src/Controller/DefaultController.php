<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Volunteer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $volunteers = $entityManager->getRepository(Volunteer::class)->findAll();
        $data = [];
        foreach ($volunteers as $volunteer) {
            $data[] = [
                'full_name' => $volunteer->getFullName(),
                'applied_jobs' => $volunteer->getJobs()
            ];
        }

        return $this->render('homepage.html.twig', ['data' => $data]);
    }
}
