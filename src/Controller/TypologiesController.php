<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Typologies Controller
 *
 * @property \App\Model\Table\TypologiesTable $Typologies
 * @method \App\Model\Entity\Typology[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TypologiesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Typologies->find("all");

        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $conditions = [
                'OR' => [
                    ['label LIKE' => '%' . $search . '%'],
                  

                  
                ]
            ];
      
         
      
            $query->where($conditions);
        }
      
      
      
              $typologies = $this->paginate($query);


        $this->set(compact('typologies'));
    }

    /**
     * View method
     *
     * @param string|null $id Typology id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel(('Users'));
        $USERS = $this->Users;
        $this->set(compact('USERS'));
        $this->paginate = [
            'contain' => [],

        ];
        $STAT = [];

        $this->loadModel('Garanties');
        $typology = $this->Typologies->get($id);
$query = $this->Garanties->find()->where(['typologie_id'=> $id,
'del'=>0]);
          $garanties = $this->paginate($query);
      
        $this->loadModel('Status');
        foreach ($garanties as $garanty ) { 
           $status = $this->Status->find()
       ->where([
           'id' => $garanty->status_id
       ])
       ->first();
        
       $STAT[$garanty->numero] = $status->label;
   
        }


        $this->set(compact('typology'));
        $this->set(compact('garanties'));
        $this->set(compact('STAT'));


    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $typology = $this->Typologies->newEmptyEntity();
        if ($this->request->is('post')) {
            $typology = $this->Typologies->patchEntity($typology, $this->request->getData());
            if ($this->Typologies->save($typology,  array( 'label'))) {
                $this->Flash->success(__("La typologie '".$typology['label']."' a été enregistée avec succès"));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__("Erreur de lors de l'enregistrement. Merci de réessayer"));
        }
        $this->set(compact('typology'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Typology id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $typology = $this->Typologies->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $typology = $this->Typologies->patchEntity($typology, $this->request->getData());
            if ($this->Typologies->save($typology)) {
                // debug($typology);

                $this->Flash->success(__('La typologie '.$typology['label']. ' a été modifiée avec succès'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Erreur modification typologie. Merci de réessayer'));
        }
        $this->set(compact('typology'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Typology id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
           
      
        $this->autoRender = false;

        $this->request->allowMethod(['post', 'delete']);
      
      
        $name =  'Typologie Intermediaire';
        $default = $this->Typologies->find()->
        where(['id'=> 0]);
             
        $default = $default->first();
        

        if($default->id == $id){
            $this->Flash->error(__('La suppression de cette Typologie est Interdite'));
    return  $this->redirect(['action' => 'index']);
         }

         if(!$this->softDeleteRelatedRecords($id)){

          $this->Flash->error(__('Impossible de supprimer cette garantie car elle contient des garanties actives'));
          return $this->redirect(['action'=> 'view',$id]);
         }
           
        $typology = $this->Typologies->get($id);
        $this->changeRelatedRecords($id, 0);
        if ($this->Typologies->delete($typology)) {
            $this->Flash->success(__("La typologie ".$typology['label']." a été supprimée avec succès"));
        } else {
            $this->Flash->error(__('Erreur lors de la suppression. Merci de réessatyer'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function changeRelatedRecords($id,$newid)   // delete les garanties du clients
    {
        $this->loadModel('Garanties');
        $this->Garanties->updateAll(
            ['typologie_id'=> $newid],
            ['typologie_id' => $id]
        );
    }

    public function softDeleteRelatedRecords($id)   // oroginal function to delete related but now will be used to avoid deletion if related exists
    {

        $this->loadModel('Garanties');


        
         

   
        $garantiesIds = $this->Garanties->find()
        ->where(['typologie_id' => $id, 'del' => false])
        ->extract('id')
        ->toArray();


        if (!empty($garantiesIds)) {

         return false;// this means this client has garanties therefore we will block deletion 
        
        }



        return true;
    
    }
}

