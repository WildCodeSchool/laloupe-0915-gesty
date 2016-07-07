<?php
/**
 * copied from http://symfony.com/doc/current/cookbook/security/target_path.html
 */
namespace WCS\GestyBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\ExceptionListener as BaseExceptionListener;

class ExceptionListener extends BaseExceptionListener
{
    protected function setTargetPath(Request $request)
    {
        // Do not save target path for XHR requests
        // You can add any more logic here you want
        // Note that non-GET requests are already ignored
        if ($request->isXmlHttpRequest()) {
            return;
        }

        parent::setTargetPath($request);
    }
}
