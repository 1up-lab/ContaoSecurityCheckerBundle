<?php

namespace Oneup\Bundle\SecurityCheckerBundle\Audit;

class Audit
{
    protected $vulnerabilities;

    public function __construct(array $vulnerabilities = [])
    {
        $this->vulnerabilities = $vulnerabilities;
    }

    public function addResponse($response)
    {
        $vulnerabilities = [];
        foreach ($response as $key => $vulnerability) {
            $vulnerabilities[] = [
                'name' => $key,
                'version' => $vulnerability['version'],
                'advisories' => array_values($vulnerability['advisories']),
            ];
        }

        $this->vulnerabilities = $vulnerabilities;
    }

    public function isVulnerable()
    {
        return count($this->vulnerabilities) > 0;
    }

    public function getVulnerabilities()
    {
        return $this->vulnerabilities;
    }
}
