<?php
namespace App\Repository;

use Framework\Database\RepositoryInterface;
use Framework\Database\IdentityMapInterface;
use Framework\Database\QueryInterface;
use App\DataMapper\UserMapper;
use App\Model\User;

class UserRepository implements RepositoryInterface
{
    private UserMapper $mapper;
    private IdentityMapInterface $identityMap;

    public function __construct(UserMapper $mapper, IdentityMapInterface $identityMap)
    {
        $this->mapper      = $mapper;
        $this->identityMap = $identityMap;
    }

    /**
     * Zoek entiteiten op aan de hand van een QueryInterface.
     *
     * @param QueryInterface $query
     * @return array<User>  Array van gevonden User-objecten
     */
    public function find(QueryInterface $query): array
    {
        $results = [];

        // Voor nu implementeren we enkel 'where("id", …)'
        $conds = $query->getConditions();
        if (isset($conds['id'])) {
            $id = (int)$conds['id'];

            // Kijk eerst in de IdentityMap
            $cached = $this->identityMap->get(User::class, $id);
            if ($cached instanceof User) {
                return [$cached];
            }

            // Anders uit database via mapper
            $user = $this->mapper->findById($id);
            if ($user !== null) {
                $this->identityMap->set(User::class, $id, $user);
                $results[] = $user;
            }
        }

        return $results;
    }

    /**
     * Sla één User-object op (insert of update).
     *
     * @param object $object
     * @throws \InvalidArgumentException als het geen User is
     */
    public function save(object $object): void
    {
        if (! $object instanceof User) {
            throw new \InvalidArgumentException('UserRepository ontvangt geen User-object');
        }

        if ($object->getId() === null) {
            $this->mapper->insert($object);
        } else {
            $this->mapper->update($object);
        }

        // Update IdentityMap
        $this->identityMap->set(User::class, $object->getId(), $object);
    }

    public function get(int $id): object
    {
        // TODO: Implement get() method.
    }

    public function remove($object): void
    {
        // TODO: Implement remove() method.
    }

    public function findOne(QueryInterface $query): object
    {
        // TODO: Implement findOne() method.
    }
}
