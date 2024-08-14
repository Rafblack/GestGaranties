<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenTime;

/**
 * Evaluations Controller
 *
 * @property \App\Model\Table\EvaluationsTable $Evaluations
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EvaluationsController extends AppController
{

    

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */


     public function restore($id){
$this->loadModel("Evaluateurs");
$this->loadModel("Garanties");

        $evaluation = $this->Evaluations->get($id);
        $garantie = $this->Garanties->get($evaluation->garantie_id);
       
        $evaluateur = $this->Evaluateurs->get($evaluation->evaluateur_id); // we need to check if its evaluateur is active bfore deciding to restore
        if($evaluateur->del != 0){
        
        $this->Flash->error(__("Impossible de restorer l'evaluation"." car son evaluateur est supprimer, veuillez d'avord restorer son evaluateur"));
        
        return $this->redirect([
            'controller' => 'evaluateurs',
            'action' => 'deleted',
            '?' => [
                'search' => $evaluateur->tel,// on recherche l' evaluateur en meme temps comma ca lutilisateur peut le restorer aussi 
            ]
        ]);
        }

        if($garantie->del != 0){
        
            $this->Flash->error(__("Impossible de restorer l'evaluation"." car sa garantie est supprimer, veuillez d'avord restorer la garantie"));
            
            return $this->redirect([
                'controller' => 'garanties',
                'action' => 'deleted',
                '?' => [
                    'search' => $garantie->numero,// on recherche l' evaluateur en meme temps comma ca lutilisateur peut le restorer aussi 
                ]
            ]);
            }
    



        
        
        $evaluation->del = 0;
        $evaluation->date_r = FrozenTime::now();
        $evaluation->restored_by = $_SESSION["user_id"];


        if($this->Evaluations->save($evaluation)){
       $this->Flash->success(__("Evaluation restore"));


        }
        else{
            $this->Flash->error(__("Veuillez ressayer"));
        }

return $this->redirect(["action"=> "deleted"]);   
        }
        
    
     public function deleted()
     {
         $this->paginate = [
             'contain' => ['Evaluateurs', 'Garanties'],
         ];
 
 
         $query = $this->Evaluations->find("deleted");
 
         $search = $this->request->getQuery('search');
         if (!empty($search)) {
             $conditions = [
                 'OR' => [
                     ['frequence LIKE' => '%' . $search . '%'],
                     ['valeur_garantie LIKE' => '%' . $search . '%'],
                     ['Evaluations.date_fin LIKE' => '%' . $search . '%'],
                     ['Evaluations.date_debut LIKE' => '%' . $search . '%'],
                     ['Evaluations.currency LIKE' => '%' . $search . '%'],
 
                   
                 ]
             ];
       
          
       
             $query->where($conditions);
         }
       
       
       
               $evaluations = $this->paginate($query);
         $this->set(compact('evaluations'));
     }
 
    public function index()
    {
        $this->paginate = [
            'contain' => ['Evaluateurs', 'Garanties'],
        ];


        $query = $this->Evaluations->find("active");

        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $conditions = [
                'OR' => [
                    ['frequence LIKE' => '%' . $search . '%'],
                    ['valeur_garantie LIKE' => '%' . $search . '%'],
                    ['Evaluations.date_fin LIKE' => '%' . $search . '%'],
                    ['Evaluations.date_debut LIKE' => '%' . $search . '%'],
                    ['Evaluations.currency LIKE' => '%' . $search . '%'],

                  
                ]
            ];
      
         
      
            $query->where($conditions);
        }
      
      
      
              $evaluations = $this->paginate($query);
        $this->set(compact('evaluations'));
    }

    /**
     * View method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel(('Users'));
        $USERS = $this->Users;
        $this->set(compact('USERS'));
        $evaluation = $this->Evaluations->get($id, [
            'contain' => ['Evaluateurs', 'Garanties'],
        ]);

        $this->set(compact('evaluation'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $evaluation = $this->Evaluations->newEmptyEntity();

        $evaluateurs = $this->Evaluations->Evaluateurs->find('list', ['limit' => 1000000])->all();
        $garanties = $this->Evaluations->Garanties->find('all', [
            'fields' => ['numero', 'libelle_garantie'],
            'limit' => 1000000
        ])->all();  
              $this->set(compact('evaluation', 'evaluateurs', 'garanties'));
        if ($this->request->is('post')) {
            $evaluation = $this->Evaluations->patchEntity($evaluation, $this->request->getData());
            
            $data = $this->request->getdata(); 
            if($data['garantie_id'] == null || $data['garantie_id'] == 'no'){
                $this->Flash->error(__('Numero Garantie Invalid'));


                return;
            }
            $this->loadModel('Evaluateurs');
          $person =  $this->Evaluateurs->find()->
            where(['id'=> $data['evaluateur_id'],])->first();
            if ($this->Evaluations->save($evaluation,true, array( 'frequence', 'valeur_garantie', 'date_fin', 'evaluateur_id','garantie_id'))) {
                $this->Flash->success(__("L'évaluation sur Garantie " . $data['Numero']. " : ". $data['libelle_garantie']." a été enregistrée avec succès par L'evaluateur ". $person->nom_prenom));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Erreur Enregistrement évaluation. Merci de réessayer'));
        }
       

    }

    /**
     * Edit method
     *
     * @param string|null $id Evaluation id.
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
        }  // afin to redirect a page ancienne au lieu de lindex
        $evaluation = $this->Evaluations->get($id, [
            'contain' => [],
        ]);
       
        if ($this->request->is(['patch', 'post', 'put'])) {
            $evaluation = $this->Evaluations->patchEntity($evaluation, $this->request->getData());

            if ($this->Evaluations->save($evaluation,true, array( 'frequence', 'valeur_garantie', 'date_fin', 'evaluateur_id','garantie_id'))) {
                $this->Flash->success(__("L'évaluation a été modifiée avec succès"));

                if ($referer) {
                    // Clear the session variable once used, if needed
                    $this->request->getSession()->delete('referercopy');
            
                    return $this->redirect($referer);
                } else {
                   
                    return $this->redirect(['controller' => 'Garanties', 'action' => 'index']);
                }            }
            $this->Flash->error(__('Erreur enregistrement des modifications. Merci de réessayer'));
        }
        $evaluateurs = $this->Evaluations->Evaluateurs->find('list', ['limit' => 200])->all();
        $garanties = $this->Evaluations->Garanties->find('list', ['limit' => 200])->all();
        $this->set(compact('evaluation', 'evaluateurs', 'garanties'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $referer = $this->request->getSession()->read('referercopy');

    // If referer is not set (first access), store it
    if (!$referer) {
        $referer = $this->request->referer();
        $this->request->getSession()->write('referercopy', $referer);
    } // afin to redirect a page ancienne au lieu de lindex
        $this->request->allowMethod(['post', 'delete']);
        $evaluation = $this->Evaluations->get($id);
        if ($this->Evaluations->delete($evaluation)) {
            $this->Flash->success(__('Evaluation supprimée avec succès'));
        } else {
            $this->Flash->error(__("Erreur lors de la suppression de l'évaluation. Merci de réessayer" ));
        }

        if ($referer) {
            // Clear the session variable once used, if needed
            $this->request->getSession()->delete('referercopy');
    
            return $this->redirect($referer);
        } else {
           
            return $this->redirect(['controller' => 'Garanties', 'action' => 'index']);
        }    }
}
