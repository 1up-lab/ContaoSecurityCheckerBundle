<?php

namespace Oneup\Bundle\ContaoSecurityCheckerBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Oneup\Bundle\ContaoSecurityCheckerBundle\OneupContaoSecurityCheckerBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            new BundleConfig(TwigBundle::class),
            (new BundleConfig(OneupContaoSecurityCheckerBundle::class))->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $loader->load('@OneupContaoSecurityCheckerBundle/Resources/config/contao-manager/config.yml');
    }
}
