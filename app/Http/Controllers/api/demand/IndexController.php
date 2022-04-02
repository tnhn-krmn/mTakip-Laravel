<?php

namespace App\Http\Controllers\api\demand;

use App\Http\Controllers\Controller;
use App\Models\Demand;
use App\Models\DemandMessage;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = request()->user();
        $data = Demand::where('userId', $user->id)->orderBy('updated_at')->get()->map(function ($item) use ($user) {
            $item['count'] = DemandMessage::where('demandId', $item->id)
                ->where('userId', '≠',$user->id)
                ->where('isRead', 1)
                ->count();
            return $item;
        });




        return response()->json([
            'success' => true,
            'data' => $data
        ]);
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
        $request->validate([
            'title' => 'required',
            'text' => 'required',
        ]);

        $user = request()->user();

        $create = Demand::create([
            'userId' => $user->id,
            'title' => $request->title,
            'text' => $request->text
        ]);

        if ($create) {
            return response()->json([
                'success' => true,
                'message' => 'Talebiniz Alındı'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Talep Oluşturulamadı'
            ]);
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
        $userId = request()->user()->id;
        $check = Demand::where('id', $id)->where('userId', $userId)->count();
        if ($check == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Böyle bir talep bulunamadı.'
            ]);
        }

        $demand = Demand::where('id', $id)->where('userId', $userId)->first();
        DemandMessage::where('demandId',$id)->where('isRead',1)->where('userId','≠',$userId)->update([
            'isRead' => 0
        ]);
        $message = DemandMessage::where('demandId', $id)->with('user')->orderBy('id', 'desc')->get();
        return response()->json([
            'success' => true,
            'demand' => $demand,
            'message' => $message
        ]);
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

    public function message(Request $request)
    {
        $id = $request->id;
        $userId = request()->user()->id;
        $check = Demand::where('id', $id)->where('userId', $userId)->count();
        if ($check == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Böyle bir talep bulunamadı.'
            ]);
        }

        if ($request->text == "") {
            return response()->json([
                'success' => false,
                'message' => 'Mesaj Boş Gönderilemez'
            ]);
        }

        $create = DemandMessage::create([
            'demandId' => $id,
            'userId' => $userId,
            'text' => $request->text
        ]);

        if ($create) {
            Demand::where('id', $id)->update([]);
            return response()->json([
                'success' => true,
                'message' => DemandMessage::where('demandId', $id)->with('user')->orderBy('id', 'desc')->get()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Mesaj Gönderilemedi."
            ]);
        }
    }
}
