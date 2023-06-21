<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
        * @OA\Get(
        * path="/v1/kategori/list",
        * summary="List Data Kategori Produk",
        * description="List Data Kategori Produk",
        * operationId="List Data Kategori Produk",
        * tags={"Kategori"},
        * security={ {"sanctum": {} }},
        * @OA\Parameter(
        *    description="Tipe pencarian berdasarkan nama kategori produk",
        *    in="query",
        *    name="search",
        *    required=false,
        *    example="Kebutuhan Pokok",
        *    @OA\Schema(
        *       type="text",
        *    )
        * ),
        * @OA\Response(
        *       response=200,
        *       description="List Data Kategori Produk",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="data",
        *               type="array",
        *               collectionFormat="multi",
        *               @OA\Items(
        *                 type="string",
        *                 example={"List Data Kategori Produk"},
        *              )
        *           )
        *       ),
        * )
        * )
        */
    public function index(Request $request)
    {
        $category = Category::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)
        ->where(function ($q) use ($request) {
            if($request->search != null)
            {
                return $q->where('name','ILIKE','%'.$request->search.'%');
            }
        })->get();
        $data = array();
        foreach($category as $c)
        {
            $item['id'] = $c->id;
            $item['name'] = $c->name;
            $data[] = $item;
        }

        return response()->json([
            'data' => $data,
            'totalrecord' => $category->count(),
        ],200);
    }

    /**
        * @OA\Post(
        * path="/v1/kategori/tambah-baru",
        * operationId="Tambah data kategori produk baru",
        * tags={"Kategori"},
        * security={ {"sanctum": {} }},
        * summary="Tambah data kategori produk baru",
        * description="Tambah data kategori produk baru",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name"},
        *               @OA\Property(property="name", type="text", description="Nama Kategori Produk Baru"),
        *            ),
        *        ),
        *    ),
        *    @OA\Response(
        *       response=201,
        *       description="Berhasil menambahkan kategori baru",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="success",
        *               type="boolval",
        *               example="true"
        *           ),
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Berhasil menambahkan kategori baru"
        *           )
        *        )
        *    ),
        *    @OA\Response(
        *       response=400,
        *       description="Penambahan kategori produk gagal, periksa kembali data validasi anda",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Penambahan kategori produk gagal, cek kembali validasi anda"
        *           )
        *       )
        *    )
        * )
        */
    public function tambah(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'   => 'required'
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $kategori = new Category();
        $kategori->bisnis_id = Auth::user()->bisnis_id;
        $kategori->cabang_id = Auth::user()->cabang_id;
        $kategori->name = $request->name;
        $kategori->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan kategori baru',
        ],201);
    }

    /**
        * @OA\put(
        * path="/v1/kategori/update-data/{id}",
        * operationId="Update data kategori produk",
        * tags={"Kategori"},
        * summary="Update data kategori produk",
        * description="Update data kategori produk",
        * security={ {"sanctum": {} }},
        * @OA\Parameter(
        *    description="Id kategori produk",
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
        *               required={"name"},
        *               @OA\Property(property="name", type="text", description="Nama kategori produk baru, yang akan menggantikan nama kategori lama"),
        *         ),
        *      ),
        *  ),
        *  @OA\Response(
        *       response=201,
        *       description="Berhasil update data kategori produk",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="success",
        *               type="boolval",
        *               example="true"
        *           ),
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Berhasil update data kategori produk"
        *           )
        *       )
        *  ),
        *  @OA\Response(
        *       response=404,
        *       description="Data kategori tidak ditemukan",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="success",
        *               type="boolval",
        *               example="false"
        *           ),
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Data kategori tidak ditemukan"
        *           )
        *       )
        *  )
        * )
        */
    public function update(Request $request,$id)
    {
        $cari = Category::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)->where('id',$id)->first();
        if($cari)
        {
            $cari->name = $request->name;
            $cari->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil update data kategori produk',
            ],201);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data kategori tidak ditemukan',
            ],404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/v1/kategori/hapus-data/{id}",
     *     description="Hapus data kategori produk",
     *     operationId="Hapus data kategori produk",
     *     summary="Hapus data kategori produk",
     *     tags={"Kategori"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         description="Id data kategori produk yang akan di hapus",
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
     *         description="Berhasil menghapus data kategori",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolval",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Berhasil menghapus data kategori"
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Terjadi kesalahan saat menghapus data kategori",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolval",
     *                  example="false"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Terjadi kesalahan saat menghapus data kategori"
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Data produk tidak ditemukan",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolval",
     *                  example="false"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Data produk tidak ditemukan"
     *              )
     *         )
     *     )
     * )
     */
    public function delete($id)
    {
        $cari = Category::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang',Auth::user()->cabang_id)->where('id',$id)->first();
        if($cari)
        {
            foreach($cari->produk as $p)
            {
                $p->category_id = 0;
                $p->save();
            }

            $hapus = Category::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang',Auth::user()->cabang_id)->where('id',$id)->delete();
            if($hapus)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menghapus data kategori',
                ],201);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'Terjadi kesalahan saat menghapus data kategori',
                ],400);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data kategori tidak ditemukan',
            ],404);
        }
    }
}
