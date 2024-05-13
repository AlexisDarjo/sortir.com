<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\FilterFormType;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\SortieRepository;
use App\Service\CityApiService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_PARTICIPANT')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'app_sortie_index', methods: ['GET', 'POST'])]
    public function index(Request $request,SortieRepository $sortieRepository): Response
    {
        $form = $this->createForm(FilterFormType::class);
        $form->handleRequest($request);

        $sorties=$sortieRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()){
            $formData = $form->getData();
        }

        return $this->render('sortie/index.html.twig', [
            'sorties' => $sorties,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_sortie_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ORGANISATEUR')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $etat = $entityManager->getRepository(Etat::class)->find(1);
        $sortie->setEtat($etat);

        // Récupérer le lieu à partir de la sortie
        $lieu = $sortie->getLieu();

        $ville = null; // Initialiser $ville à null pour éviter l'erreur

        if ($lieu instanceof Lieu) {
            // Récupérer la ville associée au lieu
            $ville = $lieu->getVille();

            if ($ville instanceof Ville) {
                // Vous pouvez accéder aux propriétés de la ville ici
                $nomVille = $ville->getNom();
                $codePostal = $ville->getCodePostal();

                // Retournez les données de la ville sous forme de réponse JSON
                return new JsonResponse([
                    'nomVille' => $nomVille,
                    'codePostal' => $codePostal
                ]);
            }
        }

        $preloadedLieu = $entityManager->getRepository(Lieu::class)->findOneBy([]);
        $preloadedVille = $entityManager->getRepository(Ville::class)->findOneBy([]);
        if ($preloadedLieu instanceof Lieu) {
            $rue = $preloadedLieu->getRue();
            $latitude=$preloadedLieu->getLatitude();
            $longitude=$preloadedLieu->getLongitude();
        }

        if ($preloadedVille instanceof Ville) {
            $codePostal = $preloadedVille->getCodePostal();
            $nomVille = $preloadedVille->getNom();

        }

        $form = $this->createForm(SortieType::class, $sortie,['lieu' => $lieu]);
        $form->handleRequest($request);

        $formSubmitted = false;

        if ($form->isSubmitted() && $form->isValid()) {
            // Vos actions de traitement du formulaire

            $formSubmitted = true;
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Un souhait a été crée.');

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'formSubmitted' => $formSubmitted,
            'rue' => $rue, // Passer la valeur de la rue au modèle Twig
            'latitude'=> $latitude,
            'longitude'=>$longitude,
            'codePostal' => $codePostal,
            'nomVille'=>$nomVille,
        ]);

    }


    #[Route('/{id}', name: 'app_sortie_show', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sortie_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ORGANISATEUR')]
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }








    #[Route('/{id}', name: 'app_sortie_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ORGANISATEUR')]
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sortie_show', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/adresse/{lieuId}', name: 'get_adresse')]
    public function getAdresse(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $lieuId = $request->get('lieuId');

        // Récupérer la rue en fonction de l'ID du lieu
        // Ici, je suppose que vous avez un repository pour l'entité Lieu
        $lieu = $entityManager->getRepository(Lieu::class)->find($lieuId);
        $rue = $lieu ? $lieu->getRue() : null;
        $latitude = $lieu ? $lieu->getLatitude() : null;
        $longitude = $lieu ? $lieu->getLongitude() : null;


        $codePostal = $lieu ? $lieu->getVille()->getCodePostal() : null;
        $nomVille = $lieu ? $lieu->getVille()->getNom() : null;

        return new JsonResponse(['adresse' => $rue,
                                'latitude'=>$latitude,
                                'longitude'=>$longitude,
                                'codePostal'=>$codePostal,
                                'nomVille'=>$nomVille,
            ]);
    }





}