<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'email' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'created' => '2023-06-18 15:26:26',
                'modified' => '2023-06-18 15:26:26',
                'nom_prenom' => 'Lorem ipsum dolor sit amet',
                'fonction' => 'Lorem ipsum dolor sit amet',
                'matricule' => 'Lor',
            ],
        ];
        parent::init();
    }
}
