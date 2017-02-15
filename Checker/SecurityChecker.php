<?php

namespace Oneup\Bundle\ContaoSecurityCheckerBundle\Checker;

use Oneup\Bundle\ContaoSecurityCheckerBundle\Audit\Audit;
use Oneup\Bundle\ContaoSecurityCheckerBundle\Event\SecurityAuditEvent;
use Doctrine\Common\Cache\CacheProvider;
use SensioLabs\Security\SecurityChecker as BaseSecurityChecker;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SecurityChecker
{
    protected $eventDispatcher;
    protected $lockFiles;
    protected $securityChecker;
    protected $cache;

    public function __construct(EventDispatcherInterface $eventDispatcher, BaseSecurityChecker $securityChecker, CacheProvider $cache)
    {
        $this->lockFiles = [];
        $this->eventDispatcher = $eventDispatcher;
        $this->securityChecker = $securityChecker;
        $this->cache = $cache;
    }

    public function run()
    {
        $audit = new Audit();

        foreach ($this->lockFiles as $lockFile) {
            $alerts = $this->securityChecker->check($lockFile);
            $audit->addResponse($alerts);
        }

        $this->cacheAudit($audit);

        $this->eventDispatcher->dispatch(SecurityAuditEvent::NAME, new SecurityAuditEvent($audit));

        return $audit;
    }

    public function hasRunOnce()
    {
        return $this->cache->fetch('oneup.contao_security_checker.audit');
    }

    public function cacheAudit(Audit $audit)
    {
        $strAudit = json_encode($audit->getVulnerabilities());
        $this->cache->save('oneup.contao_security_checker.audit', $strAudit);
        $this->cache->save('oneup.contao_security_checker.updated', new \DateTime());
    }

    public function getCachedAudit()
    {
        if (!$this->hasRunOnce()) {
            throw new \BadMethodCallException('No audit cache found.');
        }

        $strAudit = $this->cache->fetch('oneup.contao_security_checker.audit');
        $vulnerabilites = json_decode($strAudit, true);

        return new Audit($vulnerabilites);
    }

    /**
     * @return \DateTime
     */
    public function getCacheLastModified()
    {
        if (!$this->hasRunOnce()) {
            throw new \BadMethodCallException('No audit cache found.');
        }

        return $this->cache->fetch('oneup.contao_security_checker.updated');
    }

    public function addLockFile($path)
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('The lock file "%s" does not exist.', $path));
        }

        $this->lockFiles[] = $path;
    }

    public function getLockFiles()
    {
        return $this->lockFiles;
    }
}
