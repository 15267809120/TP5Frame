<?php
namespace app\admin\controller;

use app\admin\controller\Base;
use app\admin\logic\Index as IndexLogic;
use think\Session;

class Index extends Base
{
	public function __construct(){
		parent::__construct();
		$this->IndexL = new IndexLogic();
	}

    public function index(){
    	// $this->IndexL->getUserLog();
    	// $this->IndexL->getUser();
        return $this->fetch();
    }
}
