<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PurchaseRepository;
use App\Repositories\ProductionRepository;
use App\Repositories\SaleRepository;
use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
use App\Http\Requests\ProfileUpdateRequest;

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

    /**
     * Return view for user profile
     */
    public function profileView()
    {
        return view('users.profile');
    }

    /**
     * action for user profile update
     */
    public function profileUpdate(ProfileUpdateRequest $request, UserRepository $userRepo)
    {
        $inputArray['username']         = $request->get('username');
        $inputArray['name']             = $request->get('name');
        $inputArray['email']            = $request->get('email');
        $inputArray['password']         = $request->get('password');
        $inputArray['currentPassword']  = $request->get('currentPassword');

        $flag = $userRepo->updateProfile($request);

        if($flag['flag']) {
            return redirect(route('dashboard'))->with("message", "Profile Successfully Updated!")->with("alert-class", "success");
        }
        return redirect()->back()->with("message", "Profile Update failed! Error Code : ". $flag['error'])->with("alert-class", "error");
    }
}
