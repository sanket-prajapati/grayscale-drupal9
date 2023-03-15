<?php

namespace Drupal\practice_module\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Query\AlterableInterface;
//For editor access controll
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface; 

class PracticeContoller extends ControllerBase{

    public function databaseApi(){
        
        // $cache = \Drupal::cache();
        // dump($cache);
        // exit;
        
        // $values = [
        //     ['name' => 'Novak D.', 'data' => serialize(['sport' =>
        //     'tennis'])],
        //     ['name' => 'Michael P.', 'data' => serialize(['sport' =>
        //     'swimming'])],
        // ];
        // $query1=\Drupal::database()->insert('players')
        //   -> fields(['name','data']);

        // foreach($values as $valueInsert){
        //     $query1->values($valueInsert);
        // }
        // $results = $query1->execute();

        // exit;
        // return;

        // update query
        $database=\Drupal::database();
        $results1 = $database->update('players')
            ->fields(['data' => serialize([
            'sport' => 'swimming',
            'feature' => 'This guy can swim'
            ])])
            ->condition('name', 'Michael P.')
            ->execute();
        
        $results2= $database->select('players','p')
          ->fields('p')
          ->execute()->fetchAll();
        
        echo "<pre>";
        print_r($results2);
        // exit;

        // Transaction
        
        $transaction = $database->startTransaction();
        try {
        $database->update('players')
        ->fields(['data' => serialize(['sport' => 'tennis',
        'feature' => 'This guy can tennis', "new added"=> 'new2'])])
        ->condition('name', 'Novak D.')
        ->execute();
        }
        
        catch (\Exception $e) {
        $transaction->rollback();
        watchdog_exception('my_type', $e);
        }
        
    }

    public function ckeckUser(){
        global $user;
        $user_id=$user->uid; //to get the user id
        $user_name=$user->name; //to get the user name
        echo $user_id;
        echo $user_name;
        exit; 
    }

    public function loadUser(){
        $accountProxy = \Drupal::currentUser(); 
        dump($accountProxy);
        $user = \Drupal::entityTypeManager()
            ->getStorage('user')
            ->load($accountProxy->id());
        dump($user);
        dump($user->name);

        $account = $accountProxy->getAccount(); 
        dump($account);
        // exit;
        $checkUser = $account->isAnonymous();
        dump($checkUser);//false
        $checkUser = $account->isAuthenticated(); 
        dump($checkUser);//true
        dump($account->name);
        $assignRoles=$account->getRoles();
        dump($assignRoles);
        // array:2 [▼
        //   0 => "authenticated"//It is bydefault roles for every user
        //   1 => "administrator"
        // ]
        // array:2 [▼
        //     0 => "authenticated"
        //     1 => "teachers"
        // ]
        $permission= 'access content';
        $checkPermission=$account->hasPermission($permission);
        dump($checkPermission);//true

        // return in_array('teachers', $account->getRoles()) ? AccessResult::forbidden() : AccessResult::allowed();
        // exit;
        // if(in_array('teachers', $account->getRoles())){
        //     return AccessResult::forbidden('Editors are not allowed');
        // }
        exit;

    }

     /**
    * Handles the access checking.
    *
    * @param \Drupal\Core\Session\AccountInterface $account
    *
    * @return \Drupal\Core\Access\AccessResultInterface
    * @param \Symfony\Component\Routing\Route $route
    * @param \Drupal\Core\Routing\RouteMatch $route_match
    */
    public function access(AccountInterface $account, Route $route) {
        dump($route);
        $user_types = $route->getOption('_user_types');
        dump($user_types);
        dump($account); 
        // exit;
        return in_array('teachers', $account->getRoles()) ? AccessResult::forbidden() : AccessResult::allowed();
    } 
    // public function access(Route $route, Request $request, AccountInterface $account) {
    //     dump($route);
    //     dump($account);
    //     exit;
    //     $permission = $route->getRequirement('_permission');
    //     // @todo Replace user_access() with a correctly injected and session-using
    //     //   alternative.
    //     // If user_access() fails, return NULL to give other checks a chance.
    //     return $account->hasPermission($permission) ? static::ALLOW : static::DENY;
    // }

    public function imgStayle(){
        // $factory = \Drupal::service('image.factory');
        // dump($factory);
        // // $image = $factory->get($uri); 
        // // $image = $factory->get('fruit.png'); 
        // // $image = $factory->get('sites/default/files/fruit.png'); 
        // $image = $factory->get('/sites/default/files/2022-12/ipad.png'); 
        // dump($image);
        
        // $image->scale(50, 50);
        // // $image->setDisplayOptions('view', array(
        // //     'type' => 'image',
        // //     'weight' => 10,
        // //     'settings' => [
        // //       'image_style' => 'large'
        // //     ]
        // // ));
        // $image->save('public://thumbnail.jpg') ;

        // return [
        //     '#theme' => 'image',
        //     '#uri' => 'public://image.jpg',
        // ]; 

        $style = \Drupal::entityTypeManager()->getStorage('image_style')->load('thumbnail');
        // dump($style);
        $url = $style->buildUrl('public://fruit.jpg');
        // $url = $style->buildUrl('fruit.jpg');  //not works
        // dump($url);//works

        return [
            '#theme' => 'image',
            // '#uri' => 'public://image.jpg',
            '#uri' => $url,
        ]; 
    }
}
