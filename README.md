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
