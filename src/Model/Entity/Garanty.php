<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Garanty Entity
 *
 * @property int $id
 * @property string $libelle_garantie
 * @property string $description
 * @property int $montant
 * @property \Cake\I18n\FrozenDate $date_debut
 * @property \Cake\I18n\FrozenDate $date_fin
 * @property string $portee
 * @property string $numero
 * @property string|null $date_evalution
 * @property int|null $typologie_id
 * @property int|null $client_id
 * @property int|null $garant_id
 * @property string|null $statut
 *
 * @property \App\Model\Entity\Typology $typology
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Garant $garant
 */
class Garanty extends Entity
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
        'libelle_garantie' => true,
        'description' => true,
        'montant' => true,
        'date_debut' => true,
        'date_fin' => true,
        'portee' => true,
        'numero' => true,
        'date_evalution' => true,
        'typologie_id' => true,
        'client_id' => true,
        'garant_id' => true,
        'statut' => true,
        'typology' => true,
        'client' => true,
        'garant' => true,
        'classement' => true,
        'status_id' => true,
        'del' => true,
        'code_garanty' => true,
        'reference'=> true,
        'currency'=> true,
        'date_levee'=> true,
        'agence_id'=> true,
        'cree'=> true,
        'cree_id'=> true,
        'documents'=> true,
        'raison'=> true,










    ];
}
