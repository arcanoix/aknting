<?php

namespace Modules\Pos\Database\Seeds;

use App\Abstracts\Model;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Setting\Category;
use App\Utilities\Modules;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Settings extends Seeder
{
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $guest_customer_id = Contact::enabled()->customer()->where('name', trans('pos::settings.general.guest_customer'))->pluck('id')->first();
        $sale_category_id = Category::enabled()->income()->where('name', trans('pos::settings.categories.pos_sale'))->pluck('id')->first();
        $change_category_id = Category::enabled()->expense()->where('name', trans('pos::settings.categories.pos_change'))->pluck('id')->first();
        $cash_account_id = Account::enabled()->where('name', trans('pos::settings.accounts.cash'))->pluck('id')->first();
        $card_account_id = Account::enabled()->where('name', trans('pos::settings.accounts.bank'))->pluck('id')->first();

        setting()->set([
            'pos.general.guest_customer_id'  => $guest_customer_id,
            'pos.general.sale_category_id'   => $sale_category_id,
            'pos.general.change_category_id' => $change_category_id,
            'pos.general.cash_account_id'    => $cash_account_id,
            'pos.general.card_account_id'    => $card_account_id,
        ]);

        $offline_payment_methods = json_decode(setting('offline-payments.methods'), true);

        $code = 'offline-payments.bank_card.pos';

        if (!in_array($code, array_column($offline_payment_methods, 'code'))) {
            $offline_payment_methods[] = [
                'code' => $code,
                'name' => trans('pos::settings.bank_card_payment_method.name'),
                'customer' => '0',
                'order' => count($offline_payment_methods) + 1,
                'description' => trans('pos::settings.bank_card_payment_method.description'),
            ];

            setting()->set('offline-payments.methods', json_encode($offline_payment_methods));
        }

        setting()->save();

        Modules::clearPaymentMethodsCache();
    }
}
