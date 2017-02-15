<?php

namespace Oneup\Bundle\ContaoSecurityCheckerBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Oneup\Bundle\ContaoSecurityCheckerBundle\OneupContaoSecurityCheckerBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            (new BundleConfig(OneupContaoSecurityCheckerBundle::class))->setLoadAfter([ContaoCoreBundle::class])
        ];
    }
}
