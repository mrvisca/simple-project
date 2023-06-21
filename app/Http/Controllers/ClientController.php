<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
        * @OA\Get(
        * path="/v1/pelanggan/list",
        * summary="List Data Pelanggan Visca Corporation",
        * description="List Data Pelanggan Visca Corporation",
        * operationId="List Data Pelanggan Visca Corporation",
        * tags={"Pelanggan"},
        * security={ {"sanctum": {} }},
        * @OA\Parameter(
        *    description="Tipe pencarian berdasarkan nama pelanggan",
        *    in="query",
        *    name="search",
        *    required=false,
        *    example="Sumiati Bodjong",
        *    @OA\Schema(
        *       type="text",
        *    )
        * ),
        * @OA\Response(
        *       response=200,
        *       description="List Data Pelanggan Visca Corporation",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="data",
        *               type="array",
        *               collectionFormat="multi",
        *               @OA\Items(
        *                 type="string",
        *                 example={"List Data Pelanggan Visca Corporation"},
        *              )
        *           )
        *       ),
        * )
        * )
        */
    public function list(Request $request)
    {
        $client = Client::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)
        ->where(function ($q) use ($request) {
            if($request->search != null)
            {
                return $q->where('name','ILIKE','%'.$request->search.'%')->orWhere('telp','ILIKE','%'.$request->search.'%');
            }
        })->orderby('id','desc')->paginate(10);
        $data = array();
        foreach($client as $c)
        {
            $item['id'] = $c->id;
            $item['name'] = $c->name;
            $item['telpon'] = $c->telp;
            $item['tanggal_join'] = date('Y-m-d',strtotime($c->created_at));
            $data[] = $item;
        }

        return response()->json([
            'data' => $data,
            'totalrecord' => $client->total(),
        ],200);
    }

    /**
        * @OA\Post(
        * path="/v1/pelanggan/tambah-baru",
        * operationId="Tambah data pelanggan baru",
        * tags={"Pelanggan"},
        * security={ {"sanctum": {} }},
        * summary="Tambah data pelanggan baru",
        * description="Tambah data pelanggan baru",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name","telp"},
        *               @OA\Property(property="name", type="text", description="Nama Pelanggan Baru"),
         *               @OA\Property(property="telp", type="integer", description="No telpon pelanggan baru"),
        *            ),
        *        ),
        *    ),
        *    @OA\Response(
        *       response=201,
        *       description="Berhasil menambahkan data client",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="success",
        *               type="boolval",
        *               example="true"
        *           ),
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Berhasil menambahkan data client"
        *           )
        *        )
        *    ),
        *    @OA\Response(
        *       response=400,
        *       description="Penambahan pelanggan baru gagal, periksa kembali data validasi anda",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Penambahan pelanggan baru gagal, cek kembali validasi anda"
        *           )
        *       )
        *    )
        * )
        */
    public function tambah(Request $request)
    {
         //set validation
         $validator = Validator::make($request->all(), [
            'name'   => 'required',
            'telp' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $client = new Client();
        $client->bisnis_id = Auth::user()->bisnis_id;
        $client->cabang_id = Auth::user()->cabang_id;
        $client->name = $request->name;
        $client->telp = $request->telp;
        $client->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data client',
        ],201);
    }

    /**
        * @OA\put(
        * path="/v1/pelanggan/update-data/{id}",
        * operationId="Update data pelanggan",
        * tags={"Pelanggan"},
        * summary="Update data pelanggan",
        * description="Update data pelanggan",
        * security={ {"sanctum": {} }},
        * @OA\Parameter(
        *    description="Id pelanggan yang akan di ubah",
        *    in="path",
        *    name="id",
        *    required=false,
        *    example="1",
        *    @OA\Schema(
        *       type="integer",
        *    )
        *  ),
        *  @OA\RequestBody(
        *       @OA\JsonContent(),
        *       @OA\MediaType(
        *          mediaType="application/x-www-form-urlencoded",
        *          @OA\Schema(
        *              type="object",
        *               required={"name","telp"},
        *               @OA\Property(property="name", type="text", description="Nama pelanggan baru"),
        *               @OA\Property(property="telp", type="integer", description="No telpon pelanggan baru"),
        *         ),
        *      ),
        *  ),
        *  @OA\Response(
        *       response=201,
        *       description="Berhasil update data pelanggan",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="success",
        *               type="boolval",
        *               example="true"
        *           ),
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Berhasil update data pelanggan"
        *           )
        *       )
        *  ),
        *  @OA\Response(
        *       response=404,
        *       description="Data Pelanggan tidak ditemukan",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="success",
        *               type="boolval",
        *               example="false"
        *           ),
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Data Pelanggan tidak ditemukan"
        *           )
        *       )
        *  )
        * )
        */
    public function update(Request $request,$id)
    {
        $cari = Client::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)->where('id',$id);
        if($cari)
        {
            $cari->name = $request->name;
            $cari->telp = $request->telp;
            $cari->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil update data pelanggan',
            ],201);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Pelanggan tidak ditemukan',
            ],404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/v1/pelanggan/hapus-data/{id}",
     *     description="Hapus data pelanggan",
     *     operationId="Hapus data pelanggan",
     *     summary="Hapus data pelanggan",
     *     tags={"Pelanggan"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         description="Id data pelanggan yang akan di hapus",
     *         in="path",
     *         name="id",
     *         required=false,
     *         @OA\Schema(
     *             format="int64",
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Berhasil menghapus data pelanggan",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolval",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Berhasil menghapus data pelanggan"
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Terjadi kesalahan saat menghapus data pelanggan",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolval",
     *                  example="false"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Terjadi kesalahan saat menghapus data pelanggan"
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Data pelanggan tidak ditemukan",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolval",
     *                  example="false"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Data pelanggan tidak ditemukan"
     *              )
     *         )
     *     )
     * )
     */
    public function delete($id)
    {
        $cari = Client::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)->where('id',$id);
        if($cari)
        {
            $hapus = Client::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang',Auth::user()->cabang_id)->where('id',$id)->delete();
            if($hapus)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menghapus data pelanggan',
                ],201);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'Terjadi kesalahan saat menghapus data pelanggan',
                ],400);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Pelanggan tidak ditemukan',
            ],404);
        }
    }
}
