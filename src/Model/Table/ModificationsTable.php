<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Typologies Model
 *
 * @method \App\Model\Entity\Modifications newEmptyEntity()
 * @method \App\Model\Entity\Modifications newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Modifications[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Modifications get($primaryKey, $options = [])
 * @method \App\Model\Entity\Modifications findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Modifications patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Modifications[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Modifications|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Modifications saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Modifications[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Modifications[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Modifications[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Modifications[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ModificationsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('modifications');
        $this->setPrimaryKey('id');

        $this->belongsTo('Garanties', [
            'foreignKey' => 'garantie_id',
        ]);
        $this->hasMany('AuditLogs', [
            'foreignKey' => 'model_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
   

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['label'], ['allowMultipleNulls' => true]), ['errorField' => 'label']);

        return $rules;
    }
}
