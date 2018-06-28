<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\ServiceRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class ServiceComponentComposer
{
    protected $services =[], $errorHead = null;

    /**
     * Create a new services partial composer.
     *
     * @param  ServiceRepository  $serviceRepo
     * @return void
     */
    public function __construct(ServiceRepository $serviceRepo)
    {
        $this->errorHead    = config('settings.composer_code.ServiceComponentComposer');
        $errorCode          = 0;

        try {
            $this->services = $serviceRepo->getServices();
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
        $view->with(['servicesCombo' => $this->services]);
    }
}