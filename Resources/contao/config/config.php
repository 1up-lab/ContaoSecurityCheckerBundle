<?php

$GLOBALS['BE_MOD']['system']['security_checker'] = [
    'icon'          => 'bundles/oneupsecuritychecker/images/security-checker.png',
    'stylesheet'    => 'bundles/oneupsecuritychecker/css/security-checker.css',
    'callback'      => 'Oneup\Bundle\SecurityCheckerBundle\Module\Backend\SecurityCheckerModule',
];

if (System::getContainer()->getParameter('oneup_security_checker.enable_cron')) {
    $cronCycle = System::getContainer()->getParameter('oneup_security_checker.cron_cycle');

    $GLOBALS['TL_CRON'][$cronCycle][] = ['oneup.security_checker.audit_cron_job', 'run'];
}
