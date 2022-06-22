<?php

namespace App\Events\Menu;

use Illuminate\Queue\SerializesModels;

class BudgetCreated
{
    use SerializesModels;

    /**
     * @var $menu
     */
    public $menu;

    /**
     * Create a new event instance.
     *
     * @param $menu
     */
    public function __construct($menu)
    {
        $this->menu = $menu;
    }
}