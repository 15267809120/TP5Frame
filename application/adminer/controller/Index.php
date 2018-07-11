<?php
namespace app\adminer\controller;

use app\adminer\controller\Base;
use app\adminer\logic\Index as IndexLogic;
use think\Session;

class Index extends Base
{
	public function __construct(){
		parent::__construct();
		$this->IndexL = new IndexLogic();
	}

    public function index(){

        return $this->fetch();
    }
}
