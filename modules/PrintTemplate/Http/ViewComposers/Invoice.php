<?php

namespace Modules\PrintTemplate\Http\ViewComposers;

use Illuminate\View\View;
use Modules\PrintTemplate\Models\Template;

class Invoice
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $templates = Template::where('type', 'invoice')->enabled()->get();

        $url = explode('/', request()->url());
 
        $invoice_id = $url[count($url)-1];
 
        $view->getFactory()->startPush('button_group_start', view('print-template::incomes.invoices.show', compact('invoice_id', 'templates')));



 
    } 
}
