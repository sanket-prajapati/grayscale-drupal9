<?php

namespace Drupal\player_details\Controller;
use Drupal\Core\Controller\ControllerBase;

class PlayerController extends ControllerBase{
  public function check(){
    $data='This is the data';
    return [
      // '#markup'=> '<h2>This is checking controller</h2>',
      '#theme' => 'test',
      '#title' => 'Checking Controller with twig',
      '#data' => $data,
    ];
  }

  public function createPlayer(){

    $formData = \Drupal:: formBuilder()-> getForm('Drupal\player_details\Form\PlayerForm');

    return [
      '#theme' => 'player-form',
      '#title' => 'Player Form',
      '#formData' => $formData,
    ];
  }

  public function getPlayer(){
    // Before get Data clear cache for proper record getting
    // \Drupal::service("router.builder")->rebuild();
    // \Drupal::service('cache.render')->invalidateAll();

    $limit=3;
    $query= \Drupal:: database()-> select('player','p')
      ->fields('p')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit($limit);
    $results=$query->execute();

    $count=0;
    // $parameter=$_GET['page'];
    $parameter= \Drupal::request() -> query->All();
    // print_r($parameter);
    // exit;
    // $parameter = \Drupal::request()-> query->get('page');
    // echo $parameter;
    // exit;
    if(empty($parameter) || $parameter['page']==0){
      $count=1;
    }
    elseif($parameter==1){
      $count=$parameter['page']+$limit;
    }
    else{
      $count=$parameter['page']*$limit;
      $count=$count+1;
    }

    foreach($results as $rows){
      $data[]= [
        's_no'=> $count,
        'name' => $rows->name,
        'gender' => $rows->gender,
        'phone' => $rows->phone,
        'address' => $rows->address,
        'edit' => t("<a href='edit-player/$rows->id'>Edit</a>"),
        'delete' => t("<a href='delete-player/$rows->id'>Delete</a>"),
      ];
      // $count++;
      $count=$count+1;
    }
    // echo '<pre>';
    // print_r($data);
    // exit;

    $header=['S.No','Name', 'Gender', 'Phone', 'Address','Edit','Delete'];

    $buildTable['table']=[
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $data,
    ];

    $buildTable['pager']=[
      '#type' => 'pager',
    ];

    return [
      $buildTable,
      '#title' => 'Player Lists',
    ];

    // return [
    //   '#type'=> 'table',
    //   '#title'=> 'Players List',
    //   '#header'=>$header,
    //   '#rows' => $data,
    // ];
  }

  public function deletePlayer(){

    $id=\Drupal:: routeMatch()->getParameter('id');
    
    \Drupal::database()->delete('player')
      ->condition('id',$id)
      ->execute();

    \Drupal::service('cache.render')->invalidateAll();
    \Drupal::service("router.builder")->rebuild();

    $response = new \Symfony\Component\HttpFoundation\RedirectResponse('../player-list');
    $response->send();
    
    \Drupal:: Messenger()->addMessage(t('Your record deleted successfully'));
    // \Drupal:: Messenger()->addMessage(t('Your record deleted successfully'),'state', TRUE);

  }

  public function listRender(){
    $items=[
      'item-1',
      'item-2',
      'item-3',
    ];
    return [
      // '#theme'=>'list-page',
      // '#item' => $items,
      // By theme name item-list directly without twig
      '#theme' => 'item_list',
      // '#list_type' => 'ol', comment this if you required unorder list
      '#items' => $items,
      '#title' => 'List Item by Controller',
    ];
  }

  public function linksRender(){
    $links = [
      [
      'title' => 'Link 1',
      // 'url' => Url::fromRoute('<front>'),
      ],
      [
      'title' => 'Link 1',
      // 'url' => Url::fromRoute('player_details.getPlayer'),
      ]
     ];

     return [
      '#theme' => 'links',
      '#links' => $links,
      '#set_active_class' => true,
     ];
  }

  public function setAttributeBytheme(){

    /** @var \Drupal\Core\TempStore\PrivateTempStoreFactory $factory */
    $factory = \Drupal::service('tempstore.private');
    $store = $factory->get('player_details.my_collection');
    $store->set('my_key', 'my_value');
    $value = $store->get('my_key');
    echo $value;
    $store->delete('my_key'); 
    $value2 = $store->get('my_key');
    echo $value2;
    exit;

    $attributes = [
      'id' => 'my_id',
      'class' => ['class-one', 'class-two'],
      'data-custom' => 'my custom data value'
    ];
    return [
      '#theme' => 'attribute-ckeck',
      '#attributes' => $attributes,
      '#title' => 'Check attributes in tag',
    ];
  }

