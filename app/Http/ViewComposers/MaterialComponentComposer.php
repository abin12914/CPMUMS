<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\MaterialRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class MaterialComponentComposer
{
    protected $materials =[], $errorHead = null;

    /**
     * Create a new materials partial composer.
     *
     * @param  MaterialRepository  $materialRepo
     * @return void
     */
    public function __construct(MaterialRepository $materialRepo)
    {
        $this->errorHead    = config('settings.composer_code.MaterialComponentComposer');
        $errorCode          = 0;

        try {
            $this->materials = $materialRepo->getMaterials();
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
        $view->with(['materialsCombo' => $this->materials]);
    }
}