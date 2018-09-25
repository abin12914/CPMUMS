<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Http\Requests\ProductRegistrationRequest;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    protected $productRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo           = $productRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.ProductController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('products.list', [
                'products'      => $this->productRepo->getProducts([], null),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRegistrationRequest $request)
    {
        $saveFlag       = false;
        $errorCode      = 0;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $response   = $this->productRepo->saveProduct([
                'name'                      => $request->get('product_name'),
                'hsn_code'                  => $request->get('hsn_code'),
                'uom_code'                  => strtoupper($request->get('uom_code')),
                'description'               => $request->get('description'),
                'rate'                      => $request->get('rate'),
                'loading_charge_per_piece'  => $request->get('loading_charge_per_piece'),
            ]);

            if(!$response['flag']) {
                throw new AppCustomException("CustomError", $accountResponse['errorCode']);
            }

            DB::commit();
            $saveFlag = true;
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }
        }

        if($saveFlag) {
            return redirect(route('product.index'))->with("message","Product details saved successfully. Reference Number : ". $response['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to save the product details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('products.details', [
            'product' => $this->productRepo->getProduct($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('products.edit', [
            'product'       => $this->productRepo->getProduct($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRegistrationRequest $request, $id)
    {
        $saveFlag   = false;
        $errorCode  = 0;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //get product
            $product = $this->productRepo->getProduct($id);

            $response = $this->productRepo->saveProduct([
                'name'                      => $request->get('product_name'),
                'hsn_code'                  => $request->get('hsn_code'),
                'uom_code'                  => strtoupper($request->get('uom_code')),
                'description'               => $request->get('description'),
                'rate'                      => $request->get('rate'),
                'loading_charge_per_piece'  => $request->get('loading_charge_per_piece'),
            ], $product);

            if(!$response['flag']) {
                throw new AppCustomException("CustomError", $accountResponse['errorCode']);
            }

            DB::commit();
            $saveFlag = true;
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }
        }

        if($saveFlag) {
            return redirect(route('product.index'))->with("message","Product details updated successfully. Updated Record Number : ". $response['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the product details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect()->back()->with("message", "Product deletion restricted.")->with("alert-class", "error");
    }
}
