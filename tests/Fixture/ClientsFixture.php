<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ClientsFixture
 */
class ClientsFixture extends TestFixture
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
                'code' => 'Lorem ipsum d',
                'label' => 'Lorem ipsum dolor sit amet',
                'code_rct' => 'Lor',
                'segment' => 'Lorem ip',
                'agence_id' => 1,
            ],
        ];
        parent::init();
    }
}
