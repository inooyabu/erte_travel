<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Feeder;
use App\Kota;
use Auth;

class FeederController extends Controller
{
    public function index(){
        if (Auth::guard('operator')->user()->id_users == 'admin') {
            $feeder = Feeder::all();
            return view('erte.feeder.index', ['feeder' => $feeder]);

        }else{
            $kota = Auth::guard('operator')->user()->id_kota;
            $feeder = Feeder::where('id_kota', $kota)->get();
            return view('erte.feeder.index', ['feeder' => $feeder]);    
        }        
    }

    public function create(){
    	$feeder = Feeder::all();
    	$kota = Kota::all();
    	 return view('erte.feeder.create', ['feeder' => $feeder, 'kota' => $kota]);
	}

	public function store(Request $request){
    	$this->validate($request, 
            [
    		// 'id_users' => 'required',
    		'id_kota' => 'required',
    		'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'nama' => 'required',
            'kontak' => 'required',
            'jenis_kelamin' => 'required'           
        ]);

        $feeder = new Feeder();
            $feeder_select = Feeder::select('id_users');
            $feeder_count = $feeder_select->count();
                // if ($operator_count === 1 && $operator->id_users === "admin" ) {
                if ($feeder_count === 0) {
                    $feeder->id_users = 'F1';
                }else{
                    // $lastrow = $trip_select->last();
                    $lastrow=$feeder_select->orderBy('created_at','desc')->first();
                    $lastrow_id = explode('F', $lastrow->id_users);
                    $new_id = $lastrow_id[1]+1;
                    $feeder->id_users = 'F'.$new_id;
                }
        $feeder->id_kota = $request->id_kota;
        $feeder->username = $request->username;
        $feeder->email = $request->email;
        $feeder->password = $request->password;
        $feeder->nama = $request->nama;
        $feeder->kontak = $request->kontak;
        $feeder->jenis_kelamin = $request->jenis_kelamin;
        $feeder->save();

    	// $feeder = Feeder::create([
    	// 	'id_users' => $request->id_users,
     //        'id_kota' => $request->id_kota,
     //        'username' => $request->username,
     //        'email' => $request->email,
     //        'password' => bcrypt('password'),
     //        'nama' => $request->nama,
     //        'kontak' => $request->kontak,
     //        'jenis_kelamin' => $request->jenis_kelamin
    	// ]);

        session()->flash('flash_success', 'Berhasil menambahkan data feeder dengan nama '. $request->input('nama'));

    	return redirect('/feeder');
    }

    public function edit($id_users){

    	$feeder = Feeder::find($id_users);
        $kota = Kota::all();
    			    	
    	return view('erte.feeder.edit', ['feeder' => $feeder, 'kota' => $kota]);
    }

    public function show($id_users){

        $feeder = Feeder::find($id_users);
        $kota = Kota::find($id_users);          
        
        return view('erte.feeder.show', ['feeder' => $feeder, 'kota' => $kota]);

    }

    public function update($id_users, Request $request, Feeder $feeder){
    	$this->validate($request, 
            [
    		// 'id_users' => 'required',
            'id_kota' => 'required',
            'username' => 'required',
            'email' => 'required',
            // 'password' => 'required',
            'nama' => 'required',
            'kontak' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        $feeder = Feeder::find($id_users);
        // $feeder->id_users = $request->id_users;
        $feeder->id_kota = $request->id_kota;
        $feeder->username = $request->username;
        $feeder->email = $request->email;
        $feeder->nama = $request->nama;
        $feeder->kontak = $request->kontak;
        $feeder->jenis_kelamin = $request->jenis_kelamin;
        $feeder->save();

        session()->flash('flash_success', 'Berhasil mengupdate data feeder '.$feeder->nama);
        
        return redirect('/feeder');
            
    }

    public function delete($id_users){
    	$feeder = Feeder::find($id_users);
    	$feeder->delete();

        session()->flash('flash_success', "Berhasil menghapus feeder ".$feeder->nama);
        return redirect('/feeder');
        
	}



}

