<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Evaluation Entity
 *
 * @property int $id
 * @property string $frequence
 * @property int $valeur_garantie
 * @property \Cake\I18n\FrozenDate $date_fin
 * @property int|null $evaluateur_id
 * @property int|null $garantie_id
 *
 * @property \App\Model\Entity\Evaluateur $evaluateur
 * @property \App\Model\Entity\Garanty $garanty
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
        'frequence' => true,
        'valeur_garantie' => true,
        'date_fin' => true,
        'date_debut' => true,

        'evaluateur_id' => true,
        'garantie_id' => true,
        'evaluateur' => true,
        'garanty' => true,
        'currency' => true,
        'created_by' => true,

    ];
}
