<?php

namespace Libraries\Tables;

use Appendix\Core\I18n;

class ControlsBuilder
{
	private $controls = [];

	public function add($action, $uri, $modal = NULL)
	{
		if (empty($action) AND is_null($action))
			return;

		if ($action == 'edit')
		{
			$this->controls[] = sprintf(
				'<a href="%s" class="btn btn-primary" 
					title="' . I18n::load("app.controls.edit") . '"
				>
					<i class="fa fa-pen"></i>
				</a>',
				(!empty($uri)) ? $uri : '#'
			);
		}

		if ($action == 'remove')
		{
			$this->controls[] = sprintf(
				'<a href="%s" class="btn btn-primary alert-delete-button" 
					data-confirm="'.I18n::load("users.delete.modal.confirm").'" 
					data-cancel="' . I18n::load("users.delete.modal.cancel") . '" 
					data-title="'.I18n::load("users.delete.modal.title").'"
					title="' . I18n::load("app.controls.delete") . '"
				>
					<i class="fa fa-trash"></i>
				</a>',
				(!empty($uri)) ? $uri : '#'
			);
		}
	}

	public function data()
	{
		return sprintf("<div class='text-nowrap'>%s</div>", implode(" ", $this->controls));
	}
}
