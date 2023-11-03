<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;
use App\Models\Paymant_products;

class SalesController extends Controller
{
    public function index(): View
    {
        $totalSales = $this->getTotalSales();

        return view('backEnd.sales', ['totalSales' => $totalSales]);
    }

    private function getTotalSales(): array
    {
        $orders = Order::with('packages')->latest()->get();

        $totalSalesInitial = [
            'product_id'   => 0,
            'product_name' => '',
            'downloads'    => 0,
            'refund_link'  => '<a href="https://payments.ameriabank.am/Admin/clients/Default.aspx" target="_blank">Refund</a',
        ];

        $totalSales = [];

        foreach ($orders as $keyOrder => $order) {
            $customerId = $order->customer->id;

            $totalSales[$keyOrder]['customer_id']        = $customerId;
            $totalSales[$keyOrder]['order_id']           = $order->id;
            $totalSales[$keyOrder]['purchase_date']      = date(
                'Y-M-d',
                strtotime(@$order->order_payment->created_at)
            );
            $totalSales[$keyOrder]['customer_full_name'] = sprintf(
                '<a href="%1$s" target="_blank">%2$s %3$s</a>',
                route('customer.show_details', ['id' => $customerId]),
                $order->customer->first_name,
                $order->customer->last_name
            );
            $totalSales[$keyOrder]['shipping_country']   = ucfirst($order->address->shipping_country_id);
            $totalSales[$keyOrder]['sub_total']          = single_price($order->sub_total);
            $totalSales[$keyOrder]['grand_total']        = single_price($order->grand_total);
            $totalSales[$keyOrder]['refund_link']        = $totalSalesInitial['refund_link'];

            foreach ($order->packages as $package) {
                foreach ($package->products as $packageProduct) {
                    if ( ! empty($packageProduct->seller_product_sku->sku->product->id)) {
                        $productID       = $packageProduct->seller_product_sku->sku->product->id;
                        $paymentProducts = Paymant_products::where('product_id1', $productID)->where(
                            'user_id',
                            $customerId
                        )->first();

                        $totalSales[$keyOrder]['product_id']   = $productID;
                        $totalSales[$keyOrder]['product_name'] = sprintf(
                            '<a href="%1$s" target="_blank">%2$s</a>',
                            route('frontend.item.show', [
                                'category' => $packageProduct->seller_product_sku->sku->product->categories[0]->slug,
                                'seller'   => $packageProduct->seller_product_sku->sku->product->slug,
                            ]),
                            $packageProduct->seller_product_sku->sku->product->product_name
                        );


                        $totalSales[$keyOrder]['downloads'] = $paymentProducts->downloads;
                    } else {
                        $totalSales[$keyOrder]['product_id']   = $totalSalesInitial['product_id'];
                        $totalSales[$keyOrder]['product_name'] = $totalSalesInitial['product_name'];
                        $totalSales[$keyOrder]['downloads']    = $totalSalesInitial['downloads'];
                    }
                }
            }
        }

        return $totalSales;
    }
}