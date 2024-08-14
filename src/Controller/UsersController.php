<?php
declare(strict_types=1);

namespace App\Controller;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\I18n\FrozenTime;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['login']); 
        $this->getRequest()->getSession()->start();   // on commence la session direct apres le login ca regles le beug 

         
    }

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            //$_SESSION['Menu']
            // debug($_SESSION);

            $_SESSION['user'] = $this->Authentication->getIdentity()->nom_prenom;
            // debug($_SESSION['user']);
            $_SESSION['user_id'] = $this->Authentication->getIdentity()->id;
            $_SESSION['user_creation'] = $this->Authentication->getIdentity()->created;
            $_SESSION['role'] = $this->Authentication->getIdentity()->fonction;

            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'garanties',
                'action' => 'index',
            ]);
        return $this->redirect($redirect);
        }
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Votre identifiant ou votre mot de passe est incorrect'));
        }
    }

    public function index()
    {
       $query = $this->Users->find('active');
        $users = $this->paginate($query);

        $this->set(compact('users'));

    }


    public function confirmRestore ($id) {
        if ($this->request->is("post")) {
        $evaluateur = $this->Evaluateurs->get($id);
    
        $evaluateur = $this->Evaluateurs->patchEntity($evaluateur, $this->request->getData());


        if ($this->Evaluateurs->save($evaluateur)) {
            $this->Flash->success(__("L'evaluateur(rice) ".$evaluateur['nom_prenom'].' a été restoré(e) avec succès'));
            return $this->redirect(['action' => 'deleted']);

            }
            else{
            $this->Flash->error(__("Erreur restoration evaluateur(rice). Merci de réessayer"));
        }


    }


    }
    public function restore($id){

        $user = $this->Users->get($id);
        $length = strlen((string)$id) +3; // decide how many characters we are removing(the id and the DEL which would be three characters )
        
        $email = $user->email;
        $mat = $user->matricule;   
        
        $email = substr($email,0, strlen($email) - $length);   // we remove the id appending
        $mat = substr($mat,0, strlen($mat) - $length);    
        if($this->valueExists("Users","email",$email)){
       $email = null;

        }
        if($this->valueExists("Users","matricule",$mat)){
       
            $mat = null;   

        }
        
        $this->set(compact('email', 'mat', 'user', 'id')); 
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->del = 0;
            $user->date_r = FrozenTime::now();
            $user->restored_by = $_SESSION["user_id"];


            if ($this->Users->save($user, true, array('email','nom_prenom','password','modified','fonction','matricule'))) {
                $this->Flash->success(__("L'Utilisateur ".$user['nom_prenom'].' a été restoré(e) avec succès'));

                return $this->redirect(['action' => 'deleted']);
            }
            $this->Flash->error(__("Erreur restoration d'utilisateur. Merci de réessayer"));
        }
        
        }
    
    public function deleted()
    {
       $query = $this->Users->find('deleted');
        $users = $this->paginate($query);

        $this->set(compact('users'));

    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $USERS = $this->Users;
        $this->set(compact('USERS'));
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if($_SESSION['role'] != 'ADMIN'){
            $this->redirect(['controller' => 'Users', 'action' => 'index']);
           return  $this->Flash->error(__('Access refuser'));
           }
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $hasher = new DefaultPasswordHasher();
            $user->password = $hasher->hash($user->password); 
            if ($this->Users->save($user, true, array('email','nom_prenom','password','created','fonction','matricule'))) {
                $this->Flash->success(__('Compte '.$user['email'].' créé avec succès'));
 
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Erreur création compte. Merci de réessayer'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if($_SESSION['role'] != 'ADMIN' && $id != $_SESSION['user_id']){
            $this->redirect(['controller' => 'Users', 'action' => 'index']);
           return  $this->Flash->error(__('Access refuser'));
           }
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        $oldpass = $user->password;
        if ($this->request->is(['patch', 'post', 'put'])) {
         
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if($oldpass !=$this->request->getData()['password'] ){
                $user = $this->Users->patchEntity($user, $this->request->getData());  // need to rehash
                $hasher = new DefaultPasswordHasher();
                $user->password = $hasher->hash($user->password); 
            }
            if ($this->Users->save($user, true, array('email','nom_prenom','password','modified','fonction','matricule'))) {
                $this->Flash->success(__('Compte '.$user['email'].' modifié avec succès'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Erreur modification compte. Merci de réessayer'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if($_SESSION['role'] != 'ADMIN'){
            $this->redirect(['controller' => 'Users', 'action' => 'index']);
           return  $this->Flash->error(__('Access refuser'));
           }
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        $message = $user;  
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('Le Compte '.$message['email'].' a été supprimé avec succès'));
        } else {
            $this->Flash->error(__('Erreur suppression compte. Merci de réessayer'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function logout()
    {
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $this->Authentication->logout();
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }
}
