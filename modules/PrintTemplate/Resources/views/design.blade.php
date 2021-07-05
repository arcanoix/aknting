<!DOCTYPE html5>
<html>
<head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css" rel="stylesheet" />



<style>
    .pagePreview{
        width:  {{ config("print-template.pageSize-mm.".$print_template->pagesize)[0]}}mm;
        height: {{ config("print-template.pageSize-mm.".$print_template->pagesize)[1]}}mm;
        position:relative;
    }
    .droppable .draggable {
        position:absolute;
    }
    .draggable{
        border:1px dashed #ccc;
        margin-top:5px;
        padding:5px;
    }
    .dropped{
        position:absolute;
        margin:0;
        padding:0;
    }
    .resizable{
        border:1px dashed #424242;
    }
    .content-center{
        max-width:unset !important;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha256-KM512VNnjElC30ehFwehXjx1YCHPiQkOPmqnrWtpccM=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</head>
<body style="height: auto;">
<div class="wrapper" style="height: auto;">
    <div class="content-wrapper" style="min-height: 1390px;">
        <section class="content content-center">
            
            <div class="row">
                <div class="col-md-12">
                    <!-- Default box -->
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ trans('print-template::general.header_show') }}</h3>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group row">
                                <div class="col-lg-2">
                                    <button class="form-control btn btn-success" type="button" onclick="kaydet()">{{ trans("general.save") }}</button>
                                </div>
                                
                                @if ($invoice_id = Session::get('invoice_id'))
                                <div class="col-lg-2">
                                    <a class="form-control btn btn-warning" href="{{ route('sales.invoices.show' , $invoice_id) }}">{{ trans("print-template::general.backInvoice") }}</a>
                                </div> 
                                <div class="col-lg-12">
                                    <h3 class="box-title">{{ trans('print-template::general.reminderText') }}</h3>
                                </div>
                                @endif
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12">
                                    <div class="col-lg-3">
                                        <div class="box-group" id="accordion">
                                            @foreach($printItemsCategory as $itemName=>$items)
                                                <div class="panel box box-success">
                                                    <div class="box-header with-border">
                                                    <h4 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ md5($itemName) }}"> {{ $itemName }} </a>
                                                    </h4>
                                                    </div>
                                                    <div id="collapse{{ md5($itemName) }}" class="panel-collapse collapse">
                                                    <div class="box-body">
                                                        @foreach($items as $item)
                                                            <div class="draggable" key="{{ $item }}" >{{ isset($printItems[$item])?$printItems[$item]["display_name"]:"" }}</div>
                                                        @endforeach
                                                    </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="pagePreview droppable" >
                                            @if ($print_template->attachment)
                                                <img src="{{  route('uploads.get' , $print_template->attachment->id) }}" style="width: 100%;height: 100%" />
                                            @endif
                                            @foreach ($invoiceprintItems as $item)  
                                            @php
                                                $attr =json_decode($item->attr,true) ;
                                            @endphp
                                            <div class="existItem"  key="{{ $item->item_id }}" style="position: absolute;  @foreach($attr as $itemAttr=>$itemValue) {{ $itemAttr.":".$itemValue.";" }} @endforeach ">
                                                    {{ isset($printItems[$item->item_id])?$printItems[$item->item_id]["display_name"]:"" }}
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>
<script type="text/javascript">
    var kaydet;
    $(document).ready(function (){
        kaydet=function(){
            var data=[];
            $(".dropped").each(function (){
                
                //xCoord - yCoord - Width - Height - FontSize - FontType - FontWeight - FontStyle ---json
                var thisdata={};
                var thisdataJSON={};
                thisdata["id"]=$(this).attr("key");
                thisdataJSON["left"]=$(this).css("left");
                thisdataJSON["top"]=$(this).css("top");
                thisdataJSON["width"]=$(this).css("width");
                thisdataJSON["height"]=$(this).css("height");
                thisdataJSON["font-size"]=$(this).css("font-size");
                thisdataJSON["font-family"]=$(this).css("font-family");
                thisdataJSON["font-weight"]=$(this).css("font-weight");
                thisdataJSON["font-style"]=$(this).css("font-style");
                thisdataJSON["text-align"]=$(this).css("text-align");

                
                thisdata["attr"]=JSON.stringify(thisdataJSON);; 
                data.push(thisdata);
            });

            $.ajax({
                url:"{{ route("print-template.settings.save" , $print_template->id ) }}",
                type:'post',
                data:{
                    "_token": "{{ csrf_token() }}",
                    "data":data,
                    "name":$("#name").val(),
                    "type":$("#type").val()
                },
                complete:function(data){
                    //todo
                    if (data.status == 200 && data.responseJSON.status == "success"){
                        alert("{{ trans('print-template::general.successMessage') }}")
                    }else{
                        alert("{{ trans('print-template::general.unsuccessMessage') }}")
                    }
                    
                }
            })
        }

        $(".resizable").resizable();
        $(".draggable").draggable({
            //  use a helper-clone that is append to 'body' so is not 'contained' by a pane
            helper: function() {
                return jQuery(this).clone().appendTo('.droppable').css({
                    'zIndex': 5
                });
            },
            cursor: 'move',
            containment: "document"
        });
        $(".droppable").droppable({
            activeClass: 'ui-state-hover',
            accept: '.draggable',
            drop: function(event, ui) {
                var hasable = ui.draggable.hasClass("dropped");
                if (hasable==false){
                    var clone=jQuery(ui.draggable).clone().addClass("dropped").addClass("resizable").addClass("context-menu").draggable();
                    if (clone!=undefined){
                        clone.css('left',ui.position.left);    
                        clone.css('top',ui.position.top);
                        clone.css('position',"absolute");
                        clone.resizable();
                    
                        jQuery(this).append(clone);
                    }
                }
            }
        });
        $(".existItem").each(function (){
            $(this).addClass("dropped").addClass("resizable").addClass("context-menu").draggable();
            $(this).resizable();
        });

        $.contextMenu({
            selector: '.context-menu',
            callback: function (key, options) {
                if (key == "removeItem") {
                    $(this).remove();
                }
                if (key == "Arial" || key == "Verdana" || key == "Comic Sans MS" || key == "Times New Roman") {
                    $(this).css("font-family", key);
                }
                if (key == "normal" || key == "italic" || key == "bold" || key == "bold&Italic") {
                    var index = 0;
                    if (key == "bold") {
                        $(this).css("font-weight", key);
                        $(this).css("font-style", "normal");
                    }
                    else if (key == "bold&Italic") {
                        $(this).css("font-style", "italic");
                        $(this).css("font-weight", "bold");
                    }
                    else {
                        $(this).css("font-style", key);
                        $(this).css("font-weight", "normal");
                    }
                }
                if (key == "8" || key == "10" || key == "12" || key == "14" || key == "18" || key == "20" || key == "25") {
                    $(this).css("font-size", key + "px");
                }
                if (key == "left" || key == "right" || key == "center" || key == "justify") {
                    $(this).css("text-align", key);
                }
            },
            items: {
                "changeAlign": {
                    name: "{{ trans("print-template::general.contextMenu.textAlign") }}",
                    "items": {
                        "left": { "name": "{{ trans("print-template::general.contextMenu.textAlignLeft") }}" },
                        "right": { "name": "{{ trans("print-template::general.contextMenu.textAlignRight") }}" },
                        "center": { "name": "{{ trans("print-template::general.contextMenu.textAlignCenter") }}" },
                        "justify": { "name": "{{ trans("print-template::general.contextMenu.textAlignJustify") }}" },
                    }, icon: "edit"
                },
                "changeFontSize": {
                    name: "{{ trans("print-template::general.contextMenu.fontSize") }}",
                    "items": {
                        "8": { "name": "8px" },
                        "10": { "name": "10px" },
                        "12": { "name": "12px" },
                        "14": { "name": "14px" },
                        "18": { "name": "18px" },
                        "20": { "name": "20px" },
                        "25": { "name": "25px" }
                    }, icon: "edit"
                },
                "changeFontFamily": {
                    name: "{{ trans("print-template::general.contextMenu.fontSize") }}",
                    "items": {
                        "Arial": { "name": "Arial" },
                        "Verdana": { "name": "Verdana" },
                        "Comic Sans MS": { "name": "Comic Sans MS" },
                        "Times New Roman": { "name": "Times New Roman" }
                    }, icon: "edit"
                },
                "changeFontStyle": {
                    name: "{{ trans("print-template::general.contextMenu.fontStyle") }}",
                    "items": {
                        "normal": { "name": "{{ trans("print-template::general.contextMenu.fontStyleNormal") }}" },
                        "italic": { "name": "{{ trans("print-template::general.contextMenu.fontStyleItalic") }}" },
                        "bold": { "name": "{{ trans("print-template::general.contextMenu.fontStyleBold") }}" },
                        "bold&Italic": { "name": "{{ trans("print-template::general.contextMenu.fontStyleBoldItalic") }}" }
                    }, icon: "edit"
                },
                "removeItem": {
                    name: "{{ trans("print-template::general.contextMenu.delete") }}",
                    icon: "delete"
                },
            }
        });
    })
</script>

</body>
</html>