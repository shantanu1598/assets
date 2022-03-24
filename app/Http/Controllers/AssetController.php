<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Asset;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class AssetController extends Controller
{
    /**
     * 
     *  @param \Illuminate\Http\Resquest
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
//       return $request;
      return Asset::create($request->all());
    } 
    

    /**
     * @param \Illuminate\Http\Resquest
     *@return \Illuminate\Http\Response
     * 
     */
    public function verifyUser(Request $request) {
      $empId=$request->empId;
      $employeeData=$this->searchUser($empId);

      $otp=$this->generateNumericOTP();
     


      if(count($employeeData)==1)
      {
        $personalMobileNo=$employeeData[0]->personalMobileNo;

        $equenceResponse=$this->equneceApiCall($personalMobileNo,$otp);

        if($equenceResponse=='success')
        {
        return response()->json([
                        'employeeName' => $employeeData[0]->employeeName,
                        'email' => $employeeData[0]->officeEmail,
                        'empId' => $employeeData[0]->employeeID,
                        'city' => $employeeData[0]->physicalLocation,
                        'department'=> $employeeData[0]->department,
                        'otp' =>$otp                                    
                        ]);
        }

        else{
          return response()->json([
            'message'=>'Unable to send OTP'
                                                 
             ]);
        }
        
      }

      else 
      {
        return response()->json([
                       'message'=>'Invalid Employee Code OR Employee Is Not Active User'
                                                            
                        ]);

        
      }
    


    }

 

    public function equneceApiCall($personalMobileNo,$otp)
    {
      $mobileNumber=$personalMobileNo;
      $otp=$otp;
   
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.equence.in/pushsms',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "username": "recruitment",
            "password": "aeGQ-52-",
            "peId": "1001900184535850000",
            "tmplId": "1007276557498698249",
            "to": '.$personalMobileNo.',
            "from": "AHFLCO",
            "charset": "UTF-16",
            "text": "Use OTP '.$otp.' for authenticating your contact no. with Aadhar Housing Finance. Valid for 30 Mins only."
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response)->response[0]->status;
    }




    public function generateNumericOTP() {
      
        // Take a generator string which consist of
        // all numeric digits
        $generator = "1357902468";
        $n=6;
      
        // Iterate for n-times and pick a single character
        // from generator and append it to $result
          
        // Login for generating a random character from generator
        //     ---generate a random number
        //     ---take modulus of same with length of generator (say i)
        //     ---append the character at place (i) from generator to result
      
        $result = "";
      
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand()%(strlen($generator))), 1);
        }
      
        // Return result
        return $result;
    }

    public function searchUser($empId)
    {
     
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://app13.workline.hr/api/WL/WS_EmployeeDetails',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
          'APPID: EmployeeData',
          'EmpId: '.$empId,
          'Authorization: Basic QUhGTDpBaEZsQDEyMw==',
          'Content-Length: 0'
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);

     
      return json_decode($response);
    }
}
