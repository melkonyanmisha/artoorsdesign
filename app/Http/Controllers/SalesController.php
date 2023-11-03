<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

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
            'downloads'    => '4',
            'refund_link'  => '<a href="https://payments.ameriabank.am/Admin/clients/Default.aspx" target="_blank">Refund</a',
        ];

        $totalSales = [];

        foreach ($orders as $keyOrder => $order) {
            $totalSales[$keyOrder]['order_id']           = $order->id;
            $totalSales[$keyOrder]['purchase_date']      = date(
                app('general_setting')->dateFormat->format,
                strtotime(@$order->order_payment->created_at)
            );
            $totalSales[$keyOrder]['customer_full_name'] = sprintf(
                '<a href="%1$s" target="_blank">%2$s %3$s</a>',
                route('customer.show_details', ['id' => $order->customer->id]),
                $order->customer->first_name,
                $order->customer->last_name
            );
            $totalSales[$keyOrder]['shipping_country']   = ucfirst($order->address->shipping_country_id);
            $totalSales[$keyOrder]['sub_total']          = single_price($order->sub_total);
            $totalSales[$keyOrder]['grand_total']        = single_price($order->grand_total);
            $totalSales[$keyOrder]['downloads']          = $totalSalesInitial['downloads'];
            $totalSales[$keyOrder]['refund_link']        = $totalSalesInitial['refund_link'];

            foreach ($order->packages as $package) {
                foreach ($package->products as $packageProduct) {
                    if ( ! empty($packageProduct->seller_product_sku->sku->product->id)) {
                        $totalSales[$keyOrder]['product_id']   = $packageProduct->seller_product_sku->sku->product->id;
                        $totalSales[$keyOrder]['product_name'] = sprintf(
                            '<a href="%1$s?product_id=%2$s" target="_blank">%3$s</a>',
                            route('product.index'),
                            $packageProduct->seller_product_sku->sku->product->id,
                            $packageProduct->seller_product_sku->sku->product->product_name
                        );
                    } else {
                        $totalSales[$keyOrder]['product_id']   = $totalSalesInitial['product_id'];
                        $totalSales[$keyOrder]['product_name'] = $totalSalesInitial['product_name'];
                    }
                }
            }
        }

        return $totalSales;
    }
}