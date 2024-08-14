<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EvaluateursFixture
 */
class EvaluateursFixture extends TestFixture
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
                'nom_prenom' => 'Lorem ipsum dolor sit amet',
                'tel' => 'Lorem ipsum d',
                'email' => 'Lorem ipsum dolor sit a',
                'fonction_evaluateur' => 'Lorem ipsum dolor sit a',
                'nom_structure' => 'Lorem ipsum d',
            ],
        ];
        parent::init();
    }
}
