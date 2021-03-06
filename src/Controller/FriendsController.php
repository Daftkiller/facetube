<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Friends Controller
 *
 * @property \App\Model\Table\FriendsTable $Friends
 *
 * @method \App\Model\Entity\Friend[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FriendsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Senders', 'Receivers']
        ];
        $friends = $this->paginate($this->Friends);

        $this->set(compact('friends'));
    }

    /**
     * View method
     *
     * @param string|null $id Friend id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $friend = $this->Friends->get($id, [
            'contain' => ['Senders', 'Receivers']
        ]);

        $this->set('friend', $friend);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $friend = $this->Friends->newEntity();
        if ($this->request->is('post')) {
            $friend = $this->Friends->patchEntity($friend, $this->request->getData());
            if ($this->Friends->save($friend)) {
                $this->Flash->success(__('The friend has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The friend could not be saved. Please, try again.'));
        }
        $senders = $this->Friends->Senders->find('list', ['limit' => 200]);
        $receivers = $this->Friends->Receivers->find('list', ['limit' => 200]);
        $this->set(compact('friend', 'senders', 'receivers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Friend id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $friend = $this->Friends->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $friend = $this->Friends->patchEntity($friend, $this->request->getData());
            if ($this->Friends->save($friend)) {
                $this->Flash->success(__('The friend has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The friend could not be saved. Please, try again.'));
        }
        $senders = $this->Friends->Senders->find('list', ['limit' => 200]);
        $receivers = $this->Friends->Receivers->find('list', ['limit' => 200]);
        $this->set(compact('friend', 'senders', 'receivers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Friend id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $friend = $this->Friends->get($id);
        if ($this->Friends->delete($friend)) {
            $this->Flash->success(__('The friend has been deleted.'));
        } else {
            $this->Flash->error(__('The friend could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
