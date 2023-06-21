<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
        * @OA\Get(
        * path="/v1/produk/list",
        * summary="List Data Produk Master",
        * description="List Data Produk Master",
        * operationId="List Data Produk Master",
        * tags={"Produk"},
        * security={ {"sanctum": {} }},
        * @OA\Parameter(
        *    description="Halaman page data untuk ditampilkan",
        *    in="query",
        *    name="page",
        *    required=false,
        *    example="1",
        *    @OA\Schema(
        *       type="integer",
        *    )
        * ),
        * @OA\Parameter(
        *    description="Sort data berdasarkan id ketegori",
        *    in="query",
        *    name="category_id",
        *    required=false,
        *    example="1",
        *    @OA\Schema(
        *       type="integer",
        *    )
        * ),
        * @OA\Parameter(
        *    description="Tipe pencarian berdasarkan nama produk",
        *    in="query",
        *    name="search",
        *    required=false,
        *    example="Nasi Goreng Ayam",
        *    @OA\Schema(
        *       type="text",
        *    )
        * ),
        * @OA\Response(
        *       response=200,
        *       description="List Data Produk Master",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="data",
        *               type="array",
        *               collectionFormat="multi",
        *               @OA\Items(
        *                 type="string",
        *                 example={"List Data Produk Master"},
        *              )
        *           )
        *       ),
        * )
        * )
        */
    public function list(Request $request)
    {
        $produk = Product::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)
        ->where(function ($q) use ($request) {
            if($request->search != null)
            {
                return $q->where('name','ILIKE','%'.$request->search.'%');
            }
        })->where(function ($q) use ($request) {
            if($request->category_id != null)
            {
                return $q->where('category_id',$request->category_id);
            }
        })->orderby('id','desc')->paginate(10);
        $list = array();
        foreach($produk as $p)
        {
            $item['id'] = $p->id;
            $item['category_id'] = $p->category_id;
            $item['category_name'] = $p->kategori->name ?? '';
            $item['name'] = $p->name;
            $item['modal'] = $p->modal;
            $item['price'] = $p->price;
            $item['stock'] = $p->stock;
            $item['satuan'] = $p->satuan;
            $item['is_active'] = $p->is_active;
            $item['is_jual'] = $p->is_jual;
            $list[] = $item;
        }

        return response()->json([
            'data' => $list,
            'totalrecord' => $produk->total(),
        ],200);
    }

    /**
        * @OA\Post(
        * path="/v1/produk/tambah-baru",
        * operationId="Tambah data produk master",
        * tags={"Produk"},
        * security={ {"sanctum": {} }},
        * summary="Tambah data produk master",
        * description="Tambah data produk master",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"category_id","name","modal","price","stock","satuan","is_jual"},
        *               @OA\Property(property="category_id", type="integer", description="Id kategori untuk produk yang akan ditambahkan"),
        *               @OA\Property(property="name", type="text", description="Nama Produk"),
        *               @OA\Property(property="modal", type="integer", description="Harga Beli Produk"),
        *               @OA\Property(property="price", type="integer", description="Harga Jual Produk"),
        *               @OA\Property(property="stock", type="integer", description="Jumlah stock produk yang akan ditambahkan"),
        *               @OA\Property(property="satuan", type="string", enum={"pcs","bundle"}, description="Satuan stock beli / jual"),
        *               @OA\Property(property="is_jual", type="boolean", description="Jika produk di jual (true) jika tidak (false)"),
        *            ),
        *        ),
        *    ),
        *    @OA\Response(
        *       response=201,
        *       description="Berhasil menambahkan data produk",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="success",
        *               type="boolval",
        *               example="true"
        *           ),
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Berhasil menambahkan data produk"
        *           )
        *        )
        *    ),
        *    @OA\Response(
        *       response=400,
        *       description="Penambahan produk gagal, periksa kembali data validasi anda",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Penambahan produk gagal, cek kembali validasi anda"
        *           )
        *       )
        *    )
        * )
        */
    public function add_product(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name'   => 'required',
            'modal' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'satuan' => 'required',
            'is_jual' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $produk = new Product();
        $produk->bisnis_id = Auth::user()->bisnis_id;
        $produk->cabang_id = Auth::user()->cabang_id;
        $produk->category_id = $request->category_id;
        $produk->name = $request->name;
        $produk->modal = $request->modal;
        $produk->price = $request->price;
        $produk->stock = $request->stock;
        $produk->satuan = $request->satuan;
        $produk->is_active = true;
        $produk->is_jual = $request->is_jual;
        $produk->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data produk',
        ],201);
    }

    /**
        * @OA\Get(
        * path="/v1/produk/detail/{id}",
        * summary="Detail data produk",
        * description="Detail data produk",
        * operationId="Detail data produk",
        * tags={"Produk"},
        * security={ {"sanctum": {} }},
        * @OA\Parameter(
        *    description="Id Produk",
        *    in="path",
        *    name="id",
        *    required=false,
        *    example="1",
        *    @OA\Schema(
        *       type="integer",
        *       format="int64"
        *    )
        * ),
        * @OA\Response(
        *       response=200,
        *       description="Data detail produk berdasarkan id",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="data",
        *               type="array",
        *               collectionFormat="multi",
        *               @OA\Items(
        *                 type="string",
        *                 example={"Data detail produk berdasarkan id"},
        *              )
        *           )
        *       )
        * ),
        * @OA\Response(
        *       response=404,
        *       description="Data detail produk tidak ditemukan",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="success",
        *               type="boolval",
        *               example="false"
        *           ),
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Data detail produk tidak ditemukan"
        *           )
        *       )
        * )
        * )
        */
    public function detail($id)
    {
        $cari = Product::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)->where('id',$id)->first();
        if($cari)
        {
            $data = [
                'id' => $cari->id,
                'category_id' => $cari->category_id,
                'category_name' => $cari->kategori->name ?? '',
                'name' => $cari->name,
                'modal' => $cari->modal,
                'price' => $cari->price,
                'stock' => $cari->stock,
                'satuan' => $cari->satuan,
                'is_active' => $cari->is_active,
                'is_jual' => $cari->is_jual,
            ];

            return response()->json([
                'data' => $data,
            ],200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data produk tidak ditemukan',
            ],404);
        }
    }

    /**
        * @OA\put(
        * path="/v1/produk/update-data/{id}",
        * operationId="Update data produk",
        * tags={"Produk"},
        * summary="Update data produk",
        * description="Update data produk",
        * security={ {"sanctum": {} }},
        * @OA\Parameter(
        *    description="Id produk merchant",
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
        *               required={"name","modal","price","is_active","is_jual"},
        *               @OA\Property(property="name", type="text", description="Nama produk baru"),
        *               @OA\Property(property="modal", type="integer", description="Harga Beli baru"),
        *               @OA\Property(property="price", type="integer", description="Harga Jual baru"),
        *               @OA\Property(property="is_active", type="boolean", description="Nonaktifkan produk"),
        *               @OA\Property(property="is_jual", type="text", description="Tampilkan produk untuk di jual")
        *         ),
        *      ),
        *  ),
        *  @OA\Response(
        *       response=201,
        *       description="Berhasil update data produk",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="success",
        *               type="boolval",
        *               example="true"
        *           ),
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Berhasil update data produk"
        *           )
        *       )
        *  ),
        *  @OA\Response(
        *       response=404,
        *       description="Data produk tidak ditemukan",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="success",
        *               type="boolval",
        *               example="false"
        *           ),
        *           @OA\Property(
        *               property="message",
        *               type="string",
        *               example="Data produk tidak ditemukan"
        *           )
        *       )
        *  )
        * )
        */
    public function update(Request $request,$id)
    {
        $cari = Product::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)->where('id',$id)->first();
        if($cari)
        {
            $cari->name = $request->name == '' ? $cari->name : $request->name;
            $cari->modal = $request->modal == 0 ? $cari->modal : $request->modal;
            $cari->price = $request->price == 0 ? $cari->price : $request->price;
            $cari->is_active = $request->is_active == '' ? $cari->is_active : $request->is_active;
            $cari->is_jual = $request->is_jual == '' ? $cari->is_jual : $request->is_jual;
            $cari->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil update data produk',
            ],201);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data produk tidak ditemukan',
            ],404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/v1/produk/hapus-data/{id}",
     *     description="Hapus data produk",
     *     operationId="Hapus data produk",
     *     summary="Hapus data produk",
     *     tags={"Produk"},
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         description="Id data produk yang akan di hapus",
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
     *         description="Berhasil menghapus data produk",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolval",
     *                  example="true"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Berhasil menghapus data produk"
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Terjadi kesalahan saat hapus data produk",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolval",
     *                  example="false"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Terjadi kesalahan saat hapus data produk"
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
        $cari = Product::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)->where('id',$id)->first();
        if($cari)
        {
            $hapus = Product::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)->where('id',$id)->delete();

            if($hapus)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menghapus data produk',
                ],200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat hapus data produk',
                ],400);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data produk tidak ditemukan',
            ],404);
        }
    }
}
