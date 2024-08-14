<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GarantsFixture
 */
class GarantsFixture extends TestFixture
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
                'code_garant' => 'Lorem ip',
                'code_rct' => 'Lorem ip',
                'intitule_garant' => 'Lorem ipsum dolor sit a',
            ],
        ];
        parent::init();
    }
}
