<?php
namespace FutureActivities\PayPal\Model;

use FutureActivities\PayPal\Api\CredentialsInterface;
 
class Credentials implements CredentialsInterface
{
    /**
     * Returns the sandbox and live client IDs
     * 
     * @api
     * @return string
     */
    public function client() {
        return 'hello';
    }
}