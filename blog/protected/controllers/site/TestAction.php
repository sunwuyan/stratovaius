<?php

	class TestAction extends CAction
	{   
		public function test()
		{
			p("hello world!!!");
		}
		public function run()
		{
			$method='test';
			$this->$method();
		}
	}