<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Evaluation Entity
 *
 * @property int $id
 * @property string $descrip
 *  @property string $user

 * @property int $garantie_id
 * @property \Cake\I18n\FrozenDate $modification_date
 * @property int|null $garantie_id
 *
 * @property \App\Model\Entity\Modification $modification
 */
class Evaluation extends Entity
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
        'garantie_id' => true, // Allow all fields to be mass assignable.
        'user'=> true,
        "user_id"=>true,
        "modification_date"=>true,
        "descrip"=>true



    ];
}
