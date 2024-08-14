<?php
declare(strict_types=1);

namespace App\Controller;
use Cake\I18n\FrozenTime;

/**
 * Evaluateurs Controller
 *
 * @property \App\Model\Table\EvaluateursTable $Evaluateurs
 * @method \App\Model\Entity\Evaluateur[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EvaluateursController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

     

    
    public function restore($id){

        $evaluateur = $this->Evaluateurs->get($id);
        $length = strlen((string)$id) ; // decide how many characters we are removing 
        
        $email = $evaluateur->email;
        $tel = $evaluateur->tel;   
        
        $email = substr($email,0, strlen($email) - $length);   // we remove the id appending
        $tel = substr($tel,0, strlen($tel) - $length);    
        if($this->valueExists("Evaluateurs","email",$email)){
       $email = null;

        }
        if($this->valueExists("Evaluateurs","tel",$tel)){
       
            $tel = null;   

        }
        
        $this->set(compact('email', 'tel', 'evaluateur', 'id')); 
        if ($this->request->is(['patch', 'post', 'put'])) {
            $evaluateur = $this->Evaluateurs->patchEntity($evaluateur, $this->request->getData());
            $evaluateur->del = 0;
            $evaluateur->date_r = FrozenTime::now();
            $evaluateur->restored_by = $_SESSION["user_id"];
            if ($this->Evaluateurs->save($evaluateur, true, array( 'nom_prenom', 'tel', 'email', 'fonction_evaluateur','nom_structure'))) {
                $this->Flash->success(__("L'evaluateur(rice) ".$evaluateur['nom_prenom'].' a été restoré(e) avec succès'));

                return $this->redirect(['action' => 'deleted']);
            }
            $this->Flash->error(__("Erreur restoration evaluateur(rice). Merci de réessayer"));
        }
        
        }
        
    public function deleted()
    {

        $query = $this->Evaluateurs->find("deleted");

        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $conditions = [
                'OR' => [
                    ['tel LIKE' => '%' . $search . '%'],
                    ['email LIKE' => '%' . $search . '%'],
                    ['nom_prenom LIKE' => '%' . $search . '%'],
                    ['nom_structure LIKE' => '%' . $search . '%'],
                    ['fonction_evaluateur LIKE' => '%' . $search . '%'],

                  
                ]
            ];
      
         
      
            $query->where($conditions);
        }
      
      
      
              $evaluateurs = $this->paginate($query);

        $this->set(compact('evaluateurs'));
        
    }

    
    public function index()
    {

        $query = $this->Evaluateurs->find("active");

        $search = $this->request->getQuery('search');
        if (!empty($search)) {
            $conditions = [
                'OR' => [
                    ['tel LIKE' => '%' . $search . '%'],
                    ['email LIKE' => '%' . $search . '%'],
                    ['nom_prenom LIKE' => '%' . $search . '%'],
                    ['nom_structure LIKE' => '%' . $search . '%'],
                    ['fonction_evaluateur LIKE' => '%' . $search . '%'],

                  
                ]
            ];
      
         
      
            $query->where($conditions);
        }
      
      
      
              $evaluateurs = $this->paginate($query);

        $this->set(compact('evaluateurs'));
    }

    /**
     * View method
     *
     * @param string|null $id Evaluateur id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel(('Users'));
        $USERS = $this->Users;
        $this->set(compact('USERS'));
        $evaluateur = $this->Evaluateurs->get($id, [
            'contain' => ['Evaluations'],
        ]);


        $filteredEvaluations = array_filter($evaluateur->evaluations, function($evaluation) {
            return $evaluation->del == 0;
        });
        
        $evaluateur->evaluations = $filteredEvaluations; // Assign filtered garanties back to client object
        
        

        $this->set(compact('evaluateur'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $evaluateur = $this->Evaluateurs->newEmptyEntity();
        if ($this->request->is('post')) {
            $evaluateur = $this->Evaluateurs->patchEntity($evaluateur, $this->request->getData());
            if ($this->Evaluateurs->save($evaluateur, true, array( 'nom_prenom', 'tel', 'email', 'fonction_evaluateur','nom_structure'))) {
                $this->Flash->success(__("L'evaluateur(rice) ".$evaluateur['nom_prenom'].' a été ajouté(e) avec succès'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__("Erreur ajout evaluateur(rice). Merci de réessayer"));
        }
        $this->set(compact('evaluateur'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Evaluateur id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $evaluateur = $this->Evaluateurs->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $evaluateur = $this->Evaluateurs->patchEntity($evaluateur, $this->request->getData());
            if ($this->Evaluateurs->save($evaluateur, true, array( 'nom_prenom', 'tel', 'email', 'fonction_evaluateur','nom_structure'))) {
                $this->Flash->success(__("L'evaluateur(rice) ".$evaluateur['nom_prenom'].' a été modifié(e) avec succès'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__("Erreur modification evaluateur(rice). Merci de réessayer"));
        }
        $this->set(compact('evaluateur'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Evaluateur id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $evaluateur = $this->Evaluateurs->get($id);
        $message = $evaluateur;
        if(!$this->deleteRelated($id)){

            $this->Flash->error(__("Erreur suppression évaluateur(rice), car il/elle a des evaluations actives"));
            return $this->redirect(["controller"=> "Evaluateurs", "action"=> "view",$evaluateur->id]);   

        }
         // free up number and email for future evaluators
        if ($this->Evaluateurs->delete($evaluateur)) {

            $this->Flash->success(__("L'evaluateur(rice) ".$message['nom_prenom'].' a été supprimé(e) avec succès'));
        } else {
            $this->Flash->error(__("Erreur suppression évaluateur(rice). Merci de réessayer"));
        }

        return $this->redirect(['action' => 'index']);
    }


    public function deleteRelated($id){
        {
            $this->loadModel('Evaluations');
       
            

        




        
    $garantiesIds = $this->Evaluations->find()
    ->where(['evaluateur_id' => $id, 'del' => false])
    ->extract('id')
    ->toArray();

    if(!empty($garantiesIds)){
        return false;

       
    }


    return true;




        }


    
}

}
