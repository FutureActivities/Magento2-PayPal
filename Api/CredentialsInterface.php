<?php
namespace FutureActivities\PayPal\Api;
 
interface CredentialsInterface
{
    /**
     * Returns the sandbox and live client IDs
     *
     * @api
     * @return string
     */
    public function client();
}