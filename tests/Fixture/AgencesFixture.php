<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AgencesFixture
 */
class AgencesFixture extends TestFixture
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
                'code_agence' => 'Lor',
                'label' => 'Lorem ipsum d',
            ],
        ];
        parent::init();
    }
}
