<?php

namespace Oneup\Bundle\SecurityCheckerBundle\Module\Backend;

use Contao\BackendModule;
use Contao\BackendTemplate;
use Contao\Input;
use Contao\Config;
use Symfony\Component\Translation\TranslatorInterface;
use Oneup\Bundle\SecurityCheckerBundle\Audit\Audit;
use Oneup\Bundle\SecurityCheckerBundle\Checker\SecurityChecker;

class SecurityCheckerModule extends BackendModule
{
    protected $auditCache;
    protected $container;

    /** @var TranslatorInterface $translator */
    protected $translator;
    protected $requestToken;

    protected $strTemplate = 'be_maintenance';

    public function __construct()
    {
        $this->container = $this->getContainer();
        $this->translator = $this->container->get('translator');
        $tokenManager = $this->container->get('security.csrf.token_manager');
        $this->requestToken = $tokenManager->getToken($this->container->getParameter('contao.csrf_token_name'))->getValue();

        parent::__construct();
    }

    public function compile()
    {
        $this->Template->content = '';
        $this->Template->href = $this->getReferer(true);
        $this->Template->title = specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
        $this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
        $this->Template->content = $this->run();
    }

    public function run()
    {
        $objTemplate = new BackendTemplate('be_security_check');

        /** @var SecurityChecker $securityChecker */
        $securityChecker = $this->container->get('oneup_security_checker.security_checker');
        $securityChecker->addLockFile($this->container->get('kernel')->getRootDir().'/../composer.lock');

        // Init Audit.
        /** @var Audit $audit */
        $audit = null;

        if ($securityChecker->hasRunOnce()) {
            $audit = $securityChecker->getCachedAudit();
        }

        if (Input::post('run') != '') {
            // Perform an actual check against the web service and cache the result afterwards
            $audit = $securityChecker->run();
        }

        if (null !== $audit) {
            $objTemplate->isVulnerable = $audit->isVulnerable();
            $objTemplate->vulnerabilities = $audit->getVulnerabilities();
        }

        $objTemplate->hasRunOnce = $securityChecker->hasRunOnce();

        if ($securityChecker->hasRunOnce()) {
            $objTemplate->lastChecked = $securityChecker->getCacheLastModified()->format(Config::get('datimFormat'));
        }

        // Language strings
        $objTemplate->headline = $this->translator->trans('headline');
        $objTemplate->disclaimer = $this->translator->trans('disclaimer');
        $objTemplate->runSecurityCheck = $this->translator->trans('run_security_check');
        $objTemplate->auditOk = $this->translator->trans('audit.ok');
        $objTemplate->noAuditFound = $this->translator->trans('audit.not_found');
        $objTemplate->auditFailed = $this->translator->trans('audit.failed');
        $objTemplate->lastCheckedLabel = $this->translator->trans('last_checked');
        $objTemplate->requestToken = $this->requestToken;

        return $objTemplate->parse();
    }
}
