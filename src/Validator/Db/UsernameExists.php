<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Guard\Validator\Db;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Validator\Db\NoRecordExists;
use Zend\Validator\Db\RecordExists;

/**
 * Class UsernameExists
 * @package MSBios\Guard\Validator\Db
 */
class UsernameExists extends RecordExists
{
    /**
     * UsernameExists constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {

    }
}