<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\AdldapInterface;
use App\Models\Unit;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $ldap;
    
    /**
     * Constructor.
     *
     * @param AdldapInterface $adldap
     */


    public function __construct(AdldapInterface $ldap)
    {
        $this->ldap = $ldap;
    }


    public function getADusers() : mixed {

        // $search->select(['cn', 'samaccountname', 'telephone', 'mail']);
        $results = $this->ldap->search()->where('department', '=', 'information technology') // Filter users by department
        ->whereHas('mail') // Ensure users have an email attribute
        ->get(['name', 'mail', 'department']);

        $users = [];

        foreach ($results as $user) {
            $res['name'] = $user->name[0];
            $res['email'] = $user->mail[0];
            $res['dept'] = $user->department[0];

            

            array_push($users, $res);
        }

        return $this->successResponseWithData($users);
        
    }

    public function getUnitHeads(): Response {
        
        $unitHeads = Unit::whereNotNull('Unit Head Email')->whereNotNull('Unit Head Name')->get(['Unit', 'Unit Head Name', 'Unit Head Email']);

        return $this->successResponseWithData($unitHeads);
    }

    public function searchUsers(Request $request) : Response {  
        $searchKey = $request->search;
       $result =  $this->ldap->search()->where('samaccountname', 'starts_with', $searchKey)
       ->whereHas('mail') // Ensure users have an email attribute
       ->get(['name', 'mail', 'department']);

        if(!$result){
            return $this->errorResponse('No record found');
        }

        $users =[];

        foreach ($result as $data) {
            
            $res['name'] = $data->name[0];
            $res['email'] = $data->mail[0];

            array_push($users, $res);
        }


        return $this->successResponseWithData($users);
    }

   
}
