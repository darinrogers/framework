<?php

namespace Framework;

abstract class AdminController extends Controller
{
	protected function getCalledControllerName()
	{
		// forward slash because we only use this to find the view file name
		return 'Admin/' . parent::getCalledControllerName();
	}
}