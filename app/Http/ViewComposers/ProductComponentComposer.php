<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\ProductRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class ProductComponentComposer
{
    protected $products =[], $errorHead = null;

    /**
     * Create a new product partial composer.
     *
     * @param  ProductRepository  $productRepo
     * @return void
     */
    public function __construct(ProductRepository $productRepo)
    {
        $this->errorHead    = config('settings.composer_code.ProductComponentComposer');
        $errorCode          = 0;

        try {
            $this->products = $productRepo->getProducts();
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }
            
            //throw new AppCustomException("CustomError", ($this->errorHead + $errorCode));
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['productsCombo' => $this->products]);
    }
}