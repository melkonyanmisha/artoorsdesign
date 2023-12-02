<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;
use App\Models\Paymant_products;

class SalesController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $full       = boolval(request('full'));
        $totalSales = $this->getSales($full);

        return view('backEnd.sales', ['totalSales' => $totalSales]);
    }

    /**
     * @param bool $full
     *
     * @return array
     */
    private function getSales(bool $full): array
    {
        if ($full) {
            $orders = Order::with('packages')->latest()->get();
        } else {
            $orders = Order::with('packages')->latest()->paginate(100);
        }

        $totalSalesInitial = [
            'product_sku'   => 0,
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
                        $productSKU       = $packageProduct->seller_product_sku->sku->sku;

                        $paymentProducts = Paymant_products::where('product_id1', $productID)->where(
                            'user_id',
                            $customerId
                        )->first();

                        $totalSales[$keyOrder]['product_sku']   = $productSKU;
                        $totalSales[$keyOrder]['product_name'] = sprintf(
                            '<a href="%1$s" target="_blank">%2$s</a>',
                            route('frontend.item.show', [
                                'category' => $packageProduct->seller_product_sku->sku->product->categories[0]->slug,
                                'seller'   => $packageProduct->seller_product_sku->sku->product->slug,
                            ]),
                            $packageProduct->seller_product_sku->sku->product->product_name
                        );
                        $totalSales[$keyOrder]['downloads']    = $paymentProducts->downloads;
                    } else {
                        $totalSales[$keyOrder]['product_sku']   = $totalSalesInitial['product_sku'];
                        $totalSales[$keyOrder]['product_name'] = $totalSalesInitial['product_name'];
                        $totalSales[$keyOrder]['downloads']    = $totalSalesInitial['downloads'];
                    }
                }
            }
        }

        return $totalSales;
    }
}