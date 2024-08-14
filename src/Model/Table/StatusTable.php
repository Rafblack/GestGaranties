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
 * @method \App\Model\Entity\Typology newEmptyEntity()
 * @method \App\Model\Entity\Typology newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Typology[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Typology get($primaryKey, $options = [])
 * @method \App\Model\Entity\Typology findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Typology patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Typology[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Typology|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Typology saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Typology[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Typology[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Typology[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Typology[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class StatusTable extends Table
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

        $this->setTable('status');
        $this->setDisplayField('label');
        $this->setPrimaryKey('id');
        $this->hasMany('Garanties', [
            'foreignKey' => 'status_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('label')
            ->maxLength('label', 45)
            ->allowEmptyString('label')
            ->add('label', 'unique', ['rule' => 'validateUnique', 'provider' => 'table',
            'message' => 'label taken !'

        
        ]);

        $validator
            ->scalar('created_by')
            ->maxLength('created_by', 10)
            ->allowEmptyString('created_by');

        $validator
            ->scalar('modified_by')
            ->maxLength('modified_by', 10)
            ->allowEmptyString('modified_by');

        return $validator;
    }

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
