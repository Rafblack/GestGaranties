<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class EntityCheckComponent extends Component
{
    public function checkEntity($id)
    {
        $entitiesTable = TableRegistry::getTableLocator()->get('Entities'); // Adjust 'Entities' to your actual table name
        $entity = $entitiesTable->find()
            ->where(['id' => $id, 'del' => 0])
            ->first();

        return $entity;
    }
}