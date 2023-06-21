<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
        * @OA\Get(
        * path="/v1/transaksi/list-produk",
        * summary="List Data Produk Transaksi",
        * description="List Data Produk Transaksi",
        * operationId="List Data Produk Transaksi",
        * tags={"Transaksi"},
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
        *       description="List Data Produk Transaksi",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="data",
        *               type="array",
        *               collectionFormat="multi",
        *               @OA\Items(
        *                 type="string",
        *                 example={"List Data Produk Transaksi"},
        *              )
        *           )
        *       ),
        * )
        * )
        */
    public function produk_pos(Request $request)
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
        })->where('is_jual',true)->orderby('id','desc')->paginate(10);
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
        * path="/v1/transaksi/buat-transaksi",
        * operationId="Buat Transaksi Penjualan ke customer open bill / transaksi lunas",
        * tags={"Transaksi"},
        * security={ {"sanctum": {} }},
        * summary="Buat Transaksi Penjualan ke customer open bill / transaksi lunas",
        * description="Buat Transaksi Penjualan ke customer open bill / transaksi lunas",
        *     @OA\RequestBody(
        *         @OA\MediaType(
        *            mediaType="application/json",
        *            @OA\Schema(
        *               required={"transaction_id","client_id","taxer_id","discount_id","discount_temp_id","other_id","other_temp_id","taxrate","biaya_layanan","discount_nom","biaya_tambahan","grandtotal","amount","method","tipe_pembayaran"},
        *               @OA\Property(
        *                   property="transaction_id",
        *                   type="integer",
        *                   description="0 Untuk transaksi baru",
        *               ),
        *               @OA\Property(
        *                   property="client_id",
        *                   type="integer",
        *                   description="Id client / id pembeli",
        *               ),
        *               @OA\Property(
        *                   property="grandtotal",
        *                   type="integer",
        *                   description="Jumlah grandtotal penjualan",
        *               ),
        *               @OA\Property(
        *                   property="amount",
        *                   type="integer",
        *                   description="Jumlah pembayaran transaksi",
        *               ),
        *               @OA\Property(
        *                   property="payment_id",
        *                   type="integer",
        *                   description="Id Payment yang akan digunakan",
        *               ),
        *               @OA\Property(
        *                   property="details",
        *                   type="array",
        *                   @OA\Items(
        *                       @OA\Property(
        *                           property="id",
        *                           type="integer",
        *                           description="Id detail penjualan, 0 untuk menambahkan produk transaksi untuk transaksi open bill",
        *                       ),
        *                       @OA\Property(
        *                           property="product_id",
        *                           type="integer",
        *                           description="id produk terpilih yang ingin di transaksikan",
        *                       ),
        *                       @OA\Property(
        *                           property="price",
        *                           type="integer",
        *                           description="Harga jual produk",
        *                       ),
        *                       @OA\Property(
        *                           property="qty",
        *                           type="string",
        *                           description="Kuantitas Pembelian Produk",
        *                       ),
        *                       @OA\Property(
        *                           property="total",
        *                           type="integer",
        *                           description="Total harga per kuantitas produknya",
        *                       ),
        *                   ),
        *                   description="Details data transaksi produk",
        *               ),
        *            )
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Berhasil membuat transaksi baru",
        *          @OA\JsonContent(
        *               @OA\Property(
        *                   property="success",
        *                   type="boolval",
        *                   example="true"
        *               ),
        *               @OA\Property(
        *                   property="message",
        *                   type="string",
        *                   example="Berhasil membuat transaksi baru"
        *               )
        *          )
        *       ),
        *      @OA\Response(
        *          response=400,
        *          description="Transaksi gagal, cek kembali no reff dan validasi anda",
        *          @OA\JsonContent(
        *               @OA\Property(
        *                   property="message",
        *                   type="string",
        *                   example="Transaksi gagal, cek kembali no reff dan validasi anda"
        *               )
        *          )
        *       )
        * )
        */
    public function transaksi(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'client_id'   => 'required',
            'grandtotal' => 'required',
            'amount' => 'required',
            'payment_id' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if($request->transaction_id == 0)
        {
            $reff = $this->getNumberOrder();
            $cek_reff = Transaction::where('reff',$reff)->count();
            // cek no reff tidak boleh sama
            if($cek_reff == 0 && $request->grandtotal > 0)
            {
                $transaksi = new Transaction();
                $transaksi->bisnis_id = Auth::user()->bisnis_id;
                $transaksi->cabang_id = Auth::user()->cabang_id;
                $transaksi->user_id = Auth::user()->id;
                $transaksi->client_id = $request->client_id;
                $transaksi->reff = $reff;
                $transaksi->grandtotal = $request->grandtotal;
                $transaksi->amount = $request->amount;
                $transaksi->payment_id = $request->payment_id;
                $transaksi->tipe = $request->amount >= $request->grandtotal ? 'transaksi' : 'open bill';
                $transaksi->save();

                $data = $request['details'];
                foreach($data as $key => $value)
                {
                    $detail = new TransactionDetail();
                    $detail->transaction_id = $transaksi->id;
                    $detail->product_id = $value['product_id'];
                    $detail->price = $value['price'];
                    $detail->qty = $value['qty'];
                    $detail->qty_return = 0;
                    $detail->total = $value['total'];
                    $detail->save();

                    if($transaksi->amount >= $transaksi->grandtotal)
                    {
                        $product = Product::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)->where('id',$value['product_id'])->first();
                        if($product)
                        {
                            $product->stock = $product->stock - $value['qty'];
                            $product->save();
                        }
                    }
                }

                $trans_detail = TransactionDetail::where('transaction_id',$transaksi->id)->get();
                $data = array();
                foreach($trans_detail as $d)
                {
                    $item['id'] = $d->id;
                    $item['product_id'] = $d->product_id;
                    $item['product_name'] = $d->produk->name ?? '';
                    $item['price'] = $d->price;
                    $item['qty'] = $d->qty;
                    $item['total'] = $d->total;
                    $data[] = $item;
                }

                $response = [
                    'id' => $transaksi->id,
                    'bisnis_id' => $transaksi->bisnis_id,
                    'bisnis_name' => $transaksi->bisnis->name ?? '',
                    'cabang_id' => $transaksi->cabang_id,
                    'cabang_name' => $transaksi->cabang->name ?? '',
                    'user_id' => $transaksi->user_id,
                    'user_name' => $transaksi->user->name ?? '',
                    'client_id' => $transaksi->client_id,
                    'client_name' => $transaksi->client->name ?? '',
                    'reff' => $transaksi->reff,
                    'grandtotal' => $transaksi->grandtotal,
                    'amount' => $transaksi->amount,
                    'payment_id' => $transaksi->payment_id,
                    'payment_name' => $transaksi->payment->name ?? '',
                    'tipe' => $transaksi->tipe,
                    'details' => $data ?? null,
                ];

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil membuat transaksi baru',
                    'data' => $response
                ],201);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi gagal, cek kembali no reff dan validasi anda'
                ],400);
            }
        }else{
            $cari_transaksi = Transaction::where('id',$request->transaction_id)->where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)->whereRaw('amount >= grandtotal')->first();
            if($cari_transaksi){
                $cari_transaksi->client_id = $request->client_id;
                $cari_transaksi->grandtotal = $request->grandtotal;
                $cari_transaksi->amount = $request->amount;
                $cari_transaksi->payment_id = $request->payment_id;
                $cari_transaksi->tipe = $request->amount >= $cari_transaksi->grandtotal ? 'transaksi' : 'open bill';
                $cari_transaksi->save();

                $data = $request['details'];
                foreach($data as $key => $value)
                {
                    if($value['id'] == 0)
                    {
                        $detail = new TransactionDetail();
                        $detail->transaction_id = $cari_transaksi->id;
                        $detail->product_id = $value['product_id'];
                        $detail->price = $value['price'];
                        $detail->qty = $value['qty'];
                        $detail->qty_return = 0;
                        $detail->total = $value['total'];
                        $detail->save();
                    }else{
                        $cari_detail = TransactionDetail::where('id',$value['id'])->first();
                        if($cari_detail)
                        {
                            $cari_detail->product_id = $value['product_id'];
                            $cari_detail->price = $value['price'];
                            $cari_detail->qty = $value['qty'];
                            $cari_detail->total = $value['total'];
                            $cari_detail->save();
                        }
                    }

                    if($cari_transaksi->amount >= $cari_transaksi->grandtotal)
                    {
                        $data_produk = Product::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)->where('id',$value['product_id'])->first();
                        if($data_produk)
                        {
                            $data_produk->stock = $data_produk->stock - $value['qty'];
                            $data_produk->save();
                        }
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil melunasi transaksi open bill',
                ],201);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Data transaksi tidak ditemukan',
                ],404);
            }
        }
    }

    /**
        * @OA\Get(
        * path="/v1/transaksi/list-penjualan",
        * summary="Data transaksi penjualan",
        * description="Data transaksi penjualan",
        * operationId="Data transaksi penjualan",
        * tags={"Transaksi"},
        * security={ {"sanctum": {} }},
        * @OA\Parameter(
        *    description="Page untuk paginate data (1,2,3,dst)",
        *    in="query",
        *    name="page",
        *    required=false,
        *    example="1",
        *    @OA\Schema(
        *       type="integer",
        *    )
        * ),
        * @OA\Parameter(
        *    description="Pencarian transaksi dengan no reff",
        *    in="query",
        *    name="search",
        *    required=false,
        *    example="jhon",
        *    @OA\Schema(
        *       type="text",
        *    )
        * ),
        * @OA\Response(
        *       response=200,
        *       description="Data transaksi penjualan",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="data",
        *               type="array",
        *               collectionFormat="multi",
        *               @OA\Items(
        *                 type="string",
        *                 example={"Data transaksi penjualan"},
        *              )
        *           )
        *       )
        * )
        * )
        */
    public function data_penjualan(Request $request)
    {
        $transaksi = Transaction::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)
        ->where(function ($q) use ($request) {
            if($request->search != null)
            {
                return $q->where('reff','ILIKE','%'.$request->search.'%');
            }
        })->where('tipe','transaksi')->orderby('id','desc')->paginate(10);
        $data = array();
        foreach($transaksi as $t)
        {
            $list_transaksi = array();
            foreach($t->detail as $d)
            {
                $component['id'] = $d->id;
                $component['product_id'] = $d->product_id;
                $component['product_name'] = $d->produk->name ?? '';
                $component['price'] = $d->price;
                $component['qty'] = $d->qty;
                $component['total'] = $d->total;
                $list_transaksi[] = $component;
            }

            $item['id'] = $t->id;
            $item['bisnis_id'] = $t->bisnis_id;
            $item['bisnis_name'] = $t->bisnis->name ?? '';
            $item['cabang_id'] = $t->cabang_id;
            $item['cabang_name'] = $t->cabang->name ?? '';
            $item['user_id'] = $t->user_id;
            $item['user_name'] = $t->user->name ?? '';
            $item['client_id'] = $t->client_id;
            $item['client_name'] = $t->pelanggan->name ?? '';
            $item['reff'] = $t->reff;
            $item['grandtotal'] = $t->grandtotal;
            $item['amount'] = $t->amount;
            $item['change'] = $t->grandtotal == $t->amount ? 0 : $t->amount - $t->grandtotal;
            $item['payment_id'] = $t->payment_id;
            $item['tipe'] = $t->tipe;
            $item['details'] = $list_transaksi ?? null;
            $data[] = $item;
        }

        return response()->json([
            'data' => $data,
            'totalrecord' => $transaksi->total(),
        ],200);
    }

    /**
        * @OA\Get(
        * path="/v1/transaksi/list-openbill",
        * summary="Data transaksi penjualan open bill",
        * description="Data transaksi penjualan open bill",
        * operationId="Data transaksi penjualan open bill",
        * tags={"Transaksi"},
        * security={ {"sanctum": {} }},
        * @OA\Parameter(
        *    description="Page untuk paginate data (1,2,3,dst)",
        *    in="query",
        *    name="page",
        *    required=false,
        *    example="1",
        *    @OA\Schema(
        *       type="integer",
        *    )
        * ),
        * @OA\Parameter(
        *    description="Pencarian transaksi dengan no reff",
        *    in="query",
        *    name="search",
        *    required=false,
        *    example="jhon",
        *    @OA\Schema(
        *       type="text",
        *    )
        * ),
        * @OA\Response(
        *       response=200,
        *       description="Data transaksi penjualan open bill",
        *       @OA\JsonContent(
        *           @OA\Property(
        *               property="data",
        *               type="array",
        *               collectionFormat="multi",
        *               @OA\Items(
        *                 type="string",
        *                 example={"Data transaksi penjualan open bill"},
        *              )
        *           )
        *       )
        * )
        * )
        */
    public function list_open_bill(Request $request)
    {
        $transaksi = Transaction::where('bisnis_id',Auth::user()->bisnis_id)->where('cabang_id',Auth::user()->cabang_id)
        ->where(function ($q) use ($request) {
            if($request->search != null)
            {
                return $q->where('reff','ILIKE','%'.$request->search.'%');
            }
        })->where('tipe','open bill')->orderby('id','desc')->paginate(10);
        $data = array();
        foreach($transaksi as $t)
        {
            $list_transaksi = array();
            foreach($t->detail as $d)
            {
                $component['id'] = $d->id;
                $component['product_id'] = $d->product_id;
                $component['product_name'] = $d->produk->name ?? '';
                $component['price'] = $d->price;
                $component['qty'] = $d->qty;
                $component['total'] = $d->total;
                $list_transaksi[] = $component;
            }

            $item['id'] = $t->id;
            $item['bisnis_id'] = $t->bisnis_id;
            $item['bisnis_name'] = $t->bisnis->name ?? '';
            $item['cabang_id'] = $t->cabang_id;
            $item['cabang_name'] = $t->cabang->name ?? '';
            $item['user_id'] = $t->user_id;
            $item['user_name'] = $t->user->name ?? '';
            $item['client_id'] = $t->client_id;
            $item['client_name'] = $t->pelanggan->name ?? '';
            $item['reff'] = $t->reff;
            $item['grandtotal'] = $t->grandtotal;
            $item['amount'] = $t->amount;
            $item['remaining_payment'] = $t->grandtotal - $t->amount;
            $item['payment_id'] = $t->payment_id;
            $item['tipe'] = $t->tipe;
            $item['details'] = $list_transaksi ?? null;
            $data[] = $item;
        }

        return response()->json([
            'data' => $data,
            'totalrecord' => $transaksi->total(),
        ],200);
    }

    public function getNumberOrder()
    {
        $last = DB::table('transactions')->latest('id')->first();

        if ($last) {
            $item = $last->reff;
            $nwMsg = explode("_", $item);
            $inMsg = $nwMsg[1] + 1;
            $code = $nwMsg[0] . '_' . $inMsg;
        } else {
            $code = 'SL_2221';
        }
        return $code;
    }
}
