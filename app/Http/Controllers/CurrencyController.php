<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\CurrencyCreateRequest;
use App\Currency;
use App\Http\Resources\CurrencyResource;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \Gate::authorize('view', 'currencies');
        $Currency = Currency::get();
        return CurrencyResource::collection($Currency);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Gate::authorize('edit', 'currencies');
        $input=$request->only('currency_name_eng','currency_name_arab','exchange_rate');
        $input['user_by']=auth('api')->user()->id;
        $input['branch_id']=1;
        $input['company_id']=1;
        $Currency = Currency::create($input);

        return response($Currency, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        \Gate::authorize('view', 'currencies');
        return new CurrencyResource(Currency::find($id));
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
        \Gate::authorize('edit', 'currencies');
        $Currency= Currency::find($id);
        $Currency->update($request->only('user_by','currency_name_eng','currency_name_arab','exchange_rate'));

        return response($Currency, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \Gate::authorize('delete', 'currencies');
        $Currency = Currency::destroy($id);

        return response($Currency, Response::HTTP_ACCEPTED);
    }
}
