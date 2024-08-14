<?php
declare(strict_types=1);

namespace App\Controller;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
use Laminas\Diactoros\Response\JsonResponse;
/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientsController extends AppController    // modification : c'est la que jai ajouter les fonctions afin d'envoyer les donnees pour les stats
{                                                 
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
  
    public function restore($id){


        $client = $this->Clients->get($id);
        

        $length = strlen((string)$id) +3; // decide how many characters we are removing(the id and the DEL which would be three characters )
        
        $code = substr($client->code,0,strlen($client->code)-$length);// removes the id we appended to avoid duplication
        $oldcode = $code;   // lancient num
        while($this->valueExists("Clients","code",$code)){  // we will only return once we have a unique numero we first start with the orignal so we can keeop the orgianl numero
        
        
        
        $code = $this->increment($code);
     
        
        }
        
        $this->set(compact('id', 'code', 'client', 'oldcode')); // Pass garantie to get other data
        
        }
        
        public function confirmRestore ($id) {
            $client = $this->Clients->get($id);
        
            // Update Garantie with new numero and save
            $client->code = $this->request->getData('code'); // Capture 'numero' from the hidden field
            $client->del = 0;
            $client->date_r = FrozenTime::now();

            $client->restored_by = $_SESSION["user_id"];

        
            if ($this->Clients->save($client)) {
                $this->Flash->success(__('L(e)a client(e) '.$client->code. ' a ete restore.'));
            } else {
                $this->Flash->error(__('Le(a) client(e) na pas peut etre restore veuillez ressayer'));
            }
        
            return $this->redirect(['action' => 'deleted']);
        }
        
    public function deleted()
    {
        $this->paginate = [
            'contain' => ['Agences','Garanties'],
        ];

        $query = $this->Clients->find("deleted");

    


        // Handle search query if submitted
        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $conditions = [
                'OR' => [
                    ['Clients.label LIKE' => '%' . $search . '%'],
                    ['segment LIKE' => '%' . $search . '%'],
                    ['code LIKE' => '%' . $search . '%'],
                    ['Clients.code_rct LIKE' => '%' . $search . '%'],
                  
                ]
            ];

         

            $query->where($conditions);
        }

    
$clients = $this->paginate($query);

        $this->set(compact('clients'));
    }


     public function index()
    {
        $this->paginate = [
            'contain' => ['Agences','Garanties'],
        ];

        $query = $this->Clients->find("active");

    


        // Handle search query if submitted
        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $conditions = [
                'OR' => [
                    ['Clients.label LIKE' => '%' . $search . '%'],
                    ['segment LIKE' => '%' . $search . '%'],
                    ['code LIKE' => '%' . $search . '%'],
                    ['Clients.code_rct LIKE' => '%' . $search . '%'],
                  
                ]
            ];

         

            $query->where($conditions);
        }

    
$clients = $this->paginate($query);

        $this->set(compact('clients'));
    }

    /**
     * View method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel(('Users'));
        $USERS = $this->Users;
        $this->set(compact('USERS'));
        $client = $this->Clients->get($id, [
            'contain' => ['Agences', 'Garanties'],
        ]);

        $filteredGaranties = array_filter($client->garanties, function($garantie) {
            return $garantie->del == 0;
        });
        $this->loadModel('Garanties');

        $garanties =  $this->Garanties->find('active')->toArray();
        $client->garanties = $filteredGaranties;

        $STAT = [];

        $this->loadModel('Status');
     foreach ($garanties as $garanty ) { 
        $status = $this->Status->find()
    ->where([
        'id' => $garanty->status_id
    ])
    ->first();
     
    $STAT[$garanty->numero] = $status->label;

        

       
    }
    $this->set(compact('client'));
    $this->set(compact('STAT'));

}

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $client = $this->Clients->newEmptyEntity();
        if ($this->request->is('post')) {
            $client = $this->Clients->patchEntity($client, $this->request->getData());
            if ($this->Clients->save($client, true, array( 'code','label','code_rct','segment','agence_id'))) {
                $this->Flash->success(__('Le(a) client(e) '.$client['label'].' ('.$client['code'].') a été ajouté(e) avec succès'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Erreur enregistrement client(e). Merci de réessayer'));
        }
        $agences = $this->Clients->Agences->find('list', ['limit' => 200])->all();
        $this->set(compact('client', 'agences'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $referer = $this->request->getSession()->read('referercopy');

        // If referer is not set (first access), store it
        if (!$referer) {
            $referer = $this->request->referer();
            $this->request->getSession()->write('referercopy', $referer);
        } // afin to redirect a page ancienne au lieu de lindex
        $client = $this->Clients->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $client = $this->Clients->patchEntity($client, $this->request->getData());

            if ($this->Clients->save($client, true, array( 'code','label','code_rct','segment','agence_id'))) {
                $this->changeAgence($id,$client['agence_id']); // set the garanties agence_id
                $this->Flash->success(__('Le(a) client(e) '.$client['label'].' ('.$client['code'].') a été modifié(e) avec succès'));

                if ($referer) {
                    // Clear the session variable once used, if needed
                    $this->request->getSession()->delete('referercopy');
            
                    return $this->redirect($referer);
                } else {
                   
                    return $this->redirect(['controller' => 'Garanties', 'action' => 'index']);
                }            }
            $this->Flash->error(__('Erreur modification client(e). Merci de réessayer'));
        }
        $agences = $this->Clients->Agences->find('list', ['limit' => 200])->all();
        $this->set(compact('client', 'agences'));
    }
    public function softDeleteRelatedRecords($clientId)   // oroginal function to delete related but now will be used to avoid deletion if related exists
    {

        $this->loadModel('Garanties');
        $this->loadModel('Evaluations');


        
         

   
        $garantiesIds = $this->Garanties->find()
        ->where(['client_id' => $clientId, 'del' => false])
        ->extract('id')
        ->toArray();


        if (!empty($garantiesIds)) {

         return false;// this means this client has garanties therefore we will block deletion 
        
        }



        return true;
    
    }
    public function changeAgence($clientId,$id)   // changer lagence des garanties du clients
    {
        $this->loadModel('Garanties');
        $this->Garanties->updateAll(
            ['agence_id' => $id],
            ['client_id' => $clientId]
        );
    }

    /**
     * Delete method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null){
        $referer = $this->request->getSession()->read('referercopy');

    // If referer is not set (first access), store it
    if (!$referer) {
        $referer = $this->request->referer();
        $this->request->getSession()->write('referercopy', $referer);
    }  // afin to redirect a page ancienne au lieu de lindex
    $this->request->allowMethod(['post', 'delete']);
    $client = $this->Clients->get($id);
    $message = $client;   // temporary variable 

    // debug($client->del);
  
    if(!$this->softDeleteRelatedRecords($client->id)){  // block deletion
        $this->Flash->error(__('Erreur suppression client(e), car il/elle a des garanties Actives'));
        return $this->redirect(["controller"=> "Clients", "action"=> "view",$client->id]);   

    }



    if ($this->Clients->delete($client)) {
        
        $this->Flash->success(__('Le(a) client(e) ' . $message['label'] . ' (' . $message['code'] . ') a été supprimé(e) avec succès'));
    } else {
        $this->Flash->error(__('Erreur suppression client(e). Merci de réessayer'));
    }


    if ($referer) {
        // Clear the session variable once used, if needed
        $this->request->getSession()->delete('referercopy');

        return $this->redirect($referer);
    } else {
       
        return $this->redirect(['controller' => 'Garanties', 'action' => 'index']);
    }}

}