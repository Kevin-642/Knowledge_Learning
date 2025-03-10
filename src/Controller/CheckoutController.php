<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Repository\CartRepository;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CheckoutController extends AbstractController
{

    
    #[Route('/create-checkout-session', name: 'app_checkout')]
    public function createCheckoutSession(Request $request, CartRepository $cartRepository, StripeService $stripeService, EntityManagerInterface $em): Response
    {
       $user = $this->getUser();
       $cartItems = $cartRepository->findBy(['user' =>$user]);

       $session = $stripeService->createCheckoutSession(
            $cartItems,
            $this->generateUrl('success_payment', [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->generateUrl('cancel_payment', [], UrlGeneratorInterface::ABSOLUTE_URL),
       );

       return $this->redirect($session->url);
    }

    #[Route('/success_payment',name:'success_payment')]
    public function successPayment(CartRepository $cartRepository, EntityManagerInterface $em): Response
    {
        $user = $this-> getUser();
        $cartItems = $cartRepository->findBy(['user' => $user]);

        foreach($cartItems as $item){ 
            $purchase = new Purchase();
            $purchase->setUser($user);
            $purchase->setPurchaseAt(new \DateTimeImmutable());
            if ($item->getCursus()){
                $purchase->setCursus($item->getCursus());
            }elseif ($item->getLesson()){
                $purchase->setLesson($item->getLesson());
            }
            $em->persist($purchase);
            $em->remove($item);
        }
        $em->flush();

        return $this->render('checkout/success_payment.html.twig');
    }

    #[Route('/cancel_payment',name:'cancel_payment')]
    public function cancelPayment(): Response
    {
        return $this->render('checkout/cancel_payment.html.twig');
    }
}
