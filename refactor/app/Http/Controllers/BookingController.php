<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;
use Exception;
use Log;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{

    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {

        try{
            if($request->__authenticatedUser->user_type == env('ADMIN_ROLE_ID') || $request->__authenticatedUser->user_type == env('SUPERADMIN_ROLE_ID')) $user = $this->repository->getAll($request);
            else $user = $this->repository->getUsersJobs($request->user_id);
            if(!$user) throw new Exception('No data found',404);  //if we get null from user repo then this will throw exception
            return response()->json([
                'message' => 'success',
                'data' => $user
            ]);

        }catch(\Exception $e){

            Log::debug($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getcode()
            ]);
        }
       
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {

        try{
            $job = $this->repository->with('translatorJobRel.user')->find($id);
            if(!$job) throw new Exception('No data found',404);  //if we get null from user repo then this will throw exception
            return response()->json([
                'message' => 'success',
                'data' => $job
            ]);

        }catch(\Exception $e){

            Log::debug($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getcode()
            ]);
        }
        
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {

        try{
            // you should avoid getting all the data from request because of malicious data can be put by some hackers so it is better to fetch desired and needed objects from request.
            $user = $this->repository->store($request->__authenticatedUser,$request->all());
            if(!$user) throw new Exception('Unable to store data',500);  //if data does not stored this will throw exception
            return response()->json([
                'message' => 'success',
                'data' => $user
            ]);

        }catch(\Exception $e){

            Log::debug($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getcode()
            ]);
        }
        

    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {

        try{
             $user = $this->repository->updateJob($id, array_except($request->all(), ['_token', 'submit']), $request->__authenticatedUser);
             if(!$user) throw new Exception('Unable to update data',500);  //if data does not update this will throw exception
            return response()->json([
                'message' => 'success',
                'data' => $user
            ]);

        }catch(\Exception $e){

            Log::debug($e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getcode()
            ]);
        }


        
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request)
    {

        try{
            $jobEmail = $this->repository->storeJobEmail($request->all());
            if(!$jobEmail) throw new Exception('Unable to store email',500);  //if data does not store this will throw exception
           return response()->json([
               'message' => 'success',
               'data' => $jobEmail
           ]);

       }catch(\Exception $e){

           Log::debug($e->getMessage());
           return response()->json([
               'message' => $e->getMessage(),
               'code' => $e->getcode()
           ]);
       }
       
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request)
    {
        try{
           
            if($request->has('user_id')) $usersJobHistory = $this->repository->getUsersJobsHistory($request->user_id, $request);
            else throw new Exception('Please provide User Id',400); 
            if(!$usersJobHistory) throw new Exception('no data found',404);  //if data does not exist this will throw exception
           return response()->json([
               'message' => 'success',
               'data' => $usersJobHistory
           ]);

       }catch(\Exception $e){

           Log::debug($e->getMessage());
           return response()->json([
               'message' => $e->getMessage(),
               'code' => $e->getcode()
           ]);
       }
        
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request)
    {
        try{

        $acceptJob = $this->repository->acceptJob($request->all(), $request->__authenticatedUser);
        if(!$acceptJob) throw new Exception('job not accepted due to some reason',500);  // this will throw exception
           return response()->json([
               'message' => 'success',
               'data' => $acceptJob
           ]);

       }catch(\Exception $e){

           Log::debug($e->getMessage());
           return response()->json([
               'message' => $e->getMessage(),
               'code' => $e->getcode()
           ]);
       }
       
    }

    public function acceptJobWithId(Request $request)
    {
        try{

            $acceptJobId = $this->repository->acceptJobWithId($request->job_id, $request->__authenticatedUser);
            if(!$acceptJobId) throw new Exception('job not accepted due to some reason',500);  // this will throw exception
               return response()->json([
                   'message' => 'success',
                   'data' => $acceptJobId
               ]);
    
           }catch(\Exception $e){
    
               Log::debug($e->getMessage());
               return response()->json([
                   'message' => $e->getMessage(),
                   'code' => $e->getcode()
               ]);
       
            }
       
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request)
    {

        try{

            $cancelJob = $this->repository->cancelJobAjax($request->all(), $request->__authenticatedUser);
            if(!$cancelJob) throw new Exception('job not cancel due to some reason',500);  // this will throw exception
               return response()->json([
                   'message' => 'success',
                   'data' => $cancelJob
               ]);
    
           }catch(\Exception $e){
    
               Log::debug($e->getMessage());
               return response()->json([
                   'message' => $e->getMessage(),
                   'code' => $e->getcode()
               ]);

        }
       
            
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Request $request)
    {

        try{

            $endJob =  $this->repository->endJob($request->all());
            if(!$endJob) throw new Exception('job not ended due to some reason',500);  // this will throw exception
               return response()->json([
                   'message' => 'success',
                   'data' => $endJob
               ]);
    
           }catch(\Exception $e){
    
               Log::debug($e->getMessage());
               return response()->json([
                   'message' => $e->getMessage(),
                   'code' => $e->getcode()
               ]);

        }

    }

    public function customerNotCall(Request $request)
    {
        try{

            $customernotCall = $this->repository->customerNotCall($request->all());
            if(!$customernotCall) throw new Exception('not proceeded due to some reason',500);  // this will throw exception
            return response()->json([
                   'message' => 'success',
                   'data' => $customernotCall
               ]);
    
        }catch(\Exception $e){
    
               Log::debug($e->getMessage());
               return response()->json([
                   'message' => $e->getMessage(),
                   'code' => $e->getcode()
               ]);

        }

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(Request $request)
    {

        try{

            $getPotentialJobs = $this->repository->getPotentialJobs($request->__authenticatedUser);
            if(!$getPotentialJobs) throw new Exception('not proceeded due to some reason',500);  // this will throw exception
            return response()->json([
                   'message' => 'success',
                   'data' => $getPotentialJobs
               ]);
    
        }catch(\Exception $e){
    
               Log::debug($e->getMessage());
               return response()->json([
                   'message' => $e->getMessage(),
                   'code' => $e->getcode()
               ]);

        }
    }

    public function distanceFeed(Request $request)
    {

        
        try{

            $input = $request->only('distance','time','jobid','session_time','flagged','manually_handled','by_admin','admincomment');
        if($input['flagged']){
            if(!$input['admincomment']) throw new Exception('Please add Comment');
        }
        $input['flagged'] = ($input['flagged'] == true)? 'yes':'no';
        $input['manually_handled'] = ($input['manually_handled'] == true)? 'yes':'no';  
        $input['by_admin'] = ($input['by_admin'] == true)? 'yes':'no';
        if(!$input['jobid']) throw new Exception('provide job Id'); 
        $affectedRows = Distance::where('job_id', '=', $input['jobid'])->update($input);
        $affectedRows1 = Job::where('id', '=', $input['jobid'])->update($input);
            return response()->json([
                   'message' => 'success',
                   'data' => $affectedRows
               ]);
    
        }catch(\Exception $e){
    
               Log::debug($e->getMessage());
               return response()->json([
                   'message' => $e->getMessage(),
                   'code' => $e->getcode()
               ]);

        }
    }

    public function reopen(Request $request)
    {

        try{ 

            $reopen = $this->repository->reopen($request->all());
            if(!$reopen) throw new Exception('not proceeded due to some reason',500);  // this will throw exception
            return response()->json([
                   'message' => 'success',
                   'data' => $reopen
               ]);
    
        }catch(\Exception $e){
    
               Log::debug($e->getMessage());
               return response()->json([
                   'message' => $e->getMessage(),
                   'code' => $e->getcode()
               ]);

        }
       
    }

    public function resendNotifications(Request $request)
    {


        try{ 

            $job = $this->repository->find($request->jobid);
            $job_data = $this->repository->jobToData($job);
            $send =$this->repository->sendNotificationTranslator($job, $job_data, '*');
            if(!$send) throw new Exception('something went wrong not sent notification',500);  // this will throw exception
            return response()->json([
                   'message' => 'success',
                   'data' => 'push has been sent'
               ]);
    
        }catch(\Exception $e){
    
               Log::debug($e->getMessage());
               return response()->json([
                   'message' => $e->getMessage(),
                   'code' => $e->getcode()
               ]);

        }
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Request $request)
    {
       
    
        try {
            $job = $this->repository->find($request['jobid']);
            $this->repository->sendSMSNotificationToTranslator($job);
            return response(['success' => 'SMS sent']);
        } catch (\Exception $e) {
            return response(['success' => $e->getMessage()]);
        }
    }

}