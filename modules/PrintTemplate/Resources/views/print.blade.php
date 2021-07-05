@extends('layouts.print')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)
 
@section('content')
@if ($print_template->attachment)
    <img src="{{ route('uploads.get' , $print_template->attachment->id) }}" id="deleteBeforePrint" />
@endif
    <div class="page">
    @php
    $invoiceItem=array();
    $invoiceTotal=array();
    $invoiceCustom=array();
    $invoiceCustomField=array();
	
    @endphp
    @foreach ($invoiceprintItems as $item)  
        @php
			if (!isset($printItems[$item->item_id])) continue;
            $attr =json_decode($item->attr,true) ;
            $field = $printItems[$item->item_id]["field"];
            $attrname = $printItems[$item->item_id]["name"];
            $display_name = $printItems[$item->item_id]["display_name"];
         
        @endphp
        
        <div class="existItem" key="{{ $item->item_id }}"  style="position: absolute; @foreach($attr as $itemAttr=>$itemValue){{ $itemAttr.":".$itemValue.";" }}@endforeach">
            
            @if ( $field=="company")
                {{ $invoice->company->{$attrname} }} 
            @elseif  ( $field=="invoice")
                @if (stristr($attrname,"at"))
                 @date($invoice->{$attrname})
                @else
                {!! nl2br($invoice->{$attrname}) !!}
                @endif
                
            @elseif  ( $field=="customer")
                {!! nl2br($invoice->contact->{$attrname}) !!}
            @elseif  ( $field=="item" )
                @php 
                    $invoiceItem[$attrname]=$item; 
                @endphp  
            @elseif  ( $field=="total")
                @php 
                    $invoiceTotal[$attrname]=$item->attr; 
                @endphp
            @elseif (substr($display_name,0,1)=="_" )
                @php
                
                    $invoiceItem["CustomFields"]=$item; 
                @endphp
            @else
                @php 
                    $invoiceCustom[$attrname]=$attr; 
                    $invoiceCustomField[$attrname]=$printItems[$item->item_id];
                    
                @endphp
         
            @endif
        
        </div>
    @endforeach
    @php
    $itemTop=0;
    $firstLineTop=0;
    $ItemFark=array();
    @endphp
    @foreach($invoice->items as $item)
        @php 
            $nextRow=true;
        @endphp
        @foreach ($invoiceItem as $key=>$tempItem)
            @if (!empty($tempItem->attr))
                @php
                    $attr =json_decode($tempItem->attr,true);
                    if ($nextRow){
                        if ($itemTop==0){
                            $itemTop=rtrim($attr["top"],"px");
                            $firstLineTop=rtrim($attr["top"],"px");
                        }else{
                            $itemTop+=rtrim($attr["height"],"px");
                        }
                        $nextRow=false;
                    }
                    if (!isset($ItemFark[$key])){
                        //Bazı alanlar diğerlerinden aşağıda olabilir.
                        //bu yüzden bu farkı buluyoruz.
                        $ItemFark[$key]=(rtrim($attr["top"],"px")-$firstLineTop);
                    }
                    $itemTop+=$ItemFark[$key];
                    $attr["top"]=$itemTop."px";
                @endphp
                <div class="existItemFatura" style="position: absolute;   @foreach($attr as $itemAttr=>$itemValue){{ $itemAttr.":".$itemValue.";" }}@endforeach" firstLinetop="{{$firstLineTop}}">
                    @if ($key=="total" || $key=="price" )
                        @money($item->{$key}, $invoice->currency_code, true)
                    @elseif ($key=="description" )
                        {{ Modules\PrintTemplate\Http\Controllers\PrintTemplateController::custom_field("item_description", $item,$date_format,$printItems[$tempItem->item_id]) }}
                    @elseif ($key=="CustomFields")
                        
						 {!! Modules\PrintTemplate\Http\Controllers\PrintTemplateController::custom_field("CustomFields", $item,$date_format,$printItems[$tempItem->item_id]) !!} 
						
					@else
                        {{ $item->{$key} }}
                    @endif

                </div>
                @php
                        $itemTop-=$ItemFark[$key];
                    
                @endphp
            @endif
        @endforeach
    @endforeach

    @php
        $taxTop=0;
    @endphp
    @foreach($invoice->totals as $item)
        @php
            if (!isset($invoiceTotal[$item->code])) continue;
                    
                 
            $attr = json_decode($invoiceTotal[$item->code],true);
            if ($item->code=="tax"){
                if ($taxTop==0){
                    $taxTop=rtrim($attr["top"],"px");
                }else{
                    $taxTop+=rtrim($attr["height"],"px");
                }
            }else{
                $taxTop=rtrim($attr["top"],"px");
            }
            
            $attr["top"]=$taxTop;
        @endphp
        <div class="existItemTotal" style="position: absolute; @foreach($attr as $itemAttr=>$itemValue){{ $itemAttr.":".$itemValue.";" }}@endforeach">
                @money($item->amount, $invoice->currency_code, true)
               
        </div> 
    @endforeach

    @foreach($invoiceCustom as $itemName=>$attr)
        <div class="existItemTotal" style="position: absolute; @foreach($attr as $itemAttr=>$itemValue){{ $itemAttr.":".$itemValue.";" }}@endforeach">
            {!! Modules\PrintTemplate\Http\Controllers\PrintTemplateController::custom_field($itemName, $invoice,$date_format,$invoiceCustomField[$itemName]) !!} 
        </div> 
    @endforeach

</div>
@endsection
@push('css')
<style>
@page{
    margin: 0;
    size: {{ config("print-template.pageSize-type.".$print_template->pagesize)[0]}} {{ config("print-template.pageSize-type.".$print_template->pagesize)[1]}};
}

.page-break	{ display: block; page-break-before: always; }
.std{
    position:absolute;
}     
html, body, img{
    margin:0;
   
    width:  {{ config("print-template.pageSize-mm.".$print_template->pagesize)[0]}}mm;
    height: {{ config("print-template.pageSize-mm.".$print_template->pagesize)[1]}}mm;
    @if (stristr(url()->current(),"pdf"))
    font-family:DejaVu Sans !important;
    @endif
}
img{
    position: absolute;;
}
.page{
    
    margin:0;
    position:relative;
}
</style>
@endpush

@push('scripts')
<script type="text/javascript">
	@if($print_template->printBackground=='false')
    function onPrint(callback) {
        window.matchMedia('print').addListener(query => query.matches ? callback() : null)
        window.addEventListener('beforeprint', () => callback())
    }

    onPrint(() => {document.getElementById('deleteBeforePrint').style.display='None'})
	@endif
</script>
@endpush