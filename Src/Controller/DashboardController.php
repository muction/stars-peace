<?php
namespace Stars\Peace\Controller;

use Stars\Peace\Service\DashboardService;

class DashboardController extends PeaceController
{
    /**
     *  rotate dashboard
     * @param DashboardService $dashboardService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard(  DashboardService $dashboardService )
    {

        $attachmentTotal = $dashboardService->attachmentTotal();
        $adminTotal = $dashboardService->adminTotal();
        //$messageTotal = $dashboardService->messageTotal();
        return $this->view('dashboard.simple' , ['attachmentTotal' =>$attachmentTotal , 'adminTotal'=>$adminTotal ]);
    }
}
