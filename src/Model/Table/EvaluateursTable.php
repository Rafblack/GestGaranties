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
 * Evaluateurs Model
 * @property \App\Model\Table\AuditLogsTable&\Cake\ORM\Association\HasMany $AuditLogs

 * @property \App\Model\Table\EvaluationsTable&\Cake\ORM\Association\HasMany $Evaluations
 *
 * @method \App\Model\Entity\Evaluateur newEmptyEntity()
 * @method \App\Model\Entity\Evaluateur newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Evaluateur[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Evaluateur get($primaryKey, $options = [])
 * @method \App\Model\Entity\Evaluateur findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Evaluateur patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Evaluateur[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Evaluateur|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Evaluateur saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Evaluateur[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Evaluateur[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Evaluateur[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Evaluateur[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class EvaluateursTable extends Table
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
        $auditLog->model_id = "E:".$entity->nom_prenom ."," .$entity->fonction_evaluateur;

        // Optionally, you can add more details like user ID, timestamp, etc.
        $auditLog->timestamp = \Cake\I18n\FrozenTime::now();

        // Save the audit log entry
        $auditLogsTable->save($auditLog);
        $entity->tel =   $entity->tel . (string)$entity->id; 
        $entity->email =     $entity->email . (string)$entity->id; 
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
            if($fieldName == 'del' && $newValue == true && $oldValue != $newValue) {

                $stop == true;
                break;
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
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('evaluateurs');
        $this->setDisplayField(['nom_prenom','email','nom_structure']);
        $this->setPrimaryKey('id');

        $this->hasMany('Evaluations', [
            'foreignKey' => 'evaluateur_id',
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
            ->scalar('nom_prenom')
            ->maxLength('nom_prenom', 45)
            ->requirePresence('nom_prenom', 'create')
            ->notEmptyString('nom_prenom');

        $validator
            ->scalar('tel')
            ->maxLength('tel', 15)
            ->requirePresence('tel', 'create')
            ->notEmptyString('tel')
            ->add('tel', 'unique', ['rule' => 'validateUnique', 'provider' => 'table',

            'message' => 'Telephone indisponible!'

           
            
        
        ]);

        $validator
            ->email('email')
            ->maxLength('email', 25)
            ->requirePresence('email', 'create')
            ->notEmptyString('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table',
            'message' => 'email indisponible!'

        ]);

        $validator
            ->scalar('fonction_evaluateur')
            ->maxLength('fonction_evaluateur', 25)
            ->requirePresence('fonction_evaluateur', 'create')
            ->notEmptyString('fonction_evaluateur');

        $validator
            ->scalar('nom_structure')
            ->maxLength('nom_structure', 15)
            ->requirePresence('nom_structure', 'create')
            ->notEmptyString('nom_structure');

        return $validator;
    }


    public function findActive(Query $query, array $options)
    {
        return $query->where([$this->aliasField('del') => false]);
    }
    public function findDeleted(Query $query, array $options)
    {
        return $query->where([$this->aliasField('del') => true]);
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
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email','message'=>'email indisponible']);
        $rules->add($rules->isUnique(['tel']), ['errorField' => 'tel','message'=> 'Numero indisponible']);

        return $rules;
    }
}
