<?php

namespace App\EventSubscriber;

use App\Entity\Entreprise;
use App\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class ProfessionnelSubscriber implements EventSubscriberInterface
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var EngineInterface */
    protected $templating;

    /**
     * MyMailer constructor.
     *
     * @param \Swift_Mailer      $mailer
     * @param EngineInterface    $templating
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }



    public function onConfirmationInscription(GenericEvent $event)
    {
        /** @var Entreprise $entreprise */
        $entreprise = $event->getSubject();
        $mail = new \Swift_Message();

        $mails = array();
        foreach ($entreprise->getRepresentants() as $representant) {
            $mails[] = $representant->getEmail();
        }

        $mail

            ->setFrom([Events::MAIL_EXPEDITEUR => 'Forum IUT Entreprise, IUT de Troyes'])
            ->setTo($mails)
            ->setSubject('Confirmation d\'inscription au FORULM IUT Entreprise')
            ->setBody($this->templating->render('mails/confirmation.html.twig', ['entreprise' => $entreprise]))
            ->setReplyTo(Events::MAIL_EXPEDITEUR);

        $this->mailer->send($mail);
    }

    public function onConfirmationCreationCompte(GenericEvent $event)
    {
        $representant = $event->getSubject();
        $mail = new \Swift_Message();
        $mails = array();
        $mails[] = $representant->getEmail();


        $mail
            ->setFrom(Events::MAIL_EXPEDITEUR)
            ->setTo($mails)
            ->setSubject('Confirmation d\'inscription au FORULM IUT Entreprise')
            ->setBody($this->templating->render('mails/create-compte.html.twig', ['representant' => $representant]))
            ->setReplyTo(Events::MAIL_EXPEDITEUR);

        $this->mailer->send($mail);
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::CONFIRMATION_INSCRIPTION => 'onConfirmationInscription',
            Events::CONFIRMATION_CREATION_COMPTE => 'onConfirmationCreationCompte',
        ];
    }
}
