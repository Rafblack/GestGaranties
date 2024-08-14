<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EvaluateursTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EvaluateursTable Test Case
 */
class EvaluateursTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EvaluateursTable
     */
    protected $Evaluateurs;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Evaluateurs',
        'app.Evaluations',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Evaluateurs') ? [] : ['className' => EvaluateursTable::class];
        $this->Evaluateurs = $this->getTableLocator()->get('Evaluateurs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Evaluateurs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\EvaluateursTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\EvaluateursTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
