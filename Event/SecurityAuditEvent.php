<?php

namespace Oneup\Bundle\SecurityCheckerBundle\Event;

use Oneup\Bundle\SecurityCheckerBundle\Audit\Audit;
use Symfony\Component\EventDispatcher\Event;

class SecurityAuditEvent extends Event
{
    const NAME = 'oneup_security_checker.audit';

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
