<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Agences Controller
 *
 * @property \App\Model\Table\AgencesTable $Agences
 * @method \App\Model\Entity\Agence[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AgencesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Agences->find("all");

        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $conditions = [
                'OR' => [
                    ['Agences.label LIKE' => '%' . $search . '%'],
                    ['Agences.code_agence LIKE' => '%' . $search . '%'],

                  

                  
                ]
            ];
      
         
      
            $query->where($conditions);
        }
      
      
      
              $agences = $this->paginate($query);
        $this->set(compact('agences'));
    }

    /**
     * View method
     *
     * @param string|null $id Agence id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel(('Users'));
        $USERS = $this->Users;
        $this->set(compact('USERS'));

        $agence = $this->Agences->get($id, [
            'contain' => ['Clients'],
        ]);

        $filteredClients = array_filter($agence->clients, function($client) {
            return $client->del == 0;
        });
        
        $agence->clients = $filteredClients;
        

        $this->set(compact('agence'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $agence = $this->Agences->newEmptyEntity();
        if ($this->request->is('post')) {
            $agence = $this->Agences->patchEntity($agence, $this->request->getData());
            if ($this->Agences->save($agence, true, array( 'code_agence', 'label'))) {
                $this->Flash->success(__('Agence << '.$agence['label'].'>> enregistrée avec succès'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Erreur enregistrement agence. Merci de réessayer'));
        }
        $this->set(compact('agence'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Agence id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $agence = $this->Agences->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $agence = $this->Agences->patchEntity($agence, $this->request->getData());
            if ($this->Agences->save($agence,true, array( 'code_agence', 'label'))) {
                $this->Flash->success(__("L'agence <<".$agence['label'].'>> a été modifié avec succès'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Erreur modification agence. Merci de réessayer'));
        }
        $this->set(compact('agence'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Agence id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->autoRender = false;

        $this->request->allowMethod(['post', 'delete']);
       
        $default = $this->Agences->find()->
        where(['id'=> 0]);
             
        $default = $default->first();
        

        if($default->id == $id){
            $this->Flash->error(__('La suppression de cette Agence est Interdite'));
    return  $this->redirect(['action' => 'index']);
         }

         if(!$this->softDeleteRelatedRecords($id)){

            $this->Flash->error(__("l'agence ne peut pas etre supprimmer car il contient des clients actifs"));
            return  $this->redirect(["controller"=>"agences",'action' => 'view',$id]);
        }
          
         $this->changeRelatedRecords($id,0);

         $agence = $this->Agences->get($id);

        if ($this->Agences->delete($agence)) {
            $this->Flash->success(__("L'agence <<".$agence['label'].">> a été supprimée avec succès"));
        } else {
            $this->Flash->error(__('Erreur suppression agence. Merci de réessayer'));
        }

        return $this->redirect(['action' => 'index']);
    }



    
    function changeRelatedRecords($id,$newid){ // need to set the deleted clients in different agence so they are restorable 
     
        $this->loadModel('Clients');
        $this->Clients->updateAll(
            ['agence_id'=> $newid],
            ['agence_id' => $id]
        );

        $this->loadModel('Garanties');
        $this->Garanties->updateAll(
            ['agence_id'=> $newid],
            ['agence_id' => $id]
        );

    }
    public function softDeleteRelatedRecords($id)   // oroginal function to delete related but now will be used to avoid deletion if related exists
    {

        $this->loadModel('Clients');


        
         

   
        $garantiesIds = $this->Clients->find()
        ->where(['agence_id' => $id, 'del' => false])
        ->extract('id')
        ->toArray();


        if (!empty($garantiesIds)) {

         return false;// this means this client has garanties therefore we will block deletion 
        
        }



        return true;
    
    }
}
