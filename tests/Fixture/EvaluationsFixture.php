<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EvaluationsFixture
 */
class EvaluationsFixture extends TestFixture
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
                'frequence' => 'Lorem ip',
                'valeur_garantie' => 1,
                'date_fin' => '2023-06-07',
                'evaluateur_id' => 1,
                'garantie_id' => 1,
            ],
        ];
        parent::init();
    }
}
