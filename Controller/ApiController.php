<?php

namespace Oneup\Bundle\ContaoSecurityCheckerBundle\Controller;

use Oneup\Bundle\ContaoSecurityCheckerBundle\DependencyInjection\Configuration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiController extends Controller
{
    public function apiAction(Request $request)
    {
        $apiEnabled = $this->getParameter('oneup_contao_security_checker.enable_api');
        $apiToken = $this->getParameter('oneup_contao_security_checker.api_key');
        $composerFile = sprintf('%s/../composer.lock', $this->getParameter('kernel.root_dir'));

        if (!$apiEnabled) {
            throw new NotFoundHttpException('API is not enabled');
        }

        if ($request->headers->get('authorization-token') === $apiToken) {
            if(file_exists($composerFile)) {
                $response = ['locks' => []];
                $response['locks'][] = json_encode(file_get_contents($composerFile));

                return new JsonResponse($response);
            }

            throw new NotFoundHttpException('No composer.lock file found on server.');
        }

        throw new AccessDeniedHttpException('API token mismatch.');
    }
}
