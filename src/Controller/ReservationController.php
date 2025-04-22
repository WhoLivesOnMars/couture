<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ProductRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route(name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();
        $reservations = $reservationRepository->findBy(['user' => $user]);

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/admin', name: 'app_admin_reservation', methods: ['GET'])]
    public function adminReservation(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $reservations = $reservationRepository->findAll();
        } else {
            $reservations = $reservationRepository->findByUserProducts($user);
        }

        return $this->render('reservation/admin/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/new/{id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(int $id, Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $product = $productRepository->find($id);

        $reservation = new Reservation();
        $reservation->setProduct($product);
        $reservation->setUser($this->getUser());
        $reservation->setDateReservation(new \DateTimeImmutable());

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'product' => $product,
        ]);
    }

    // #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    // public function show(Reservation $reservation): Response
    // {
    //     return $this->render('reservation/show.html.twig', [
    //         'reservation' => $reservation,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    // {
    //    $form = $this->createForm(ReservationType::class, $reservation);
    //    $form->handleRequest($request);

    //    if ($form->isSubmitted() && $form->isValid()) {
    //        $entityManager->flush();

    //        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    //    }

    //    return $this->render('reservation/edit.html.twig', [
    //        'reservation' => $reservation,
    //        'form' => $form,
    //    ]);
    //}

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($reservation->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous ne pouvez pas supprimer cette réservation.");
        }

        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
