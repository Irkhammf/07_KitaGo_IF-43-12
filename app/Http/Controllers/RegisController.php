<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Interfaces\CRUDInterface;
use App\Models\Customer;
use App\Models\PenyediaJasa;

class RegisController extends Controller
{
    private CRUDInterface $customerRepo;
    private CRUDInterface $penyediaJasaRepo;

    public function __construct(CRUDInterface $customerRepo, CRUDInterface $penyediaJasaRepo) 
    {
        $this->customerRepo = $customerRepo;
        $this->penyediaJasaRepo = $penyediaJasaRepo;
    }

    public function index()
    {
        $dataC = $this->customerRepo->all();
        $dataA = $this->penyediaJasaRepo->all();
        return view('kgweb.regis', ['listC' => $dataC, 'listA' => $dataA]);
    }

    public function create($type)
    {
        if ($type == 'agent' || $type == 'customer'){
            return view('kgweb.regis', [
                'title' => 'Registrasi User',
                'method' => 'POST',
                'action' => 'kgweb/register',
                'type' => $type,
            ]);
        } else {
            return redirect('/');
        }
    }

    public function store(Request $request)
    {
        if (isset($request -> usernameC)) {
            if ($request -> usernameC == null || $request -> passwordC == null || $request -> nama_lengkap == null || $request -> emailC == null
            || $request -> birthDate == null || $request -> telpNumbC == null) {
                return redirect('/kgweb/regis/c')->with('msg', 'Silahkan isi data yang masih kosong');
            } else {
                $customers = Customer::get();
                foreach ($customers as $c) {
                    if ($c -> usernameC == $request -> usernameC) {
                        return redirect('/kgweb/regis/c')->with('msg', 'Username tersebut sudah diambil!');
                    } else if ($c -> emailC == $request -> emailC) {
                        return redirect('/kgweb/regis/c')->with('msg', 'Email tersebut sudah terdaftar!');
                    }
                }
                $data = new Customer;
                    $data->id = $request->id;
                    $data->nama_lengkap = $request->nama_lengkap;
                    $data->emailC = $request->emailC;
                    $data->birthDate = $request->birthDate;
                    $data->telpNumbC = $request->telpNumbC;
                    $data->usernameC = $request->usernameC;
                    $data->passwordC = Hash::make($request->passwordC);
                $data->save();
                $this->customerRepo->store($data);
                return redirect('/kgweb/login');
            }
        } else if (isset($request -> usernmaeP)) {
            if ($request -> usernameP == null || $request -> passwordP == null || $request -> nama_penyedia_jasa == null || $request -> emailP == null
            || $request -> alamat == null || $request -> telpNumbP == null) {
                return redirect('/kgweb/regis/p')->with('msg', 'Silahkan isi data yang masih kosong');
            } else {
                $pjs = PenyediaJasa::get();
                foreach ($pjs as $p) {
                    if ($p -> usernameP == $request -> usernameP) {
                        return redirect('/kgweb/regis/p')->with('msg', 'Username tersebut sudah diambil!');
                    } else if ($p -> emailP == $request -> emailP) {
                        return redirect('/kgweb/regis/p')->with('msg', 'Email tersebut sudah terdaftar!');
                    }
                }
                $data = new PenyediaJasa;
                    $data->id = $request->id;
                    $data->nama_penyedia_jasa = $request->nama_penyedia_jasa;
                    $data->emailP = $request->emailP;
                    $data->alamat = $request->alamat;
                    $data->telpNumbP = $request->telpNumbP;
                    $data->usernameP = $request->usernameP;
                    $data->passwordP = Hash::make($request->passwordP);
                $data->save();
                $this->penyediaJasaRepo->store($data);
                return redirect('/kgweb/login');
            }
        }
    }
}