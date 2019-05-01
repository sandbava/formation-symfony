<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_booking_index")
     */
    public function index(BookingRepository $repo)
    {
        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $repo->findAll(),
        ]);
    }

    /**
     * Permet d'éditer une réservation
     *
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $booking->setAmount(0);

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash('success', "La reservation n°{$booking->getId()} a bien été modifée !");

            return $this->redirectToRoute("admin_booking_index");
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * Permet de supprimer une réservation
     *
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     */
    public function delete(Booking $booking, ObjectManager $manager)
    {
        $id = $booking->getId();

        $manager->remove($booking);
        $manager->flush();

        $this->addFlash('success', "La réservation {$id} a bien été supprimée !");

        return $this->redirectToRoute('admin_booking_index');
    }
}
