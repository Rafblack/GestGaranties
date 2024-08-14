<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Garant Entity
 *
 * @property int $id
 * @property string $code_garant
 * @property string $code_rct
 * @property string $intitule_garant
 *
 * @property \App\Model\Entity\Garanty[] $garanties
 */
class Garant extends Entity
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
        'code_garant' => true,
        'code_rct' => true,
        'intitule_garant' => true,
        'garanties' => true,
    ];
}
