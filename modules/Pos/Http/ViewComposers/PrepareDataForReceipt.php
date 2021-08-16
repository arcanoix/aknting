<?php

namespace Modules\Pos\Http\ViewComposers;

use App\Models\Auth\User;
use App\Models\Common\Media;
use File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Image;
use Intervention\Image\Exception\NotReadableException;

class PrepareDataForReceipt
{
    public function compose(View $view)
    {
        $order = $view->getData()['order'];

        $served_by = User::where('id', $order->created_by)->pluck('name')->first();

        $payments = $order->transactions
            ->where('type', 'income')
            ->map(function ($transaction) {
                return [
                    // TODO: keep additional transactions data instead of comparing with a setting
                    'type'   => $transaction->account_id == setting('pos.general.cash_account_id') ? trans('general.cash') : trans('pos::pos.card'),
                    'amount' => $transaction->amount,
                ];
            });

        // TODO: keep additional order data instead of getting from transactions
        $change = $order->transactions
            ->where('type', 'expense')
            ->pluck('amount')
            ->first();

        $total = $order->totals
            ->where('code', 'total')
            ->pluck('amount')
            ->first();

        $view->with('served_by', $served_by)
            ->with('payments', $payments)
            ->with('change', $change)
            ->with('total', $total)
            ->with('logo', $this->getLogo());
    }

    protected function getLogo(): string
    {
        if (!setting('pos.general.show_logo_in_receipt')) {
            return '';
        }

        $media_id = setting('company.logo');

        $media = Media::find($media_id);

        if (!empty($media)) {
            $path = $media->getDiskPath();

            if (Storage::missing($path)) {
                return '';
            }
        } else {
            $path = base_path('public/img/company.png');
        }

        try {
            $image = Image::cache(function($image) use ($media, $path) {
                $width = setting('invoice.logo_size_width');
                $height = setting('invoice.logo_size_height');

                if ($media) {
                    $image->make(Storage::get($path))->resize($width, $height)->encode();
                } else {
                    $image->make($path)->resize($width, $height)->encode();
                }
            });
        } catch (NotReadableException | \Exception $e) {
            Log::info('Company ID: ' . company_id() . ' modules/Pos/Http/ViewComposers/PrepareDataForReceipt.php exception.');
            Log::info($e->getMessage());

            $path = base_path('public/img/company.png');

            $image = Image::cache(function($image) use ($path) {
                $width = setting('invoice.logo_size_width');
                $height = setting('invoice.logo_size_height');

                $image->make($path)->resize($width, $height)->encode();
            });
        }

        if (empty($image)) {
            return '';
        }

        $extension = File::extension($path);

        return 'data:image/' . $extension . ';base64,' . base64_encode($image);
    }
}
