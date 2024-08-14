<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GarantiesFixture
 */
class GarantiesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'libelle_garantie' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'montant' => 1,
                'date_debut' => '2023-06-13',
                'date_fin' => '2023-06-13',
                'portee' => 'Lorem ip',
                'numero' => 'Lor',
                'date_evalution' => 'Lorem ipsum dolor sit amet',
                'typologie_id' => 1,
                'client_id' => 1,
                'garant_id' => 1,
                'statut' => 'L',
            ],
        ];
        parent::init();
    }
}
