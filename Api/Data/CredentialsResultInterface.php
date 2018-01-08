<?php
namespace FutureActivities\PayPal\Api\Data;

/**
 * @api
 */
interface CredentialsResultInterface
{
    /**
     * Set the data
     * 
     * @param boolean $value
     * @return null
     */
    public function setSandboxEnabled($value);
        
    /**
     * Get the result 
     * 
     * @return boolean
     */
    public function getSandboxEnabled();
    
    /**
     * Set the data
     * 
     * @param string $id
     * @return string
     */
    public function setSandbox($id);
    
    /**
     * Get the result
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
