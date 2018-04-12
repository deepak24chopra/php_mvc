<?php


class Home extends Controller {

  public function __construct($controller, $action) {
    parent::__construct($controller, $action);
  }

  public function indexAction() {
    $db = DB::get_instance();
    $contacts = $db->find_first('sandbox',[
      'conditions' => ['first=?','last=?'],
      'bind' => ['deepak','parham'],
      
    ]);
    dnd($contacts);
    $this->view->render('home/index');
  }

}
