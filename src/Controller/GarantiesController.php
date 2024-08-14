<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\Database\Expression\QueryExpression;
use Cake\Database\Query;
use Cake\Filesystem\File;
use Cake\I18n\FrozenTime;
use Exception;

/**
 * Garanties Controller
 *
 * @property \App\Model\Table\GarantiesTable $Garanties
 * @method \App\Model\Entity\Garanty[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GarantiesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
public function restore($id){
$this->loadModel("Clients");

$garantie = $this->Garanties->get($id);
$client = $this->Clients->get($garantie->client_id); // we need to check if its client is active bfore deciding to restore
if($client->del != 0){

$this->Flash->error(__("Impossible de restorer la garantie". $garantie->numero. "car son client " . $client->label . " : ". $client->code . "est supprimer, veuillex restorer ce client"));

return $this->redirect([
    'controller' => 'clients',
    'action' => 'deleted',
    '?' => [
        'search' => $client->code,// on recherche le client en meme temps comma ca lutilisateur peut le restorer aussi 
    ]
]);
}
$length = strlen((string)$id) +3; // decide how many characters we are removing(the id and the DEL which would be three characters )

$numero = substr($garantie->numero,0,strlen($garantie->numero)-$length);// removes the id we appended to avoid duplication
$oldnumero = $numero;   // lancient num
while($this->valueExists("Garanties","numero",$numero)){  // we will only return once we have a unique numero we first start with the orignal so we can keeop the orgianl numero
$last = substr($numero,strlen($numero)-1,1); // get the last character
$G = false;
if($last == "G"){
$G = true;
    $numero = substr($numero,0,strlen($numero)-1);  // remove G
}

$numero = $this->increment($numero);
if($G){
$numero.="G"; // append it back
}


}

$this->set(compact('id', 'numero', 'garantie', 'oldnumero')); // Pass garantie to get other data

}

public function confirmRestore ($id) {
    $garantie = $this->Garanties->get($id);

    // Update Garantie with new numero and save
    $garantie->numero = $this->request->getData('numero'); // Capture 'numero' from the hidden field
    $garantie->del = 0;
    $garantie->date_r = FrozenTime::now();
    $garantie->restored_by = $_SESSION["user_id"];



    if ($this->Garanties->save($garantie)) {
        $this->Flash->success(__('La garantie '.$garantie->numero. ' a ete restore.'));
    } else {
        $this->Flash->error(__('La garantie na pas peut etre restore veuillez ressayer'));
    }

    return $this->redirect(['action' => 'deleted']);
}

public function deleted(){// fonction pur voir les clients supprimer


    $this->paginate = [
        'contain' => ['Typologies', 'Clients', 'Garants'],

    ];
    
    $STAT = [];

    $this->loadModel('Status');

    $query = $this->Garanties->find('deleted');

    // Handle search query if submitted
    $search = $this->request->getQuery('search');
    if (!empty($search)) {
        $conditions = [
            'OR' => [
                ['libelle_garantie LIKE' => '%' . $search . '%'],
                ['montant LIKE' => '%' . $search . '%'],
                ['date_debut LIKE' => '%' . $search . '%'],
                ['date_fin LIKE' => '%' . $search . '%'],
                ['numero LIKE' => '%' . $search . '%'],
                ['classement LIKE' => '%' . $search . '%'],
                ['reference LIKE' => '%' . $search . '%'],
                ['currency LIKE' => '%' . $search . '%'],

            ]
        ];

        // Add manual conditions based on similar terms
        if (stripos($search, 'leve') !== false) { // Check if 'leve' is part of the search term
            $conditions['OR'][] = ['status_id' => 2];
        }
       else if (stripos($search, 'encou') !== false) { // Check if 'encour' is part of the search term
            $conditions['OR'][] = ['status_id' => 1];
        }
        else if(stripos($search, 'echo') !== false){
            $conditions['OR'][] = ['status_id' => 3];


        }

        $query->where($conditions);
    }


$garanties = $this->paginate($query);


foreach ($garanties as $garanty ) { 
$status = $this->Status->find()
->where([
'id' => $garanty->status_id
])
->first();

$STAT[$garanty->numero] = $status->label;

}


    $this->set(compact('garanties'));
    $this->set(compact('STAT'));


}
    public function index()
    {
   
      



        $this->paginate = [
            'contain' => ['Typologies', 'Clients', 'Garants'],

        ];
        
        $STAT = [];

        $this->loadModel('Status');
   
        $query = $this->Garanties->find('active');

        // Handle search query if submitted
        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $conditions = [
                'OR' => [
                    ['libelle_garantie LIKE' => '%' . $search . '%'],
                    ['montant LIKE' => '%' . $search . '%'],
                    ['date_debut LIKE' => '%' . $search . '%'],
                    ['date_fin LIKE' => '%' . $search . '%'],
                    ['numero LIKE' => '%' . $search . '%'],
                    ['classement LIKE' => '%' . $search . '%'],
                    ['reference LIKE' => '%' . $search . '%'],
                    ['currency LIKE' => '%' . $search . '%'],

                ]
            ];

            // Add manual conditions based on similar terms
            if (stripos($search, 'leve') !== false) { // Check if 'leve' is part of the search term
                $conditions['OR'][] = ['status_id' => 2];
            }
           else if (stripos($search, 'encou') !== false) { // Check if 'encour' is part of the search term
                $conditions['OR'][] = ['status_id' => 1];
            }
            else if(stripos($search, 'echo') !== false){
                $conditions['OR'][] = ['status_id' => 3];


            }

            $query->where($conditions);
        }

    
$garanties = $this->paginate($query);


foreach ($garanties as $garanty ) { 
    $status = $this->Status->find()
->where([
    'id' => $garanty->status_id
])
->first();
 
$STAT[$garanty->numero] = $status->label;

 }


        $this->set(compact('garanties'));
        $this->set(compact('STAT'));



    }
    /**
     * View method
     *
     * @param string|null $id Garanty id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel(('Users'));
        $USERS = $this->Users;
        $garanty = $this->Garanties->get($id, [
            'contain' => ['Typologies', 'Clients', 'Garants','Evaluations'],
        ]);
        $this->loadModel('Status');
        $filteredEvaluations = array_filter($garanty->evaluations, function($evaluation) {
            return $evaluation->del == 0;
        });
        
        $garanty->evaluations = $filteredEvaluations;
         if($garanty->garants != null){
        $filteredGarants = array_filter($garanty->garants, function($garant) {
            return $garant->del == 0;


        });
        $garanty->garants = $filteredGarants;

    }
        

        $status = $this->Status->find()
    ->where([
        'id' => $garanty->status_id
    ])
    ->first();

    $h = [];  // array to take in id and spit out le nonprenom
     $this->loadModel('Evaluateurs');
     $person = $this->Evaluateurs->find('all');
     foreach ($person as $p) {
       $h[$p->id] = $p->nom_prenom;  
     }
     
    $status = $status->label;

        $this->set(compact('garanty'));                // gain access to status through its table rather than a column
        $this->set(compact('status'));
        $this->set(compact('h'));
        $this->set(compact('USERS'));



    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('Agences');
        $this->loadModel('Clients');
        $this->loadModel('Garants');

        $garanty = $this->Garanties->newEmptyEntity();
        $CLIENT = $this->Clients->newEmptyEntity();
        $garant = $this->Garants->newEmptyEntity();
        
        $typologies = $this->Garanties->Typologies->find('list', ['limit' => 200])->all();
        $clients = $this->Garanties->Clients->find('list', ['limit' => 200])->all();
        $garants = $this->Garanties->Garants->find('list', ['limit' => 200])->all();
        $agences = $this->Agences->find('list', ['limit' => 200])->all();


        $this->set(compact('garanty', 'typologies', 'CLIENT', 'garants','agences','CLIENT'));

        if ($this->request->is('post')) {
            $garantData = $this->request->getData();
            if($garantData['numero'] != null) {
     $garantData['numero'] = trim($garantData['numero'] );// so it accounts the spaces
            }
               
            $garanty = $this->Garanties->patchEntity($garanty,   $garantData );

            if(trim($garanty->montant) == "" || $garanty->montant == null) {
                $garanty->montant = 0;
            }

            $CLIENT =$this->Clients->patchEntity($CLIENT, $this->request->getData());
            $garantData = $this->request->getData();


          
            if(isset($garantData['garant_code_rct'])){   // cest seulement possible si un garant as ete saisi et pas retrouver
            $garantData['code_rct'] = $garantData['garant_code_rct'];
            $garant =$this->Garants->patchEntity($garant, $garantData);
    
            $gid = $this->request->getData()['garant_id'];  

            }
            else{
                $gid = 'nothing';// c'est que c'est un garant qui existe
            }
            if($gid == ''){
                $gid == 'nothing';    // on utilise les id pour envoyer des info et pour creer des nouveau
            }
            // debug($gid);
       
           $id= $this->request->getData()['client_id'];
                
       
if($this->request->getData()['client_id'] == null){    //ca veux dire que c'est un nouveau client(le user na pas utiliser un client qui existe)

    if(!$garanty->hasErrors() && !$CLIENT->hasErrors() && ( !$garant->hasErrors() || $gid != 'noting' )){  


    $CLIENT->code = $this->request->getData()['code'];
    $CLIENT->code_rct = $this->request->getData()['code_rct'];
    // debug($CLIENT);




    if ($this->Clients->save($CLIENT, true, array( 'code','label','code_rct','segment','agence_id'))) {
        $this->Flash->success(__('Le(a) client(e) '.$CLIENT['label'].' ('.$CLIENT['code'].') a été ajouté(e) avec succès'));
        $client = $this->Clients->find()->
        where(['code'=> $CLIENT->code,    
               'del' => 0,
    ]);

        
    $client = $client->first(); 
    // $CLIENT->client_id = "1";
    $garanty->client_id =$client->id;
   }
    
    }
    else{  // si il ya des erreurs

        $errors = $CLIENT->getErrors();
        $errorMessages = [];
        
        foreach ($errors as $field => $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $errorMessages[] = $error;
            }
        }
        
        if (!empty($errorMessages)) {
            $this->Flash->error(__('Erreurs: ') . implode(', ', $errorMessages));
        } 
        return;
    }
}      
else if($id == 'wrong'){
    $this->Flash->error(__('Agence Invalid pour ce client. Merci de réessayer'));
    return;
}  
 else {
   

    $garanty->client_id = $this->request->getData()['client_id'];

     
 }

 if($gid == null){    // new garant

    if(!$garanty->hasErrors() && !$garant->hasErrors()){


        $garant->code_garant = $this->request->getData()['code_garant'];
        $garant->code_rct = $garantData['code_rct'];
        // debug($CLIENT);
    
    
    
    
        if ($this->Garants->save($garant, true, array( 'code_garant','intitule_garant','code_rct'))) {
            $this->Flash->success(__('Le(a) Garant(e) '.$garant['intitule_garant'].' ('.$garant['code_garant'].') a été ajouté(e) avec succès'));
            $gar = $this->Garants->find()->
            where(['code_garant'=> $garant->code_garant,
                   'del' => 0,
        ]);
    
            
        $gar = $gar->first(); 
        // $CLIENT->client_id = "1";
        $garanty->garant_id =$gar->id;
       }
        
        }
        else{
            $errors = $garant->getErrors();

            $errorMessages = [];
            
            foreach ($errors as $field => $fieldErrors) {
                foreach ($fieldErrors as $error) {
                    $errorMessages[] = $error;
                }
            }
            
            if (!empty($errorMessages)) {
                $this->Flash->error(__('Erreurs: ') . implode(', ', $errorMessages));
            }
            return;
        }
    }
    else if($gid == 'nothing'){

    }      
   
     else {
       
    
        $garanty->garant_id = $this->request->getData()['garant_id'];
    
         
     }
     
     $date_debut = isset($garanty['date_debut']) ? $garanty['date_debut']->format('Y-m-d H:i:s') : null;
     $date_fin = isset($garanty['date_fin']) ? $garanty['date_fin']->format('Y-m-d H:i:s') : null;
    //  $date_evalution = isset($garanty['date_evalution']) ? $garanty['date_evalution']->format('Y-m-d H:i:s') : null;
     
     // Assign formatted datetime values back to $garanty array
     $garanty['date_debut'] = $date_debut;
     $garanty['date_fin'] = $date_fin;
    //  $garanty['date_evalution'] = $date_evalution;
     
     // Assign formatted datetime values back to $garanty array
    //  $garanty['date_debut'] = $date_debut;
    //  $garanty['date_fin'] = $date_fin;
    //  $garanty['date_evalution'] = $date_evalution;

 // if()           // will only reahc this part if no errors happened above
            if($id != null || !$CLIENT->hasErrors()){// if its an existing client or there are no errors
                $uploadedFiles = $this->request->getData('document');
                $filePaths = [];
        
                if (!empty($uploadedFiles[0]->getClientFilename())) {
                    foreach ($uploadedFiles as $uploadedFile) {
                        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                            $uploadPath = WWW_ROOT . 'uploads' . DS;
                            $filename = $uploadedFile->getClientFilename();
                            $filename = time() . '_' . $filename; // Adding a timestamp to avoid file name collision
        
                            try {
                                $uploadedFile->moveTo($uploadPath . $filename);
                                $filePaths[] = $filename; // Store filename
                            } catch (Exception $e) {
                                $this->Flash->error('Error uploading file: ' . $filename . '. Please try again.');
                            }
                        }
                    }
        
                    // Store filenames as a JSON array in the 'documents' field
                    $garanty->documents = json_encode($filePaths);
                }

            if ($this->Garanties->save($garanty,true, array('libelle_garantie','description','montant',"date_debut","date_fin",'portee','numero','reference','status_id','agence_id','client_id','garant_id','classement'))) {
                $garantyy = $this->Garanties->find('active')->where(['reference'=> $garanty['reference']])->first();
               
                $this->Flash->success(__('La garantie .: ' . 'NUMERO: '.   $garantyy['numero'].  ':'  .$garanty['libelle_garantie']. ' a été enregistrée avec succès'));
                      
                return $this->redirect(['action' => 'index']);
            }
        

    }
    else{
        $this->Flash->error(__('Client Invalid. Merci de réessayer'));


    }
            $this->Flash->error(__('Erreur enregistrment garantie. Merci de réessayer'));
        }
      
    }
    public function deleteDocument($id, $filename)
    {
        $this->request->allowMethod(['post', 'delete']);
    
        // Retrieve the garantie
        $garanty = $this->Garanties->get($id);
    
        // Decode the JSON array of documents
        $documents = json_decode($garanty->documents);
    
        // Find the index of the document to delete
        $index = array_search($filename, $documents);
    
        if ($index !== false) {
            // Remove the filename from the array
            unset($documents[$index]);
    
            // Save the updated documents list
            $garanty->documents = json_encode(array_values($documents));
    
            if ($this->Garanties->save($garanty)) {
                // Delete the physical file
                $uploadPath = WWW_ROOT . 'uploads' . DS;
                $file = new File($uploadPath . $filename);
                if ($file->delete()) {
                    $this->Flash->success(__('Le fichier a été supprimé avec succès'));
                } else {
                    $this->Flash->error(__('Erreur lors de la suppression du fichier. Veuillez réessayer'));
                }
            } else {
                $this->Flash->error(__('Erreur lors de la sauvegarde de la garantie. Veuillez réessayer'));
            }
        } else {
            $this->Flash->error(__('Fichier introuvable'));
        }
    
        return $this->redirect(['action' => 'documents', $id]);
    }
    /**
     * Edit method 
     *
     * @param string|null $id Garanty id.
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
        }   // afin to redirect a page ancienne au lieu de lindex
        $garanty = $this->Garanties->get($id, [
            'contain' => [],
        ]);
        $before = unserialize(serialize($garanty)); // create a deep copy of the object
        
        $typologies = $this->Garanties->Typologies->find('list', ['limit' => 200])->all();
        $clients = $this->Garanties->Clients->find('list', ['limit' => 200])->all();
        $garants = $this->Garanties->Garants->find('list', ['limit' => 200])->all();
  
        $this->set(compact('garanty', 'typologies', 'clients', 'garants'));

        $this->loadModel('Modifications');
        $modification = $this->Modifications->newEmptyEntity();

        $given = $id;
        $this->set(compact('given'));// pour le garantie_id de mod
        $original = $garanty->status_id;  // store original status 
   
        if ($this->request->is(['patch', 'post', 'put'])) {
            $garanty = $this->Garanties->patchEntity($garanty, $this->request->getData());
            if(trim($garanty->montant) == "" || $garanty->montant == null) {
                $garanty->montant = 0;
            }

            $data = $this->request->getData();
          
            if($before != $garanty && ($data['descrip'] == null || trim($data['descrip']) == '' ) ){
              
          $this->Flash->error(__('entrez Une description/raison de modification'));
          return;
            }
            else{

                $modification = $this->Modifications->patchEntity($modification, $this->request->getData());

            }

            $garantData = $this->request->getData();

              // Handle multiple file uploads for 'documents' field
              $uploadedFiles = $this->request->getData('document');
              if(!empty($uploadedFiles)){
                if($garanty->documents != null){
        $existingDocuments = json_decode($garanty->documents, true); // Decode existing documents as array
                } else { $existingDocuments = [];   }
        $filePaths = [];

        // Append new uploaded files to existing documents
        foreach ($uploadedFiles as $uploadedFile) {
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $uploadPath = WWW_ROOT . 'uploads' . DS;
                $filename = time() . '_' . $uploadedFile->getClientFilename();

                try {
                    $uploadedFile->moveTo($uploadPath . $filename);
                    $existingDocuments[] = $filename; // Append filename to existing documents array
                } catch (Exception $e) {
                    $this->Flash->error('Error uploading file: ' . $uploadedFile->getClientFilename() . '. Please try again.');
                }
            }
        }

        // Update garanty entity with updated documents array
        $garanty->documents = json_encode($existingDocuments);  // debug($this->request->getData());

    }
 

    if($original != $data['status_id'] && $data['status_id'] == 2 ){  // if the status id was changed and its now levee we have to set the date 
        $date = date('Y-m-d H:i:s');
  
        $garanty->date_levee = $date; 
    }
            if ($this->Garanties->save($garanty,true, array('libelle_garantie','descritption','documents','montant','date_debut','date_fin','portee','numero','reference','status_id','date_levee'))) {
                $garanty = $this->Garanties->get($id);
                $nomod = (($modification->descrip == null) && trim($modification->descrip) != '');

                if($nomod == false ){

                $this->Modifications->save($modification);


                }
                $this->Flash->success(__('La garantie NO.: '.$garanty['numero']. ','.' a été modifiée avec succès'));

                if ($referer) {
                    // Clear the session variable once used, if needed
                    $this->request->getSession()->delete('referercopy');
            
                    return $this->redirect($referer);
                } else {
                   
                    return $this->redirect(['controller' => 'Garanties', 'action' => 'index']);
                }            }
            $this->Flash->error(__('Erreur modification garantie. Merci de réessayer'));
        }
     


    
    }

    public function documents($id = null){
        $garantie = $this->Garanties->get($id);

      $this->set(compact('garantie'));

    }

    /**
     * Delete method
     *
     * @param string|null $id Garanty id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {

       
   
        //  $this->request->allowMethod(['post', 'delete']);
    $garantie = $this->Garanties->get($id);
  
   
   $date =date('Y-m-d H:i:s');


   $message = $garantie;// since the values of  garanty will change



   if(!$this->deleteRelated($id)){


    $this->Flash->error(__('Erreur suppression de la garantie, car il a des evaluations actives'));

    return $this->redirect(["controller"=> "Garanties", "action"=> "view",$garantie->id]);   
}

$this->set(compact("garantie"));
// $this->request->getSession()->delete('referercopy');

$referer = $this->request->getSession()->read('referercopy');

// If referer is not set (first access), store it
if (!$referer) {
    $referer = $this->request->referer();
    $this->request->getSession()->write('referercopy', $referer);
}
// debug($referer);
if ($this->request->is(['patch', 'post', 'put'])) { // set the raison 
    $garantie = $this->Garanties->patchEntity($garantie, $this->request->getData());
    if ($this->Garanties->delete($garantie)) {
        $this->Flash->success(__('Le garantie ' . $message['numero'] . ' (' . $message['libelle_garantie'] . ') a été supprimé(e) avec succès'));
        if ($referer) {
            // Clear the session variable once used, if needed
            $this->request->getSession()->delete('referercopy');
            // debug($referer);

            return $this->redirect($referer);
        } else {

           
            return $this->redirect(['controller' => 'Garanties', 'action' => 'index']);
        }
    } else {
        $this->Flash->error(__('Erreur suppression de la garantie. Merci de réessayer'));
    }

}
    }
    public function raison($id = null)
    {
      
        $garanty = $this->Garanties->get($id, [
            'contain' => [],
        ]);
        

      
        $this->set(compact('garanty'));

    }


    public function deleteRelated($id)  // willl now return boolean to determine whether the garanty has evaluatuions 
        {

            

            $this->loadModel('Evaluations');



            
        $garantiesIds = $this->Evaluations->find()
        ->where(['garantie_id' => $id, 'del' => false])
        ->extract('id')
        ->toArray();

        if(!empty($garantiesIds)){
            return false;

           
        }


        return true;


    
    }

    public function dashboard(){
        $garanty = $this->Garanties->get($id, [
            'contain' => ['Typologies', 'Clients', 'Garants'],
        ]);

        $this->set(compact('garanty'));


    }

    public function viewDocument($id = null, $filename = null)
    {
        $garanty = $this->Garanties->get($id);
    
        if ($filename && !empty($garanty->documents)) {
            $documents = json_decode($garanty->documents, true);
            foreach ($documents as $document) {
                if ($document === $filename) {
                    $file = WWW_ROOT . 'uploads' . DS . $filename;
    
                    $response = $this->response->withFile($file, [
                        'download' => true,
                        'name' => $filename,
                    ]);
                    return $response;
                }
            }
            $this->Flash->error('Document not found.');
        } else {
            $this->Flash->error('Document not found.');
        }
    
        return $this->redirect(['action' => 'index']);
    }
    

}
