<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\DemandMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;

class DemandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $demandStatus = Demand::where('id', $request->demandId)->first()->status;

        if ($demandStatus == Demand::CLOSED) {return redirect()->back();}
        $create = DemandMessage::create([
            'userId' => Auth::id(),
            'demandId' => $request->demandId,
            'text' => $request->text
        ]);

        if ($create) {
            Demand::where('id',$request->demandId)->update([]);
            return redirect()->back()->with('alert', 'Mesaj Gönderildi')->with('alert-style', 'success');
        } else {
            return redirect()->back()->with('alert', 'Mesaj Gönderilemedi')->with('alert-style', 'danger');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Demand::where('id', $id)->with('customer')->first();
        $messages = DemandMessage::where('demandId', $data->id)->with('user')->orderBy('id', 'desc')->get();
        return view('demand.show', ['data' => $data, 'messages' => $messages]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function status($demandId, $statusId)
    {
        Demand::where('id', $demandId)->update([
            'status' => $statusId
        ]);

        return redirect()->back();
    }
}
