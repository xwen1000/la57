<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;

class Down
{
	protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    protected function script()
    {
        return <<<SCRIPT

$('.fa-arrow-circle-down').on('click', function () {
	var host = location.host;
	var url = "http://"+ host +"/admin/tables/down/"+$(this).data('id');
    window.open(url);
});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a href='javascript:void(0);'><i class='fa fa-download' data-id='{$this->id}'></i></a>";
    }

     public function __toString()
    {
        return $this->render();
    }

}