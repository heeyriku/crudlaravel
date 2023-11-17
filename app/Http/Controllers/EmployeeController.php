<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeExport;
use App\Models\Employee;
use PDF;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function index(Request $request){
        if($request->has('search')){

            $data = Employee::Where('nama','LIKE','%'.$request->search.'%')->paginate(10);

        }else{

            $data = Employee::paginate(5);
            // dd($data);

        }
        return view('datapegawai',compact('data'));

    }
    public function tambahpegawai(){
        return view('tambahdata',);
    }

    public function insertdata(Request $request){
        // dd($request->all());
        $data = Employee::create($request->all());
        if($request->hasFile('foto')){
            $request->file('foto')->move('fotopegawai/', $request->file('foto')->getClientOriginalName());
            $data->foto = $request->file('foto')->getClientOriginalName();
            $data->save();
        }

        return redirect()->route('pegawai')->with('success','Data Berhasil di Tambahkan');
    }

    public function tampilkandata($id){
        $data = Employee::find($id);
        // dd($data);

        return view('tampildata', compact('data'));
    }

    public function updatedata(Request $request, $id){
        $data = Employee::find($id);
        // dd($data);
        $data->update($request->all());
        return redirect()->route('pegawai')->with('success','Data Berhasil di Update');
    }

    public function delete($id){
        $data = Employee::find($id);
        $data->delete();
        return redirect()->route('pegawai')->with('success','Data Berhasil di Hapus');
    }

    public function exportpdf(){
        $data = Employee::all();

        view()->share('data', $data);
        $pdf = PDF::loadview('datapegawai-pdf');
        return $pdf->download('Data Pegawai.pdf');
    }

    public function exportexcel(){
      return Excel::download(new EmployeeExport, 'Data Pegawai.xlsx');
    }
}
