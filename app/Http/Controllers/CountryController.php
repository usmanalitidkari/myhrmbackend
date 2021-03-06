<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Requests\CountryCreateRequest;
use App\Http\Resources\CountryResource;
use App\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PHPUnit\Framework\Constraint\IsEmpty;
use Symfony\Component\HttpFoundation\Response;

class CountryController extends Controller
{
    /**
     * @OA\Get(path="/Country",
     *   security={{"bearerAuth":{}}},
     *   tags={"Country"},
     *   @OA\Response(response="200",
     *     description="Country Collection",
     *   )
     * )
     */
    public function index()
    {
        \Gate::authorize('view', 'countries');
        $Countries= Country::with(['countrycities'])->get();
        return CountryResource::collection($Countries);
    }

    /**
     * @OA\Get(path="/Country/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"Country"},
     *   @OA\Response(response="200",
     *     description="User",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Country ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function show($id)
    {
        \Gate::authorize('view', 'countries');
        return new CountryResource(Country::find($id));
    }

    /**
     * @OA\Post(
     *   path="/country",
     *   security={{"bearerAuth":{}}},
     *   tags={"country"},
     *   @OA\Response(response="201",
     *     description="Country Create",
     *   )
     * )
     */
    public function store(CountryCreateRequest $request)
    {
        \Gate::authorize('edit', 'countries');
        $input=$request->only('country_name_arab', 'nationality_arab', 'country_name_eng', 'nationality_eng');
        $input['user_by']=auth('api')->user()->id;
        $Country = Country::create($input);
        return response($Country, Response::HTTP_CREATED);
    }
////
    /**
     * @OA\Put(
     *   path="/country/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"country"},
     *   @OA\Response(response="202",
     *     description="Country Update",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Country ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function update(Request $request, $id)
    {
        \Gate::authorize('edit', 'countries');
        $Country = Country::find($id);
        $Country->update($request->only('country_name_arab', 'nationality_arab', 'country_name_eng', 'nationality_eng'));
        return response($Country, Response::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(path="/country/{id}",
     *   security={{"bearerAuth":{}}},
     *   tags={"country"},
     *   @OA\Response(response="204",
     *     description="Country Delete",
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     description="Country ID",
     *     in="path",
     *     required=true,
     *     @OA\Schema(
     *        type="integer"
     *     )
     *   )
     * )
     */
    public function destroy($id)
    {
        \Gate::authorize('delete', 'countries');
        $Delete = City::where('country_id', $id)->first();

        if (empty($Delete)) {
            $country=Country::destroy($id);
            return response($country, Response::HTTP_ACCEPTED);
        } else {
            return response()->json([
          'success' => false,
          'message' => "This Data Can't Be Delete Because Use In Other Place !"
        ]);
        }
    }
}
