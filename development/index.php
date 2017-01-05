<?php

	@include_once('Libs/startup.php');
	
	Nette_DebugAdapter::Init(MvcCore::GetEnvironment() == 'development');

	MvcCore::Run(1);