<?php
namespace FutureActivities\PayPal\Api;
 
interface CredentialsInterface
{
    /**
     * Returns the sandbox and live client IDs
     * 
     * @api
     * @return FutureActivities\PayPal\Api\Data\CredentialsResultInterface
     */
    public function client();
}