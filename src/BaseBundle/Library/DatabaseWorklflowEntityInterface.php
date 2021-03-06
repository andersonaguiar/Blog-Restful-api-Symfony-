<?php

namespace BaseBundle\Library;

/**
 * Interface DatabaseWorkflowEntityInterface
 * @package BaseBundle\Library
 */
interface DatabaseWorkflowEntityInterface{

    /**
     * @return string
     */
    public function getLiteralType();

    /**
     * @return string
     */
    public function getLiteralName();

    /**
     * @return int
     */
    public function getIdentifier();
    
}