<?php
namespace FutureActivities\PayPal\Api\Data;

/**
 * @api
 */
interface CredentialsResultInterface
{
    /**
     * Set the result type
     * 
     * @param string $type
     * @return string
     */
    public function setSandbox($id);
    
    /**
     * Get the result type
     * 
     * @return string
     */
    public function getSandbox();

    /**
     * Set the data
     * 
     * @param string $id
     * @return null
     */
    public function setProduction($id);
        
    /**
     * Get the result ID
     * 
     * @return string
     */
    public function getProduction();
}
