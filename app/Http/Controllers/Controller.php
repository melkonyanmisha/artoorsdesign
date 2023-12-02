<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Traits\Notification;
use App\Models\User;
use \App\Models\Comment;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Notification;

    function store_notif(Request $request){
        $user = \App\Models\User::find(auth()->id());
        $user->notification_send  = $request->notification_send ==  "true";
        $user->save();
    }

    public function send_notid($astx){
        $this->createSystemNotification(\App\Models\User::find(1),'Review ' . $astx->astx,'https://artoorsdesign.com/review/product-list');
    }

    public function delete_chat(Request $request){
        $messages  = \App\Models\Message::where([['from_id' ,'=', auth()->id()],['to_id',"=",$request->id]])->orwhere([['to_id' ,'=', auth()->id()],['from_id',"=",$request->id]])->get();

        foreach ($messages as $message){
            $message->delete();
        }
    }

    public function block_user(Request $request){
        \App\Models\Block_user::create($request->all());
    }

    public function un_block_user(Request $request){
        \App\Models\Block_user::find($request->id)->delete();
    }

    public function delete_notif(Request $request){
        \Modules\OrderManage\Entities\CustomerNotification::find($request->id)->delete();
    }

    public function find_user(Request $request){
        $to_user = \App\Models\User::find($request->id);

        return view('mainInclude', compact('to_user'));
    }

    public function ok($items = null)
    {
    return response()->json($items)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }

    public function idramFail(Request $request)
    {

        $payment = Payment::orderBY('id', 'desc')->where('order_id', $request->EDP_BILL_NO)->where('status', 'panding')->where('gateway', 'idram')->first();
        $payment_order = OrderPayments::orderBY('id', 'desc')->where('order_id', $request->EDP_BILL_NO)->where('status', 'panding')->where('gateway', 'idram')->first();
        $payment_order_buy_for_your = BuyforPay::orderBY('id', 'desc')->where('buy_for_you_id', $request->EDP_BILL_NO)->where('status', 'panding')->where('gateway', 'BuyForYourIdram')->first();


        if (!is_null($payment)) {
            $payment->update([
                'status' => 'fail',
            ]);
            return redirect()->route('fill.balance', ['username' => auth()->user()->username])->with('messageError', 'arca.error');
        } else if (!is_null($payment_order)) {
            $payment_order->update([
                'status' => 'fail',
            ]);

            return redirect()->route('order')->withInput(['order_id' => $request->EDP_BILL_NO,])->with('messageError', 'arca.error');
        } else if (!is_null($payment_order_buy_for_your)) {
            $payment_order_buy_for_your->update([
                'status' => 'fail',
            ]);

            return redirect()->route('transaction.show', $request->EDP_BILL_NO)->withInput(['order_id' => $request->EDP_BILL_NO,])->with('messageError', 'arca.error');
        } else {
            return abort(404);
        }

    }


    public function minchev_download()
    {
        return view('downloadForm')->render();
    }

    public function download_files()
    {
        $pdf = \session()->get('pdf');
        \session()->put('exav',$pdf);
        \session()->forget('pdf');
        return response()->download(public_path($pdf));
    }


    public function arcaCreate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            // 'gateway' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['fail' => true, 'message' => '']);
        }

        $order_id = substr(preg_replace('/[^0-9]/', '', md5(microtime())), 0, 8);
        $test = \App\Models\Payment::where('order_id', $order_id)->get();

        if ($test->count() > 0) {
            $order_id = substr(preg_replace('/[^0-9]/', '', md5(microtime())), 0, 8);
        }
        $order_desc = 'Order for filling balance of user (username: ' . \Auth::user()->username . ').';
        $cdp_price = $request->amount;
        $amount = $cdp_price;

        $data = [
//            'gateway' => 'arca',
            'user_id' => \Auth::id(),
            'Amount' => $amount,
            'order_id' => $order_id,//$order_id,
            'status' => 'panding',
            'created_at' =>  Carbon::now()->toDateTimeString(),
            'description' => $order_desc,

        ];


        $create = \App\Models\Payment::create($data);


        $lang = \App::getLocale();
        $arrayArca = [
            "ClientID" => '11690e40-c297-409c-97ca-f20e27939504',
            'Amount' => $amount,
            'OrderID' => $order_id,//$order_id
            'language' => 'en',
            'Username' => '19532139_api',
            'Password' => 'hf43BX3uPSTug0op',
            'Description' => $order_desc,
            'BackURL' => route('arca.result'),
            'Currency' => '840'
        ];

        $server_output = Http::post('https://services.ameriabank.am/VPOS/api/VPOS/InitPayment',$arrayArca)->body();

        //errorCode
        $status = [];

        $status = json_decode($server_output);

        if ($status->ResponseCode == 1) {
            $orderId = $status->PaymentID;
            $form = view('payment', compact( 'orderId'))->render();
        }else{
            return response()->json(['Ameria Response Error']);
        }

        if ($create) {
            return response()->json(['fail' => false, 'form' => $form]);
        }
    }

    public function arcaInfo(Request $request)
    {
        $lang = \App::getLocale();

        $arrayArca = [
            'PaymentID' => $request->all()['paymentID'],
            'Username' => '19532139_api',
            'Password' => 'hf43BX3uPSTug0op',
        ];
//        dd($request->all());
        $server_output = Http::post('https://services.ameriabank.am/VPOS/api/VPOS/GetPaymentDetails',$arrayArca)->body();
        $status = json_decode($server_output);
        if ($status->ResponseCode == '00') {
            $payMant = \App\Models\Payment::where('order_id', $status->OrderID)->where('status', 'panding')->first();
            if (!empty($payMant)) {

                $payMant->update([
                    'status' => 'Approved'//$status->ErrorMessage
                ]);


                return redirect()->route('frontend.checkout',[
                    'step'=>'select_payment',
                    'shipping_method'=>1,
                    'payment_id' => 1,
                    'gateway_id' => 1
                ]);
            }


        } else {

            return redirect('/profile?a=purchases')->with('messageError', 'Error');
        }


    }



    public function store_comment(Request $request):JsonResponse{
        $request->validate([
            'text' => 'required',
        ]);
        $request['created_at'] = Carbon::now()->toDateTimeString();

        $comment = Comment::create($request->all());

        $superAdminUsers = User::where('is_active', 1)->whereHas('role', function ($query) {
            return $query->where('type', 'superadmin');
        })->get();

        foreach ($superAdminUsers as $currentUser) {
            $this->createSystemNotification($currentUser,'Comment','/comment/admin?id='.$comment->id);
        }

        return response()->json(['success' => true, 'message' => 'Review created successfully']);
    }

    public function delete_comment(Request $request){

        $comments = Comment::where('to_user_id',$request->id)->pluck('id')->toArray();
        Comment::destroy($comments);
        Comment::find($request->id)->delete();
    }

    public function change_password(Request $request){

        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return $user;

    }
    public function change_notification(Request $request){

        $user = auth()->user();

        if ($request->notification_send == 'true')
            $user->notification_send = 1;
        elseif($request->notification_send == 'false')
            $user->notification_send = 0;

        $user->save();

        return $user;

    }

    public function change_email(Request $request){

        $request->validate([
            'email' => ['required', 'string', 'max:255', 'unique:users,email', 'check_unique_phone'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if(Hash::check($request->password, auth()->user()->password)){
            $user = auth()->user();
            $user->email = $request->email;
            $user->save();

            return response()->json([],200);
        }

        return response()->json([],500);

    }


    public function success($items = null, $status = 200)
    {
    $data = ['status' => 'success'];

    if ($items instanceof Arrayable) {
    $items = $items->toArray();
    }

    if ($items) {
    foreach ($items as $key => $item) {
    $data[$key] = $item;
    }
    }
    return response()->json($data, $status)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }

    public function error($items = null, $status = 500)
    {
    $data = array();

    if ($items) {
    foreach ($items as $key => $item) {
    $data['errors'][$key][] = $item;
    }
    }

    return response()->json($data, $status)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }
}
