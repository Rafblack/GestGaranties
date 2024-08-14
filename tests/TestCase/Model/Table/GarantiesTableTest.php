<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GarantiesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GarantiesTable Test Case
 */
class GarantiesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\GarantiesTable
     */
    protected $Garanties;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Garanties',
        'app.Typologies',
        'app.Clients',
        'app.Garants',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Garanties') ? [] : ['className' => GarantiesTable::class];
        $this->Garanties = $this->getTableLocator()->get('Garanties', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Garanties);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\GarantiesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\GarantiesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
