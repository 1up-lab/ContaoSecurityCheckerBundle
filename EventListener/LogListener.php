<?php

namespace Oneup\Bundle\SecurityCheckerBundle\EventListener;

use Contao\System;
use Oneup\Bundle\SecurityCheckerBundle\Event\SecurityAuditEvent;

class LogListener
{
    public function onSecurityAudit(SecurityAuditEvent $event)
    {
        $audit = $event->getAudit();

        // TODO: Replace me with the contao-logger-service when available
        // See https://github.com/contao/core-bundle/pull/449
        System::log(
            'A security audit was performed. Number of issues: '.count($audit->getVulnerabilities()),
            'LogListener::onSecurityAudit',
            $audit->isVulnerable() ? 'ERROR' : 'CRON'
        );
    }
}
