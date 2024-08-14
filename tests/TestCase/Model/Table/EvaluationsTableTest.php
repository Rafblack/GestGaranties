<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EvaluationsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EvaluationsTable Test Case
 */
class EvaluationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EvaluationsTable
     */
    protected $Evaluations;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Evaluations',
        'app.Evaluateurs',
        'app.Garanties',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Evaluations') ? [] : ['className' => EvaluationsTable::class];
        $this->Evaluations = $this->getTableLocator()->get('Evaluations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Evaluations);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\EvaluationsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\EvaluationsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
