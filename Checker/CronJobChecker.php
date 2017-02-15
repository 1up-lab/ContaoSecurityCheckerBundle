<?php

namespace Oneup\Bundle\ContaoSecurityCheckerBundle\Checker;

class CronJobChecker
{
    protected $securityChecker;
    protected $kernelRootDir;

    public function __construct(SecurityChecker $securityChecker, $kernelRootDir)
    {
        $this->securityChecker = $securityChecker;
        $this->kernelRootDir = $kernelRootDir;
    }

    public function run()
    {
        $this->securityChecker->addLockFile($this->kernelRootDir.'/../composer.lock');
        $this->securityChecker->run();
    }
}
