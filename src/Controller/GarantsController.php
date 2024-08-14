<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenTime;

/**
 * Garants Controller
 *
 * @property \App\Model\Table\GarantsTable $Garants
 * @method \App\Model\Entity\Garant[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GarantsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */


     
    public function restore($id){

        $garant = $this->Garants->get($id);
        $length = strlen((string)$id) +3; // decide how many characters we are removing(the id and the DEL which would be three characters )
        
        $code = substr($garant->code_garant,0,strlen($garant->code_garant)-$length);// removes the id we appended to avoid duplication
        $oldcode = $code;   // lancient num
        while($this->valueExists("Garants","code_garant",$code)){  // we will only return once we have a unique numero we first start with the orignal so we can keeop the orgianl numero
        
        
        
        $code = $this->increment($code);
     
        
        }
        
        $this->set(compact('id', 'code', 'garant', 'oldcode')); // Pass garantie to get other data
        
        }
        
        public function confirmRestore ($id) {
            $garant = $this->Garants->get($id);
        
            // Update Garantie with new numero and save
            $garant->code_garant = $this->request->getData('code'); 
            $garant->del = 0;
            $garant->date_r = FrozenTime::now();
            $garant->restored_by = $_SESSION["user_id"];


        
            if ($this->Garants->save($garant)) {
                $this->Flash->success(__('L(e)a garant(e) '.$garant->code_garant. ' a ete restore.'));
            } else {
                $this->Flash->error(__('Le(a) garant(e) na pas peut etre restore veuillez ressayer'));
            }
        
            return $this->redirect(['action' => 'deleted']);
        }
     public function deleted()

     {
   // Handle search query if submitted
 
 
   $query = $this->Garants->find("deleted");
 
   $search = $this->request->getQuery('search');
   if (!empty($search)) {
       $conditions = [
           'OR' => [
               ['intitule_garant LIKE' => '%' . $search . '%'],
               ['code_garant LIKE' => '%' . $search . '%'],
               ['Garants.code_rct LIKE' => '%' . $search . '%'],
             
           ]
       ];
 
    
 
       $query->where($conditions);
   }
 
 
 
         $garants = $this->paginate($query);
 
         $this->set(compact('garants'));
     }
    public function index()

    {
  // Handle search query if submitted


  $query = $this->Garants->find("active");

  $search = $this->request->getQuery('search');
  if (!empty($search)) {
      $conditions = [
          'OR' => [
              ['intitule_garant LIKE' => '%' . $search . '%'],
              ['code_garant LIKE' => '%' . $search . '%'],
              ['Garants.code_rct LIKE' => '%' . $search . '%'],
            
          ]
      ];

   

      $query->where($conditions);
  }



        $garants = $this->paginate($query);

        $this->set(compact('garants'));
    }

    /**
     * View method
     *
     * @param string|null $id Garant id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel(('Users'));
        $USERS = $this->Users;
        $this->set(compact('USERS'));
        $garant = $this->Garants->get($id, [
            'contain' => ['Garanties'],
        ]);

        $filteredGaranties = array_filter($garant->garanties, function($garantie) {
            return $garantie->del == 0;
        });
        $garant->garanties = $filteredGaranties;


        $this->set(compact('garant'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $garant = $this->Garants->newEmptyEntity();
        if ($this->request->is('post')) {
            $garant = $this->Garants->patchEntity($garant, $this->request->getData());
            if ($this->Garants->save($garant, true, array('code_garant','code_rct','intitule_garant'))) {
                $this->Flash->success(__('Le(a) garant(e) Code RCT: '.$garant['code_garant']. ','.' a été enregistré(e) avec succès'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Erreur enregistrement garant(e). Merci de réessayer'));
        }
        $this->set(compact('garant'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Garant id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $garant = $this->Garants->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $garant = $this->Garants->patchEntity($garant, $this->request->getData());
            if ($this->Garants->save($garant, true, array('code_garant','code_rct','intitule_garant'))) {
                $this->Flash->success(__('Le(a) garant(e) Code RCT: '.$garant['code_garant'].','.' a été modifié(e) avec avec succès'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Erreur modification garant(e). Merci de réessayer'));
        }
        $this->set(compact('garant'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Garant id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function DeleteRelatedRecords($clientId)   // oroginal function to delete related but now will be used to avoid deletion if related exists
    {

        $this->loadModel('Garanties');


        
         

   
        $garantiesIds = $this->Garanties->find()
        ->where(['garant_id' => $clientId, 'del' => false])
        ->extract('id')
        ->toArray();


        if (!empty($garantiesIds)) {

         return false;// this means this client has garanties therefore we will block deletion 
        
        }


        return true;
    
    }

    public function delete($id = null)
    {
       
    
        $this->request->allowMethod(['post', 'delete']);
        $garant = $this->Garants->get($id);
         $message = $garant;

         if(!$this->DeleteRelatedRecords($id)){


            $this->Flash->error(__('Erreur suppression garant(e), car il a des garanties actives'));
        
    return $this->redirect(["action"=> "view",$id]);   
           }
        if ($this->Garants->delete($garant)) {
            $this->Flash->success(__('Le(a) garant(e) Code RCT: '.$message['code_rct'].' a été supprimé(e) avec succès'));
        } else {
            $this->Flash->error(__('Erreur suppression garant(e). Merci de réessayer'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
