<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Authentication\Storage;

use MSBios\Authentication\Storage\ResourceStorage as DefaultResourceStorage;
use MSBios\Db\TableManagerAwareInterface;
use MSBios\Db\TableManagerAwareTrait;
use MSBios\Db\TablePluginManager;
use MSBios\Guard\Resource\Table\UserTableGateway;
use Zend\Session\ManagerInterface as SessionManager;

/**
 * Class ResourceStorage
 * @package MSBios\Guard\Authentication\Storage
 */
class ResourceStorage extends DefaultResourceStorage implements TableManagerAwareInterface
{
    use TableManagerAwareTrait;

    /**
     * ResourceStorage constructor.
     * @param TablePluginManager $tablePluginManager
     * @param null $namespace
     * @param null $member
     * @param SessionManager|null $manager
     */
    public function __construct(TablePluginManager $tablePluginManager, $namespace = null, $member = null, SessionManager $manager = null)
    {
        parent::__construct($namespace, $member, $manager);
            $this->setTableManager($tablePluginManager);
    }

    /**
     * @return string
     */
    public function read()
    {
        /** @var string $identity */
        $identity = parent::read();

        if (! empty($identity) && is_string($identity)) {
            $table = $this
                ->getTableManager()
                ->get(UserTableGateway::class);

            return $table->fetchOneByUsername($identity);
        }

        return $identity;
    }
}
