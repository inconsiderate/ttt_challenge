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


class VolunteerController extends AbstractController
{
    // create a new volunteer
    #[Route('/api/volunteers/create', name: 'volunteer_create')]
    public function volunteerCreate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        // input validation
        if (!isset($data['first_name'])) {
          return $this->json(['error' => 'first_name is required'], Response::HTTP_BAD_REQUEST);
        }
        if (!isset($data['last_name'])) {
          return $this->json(['error' => 'last_name is required'], Response::HTTP_BAD_REQUEST);
        }

        $volunteer = new Volunteer();
        $volunteer->setFirstName($data['first_name']);
        $volunteer->setLastName($data['last_name']);

        $entityManager->persist($volunteer);
        $entityManager->flush();

        return $this->json([
          'message' => "{$volunteer->getFullName()} (id: {$volunteer->getId()}) created succesfully."
        ]);
    }
    
    // paginated list of all volunteers and the jobs they applied for
    #[Route('/api/volunteers', name: 'volunteer_list')]
    public function volunteerList(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
      $page = (int) $request->query->get('page', 1);
      $pageSize = (int) $request->query->get('pageSize', 10);
      $offset = ($page - 1) * $pageSize;
      $totalVolunteers = $entityManager->getRepository(Volunteer::class)->count([]);
      $volunteers = $entityManager->getRepository(Volunteer::class)->findBy([], null, $pageSize, $offset);
      
      $data = [];
      foreach ($volunteers as $volunteer) {
          $data[] = [
              'full_name' => $volunteer->getFullName(),
              'applied_jobs' => $volunteer->getJobs()
          ];
      }

      return $this->json([
          'current_page' => $page,
          'page_size' => $pageSize,
          'total_volunteers' => $totalVolunteers,
          'total_pages' => ceil($totalVolunteers / $pageSize),
          'volunteers' => $data,
      ]);
    }

    // return details on a single volunteer
    #[Route('/api/volunteers/{id}', name: 'volunteer_details')]
    public function volunteerDetails(Volunteer $volunteer): JsonResponse
    {
      $data = [];
      $data['name'] = $volunteer->getFullName();
      $data['jobs'] = $volunteer->getJobs();
      
      return $this->json($data);
    }
}