<?php
namespace App\Controller;

use App\Entity\Job;
use App\Entity\Volunteer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class JobController extends AbstractController
{

    // create a new job
    #[Route('/api/jobs/create', name: 'job_create')]
    public function jobCreate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        // input validation
        if (!isset($data['name'])) {
          return $this->json(['error' => 'name is required'], Response::HTTP_BAD_REQUEST);
        }
        if (!isset($data['description'])) {
          return $this->json(['error' => 'description is required'], Response::HTTP_BAD_REQUEST);
        }

        $job = new Job();
        $job->setName($data['name']);
        $job->setDescription($data['description']);

        $entityManager->persist($job);
        $entityManager->flush();

        return $this->json([
          'message' => "{$job->getName()} (id: {$job->getId()}) created succesfully."
        ]);
    }

    // json list of all Jobs
    #[Route('/api/jobs', name: 'job_list')]
    public function jobList(EntityManagerInterface $entityManager): JsonResponse
    {
      $jobs = $entityManager->getRepository(Job::class)->findAll();
      $data = [];
      foreach ($jobs as $job) {
          $data[] = [
              'name' => $job->getName(),
              'description' => $job->getDescription()
          ];
      }

      return $this->json($data);
    }

    // details of a single job with associated volunteers
    #[Route('/api/jobs/{id}', name: 'job_details')]
    public function jobDetails(Job $job): JsonResponse
    {
      $data = [];
      $data['name'] = $job->getName();
      $data['description'] = $job->getDescription();
      $data['volunteers'] = $job->getVolunteers();
      
    
      return $this->json($data);
    }
}