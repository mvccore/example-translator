<?php

	@include_once('/Libs/startup.php');
	@Nette_DebugAdapter::Init(TRUE);
	MvcCore::Run(1);