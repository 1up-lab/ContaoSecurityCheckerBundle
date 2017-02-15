<?php

namespace Oneup\Bundle\ContaoSecurityCheckerBundle\Event;

use Oneup\Bundle\ContaoSecurityCheckerBundle\Audit\Audit;
use Symfony\Component\EventDispatcher\Event;

class SecurityAuditEvent extends Event
{
    const NAME = 'oneup_contao_security_checker.audit';

    protected $audit;

    public function __construct(Audit $audit)
    {
        $this->audit = $audit;
    }

    public function getAudit()
    {
        return $this->audit;
    }
}
