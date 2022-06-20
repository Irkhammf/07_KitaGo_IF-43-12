<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; /*memanggil libarary */
use Illuminate\Support\Facades\Session;
use App\Models\Refund;
use App\Models\Booking;

class RefundController extends Controller
{
    public function refund(Request $request, $id)
    {
        if (null == Session::get('user')){
            return redirect('/kgweb/login');
        }
        return view('kgweb.refund', [
            'title' => 'Refund Paket',
            'method' => 'POST',
            'action' => "kgweb/$id/refundBook",
            'idBook' => $request->idBook,
            'date' => date('d/m/Y'),
        ]);
    }

    public function refundBook(Request $request, $id)
    {
        if (null == Session::get('user')){
            return redirect('/kgweb/login');
        }
        if (null == $request->alasan) {
            $request->alasan = "";
        }
        Booking::destroy($id);
        $user = session('user');
        $data = new Refund;
            $data->idUser = $request->idUser;
            $data->dateRefund = date('Y-m-d');
            $data->alasan = $request->alasan;
        $data->save();
        $idUser = $user -> id;
        return view('kgweb.refundmsg');
    }
}
