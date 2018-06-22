<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\BranchRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class BranchComponentComposer
{
    protected $branches = [], $errorHead = null;

    /**
     * Create a new branches partial composer.
     *
     * @param  BranchRepository  $branches
     * @return void
     */
    public function __construct(BranchRepository $branchRepo)
    {
        $errorCode          = 0;
        $this->errorHead    = config('settings.composer_code.BranchComponentComposer');

        try {
            $this->branches = $branchRepo->getBranches();
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
        $view->with(['branchesCombo' => $this->branches]);
    }
}