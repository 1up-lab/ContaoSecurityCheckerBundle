<?php

namespace Oneup\Bundle\ContaoSecurityCheckerBundle\EventListener;

use Contao\Environment;
use Contao\StringUtil;
use Contao\Config;
use Oneup\Bundle\ContaoSecurityCheckerBundle\Event\SecurityAuditEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class NotificationListener
{
    protected $mailer;
    protected $translator;
    protected $templating;
    protected $request;
    protected $enable;
    protected $email;
    protected $suppressManualAudits;
    protected $onlyFailedAudits;

    public function __construct(
        \Swift_Mailer $mailer,
        TranslatorInterface $translator,
        EngineInterface $templating,
        RequestStack $requestStack,
        $enable,
        $email,
        $suppressManualAudits,
        $onlyFailedAudits
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->templating = $templating;
        $this->request = $requestStack->getCurrentRequest();
        $this->enable = $enable;
        $this->email = $email ?: Config::get('adminEmail');
        $this->suppressManualAudits = $suppressManualAudits;
        $this->onlyFailedAudits = $onlyFailedAudits;

        // RequestStack is empty, when this listener gets called on kernel.terminate - WTF?
        // TODO: investigate this!
        if (null === $this->request) {
            // that's a fucking hack. you should never do this. never. ever. never ever!
            $this->request = Request::createFromGlobals();
        }
    }

    public function onSecurityAudit(SecurityAuditEvent $event)
    {
        $audit = $event->getAudit();
        $transKey = $audit->isVulnerable() ? 'failed' : 'ok';

        if (!$this->enable) {
            return;
        }

        if (!$this->isCron() && $this->suppressManualAudits) {
            return;
        }

        if ($this->onlyFailedAudits && !$audit->isVulnerable()) {
            return;
        }

        list($adminName, $adminEmail) = StringUtil::splitFriendlyEmail(Config::get('adminEmail'));

        $message = new \Swift_Message();
        $message->setFrom($adminEmail, $adminName);
        $message->setTo($this->email);
        $message->setSubject($this->translator->trans('mail.subject.'.$transKey, ['%host%' => \Idna::decode(Environment::get('host'))]));
        $message->setBody(
            $this->templating->render('OneupContaoSecurityCheckerBundle:Mail:mail.html.twig', [
                'isVulnerable' => $audit->isVulnerable(),
                'vulnerabilities' => $audit->getVulnerabilities(),
                'request' => $this->request,
            ]),
            'text/html'
        );

        $this->mailer->send($message);
    }

    /**
     * Returns false if the action was triggered manually.
     *
     * @todo This is a quite bad hack. Is there another way, anyone?
     * @return bool
     */
    protected function isCron()
    {
        return TL_MODE === 'FE';
    }
}
