<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hmo;
use App\Models\Order;
use App\Models\Batch;
use Carbon\Carbon;
use Mail;

class OrderController extends Controller
{
    /**
 * success response method.
 *
 * @return \Illuminate\Http\Response
 */
    public function orderSubmit(Request $request)
    {
        $data = $request->all();

        $hmoData = [
          'code' => $data['hmo']['code'],
          'name' => $data['hmo']['name'],
        ];

        //create hmo if it does not exist
        $hmo = Hmo::firstOrCreate(
            ['code' => $hmoData['code']],
            $hmoData
        );

        //create batch
        $batchData =[
          'hmo_id'=>$hmo->id,
          'edate' => Carbon::parse($data['edate']),
        ];
        $batch = Batch::create($batchData);

        //store each order
        foreach ($data['orders'] as $order) {
            $orderData = [
          'hmo_id'=>$hmo->id,
          'batch_id'=>$batch->id,
          'items' => json_encode($order),
          ];
            $order = Order::create($orderData);
        }

        //send mail
        $mailData = [
          'name'=>$hmo->name,
          'email'=>$hmo->email,
          'subject'=>"Your order has been sent",
          'body'=> "Total orders submitted " . $data['total'],
        ];

        Mail::send('mail/order', $mailData, function ($message) use ($mailData) {
            $message->to($mailData['email'], $mailData['name'])->subject($mailData['subject']);
            $message->from('fullstack@site.com', 'FullStack Test');
        });

        return response()->json(["message"=>"Order submitted successfully"],200);
    }
}
