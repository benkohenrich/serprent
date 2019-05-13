<?php

namespace Libraries\Tables;

use Appendix\Core\I18n;

class ControlsBuilder
{
	private $controls = [];

	public function add($action, $uri, $additional_data = [])
	{
		if (empty($action) AND is_null($action))
			return;

		if ($action == 'edit')
		{
			$this->controls[] = sprintf(
				'<a href="%s" class="btn btn-primary btn-sm" 
					title="' . I18n::load("app.controls.edit") . '"
				>
					<i class="fa fa-pen"></i>
				</a>',
				(!empty($uri)) ? $uri : '#'
			);
		}

		if ($action == 'remove')
		{
			$confirm_message = !empty($additional_data['confirm_message'])
				? $additional_data['confirm_message'] : '';
			$this->controls[] = sprintf(
				'<a href="%s" class="btn btn-primary confirm-button btn-sm" 
					title="' . I18n::load("app.controls.delete") . '"
					data-confirm-message="' . sprintf("%s %s", I18n::load('app.plugins.toastr.confirm.delete'), $confirm_message) . '?"
					data-success-event="confirmDeleteEvent"
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
