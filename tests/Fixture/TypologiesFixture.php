<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TypologiesFixture
 */
class TypologiesFixture extends TestFixture
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
                'label' => 'Lorem ipsum dolor sit amet',
                'created_by' => 'Lorem ip',
                'modified_by' => 'Lorem ip',
            ],
        ];
        parent::init();
    }
}
