<?php

$GLOBALS['BE_MOD']['system']['security_checker'] = [
    'stylesheet'    => 'bundles/oneupcontaosecuritychecker/css/security-checker.css',
    'callback'      => Oneup\Bundle\ContaoSecurityCheckerBundle\Module\Backend\SecurityCheckerModule::class,
];

if (System::getContainer()->getParameter('oneup_contao_security_checker.enable_cron')) {
    $cronCycle = System::getContainer()->getParameter('oneup_contao_security_checker.cron_cycle');

    $GLOBALS['TL_CRON'][$cronCycle][] = ['oneup_contao_security_checker.audit_cron_job', 'run'];
}
