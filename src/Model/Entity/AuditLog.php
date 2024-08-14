<?php 


namespace App\Model\Entity;

use Cake\ORM\Entity;

class AuditLog extends Entity {
    // Define accessible fields
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
