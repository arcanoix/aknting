<?php

namespace Modules\PrintTemplate\Http\Controllers;

use App\Models\Sale\Invoice;
use App\Models\Common\Item;
use App\Traits\Uploads;
use Illuminate\Http\Request as Requests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PrintTemplate\Http\Requests\Template as Request;
use Modules\PrintTemplate\Models\Template;
use Modules\PrintTemplate\Models\TemplateItem;

class PrintTemplateController extends Controller
{
    use  Uploads;

    protected $type;
    protected $pagesize;
    protected $printItems;
    protected $printItemsCategory;
 

    public function __construct()
    {
        $this->type = config("print-template.printType");
        $this->pagesize = config("print-template.pageSize");
        $this->printItemsCategory = config("print-template.printItemsCategory");
        $this->printItems = config("print-template.printItems");
	
    }

	public function customFieldsModule(){
        
        
	
		if (\Module::collections()->has('custom-fields')){ //CUSTOM FIELD MODULE INSTALLED AND ACTIVE
		
            $items = \Modules\CustomFields\Models\Field::orderBy("id")->collect();
            if ($items->count()<=0){ return;}
			$this->printItemsCategory["CustomFields"]=range(1000,(1000+($items->count()-1)));
			$printItemId=1000;
			foreach($items as $item){
				$field="CustomFields";
				$name=$item->code;
				if ($item->location->code == 'common.items'){
					$field="item";
					$name="CustomFields";
				}
				$this->printItems[$printItemId]=array("type"=>"CustomFields","field"=>$field,"name"=>$name,"display_name"=>$item->name,"customFieldsId"=>$item->id);
				$printItemId++;
			}
		}
	}
	
	
    public static function custom_field($name,$invoice,$date_format,$attr=""){
       
   
		
		//for CustomFields Module
		if (isset($attr["type"]) && $attr["type"]=="CustomFields"){
			
            if (\Module::collections()->has('custom-fields')){ //CUSTOM FIELD MODULE INSTALLED AND ACTIVE
                
                $items = \Modules\CustomFields\Models\FieldValue::where("field_id","=",$attr["customFieldsId"])->get();
               
				foreach ($items as $item){
                    
					switch ($item->model_type){
                        case "App\Models\Common\Contact":

							if ($invoice->contact->id==$item->model_id){
                                if ($item->type=="date"){
                                    return \Date::parse($item->value)->format($date_format);
                                }
                                if ($item->type=="dateTime"){
                                    return \Date::parse($item->value)->format($date_format." H:i:s");
                                }
                                return $item->value;
                            }
							break;
						case "App\Models\Common\Company":
							if ($invoice->company->id==$item->model_id){
                                if ($item->type=="date"){
                                    return \Date::parse($item->value)->format($date_format);
                                }
                                if ($item->type=="dateTime"){
                                    return \Date::parse($item->value)->format($date_format." H:i:s");
                                }
                                return $item->value;
                            }
							break;
						case "App\Models\Document\Document":
                            if ($invoice->id==$item->model_id){
                                if ($item->type=="date"){
                                    return \Date::parse($item->value)->format($date_format);
                                }
                                if ($item->type=="dateTime"){
                                    return \Date::parse($item->value)->format($date_format." H:i:s");
                                }
                                return $item->value;
                            }
							break; 
						case "App\Models\Document\DocumentItem":
							//there is $invoice variable uses as $item
                                if ($invoice->id==$item->model_id){
                                    if ($item->type=="date"){
                                        return \Date::parse($item->value)->format($date_format);
                                    }
                                    if ($item->type=="dateTime"){
                                        return \Date::parse($item->value)->format($date_format." H:i:s");
                                    }
                                    return $item->value;
                                }
							break; 
						case "App\Models\Common\Item":
							//there is $invoice variable uses as $item
							
							if ($invoice->item_id==$item->model_id){
                                if ($item->type=="date"){
                                    return \Date::parse($item->value)->format($date_format);
                                }
                                if ($item->type=="dateTime"){
                                    return \Date::parse($item->value)->format($date_format." H:i:s");
                                }
                                return $item->value;
                            }
							break; 
			
						default;
                            if ($item->type=="date"){
                                return \Date::parse($item->value)->format($date_format);
                            }
                            if ($item->type=="dateTime"){
                                return \Date::parse($item->value)->format($date_format." H:i:s");
                            }
                            return $item->value;
                        break;
					}
				}
				
				return "";
			}
			
		}
		
        switch ($name) {
                case 'item_description':
                    return $invoice->description;
                break;
                case 'invoiced_at_date':
                    return \Date::parse($invoice->invoiced_at)->format($date_format);
                break;
                case 'invoiced_at_hour':
                    return \Date::parse($invoice->invoiced_at)->toTimeString();
                break;
            
                case 'due_at_date':
                    return \Date::parse($invoice->due_at)->format($date_format);
                break;
            
                case 'due_at_hour':
                    return \Date::parse($invoice->due_at)->toTimeString();
                break;
            
                case 'total_word':
                    if ($invoice->currency_code=="TRY")
                    return PrintTemplateController::convertMoneytoText($invoice->amount);
                    return "Only work invoice currency code is TRY. if you want to implement for your currency please contact us. ";
                break;
            
                case 'sub_total_with_type':
                    foreach ($invoice->totals as $total){
                        if ($total->code == 'sub_total'){
                            return trans($total->title)." :".money($total->amount, $invoice->currency_code, true);
                        }
                    }
					return "";
                break;
                case 'discout_with_type':
                    foreach ($invoice->totals as $total){
                        if ($total->code == 'discount'){
                            return trans($total->title)." :".money($total->amount, $invoice->currency_code, true);
                        }
                    }
					return "";
                break;
                case 'item_discount_with_type':
                    foreach ($invoice->totals as $total){
                        if ($total->code == 'item_discount'){
                            return trans($total->title)." :".money($total->amount, $invoice->currency_code, true);
                        }
                    }
					return "";
                break;
                case 'tax_with_type':
                    $return ="";
                    foreach ($invoice->totals as $total){
                        if ($total->code == 'tax'){
                            $return.= trans($total->title)." :".money($total->amount, $invoice->currency_code, true)."<br />";
                        }
                    }
                    return rtrim($return,"<br />");
                break;
                case 'total_with_type':
                    foreach ($invoice->totals as $total){
                        if ($total->code == 'total'){
                            return trans($total->title)." :".money($total->amount, $invoice->currency_code, true);
                        }
                    }
					return "";
                break;
                case 'tax_only_name':
                    $return ="";
                    foreach ($invoice->totals as $total){
                        if ($total->code == 'tax'){
                            $return.= trans($total->title)."<br />";
                        }
                    }
                    return rtrim($return,"<br />");
                break;
                case 'paid':
                    return money($invoice->paid, $invoice->currency_code, true);
                break;
                case 'item_discount_only_name':
                    return trans("invoices.item_discount");
                break;
                case 'paid_with_type':
                    return trans("invoices.paid")." :".money($invoice->paid, $invoice->currency_code, true);
                break;
                case 'paid_only_name':
                    return trans("invoices.paid");
                break;
                case 'amount':
                    return money($invoice->amount-$invoice->paid, $invoice->currency_code, true);
                break;
                case 'amount_with_type':
                    return trans("invoices.total")." :".money($invoice->amount-$invoice->paid, $invoice->currency_code, true);
                break;
                case 'amount_only_name':
                    return trans("invoices.total");
                break;
            
            default:
            return "";
                break;
        }

        return "Not yet implement! Please contact us";
    }
    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Template $print_template)
    {
		$this->customFieldsModule();
		
        $printItems = $this->printItems;
		
        $printItemsCategory = $this->printItemsCategory;
        $invoiceprintItems = TemplateItem::where(["template_id" => $print_template->id])->get();
  
		return view('print-template::show',compact('print_template', 'printItems', 'invoiceprintItems', 'printItemsCategory'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function design(Template $print_template)
    {
		$this->customFieldsModule();
		
        $printItems = $this->printItems;
		
        $printItemsCategory = $this->printItemsCategory;
        $invoiceprintItems = TemplateItem::where(["template_id" => $print_template->id])->get();
  
		return view('print-template::design',compact('print_template', 'printItems', 'invoiceprintItems', 'printItemsCategory'));
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $templates =  Template::get();

        return view('print-template::index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $type = $this->type;
        $pagesize = $this->pagesize;

        return view('print-template::create', compact('type', 'pagesize'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $print_template = Template::create($request->all());

        if ($request->file('attachment')) {
            $media = $this->getMedia($request->file('attachment'), 'print-template');
            $print_template->attachMedia($media, 'attachment');
            $print_template->save();
        }
        
        $message = trans('messages.success.added', ['type' => trans_choice('print-template::general.title', 1)]);

        flash($message)->success();

        return response()->json([
            'errors' => false,
            'success' => true,
            'message' => $message,
            'redirect' => route('print-template.settings.index'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Template $print_template)
    {
        $types = $this->type;
        $pagesizes = $this->pagesize;

        return view('print-template::edit', compact('print_template', 'types', 'pagesizes'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Template $print_template, Requests $request)
    {
        $input = $request->input();

        if ($request->file('attachment')) {
            $media = $this->getMedia($request->file('attachment'), 'print-template');
            if ($media){
                $print_template->attachMedia($media, 'attachment');
            }
        }

        $print_template->update($input);

        $message = trans('messages.success.updated', ['type' => trans('print-template::general.title')]);

        flash($message)->success();

         $response = [
            'status' => null,
            'success' => true,
            'error' => false,
            'message' => $message,
            'data' => null,
            'redirect' => route('print-template.settings.index'),
        ];


        return response()->json($response);



    }

    public function save(Template $print_template, Requests $request)
    { 
		$template_item = TemplateItem::where("template_id","=", $print_template->id);
		$template_item->forceDelete();

        $req = $request->all();

        if (!empty($req["data"])){
			foreach ($req["data"] as  $item) {
				$template_item = new TemplateItem();
				$template_item->template_id=$print_template->id;
				$template_item->item_id=$item["id"];
				$template_item->attr=$item["attr"];
				$template_item->company_id=company_id();
				$template_item->save();
			}
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Template $print_template)
    {
        $print_template->delete();

        $message = trans('messages.success.deleted', ['type' => trans('print-template::general.title')]);

        flash($message)->success();

        return response()->json([
            'errors' => false,
            'success' => true,
            'message' => $message,
            'redirect' => route('print-template.settings.index'),
        ]);
    }

    public function enable(Template $print_template)
    {
        $print_template->enabled = 1;
        $print_template->save();

        $message = trans('messages.success.enabled', ['type' => trans_choice('print-template::general.title', 1)]);

        flash($message)->success();

        return redirect()->route('print-template.settings.index');
    }


    public function disable(Template $print_template)
    {
        $print_template->enabled = 0;
        $print_template->save();

        $message = trans('messages.success.disabled', ['type' => trans_choice('print-template::general.title', 1)]);

        flash($message)->success();

        return  redirect()->route('print-template.settings.index');
    }

    public function printInvoice(Template $print_template,Invoice $invoice)
    {
		
		$this->customFieldsModule();
        $printItems = $this->printItems;
        $invoiceprintItems = TemplateItem::where(["template_id"=>$print_template->id])->get();

		if ($invoiceprintItems->isEmpty()) {
			return redirect()->route('print-template.settings.show' , $print_template->id)->with('invoice_id', $invoice->id);
        }

        $default = 'd M Y';
        $chars = ['dash' => '-', 'slash' => '/', 'dot' => '.', 'comma' => ',', 'space' => ' '];
        $loca_date_format = setting('localisation.date_format', $default);
        $date_separator = $chars[setting('localisation.date_separator', 'space')];
         
        $date_format  =  str_replace(' ', $date_separator, $loca_date_format);
      
        return view('print-template::print', compact('print_template', 'invoice', 'printItems', 'invoiceprintItems','date_format'));
    }
   
    public static function convertMoneytoText($sayi)
    {
		
        $nokta = explode(".",$sayi);
        $yazi = "";
        if (count($nokta) > 1) {
            $say1 = $nokta[0];
            $yazi .=PrintTemplateController::cevirSayiYaziTr($say1)." Türk Lirası ";
            $say2 = $nokta[1];
            $yazi .=PrintTemplateController::cevirSayiYaziTr($say2)." Kuruş";
        } else {
            $say1 = $sayi;
            $yazi .=PrintTemplateController::cevirSayiYaziTr($say1)." Türk Lirası ";
        }
        //Türkçe için bir istisna -- İnglizce için 18 de istisna vardır tek "t" ile yazılır.
        if (substr($yazi,0,6)=="BirBin"){
            $yazi = substr($yazi,3);
        }

        return trans("print-template::general.moneyTextPrefix").$yazi;
    }
 
    public  static function cevirSayiYaziTr($sayi) {
        $o = config("print-template.basamaklar");
        $basamak = array_reverse(str_split(implode('', array_reverse(str_split($sayi))), 3));
        $basamak_sayisi = count($basamak);
        for($i=0; $i < $basamak_sayisi; ++$i) {
            // Sayıyı basamaklarına ayırdığımızda basamaklar tersine döndüğü için burada ufak bir işlem ile basamakları düzeltiyoruz
            $basamak[$i] = str_pad(implode(array_reverse(str_split($basamak[$i]))), 3, "0", STR_PAD_LEFT);
            /*if(strlen($basamak[$i]) == 1)
                $basamak[$i] = '00' . $basamak[$i];
            elseif(strlen($basamak[$i]) == 2)
                $basamak[$i] = '0' . $basamak[$i];
            */
        }
        $yenisayi = [];
        foreach($basamak as $k => $b) {
            if($b[0] > 0)
                $yenisayi[] = ($b[0] > 1 ? $o['birlik'][$b[0]-1] . '' : '') . $o['basamak'][0];

            if($b[1] > 0)
                $yenisayi[] = $o['onluk'][$b[1]-1];

            if($b[2] > 0)
                $yenisayi[] = $o['birlik'][$b[2]-1];
            if($basamak_sayisi > 1)
                $yenisayi[] = $o['basamak'][$basamak_sayisi-1];
            --$basamak_sayisi;
        }

        return implode('', $yenisayi);
    }

}
