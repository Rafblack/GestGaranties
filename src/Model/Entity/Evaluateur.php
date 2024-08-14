<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Evaluateur Entity
 *
 * @property int $id
 * @property string $nom_prenom
 * @property string $tel
 * @property string $email
 * @property string $fonction_evaluateur
 * @property string $nom_structure
 *
 * @property \App\Model\Entity\Evaluation[] $evaluations
 */
class Evaluateur extends Entity
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
        'nom_prenom' => true,
        'tel' => true,
        'email' => true,
        'fonction_evaluateur' => true,
        'nom_structure' => true,
        'evaluations' => true,
    ];
}
