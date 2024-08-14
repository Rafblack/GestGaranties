<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Client Entity
 *
 * @property int $id
 * @property string $code
 * @property string $label
 * @property string $code_rct
 * @property string|null $segment
 * @property int|null $agence_id
 *
 * @property \App\Model\Entity\Agence $agence
 * @property \App\Model\Entity\Garanty[] $garanties
 */
class Client extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'code' => true,
        'label' => true,
        'code_rct' => true,
        'segment' => true,
        'agence_id' => true,
        'agence' => true,
        'garanties' => true,
        'deleted' => true,
        'deleted_at' => true,


    ];
}
