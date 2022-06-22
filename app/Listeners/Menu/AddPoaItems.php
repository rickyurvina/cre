<?php

namespace App\Listeners\Menu;

use App\Events\Menu\PoaCreated;

class AddPoaItems
{
    /**
     * Handle the event.
     *
     * @param  $event
     *
     * @return void
     */
    public function handle(PoaCreated $event)
    {
        // Set Current Module
        session(['module' => trans('general.module_poa')]);

        $menu = $event->menu;
        $user = user();

        // Home
//        $menu->add(trans('general.start'), ['route' => 'poa.home'])
//            ->append('</span>')
//            ->prepend('<i class="fal fa-globe"></i> <span class="nav-link-text">')
//            ->link->attr(['title' => trans('general.start'), 'data-filter-tags' => strtolower(trans('general.start'))]);

        // POAs
        $menu->add(trans('general.poa'), ['route' => 'poa.poas'])
            ->append('</span>')
            ->prepend('<i class="fal fa-tasks"></i> <span class="nav-link-text">')
            ->link->attr(['title' => trans('general.poa'), 'data-filter-tags' => strtolower(trans('general.start'))]);

        // INDICATORS GOAL CHANGE REQUEST
        /*$menu->add(strtoupper(trans('general.poa_requests')), ['route' => 'poa.goal_change_request'])
            ->append('</span>')
            ->prepend('<i class="fal fa-file-signature"></i> <span class="nav-link-text">')
            ->link->attr(['title' => trans('general.poa'), 'data-filter-tags' => strtolower(trans('general.start'))]);*/

        // POAs REPORTS
        $menu->add(trans('general.poa_report'), ['route' => 'poa.reports'])
            ->append('</span>')
            ->prepend('<i class="ni ni-bar-chart"></i> <span class="nav-link-text">')
            ->link->attr(['title' => trans('general.poa_report'), 'data-filter-tags' => strtolower(trans('general.poa_report'))]);

        // POAs CONTROL DE CAMBIOS
        $menu->add(trans('general.change_control'), ['route' => 'poa.change_control'])
            ->append('</span>')
            ->prepend('<i class="fal fa-exchange"></i> <span class="nav-link-text">')
            ->link->attr(['title' => trans('general.change_control'), 'data-filter-tags' => strtolower(trans('general.change_control'))]);

        // POAs CARD REPORTS
        $menu->add(trans('general.card_report'), ['route' => 'poa.reports.index'])
            ->append('</span>')
            ->prepend('<i class="fal fa-table"></i> <span class="nav-link-text">')
            ->link->attr(['title' => trans('general.card_report'), 'data-filter-tags' => strtolower(trans('general.card_report'))]);

        if (session('company_id')===1){
            $menu->add(trans('general.poa_requests'), ['route' => 'poa.goal_change_request'])
                ->append('</span>')
                ->prepend('<i class="fal fa-ballot"></i> <span class="nav-link-text">')
                ->link->attr(['title' => trans('general.poa_requests'), 'data-filter-tags' => strtolower(trans('general.poa_requests'))]);
        }

        //CATALOG ACTIVITIES
        $menu->add(trans('general.poa_activities_catalogs'), ['route' => 'poa.manage_catalog_activities'])
                ->append('</span>')
                ->prepend('<i class="fal fa-book"></i> <span class="nav-link-text">')
                ->link->attr(['title' => trans('general.poa_activities_catalogs'), 'data-filter-tags' => strtolower(trans('general.poa_activities_catalogs'))]);

    }
}
