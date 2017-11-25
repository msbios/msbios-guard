<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Authentication\Storage;

use MSBios\Authentication\Storage\ResourceStorage as DefaultResourceStorage;
use MSBios\Db\TableManagerAwareInterface;
use MSBios\Db\TableManagerAwareTrait;

/**
 * Class ResourceStorage
 * @package MSBios\Guard\Authentication\Storage
 */
class ResourceStorage extends DefaultResourceStorage implements TableManagerAwareInterface
{
    use TableManagerAwareTrait;

    /**
     * @return string
     */
    public function read()
    {
        /** @var string $identity */
        $identity = parent::read();

        if (!empty($identity) && is_string($identity)) {
            $table = $this->getTableManager()->get(get_class($this));
            return $table->fetchOneByUsername($identity);
        }

        return $identity;

    }


}