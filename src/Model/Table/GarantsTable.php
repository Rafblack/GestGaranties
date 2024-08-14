<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Garants Model
 * @property \App\Model\Table\AuditLogsTable&\Cake\ORM\Association\HasMany $AuditLogs

 * @property \App\Model\Table\GarantiesTable&\Cake\ORM\Association\HasMany $Garanties
 *
 * @method \App\Model\Entity\Garant newEmptyEntity()
 * @method \App\Model\Entity\Garant newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Garant[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Garant get($primaryKey, $options = [])
 * @method \App\Model\Entity\Garant findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Garant patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Garant[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Garant|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Garant saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Garant[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Garant[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Garant[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Garant[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class GarantsTable extends Table
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
        $auditLog->model_id = "G:".$entity->intitule_garant . ",Code:". $entity->code_garant;

        // Optionally, you can add more details like user ID, timestamp, etc.
        $auditLog->timestamp = \Cake\I18n\FrozenTime::now();

        // Save the audit log entry
        $auditLogsTable->save($auditLog);

        $entity->code_garant =  $entity->code_garant.(string)$entity->id . "DEL"; 

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
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('garants');
        $this->setDisplayField(['code_garant','intitule_garant']);
        $this->setPrimaryKey('id');

        $this->hasMany('Garanties', [
            'foreignKey' => 'garant_id',
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
            ->scalar('code_garant')
            ->maxLength('code_garant', 9,'La longueur maximale autorisée pour le code garant est de 9 caractères.')
            ->requirePresence('code_garant', 'create', 'le code_garant Ne peut pas être laissé vide')
            ->notEmptyString('code_garant', 'le code_garant Ne peut pas être laissé vide')
            ->add('code_garant', 'unique', ['rule' => 'validateUnique', 'provider' => 'table',

            'message' => 'code_garant indisponible !'

        
        
        ])
        ->add('code_garant', 'custom', [
            'rule' => function ($value, $context) {
  
                return ctype_alnum($value); // make sure it only consists of letters and numbers 
            },
            'message' => 'Le code garant ne doit contenir que des lettres et des chiffres.',
        ]);

        $validator
            ->scalar('code_rct')
            ->maxLength('code_rct', 9, 'La longueur maximale autorisée pour le code_rct est de 9 caractères.')
            ->requirePresence('code_rct', 'create')
            ->notEmptyString('code_rct')
            ->add('code_rct', 'unique', ['rule' => 'validateUnique', 'provider' => 'table',

            'message' => 'code_rct(garant) indisponible !'

        
                   
        
    ]);

        $validator
            ->scalar('intitule_garant')
            ->maxLength('intitule_garant', 45)
            ->requirePresence('intitule_garant', 'create')
            ->notEmptyString('intitule_garant');

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
        $rules->add($rules->isUnique(['code_garant']), ['errorField' => 'code_garant']);
        $rules->add($rules->isUnique(['code_rct']), ['errorField' => 'code_rct']);

        return $rules;
    }
}
