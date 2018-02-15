Contao Security Checker Bundle
==============================

This extension provides a way to automatically or manually check your installed vendor extensions and the Contao core against the open vulnerability database at [FriendsOfPHP/security-advisories](https://github.com/FriendsOfPHP/security-advisories).

[![Author](http://img.shields.io/badge/author-@1upgmbh-blue.svg?style=flat-square)](https://twitter.com/1upgmbh)
[![Software License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](http://img.shields.io/packagist/dt/oneup/contao-security-checker-bundle.svg?style=flat-square)](https://packagist.org/packages/oneup/contao-security-checker-bundle)

-- 

Features included:
* Perform the check regularly.
* Get an E-Mail if the audit failed in any way. (Or always get an email if a check was performed. Your choice.)
* Start the check manually.
* Suppress notifications for manually started checks.

--

![Screenshot](https://cloud.githubusercontent.com/assets/754921/15356457/11e74f6e-1cf9-11e6-9d63-a13de0ef31b3.png)

**Note**: A clean check does not imply that there are no security problems present, it just means that the test against the underlying database reveiled nothing.

Documentation
-------------

## Installation

Perform the following steps to install and use the basic functionality of the OneupUploaderBundle:

* Download the ContaoSecurityCheckerBundle using Composer
* Enable the bundle
* Configure the bundle

### Step 1: Download the ContaoSecurityCheckerBundle

Add OneupUploaderBundle to your composer.json using the following construct:

    $ composer require oneup/contao-security-checker-bundle "^0.3"

Composer will install the bundle to your project's ``vendor/oneup/contao-security-checker-bundle`` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Oneup\Bundle\ContaoSecurityCheckerBundle\OneupContaoSecurityCheckerBundle(),
    ];
}
```

Enable the bundles api route:

``` yml

# app/config/routing.yml

oneup_contao_security_checker:
    prefix: /security-advisories
    resource: "@OneupContaoSecurityCheckerBundle/Resources/config/routing.yml"
# ...
```

### Step 3: Configure the bundle

Add this little configuration to your `app/config/config.yml` and adjust it to your needs.

```yaml
# app/config/config.yml

# OneupContaoSecurityChecker configuration
oneup_contao_security_checker:
    enable_notifications: true
    suppress_manual_audits: false
    notify_only_failed_audits: true
    notification_email: your@email.here
    cron_cycle: daily
    enable_cron: true
    enable_api: false
    api_key: ~
```

Upgrade Notes
-------------
* Version **0.4.0** Added an API endpoint, per default disabled (see [#7](https://github.com/1up-lab/ContaoSecurityCheckerBundle/pull/7))
* Version **0.3.0** Added Contao Manager Plugin
* Version **0.2.0** Renamed Bundle (update/check your `app/config/config.yml`)
* Version **0.1.0** Initial release

License
-------

This bundle is under the MIT license. See the complete license in the bundle.


Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/1up-lab/contao-security-checker-bundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Contao Standard Edition](https://github.com/contao/standard-edition)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.
