<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PurchaseRepository;
use App\Repositories\ProductionRepository;
use App\Repositories\SaleRepository;
use App\Repositories\AccountRepository;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Show the user dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(
        PurchaseRepository $purchaseRepo,
        ProductionRepository $productionRepo,
        SaleRepository $saleRepo,
        AccountRepository $accountRepo
    ){
        $purchaseCount      = $purchaseRepo->getPurchases()->count();
        $productionCount    = $productionRepo->getProductions()->count();
        $saleCount          = $saleRepo->getsales()->count();
        $accountCount       = $accountRepo->getaccounts()->count();

        return view('users.dashboard', compact('purchaseCount', 'productionCount', 'saleCount', 'accountCount'));
    }
}
