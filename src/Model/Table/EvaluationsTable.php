<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Evaluations Model
 * @property \App\Model\Table\AuditLogsTable&\Cake\ORM\Association\HasMany $AuditLogs

 * @property \App\Model\Table\EvaluateursTable&\Cake\ORM\Association\BelongsTo $Evaluateurs
 * @property \App\Model\Table\GarantiesTable&\Cake\ORM\Association\BelongsTo $Garanties
 *
 * @method \App\Model\Entity\Evaluation newEmptyEntity()
 * @method \App\Model\Entity\Evaluation newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Evaluation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Evaluation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Evaluation findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Evaluation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Evaluation[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Evaluation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Evaluation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class EvaluationsTable extends Table
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
        $auditLog->model_id = $entity->date_fin;

        // Optionally, you can add more details like user ID, timestamp, etc.
        $auditLog->timestamp = \Cake\I18n\FrozenTime::now();

        // Save the audit log entry
        $auditLogsTable->save($auditLog);

        // Prevent the physical deletion from proceeding
        return false;
    }
 
  
    public function delete(EntityInterface $entity, $options = []): bool
    {
        $event = new Event('Model.beforeDelete', $this, [
            'entity' => $entity,
            'options' => $options
        ]);
        if (!$this->beforeDelete($event,$entity, $options)) {
        }
         
        // Set the 'deleted' field to true
        $entity->del = true;
        $entity->del_at = date('Y-m-d H:i:s');

        

        // Save the entity to persist the soft deletion
         parent::save($entity, $options);
         return true;
    }
 
    public function findActive(Query $query, array $options)
    {
        return $query->where([$this->aliasField('del') => false]);
    }
    public function findDeleted(Query $query, array $options)
    {
        return $query->where([$this->aliasField('del') => true]);
    }

    
    public function beforeSave(EventInterface $event, EntityInterface $entity, ArrayObject $options) {
        if (session_status() == PHP_SESSION_NONE) {   // a check so it doesnt run and crash before the session starts
            return;
        }
        else if( isset($_SESSION['user_id']) == false){
            return;
        }
    $stop = false; // determine whether to log;
        
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
            if($fieldName == 'del' && $newValue == 1 && $oldValue != $newValue) { // were deleting so we just need to show that
                $stop == true;
                                   break;

              }
              elseif($fieldName == 'del' && $newValue == 0 && $oldValue != $newValue) {// makie it clear we are restoring in this case 
      $action = "restore";
              }
              
              
   
               // Log the change
               $changes[] = [
                   'field' => $fieldName,
                   'old_value' => $oldValue,
                   'new_value' => $newValue,
               ];
           }
                 if($stop == false){
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
     * 
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('evaluations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Evaluateurs', [
            'foreignKey' => 'evaluateur_id',
        ]);
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
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('frequence')
            ->maxLength('frequence', 10)
            ->requirePresence('frequence', 'create')
            ->notEmptyString('frequence');

        $validator
            ->integer('valeur_garantie')
            ->greaterThanOrEqual('valeur_garantie', 0 , __('Valeur invalid'))
            ->lessThanOrEqual('valeur_garantie', 2147483647 , __('Valeur invalid'));

           
        $validator
            ->date('date_fin')
            ->requirePresence('date_fin', 'create')
            ->notEmptyDate('date_fin');

            $validator
            ->requirePresence('date_debut', 'create')
            ->notEmptyDate('date_debut');

        $validator
            ->integer('evaluateur_id')
            ->requirePresence('evaluateur_id', 'create');


        $validator
            ->integer('garantie_id')
            ->allowEmptyString('garantie_id');

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
        $rules->add($rules->existsIn('evaluateur_id', 'Evaluateurs'), ['errorField' => 'evaluateur_id']);
        $rules->add($rules->existsIn('garantie_id', 'Garanties'), ['errorField' => 'garantie_id']);

        return $rules;
    }
}
