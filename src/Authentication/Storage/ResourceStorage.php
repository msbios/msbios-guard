<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Guard\Authentication\Storage;

use MSBios\Authentication\IdentityInterface;
use MSBios\Authentication\Storage\ResourceStorage as DefaultResourceStorage;
use MSBios\Db\TableManagerAwareInterface;
use MSBios\Db\TableManagerAwareTrait;
use MSBios\Db\TablePluginManager;
use MSBios\Guard\Resource\Table\RoleTableGateway;
use MSBios\Guard\Resource\Table\UserTableGateway;
use MSBios\Guard\Resource\UserInterface;
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
    public function __construct(
        TablePluginManager $tablePluginManager,
        $namespace = null,
        $member = null,
        SessionManager $manager = null
    ) {
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

            /** @var TablePluginManager $tableManager */
            $tableManager = $this
                ->getTableManager();

            /** @var UserTableGateway $userTableGateway */
            $userTableGateway = $tableManager->get(UserTableGateway::class);

            /** @var UserInterface|IdentityInterface $identity */
            if ($identity = $userTableGateway->fetchOneByUsername($identity)) {

                /** @var RoleTableGateway $roleTableGateway */
                $roleTableGateway = $tableManager->get(RoleTableGateway::class);

                $roles = [];

                foreach ($roleTableGateway->fetchByUser($identity) as $role) {
                    $roles[] = $role;
                }

                $identity->setRoles($roles);

                return $identity;
            }
        }

        return $identity;
    }
}
