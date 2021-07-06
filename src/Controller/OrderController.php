<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class OrderController extends AbstractController
{
    /**
     * @Route("/order/create", name="order_create")
     */
    public function create(NotifierInterface $notifier): Response
    {
        /*
            Notification pour prévenir d'une nouvelle commande
        */
            // Création d'une notification avec un sujet et un canal            
            $notification = new Notification('Nouvelle commande', ['email', 'chat']);
            // Le tableau de canal définit comment la notification va être distribuée
            // ['email', 'sms']  enverrait à a fois par mail ET par SMS

            // Ajout du contenu à la notification
            $notification->content('Une nouvelle commande vient d\être passée');

            // On peut aussi ajouter des emojis
            $notification->emoji('💀');


        /*
            Destinataire de la notification
        */
            // Création du destinataire
            $recipient = new Recipient(
                'destinataire@test.fr',
                '33633821119'
            );

        /*
            Envoi de la notification
        */
            $notifier->send($notification, $recipient);


        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }
}
