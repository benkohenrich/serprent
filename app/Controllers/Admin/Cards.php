<?php

namespace Controllers\Admin;

use Appendix\Core\I18n;
use Appendix\Core\Router;
use Appendix\Libraries\Input;
use Controllers\Admin;
use Appendix\Exceptions\PageNotFound;

class Cards extends Admin
{
	/**
	 * Users constructor.
	 * @throws PageNotFound
	 * @throws \Exceptions\SystemException
	 */
	public function __construct()
	{
		parent::__construct();

		if (!$this->check_permission('cards.management') AND !Input::is_ajax_request())
			throw new PageNotFound;
	}

	public function overview()
	{
		if (Input::is_ajax_request())
		{
			$this->view->set_file(FALSE);
		}
		else
		{
			$this->view->register([
				'breadcrumbs' 				=> [
					[
						'title' 			=> I18n::load('cards.overview.breadcrumbs.title'),
						'url' 				=> Router::uri([ 'cards' ])
					],
				],
				'page_title' => I18n::load('cards.overview.breadcrumbs.header')
			]);
		}
	}
}