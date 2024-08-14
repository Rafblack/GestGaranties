<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Typologies Model
 * @property \App\Model\Table\AuditLogsTable&\Cake\ORM\Association\HasMany $AuditLogs

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
class TypologiesTable extends Table
{
    public function beforeDelete(EventInterface $event, $entity, $options)
    {
       
        // Soft delete action: Set 'del' flag to 1
        $entity->del = 1;   // soft deletion

        // Log the soft deletion event
        $auditLogsTable = $this->AuditLogs;

        $auditLog = $auditLogsTable->newEmptyEntity();
        $auditLog->user_id = $_SESSION['user_id']; // Assuming $_SESSION['user_id'] contains the user ID

        $auditLog->action = 'delete';
        $auditLog->model = $this->getAlias(); // 'Agences' in this case
        $auditLog->model_id = "T:".$entity->label. ",CRBY:". $entity->created_by;

        // Optionally, you can add more details like user ID, timestamp, etc.
        $auditLog->timestamp = \Cake\I18n\FrozenTime::now();

        // Save the audit log entry
        $auditLogsTable->save($auditLog);

        // Prevent the physical deletion from proceeding
        return true;
    }
 
  
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options) {
        
        if (session_status() == PHP_SESSION_NONE) {   // a check so it doesnt run and crash before the session starts
            return;
        }
        else if( isset($_SESSION['user_id']) == false){
            return;
        }
        $connection = ConnectionManager::get($this->defaultConnectionName());
    
        // Determine the next model_id based on the maximum ID in the table
    
        $query = "SELECT MAX(id) AS max_id FROM $this->_table";
        $result = $connection->execute($query)->fetch('assoc');
        $maxId = $result['max_id'] ?? 0;
        $nextModelId = $maxId + 1;
        if($entity->isNew()){
            $entity->id = $nextModelId;

            $action = 'create';
            




        }
        else{
            $action = 'update';
        }


         // Prepare to log changes
         $changes = [];

         // For new entities, $entity->getDirty() will give all fields as new values
         foreach ($entity->getDirty() as $fieldId => $fieldName) {
             $oldValue = $entity->getOriginal($fieldName);
             $newValue = $entity->get($fieldName);
             
             // Skip fields that are not changed
             if($action == 'update'){
             if ($oldValue === $newValue) {
                 continue;
             }
            }
            
 
             // Log the change
             $changes[] = [
                 'field' => $fieldName,
                 'old_value' => $oldValue,
                 'new_value' => $newValue,
             ];
         }
 
         // Save changes to audit log
         foreach ($changes as $change) {
             $auditLog = $this->AuditLogs->newEmptyEntity();
             $auditLog->user_id = $_SESSION['user_id']; // Assuming $_SESSION['user_id'] contains the user ID
             $auditLog->action = $action;
             $auditLog->model = $this->getAlias(); // Get the model name
             $auditLog->model_id = $entity->isNew() ? $nextModelId : $entity->id; // Use null for new entities
             $auditLog->field = $change['field'];
             $auditLog->old_value = $change['old_value'];
             $auditLog->new_value = $change['new_value'];
             
             $this->AuditLogs->save($auditLog);
         }
 
 
 
 
 
 
 
 








        if ($action == 'create') {
            // New record, set created_by if it exists in the entity
       
                $entity->set('created_by', $_SESSION['user_id']);
           
        
        
        }
       
       
       
        
        else {
            $action = 'update';
            // Existing record, set modified_by and modified fields if they exist
                $entity->set('modified_by', $_SESSION['user_id']);
            
                $entity->set('modified', date('Y-m-d H:i:s'));
            
        }

       











        return true;
    }
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('typologies');
        $this->setDisplayField('label');
        $this->setPrimaryKey('id');

        $this->hasMany('Garanties', [
            'foreignKey' => 'typologie_id',
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
    public function validationDefault(Validator $validator): Validator
    {
        
        $validator
            ->maxLength('label', 35)
            ->requirePresence('label','create')
            ->add('label', 'unique', ['rule' => 'validateUnique', 'provider' => 'table',
            'message' => 'label taken !'

        ]);


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