  public function entityApi(){
    // Quering entities or building entities
    $query = \Drupal::entityTypeManager()->getStorage('node')->getQuery(); 
    $query->condition('type', 'article')
      ->condition('status', TRUE)
      ->range(0, 10)
      ->sort('created', 'DESC');
    $ids = $query->execute();
    echo "<pre>";
    print_r($ids);
    // exit;

    // Loading entities
    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($ids); 
    // $nodes = \Drupal::entityTypeManager()->getStorage('node')->load($ids);
    // $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['type' => 'article']);

    // Reading Entity
    echo "<pre>";
    print_r($nodes);//works
    // $names = $nodes->get('body')->getValue();//not works
    $data=[];
    foreach($nodes as $key => $node){
      $data[$key]['title']=$node->get('title')->getValue();
      
      $data[$key]['body']=$node->get('body')->getValue()[0]['value'];
      $data[$key]['tags']=$node->get('field_tags')->getValue()[0]['value'];;
    }

    dump($data);
    // echo $data[0]['value'];
    echo $data[6]['title'][0]['value'];
    print_r($data);
    // exit;

    // Manipulating entities
    // $nodes->set('title', 'new title');//not works
    // foreach($nodes as $key => $node){
    //   $data[$key]['title']=$node->set('title', 'new title');
    // }
    // echo "<pre>";
    // print_r($data);
    // exit;

    // Creating entities
    // Not works
    // $values = [
    //   'type' => 'article',
    //   'title' => 'My title'
    //  ];
    //  /** @var \Drupal\node\NodeInterface $node */
    //  $node2 = \Drupal::entityTypeManager()->getStorage('node')->create($values);
    //  $node2->set('field_custom', 'some text');
    //  $node2->save();

    //  print_r($node2);
    // Createing entity over
    
    // exit;
    // Please make this exit uncomment and go to routing path in crome

    // Reading entities
    /** @var \Drupal\node\Entity\NodeType $type */
    $type = \Drupal::entityTypeManager()->getStorage('node_type')->load('article');
     echo "<pre>";
    // print_r($type);//works
    // exit;

    // If content entity type
    $description = $type->getDescription();
    //If configuration entity type
    // $description = $type->get('description');
    // echo "<pre>";
    // print_r($description);//works
    // exit;

    $id = $type->id();
    $label = $type->label();
    $uuid = $type->uuid();
    $bundle = $type->bundle();
    $language = $type->language();
    echo $id."<br/>";
    // echo $label."<br/>";
    echo $uuid."<br/>";
    // echo $bundle."<br/>";
    // echo $desc;
    // echo $language."<br/>";
    // exit;

    /** @var \Drupal\node\NodeInterface $node */
    // $node = Node::load(8);//not works
    // $node8 = Node::load($ids);
    $node8= \Drupal\node\Entity\Node::load($id);//works
    /** @var \Drupal\Core\Field\FieldItemListInterface $title */
    // $title = $node8->get('title');//not works call to member function get on null
    // echo $title;

    // $names = $nodes->get('body')->getValue();
    // $tags = $nodes->get('field_tags')->referencedEntities();


    // Creating entity
    $values = [
      'type' => 'article',
      'title' => 'My title'
     ];
     print_r($values);
     /** @var \Drupal\node\NodeInterface $node */
     $node = \Drupal::entityTypeManager()->getStorage('node')->create($values);
     $node->set('field_custom', 'some text');
     $node->save();
     

    //  exit;

  }

