<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Inscription;
use App\Entity\Sortie;
use App\Form\InscriptionType;
use App\Repository\EtatRepository;
use App\Repository\InscriptionRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\VarDumper\VarDumper;

#[Route('/inscription')]
class InscriptionController extends AbstractController
{
    #[Route('/', name: 'app_inscription_index', methods: ['GET'])]
    public function index(InscriptionRepository $inscriptionRepository): Response
    {
        return $this->render('inscription/index.html.twig', [
            'inscriptions' => $inscriptionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_inscription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $inscription = new Inscription();
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($inscription);
            $entityManager->flush();

            return $this->redirectToRoute('app_inscription_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inscription/new.html.twig', [
            'inscription' => $inscription,
            'form' => $form,
        ]);
    }

    #[Route('/{idParticipant}', name: 'app_inscription_show', methods: ['GET'])]
    public function show(Inscription $inscription): Response
    {
        return $this->render('inscription/show.html.twig', [
            'inscription' => $inscription,
        ]);
    }

    #[Route('/{idParticipant}/edit', name: 'app_inscription_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Inscription $inscription, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_inscription_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inscription/edit.html.twig', [
            'inscription' => $inscription,
            'form' => $form,
        ]);
    }

    #[Route('/{idParticipant}', name: 'app_inscription_delete', methods: ['POST'])]
    public function delete(Request $request, Inscription $inscription, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inscription->getIdParticipant(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($inscription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_inscription_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{idSortie}/{idUser}', name: 'app_inscription_participate', methods: ['GET', 'POST'])]
    public function participate(Request $request,EtatRepository $etatRepository,SortieRepository $sortieRepository, ParticipantRepository $participantRepository, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $inscription = new Inscription();
        $idUser = $request->attributes->get('idUser');
        $participant = $participantRepository->find($idUser);
        $inscription->setIdParticipant($participant);

        $idSortie = $request->attributes->get('idSortie');
        $sortie = $sortieRepository->find($idSortie);
        $inscription->setIdSortie($sortie);

        $inscription->setDate(new \DateTime());

        // Vérifier si la sortie est ouverte et si la date limite d'inscription n'est pas dépassée
        if ($sortie->getEtat()->getLibelle() !== 'Ouverte' ||
            $sortie->getInscriptions()->count() >= $sortie->getNbInscriptionsMax() ||
            $sortie->getDateLimiteInscription() < new \DateTime()) {

            $this->addFlash('danger', 'Inscription impossible. La sortie est fermée ou la date limite d\'inscription est dépassée.');
            return $this->redirectToRoute('app_sortie_index');
        }

        $entityManager->persist($inscription);
        $entityManager->flush();

        // Afficher un message de débogage
        error_log('Message de débogage');

        //Si le nombre max de participant max est atteint, alors l'état change ete devient Cloturée (id =3)
        if ($sortie->getInscriptions()->count() == $sortie->getNbInscriptionsMax()-1){
            $etat=$etatRepository->find(3);
            $sortie->setEtat($etat);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sortie_index', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/unsubscribe/{idSortie}/{idUser}', name: 'app_inscription_unsubscribe', methods: ['GET', 'POST'])]
    public function unsubscribe(Request $request,EtatRepository $etatRepository,SortieRepository $sortieRepository, InscriptionRepository $inscriptionRepository, EntityManagerInterface $entityManager): Response
    {
        $idUser = $request->attributes->get('idUser');
        $idSortie = $request->attributes->get('idSortie');
        $inscription = $inscriptionRepository->findOneBy(['idParticipant' => $idUser, 'idSortie' => $idSortie]);

        // Vérifier que la sortie n'a pas commencé.
        $idSortie = $request->attributes->get('idSortie');
        $sortie = $sortieRepository->find($idSortie);
        if ($sortie->getEtat()->getLibelle() !== 'Ouverte' && $sortie->getEtat()->getLibelle() !== 'Créée'  && $sortie->getEtat()->getLibelle() !== 'Clôturée') {
            $this->addFlash('danger', 'Désinscription impossible. La date limite est dépassée.');
            return $this->redirectToRoute('app_sortie_index');
        }

        if ($inscription) {
            $entityManager->remove($inscription);
            //Si le nombre max de participant max est atteint, alors l'état change ete devient Ouverte (id =3)
            if ($sortie->getInscriptions()->count() == $sortie->getNbInscriptionsMax()){
                $etat=$etatRepository->find(2);
                $sortie->setEtat($etat);
                $entityManager->persist($sortie);
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sortie_index');
    }
}