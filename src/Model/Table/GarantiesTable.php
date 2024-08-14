<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Garanties Model
 * @property \App\Model\Table\AuditLogsTable&\Cake\ORM\Association\HasMany $AuditLogs

 * @property \App\Model\Table\TypologiesTable&\Cake\ORM\Association\BelongsTo $Typologies
 * @property \App\Model\Table\ClientsTable&\Cake\ORM\Association\BelongsTo $Clients
 * @property \App\Model\Table\GarantsTable&\Cake\ORM\Association\BelongsTo $Garants
 *
 * @method \App\Model\Entity\Garanty newEmptyEntity()
 * @method \App\Model\Entity\Garanty newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Garanty[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Garanty get($primaryKey, $options = [])
 * @method \App\Model\Entity\Garanty findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Garanty patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Garanty[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Garanty|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Garanty saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Garanty[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Garanty[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Garanty[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Garanty[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class GarantiesTable extends Table
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
        $auditLog->model_id =  "G:".$entity->libelle_garantie . ",NUM: ". $entity->numero; 

        // Optionally, you can add more details like user ID, timestamp, etc.
        $auditLog->timestamp = FrozenTime::now();

        // Save the audit log entry
        $auditLogsTable->save($auditLog);
        $entity->numero =      $entity->numero.(string)$entity->id."DEL"; 




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
             $old = [];
             $new = [];
             if($action == 'update'){
                if ($oldValue === $newValue) {
                    continue;
                }
               }
             if($fieldName == 'documents'){
                
                $new = explode(', ', $newValue); // Join elements with comma and space
                if($oldValue == null){
                    $old = [];
                }
                else{
                $old = explode(', ', $oldValue); // Join elements with comma and space
                }
               
                 if(count($new) >= count($old)){     // this means we added a file

                 $oldValue = "Fichiers(Added)";
                 
                 $addedFiles = array_diff($new, $old);
    $newValue = "";
  
    $newValue .= ', ' . implode(', ', $addedFiles); // Concatenate added files with a comma


             }
             else if(count($new) < count($old)){
                  // $oldValue = "Added Files";
          
                $oldValue = "Fichiers(Deleted)";
                $deletedFiles = array_diff($old, $new);
                $newValue = "";
                $newValue .= ", ". implode(", ", $deletedFiles);


             }
             else{
                debug($oldValue . "new" . $newValue);
                continue;
             }

            }
           
              
            if($fieldName == 'del' && $newValue == 1 && $oldValue != $newValue) { // were deleting so we just need to show that
                $stop == true;
                                   break;

              }
              elseif($fieldName == "raison" && $newValue != $oldValue) {    // part of the deletion process
                
                $stop = true;
            
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

        $this->setTable('garanties');
        $this->setDisplayField(['numero', 'libelle_garantie']);
        $this->setPrimaryKey('id');

        $this->belongsTo('Typologies', [
            'foreignKey' => 'typologie_id',
        ]);
        $this->belongsTo('Status', [
            'foreignKey' => 'status_id', // Adjust this according to your database schema
        ]);
        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
        ]);
        $this->belongsTo('Status', [
            'foreignKey' => 'status_id)',
        ]);
        $this->belongsTo('Garants', [
            'foreignKey' => 'garant_id',
        ]);

        $this->hasMany('Evaluations', [
            'foreignKey' => 'garantie_id',
        ]);
        $this->hasMany('Modifications', [
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
            ->scalar('libelle_garantie')
            ->maxLength('libelle_garantie', 45)
            ->requirePresence('libelle_garantie', 'create')
            ->notEmptyString('libelle_garantie');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->requirePresence('description', 'create')
            ->notEmptyString('description')
            ->scalar('descrip')
            ->maxLength('descrip', 255)
            ->requirePresence('descrip', 'create')
            ->notEmptyString('descrip');

        $validator
        ->allowEmptyString('montant', null, __('Montant invalid'))
        ->integer('montant', __('Montant invalid'))
        ->greaterThanOrEqual('montant', 0, __('Montant invalid'))
        ->lessThanOrEqual('montant', 2147483647, __('Montant invalid'));





        $validator
            ->date('date_debut')
            ->requirePresence('date_debut', 'create')
            ->notEmptyDate('date_debut');

        $validator
        ->date('date_fin')
        ->requirePresence('date_fin', 'create')
        ->notEmptyDate('date_fin')
        ->add('date_fin', 'custom', [
            'rule' => function ($value, $context) {
                $dateDebut = $context['data']['date_debut'];
                return strtotime($value) >= strtotime($dateDebut);
            },
            'message' => 'La date de fin ne peut pas être avant la date de début.'
        ]);

        $validator
            ->scalar('portee')
            ->maxLength('portee', 40)
            ->requirePresence('portee', 'create')
            ->notEmptyString('portee');
            $validator
            ->scalar('raison')
            ->maxLength('raison', 255)
            ->requirePresence('raison', 'create')
            ->notEmptyString('raison');

        $validator
            ->maxLength('numero', 8)
            ->add('numero', 'unique', ['rule' => 'validateUnique', 'provider' => 'table',

            'message' => 'numero insisponible !(pas unique)'

        
    ])
    ->add('numero', 'custom', [
        'rule' => function ($value, $context) {
           if($value ==null || trim($value) == ''){
            return true;
           }
            return ctype_alnum($value); // make sure it only consists of letters and numbers 
        },
        'message' => 'Le numero ne doit contenir que des lettres et des chiffres.',
    ]);
       
        

    $validator
    ->scalar('reference')
    ->maxLength('reference', 45)
    ->requirePresence('reference', 'create');

    


$validator
->add('date_fin','minValue',['rule'=>
function($value,$context){
$date = date('Y-m-d');
return $value >=  $date;

},
'message'=> "Date de fin ne peut pas être avant aujourd'hui !"




]);




      

        $validator
            ->integer('typologie_id')
            ->allowEmptyString('typologie_id');

        $validator
            ->integer('client_id')
            ->allowEmptyString('client_id');

        $validator
            ->integer('garant_id')
            ->allowEmptyString('garant_id');

        $validator
            ->scalar('statut')
            ->maxLength('statut', 1)
            ->allowEmptyString('statut')

            ->add('montant', 'maxValue', [
                'rule' => function ($value, $context) {
                    return $value <= 2147483647;
                },
                'message' => 'montant must not exceed the maximum integer value.'
            ])
            ->add('montant', 'minValue', [
                'rule' => function ($value, $context) {
                    return $value >= 0;
                },
                'message' => 'montant must not be negative'
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
        $rules->add($rules->isUnique(['numero']), ['errorField' => 'numero']);
        $rules->add($rules->existsIn('typologie_id', 'Typologies'), ['errorField' => 'typologie_id']);
        $rules->add($rules->existsIn('client_id', 'Clients'), ['errorField' => 'client_id']);
        $rules->add($rules->existsIn('garant_id', 'Garants'), ['errorField' => 'garant_id']);

        return $rules;
    }
}