  public function databaseCheck(){
    $database2 = \Drupal::database();
    // $results2 = $database2->query("SELECT * FROM {player} WHERE [id]= :id", [':id' => 1]);
    $results2 = $database2->query("select * from {player} where [name]=:name", [':name' => 'Mohit']);

    // Second way to fetch data
    $results3 = $database2-> select('player','p')
      ->fields('p')
      ->condition('id',1)
      ->execute();
      // ->execute()->fetchAll();

    // Handing Results
      // 1. by fetch as associative array or by fetch as object
      // FetchAssoc() or FetchAll()
    
      //1. Fetch as associative array
      while($rows3=$results3->fetchAssoc()){
        $name3=$rows3['name'];
      }
      echo $name3."<br/>";

      // 2.Fetch as object
      $data2=[];
      foreach($results2 as $rows2){
        // By storing in associate array as key and value
        $data2[]= [
          'name' => $rows2->name,
          'gender' => $rows2->gender,
          'phone' => $rows2->phone,
          'address' => $rows2->address,
        ];
        // You can also store in variable
        $name =$rows2->name;
        $address= $rows2->address;

      }

      echo "$name and adress is $address";


      echo '<pre>';
      print_r($data2);
      // exit;
    // Handing Results Over
  
    // Join in query
    // $result = $database->query("SELECT * FROM {players} p JOIN
    //   {teams} t ON t.[id] = p.[team_id] WHERE p.[id] = :id", [':id'=> 1]);
    
    // $results4 = \Drupal::database() -> query("select * from {Employees} e join {Department} d on e.[id]=d.[id]");
    $results4 = \Drupal::database() -> query("select * from {Employees} e join {Department} d on e.[id]=d.[id] where e.id=:id", [':id'=>1212]);

    // $records9 = $results4->fetchAllAssoc('Ename');
    // echo $records;//Not works 
    // print_r($records9);//works but whole array get

    $data4=[];
    foreach($results4 as $rows4){
      $data4[]=[
        'ename'=>$rows4->Ename,
        'address' => $rows4->address,
        'departId' => $rows4->departID,
        'designation' => $rows4->designation,

      ];
    }

    echo '<pre>';
    print_r($data4);

    // Complex query
    // $database->query("SELECT p.[id], p.[name] as player_name, t.[name] as team_name, t.[description] as team_description, p.[data] FROM {players} p JOIN {teams} t ON t.[id] = p.[team_id] WHERE p.[id] = :id", [':id' => 1])->fetchAll();

    $results5= \Drupal::database()->query("SELECT e.[id], e.[Ename] as employee_name, d.[departID] as department_No, d.[designation] as designation from {Employees} e join {Department} d on e.[id]=d.[id] where e.[id]=:id", [':id'=>1212])->fetchAll();

    echo "<pre>";
    print_r($results5);//get array
    print_r($results5[0]);//get object
    echo $results5[0]->employee_name;//Its works for me get object property  
    // exit;
   
    $query6 = \Drupal::database()->select('Employees', 'e');
    $query6->join('Department', 'd');
    $query6->addField('e', 'Ename', 'employee_name');
    $query6->addField('d', 'designation', 'designation');
    $query6->addField('d', 'departID', 'depart_Id');
    $result6 = $query6
    // ->fields('e', ['id', 'Ename'])
    // ->fields('e', ['id'])
    // ->condition('e.id', 1212)
    ->execute();
    $records6 = $result6->fetchAll();

    // echo "<pre>";
    // print_r($records6);//Not works properly it records repeate in loop
    // echo "<br/>".$records6[0]->employee_name;

    // Range queries
    // $results7 = \Drupal::database()->queryRange("SELECT * FROM {player}", 0,8);//Works but output is different for that fetchAll() use below

    // $results7 = \Drupal::database()->queryRange("SELECT * FROM {player}", 0,8)->fetchAll();
    // By query Builder
    // $results7 = \Drupal::database()->select('player', 'p')
    //   ->fields('p')
    //   ->range(0, 10)
    //   ->execute()->fetchAll();

    echo "<pre>";
    print_r($results7);
    print_r($results7[2]);
    print_r($results7[2]->name);

    // $transaction = \Drupal::database()->startTransaction();
    // try{
      $results= \Drupal::database()->update('player')
      ->fields(['name'=>'Axay23', 'phone'=>82424242])
      ->condition('name','Axay')
      ->execute();
    // }
    // catch(\Exception $e) {
    //   $transaction->rollback();
    //   watchdog_exception('my_type', $e);
    // }//not works 

    //Without hook_query_alter simple 
    $results11 = \Drupal::database()->select('players', 'p')
      ->fields('p')
      ->execute()->fetchAll();
    print_r($results11);
    

    //With hook_query_alter 
    $results10 = \Drupal::database()->select('players', 'p')
      ->fields('p')
      ->addTag('player_query')
      ->execute()->fetchAll();
    print_r($results10);//works
    // With hook_query_alter
    //Record get With condition and join which is added to hook_query_alter
    // Array
    //   (
    //   [0] => stdClass Object
    //       (
    //           [id] => 3
    //           [team_id] => 3
    //           [name] => Michael P.
    //           [data] => a:2:{s:5:"sport";s:8:"swimming";s:7:"feature";s:17:"This guy can swim";}
    //           [team_name] => My Team
    //       )
    //   )
    dump($results10);

    // exit;

  
  }

  public function insertOperation(){

    // Insert Query
    // Insert multiple value

    $valueInsert=[
      ['name'=>'Mahek92','gender'=>'Female','phone'=>92324244,'address'=>'92 Bhumi apartment, Anand',
      ],
      ['name'=>'Yogesh23','gender'=>'Male','phone'=>9232423284,'address'=>'98 Bhumi apartment, Mahesana',
      ],
    ];
    $query9=\Drupal:: database()-> insert('player')
      -> fields(['name','gender','phone','address']);
    
    foreach($valueInsert as $valueHere){
      $query9->values($valueHere);
    }
    $query9-> execute();//works

    //Second way of multiple insertion record
    $query8=\Drupal:: database()-> insert('player')
      -> fields(['name','gender','phone','address']);
    $query8-> values([
        'Mahek2',
        'Male',
        923242938,
        '92 Bhumi apartment, Surat',
      ])
      ->execute();//works
    $query8->values([
        'Yogesh',
        'Male',
        923242324,
        '91 Bhumi apartment, Surat',
      ])
      ->execute();//works 

    
    // exit;
  }


}