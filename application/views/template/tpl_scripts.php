<script>
/*Notification*/(function($){var Notification=function(element,options){this.$element=$(element);this.$note=$('<div class="alert"></div>');this.options=$.extend(true,{},$.fn.notify.defaults,options);if(this.options.transition){if(this.options.transition=='fade'){this.$note.addClass('in').addClass(this.options.transition);}else{this.$note.addClass(this.options.transition);}}else{this.$note.addClass('fade').addClass('in');}if(this.options.type){this.$note.addClass('alert-'+this.options.type);}else{this.$note.addClass('alert-success');}if(!this.options.message&&this.$element.data("message")!==''){this.$note.html(this.$element.data("message"));}else{if(typeof this.options.message==='object'){if(this.options.message.html){this.$note.html(this.options.message.html);}else if(this.options.message.text){this.$note.text(this.options.message.text);}}else{this.$note.html(this.options.message);}}if(this.options.closable){var link=$('<a class="close pull-right" href="#">&times;</a>');}$(link).on('click',$.proxy(onClose,this));this.$note.prepend(link);return this;};var onClose=function(){this.options.onClose();$(this.$note).remove();this.options.onClosed();return false;};Notification.prototype.show=function(){if(this.options.fadeOut.enabled){this.$note.delay(this.options.fadeOut.delay||3000).fadeOut('slow',$.proxy(onClose,this));}this.$element.append(this.$note);this.$note.alert();};Notification.prototype.hide=function(){if(this.options.fadeOut.enabled){this.$note.delay(this.options.fadeOut.delay||3000).fadeOut('slow',$.proxy(onClose,this));}else{onClose.call(this);}};$.fn.notify=function(options){return new Notification(this,options);};$.fn.notify.defaults={type:'success',closable:true,transition:'fade',fadeOut:{enabled:true,delay:3000},message:null,onClose:function(){},onClosed:function(){}}})(window.jQuery);
/*Jquery Cookie*/(function(factory){if(typeof define==='function'&&define.amd){define(['jquery'],factory);}else if(typeof exports==='object'){factory(require('jquery'));}else{factory(jQuery);}}(function($){var pluses=/\+/g;function encode(s){return config.raw?s:encodeURIComponent(s);}function decode(s){return config.raw?s:decodeURIComponent(s);}function stringifyCookieValue(value){return encode(config.json?JSON.stringify(value):String(value));}function parseCookieValue(s){if(s.indexOf('"')===0){s=s.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,'\\');}try{s=decodeURIComponent(s.replace(pluses,' '));return config.json?JSON.parse(s):s;}catch(e){}}function read(s,converter){var value=config.raw?s:parseCookieValue(s);return $.isFunction(converter)?converter(value):value;}var config=$.cookie=function(key,value,options){if(value!==undefined&&!$.isFunction(value)){options=$.extend({},config.defaults,options);if(typeof options.expires==='number'){var days=options.expires,t=options.expires=new Date();t.setTime(+t+days*864e+5);}return(document.cookie=[encode(key),'=',stringifyCookieValue(value),options.expires?'; expires='+options.expires.toUTCString():'',options.path?'; path='+options.path:'',options.domain?'; domain='+options.domain:'',options.secure?'; secure':''].join(''));}var result=key?undefined:{};var cookies=document.cookie?document.cookie.split('; '):[];for(var i=0,l=cookies.length;i<l;i++){var parts=cookies[i].split('=');var name=decode(parts.shift());var cookie=parts.join('=');if(key&&key===name){result=read(cookie,value);break;}if(!key&&(cookie=read(cookie))!==undefined){result[name]=cookie;}}return result;};config.defaults={};$.removeCookie=function(key,options){if($.cookie(key)===undefined){return false;}$.cookie(key,'',$.extend({},options,{expires:-1}));return!$.cookie(key);};}));
/*Dynamite Table*/!function($){'use strict';$.fn.dynamitable=function(options){var dynamitable=this;var dynamitableCore=new(function($dynamitable){this.getIndex=function($field){return $field.parents('tr').children('td, th').index($field);};this.getBody=function(){return $dynamitable.find('tbody');};this.getRows=function(){return this.getBody().children('tr');};this.getField=function(index,$row){return $row.children('td, th').eq(index);};this.getValue=function(index,$row){return this.getField(index,$row).text();};})($(this));this.filterList=[];this.displayAll=function(){dynamitableCore.getRows().each(function(){$(this).find("td").removeAttr("style");$(this).show();});return this;};this.filter=function filter(index,matches){var regex=new RegExp(matches,'i');dynamitableCore.getRows().each(function(){if(true!==regex.test(dynamitableCore.getValue(index,$(this)))){$(this).hide();}else{if(matches){dynamitableCore.getField(index,$(this)).css("background","yellow");}}});return this;};this.addFilter=function addFilter(selector){dynamitable.filterList.push(selector);var filterAction=function(){dynamitable.displayAll();$(dynamitable.filterList).each(function(index,selector){$(dynamitable).find(selector).each(function(){var $this=$(this);dynamitable.filter(dynamitableCore.getIndex($this.parent('td, th')),$this.val());});});};$(selector).on('change keyup keydown',filterAction);return this;};this.addSorter=function addSorter(selector,order){$(dynamitable).find(selector).each(function(){var $this=$(this);var index=dynamitableCore.getIndex($this.parent('td, th'));$this.on('click',function(){dynamitable.sorter(index,order);});});return this;};this.sorter=function sorter(index,order){dynamitableCore.getBody().append(dynamitableCore.getRows().detach().sort(function(row_a,row_b){var value_a=dynamitableCore.getValue(index,$(row_a));var value_b=dynamitableCore.getValue(index,$(row_b));var order_desc=('desc'===order)?true:false;if(value_a.replace(/[^\d-]/g,'')!==''&&value_b.replace(/[^\d-]/g,'')!==''){value_a=parseFloat(value_a.replace(/[^\d,.-]/g,""));value_b=parseFloat(value_b.replace(/[^\d,.-]/g,""));}if(value_a===value_b){return 0;}return(value_a>value_b)?order_desc?1:-1:order_desc?-1:1;}));return this;};return this;};}(jQuery);
/*Table Export*/(function($){$.fn.extend({tableExport:function(options){var defaults={separator:',',ignoreColumn:[],tableName:'table',type:'csv',pdfFontSize:14,pdfLeftMargin:20,escape:'true',htmlContent:'false',consoleLog:'false'};var options=$.extend(defaults,options);var el=this;if(defaults.type==='csv'||defaults.type==='txt'){var tdData="";$(el).find('thead').find('tr').each(function(){tdData+="\n";$(this).filter(':visible').find('th').each(function(index,data){if($(this).css('display')!=='none'){if(defaults.ignoreColumn.indexOf(index)===-1){tdData+='"'+parseString($(this))+'"'+defaults.separator;}}});tdData=$.trim(tdData);tdData=$.trim(tdData).substring(0,tdData.length-1);});$(el).find('tbody').find('tr').each(function(){tdData+="\n";$(this).filter(':visible').find('td').each(function(index,data){if($(this).css('display')!=='none'){if(defaults.ignoreColumn.indexOf(index)===-1){tdData+='"'+parseString($(this))+'"'+defaults.separator;}}});tdData=$.trim(tdData).substring(0,tdData.length-1);});if(defaults.consoleLog==='true'){console.log(tdData);}var base64data="base64,"+$.base64.encode(tdData);var downloadLink=document.createElement("a");downloadLink.href='data:application/'+defaults.type+';filename=exportData;'+base64data;downloadLink.download="dataexport."+defaults.type;document.body.appendChild(downloadLink);downloadLink.click();document.body.removeChild(downloadLink);}else if(defaults.type==='sql'){var tdData="INSERT INTO `"+defaults.tableName+"` (";$(el).find('thead').find('tr').each(function(){$(this).filter(':visible').find('th').each(function(index,data){if($(this).css('display')!=='none'){if(defaults.ignoreColumn.indexOf(index)===-1){tdData+='`'+parseString($(this))+'`,';}}});tdData=$.trim(tdData);tdData=$.trim(tdData).substring(0,tdData.length-1);});tdData+=") VALUES ";$(el).find('tbody').find('tr').each(function(){tdData+="(";$(this).filter(':visible').find('td').each(function(index,data){if($(this).css('display')!=='none'){if(defaults.ignoreColumn.indexOf(index)===-1){tdData+='"'+parseString($(this))+'",';}}});tdData=$.trim(tdData).substring(0,tdData.length-1);tdData+="),";});tdData=$.trim(tdData).substring(0,tdData.length-1);tdData+=";";if(defaults.consoleLog==='true'){console.log(tdData);}var base64data="base64,"+$.base64.encode(tdData);var downloadLink=document.createElement("a");downloadLink.href='data:application/sql;filename=exportData;'+base64data;downloadLink.download="dataexport.sql";document.body.appendChild(downloadLink);downloadLink.click();document.body.removeChild(downloadLink);}else if(defaults.type==='json'){var jsonHeaderArray=[];$(el).find('thead').find('tr').each(function(){var tdData="";var jsonArrayTd=[];$(this).filter(':visible').find('th').each(function(index,data){if($(this).css('display')!=='none'){if(defaults.ignoreColumn.indexOf(index)===-1){jsonArrayTd.push(parseString($(this)));}}});jsonHeaderArray.push(jsonArrayTd);});var jsonArray=[];$(el).find('tbody').find('tr').each(function(){var tdData="";var jsonArrayTd=[];$(this).filter(':visible').find('td').each(function(index,data){if($(this).css('display')!=='none'){if(defaults.ignoreColumn.indexOf(index)===-1){jsonArrayTd.push(parseString($(this)));}}});jsonArray.push(jsonArrayTd);});var jsonExportArray=[];jsonExportArray.push({header:jsonHeaderArray,data:jsonArray});if(defaults.consoleLog==='true'){console.log(JSON.stringify(jsonExportArray));}var base64data="base64,"+$.base64.encode(JSON.stringify(jsonExportArray));var downloadLink=document.createElement("a");downloadLink.href='data:application/json;filename=exportData;'+base64data;downloadLink.download="dataexport.json";document.body.appendChild(downloadLink);downloadLink.click();document.body.removeChild(downloadLink);}else if(defaults.type==='xml'){var xml="<?php echo '<?xml version=\"1.0\" encoding=\"utf-8\"?>';?>";xml+='<tabledata><fields>';$(el).find('thead').find('tr').each(function(){$(this).filter(':visible').find('th').each(function(index,data){if($(this).css('display')!=='none'){if(defaults.ignoreColumn.indexOf(index)===-1){xml+="<field>"+parseString($(this))+"</field>";}}});});xml+='</fields><data>';var rowCount=1;$(el).find('tbody').find('tr').each(function(){xml+='<row id="'+rowCount+'">';var colCount=0;$(this).filter(':visible').find('td').each(function(index,data){if($(this).css('display')!=='none'){if(defaults.ignoreColumn.indexOf(index)===-1){xml+="<column-"+colCount+">"+parseString($(this))+"</column-"+colCount+">";}}colCount++;});rowCount++;xml+='</row>';});xml+='</data></tabledata>';if(defaults.consoleLog==='true'){console.log(xml);}var base64data="base64,"+$.base64.encode(xml);var downloadLink=document.createElement("a");downloadLink.href='data:application/xml;filename=exportData;'+base64data;downloadLink.download="dataexport.xml";document.body.appendChild(downloadLink);downloadLink.click();document.body.removeChild(downloadLink);}else if(defaults.type==='excel'||defaults.type==='doc'||defaults.type==='powerpoint'){var excel="<table>";$(el).find('thead').find('tr').each(function(){excel+="<tr>";$(this).filter(':visible').find('th').each(function(index,data){if($(this).css('display')!=='none'){if(defaults.ignoreColumn.indexOf(index)===-1){excel+="<td>"+parseString($(this))+"</td>";}}});excel+='</tr>';});var rowCount=1;$(el).find('tbody').find('tr').each(function(){excel+="<tr>";var colCount=0;$(this).filter(':visible').find('td').each(function(index,data){if($(this).css('display')!=='none'){if(defaults.ignoreColumn.indexOf(index)===-1){excel+="<td>"+parseString($(this))+"</td>";}}colCount++;});rowCount++;excel+='</tr>';});excel+='</table>';if(defaults.consoleLog==='true'){console.log(excel);}var excelFile="<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:"+defaults.type+"' xmlns='http://www.w3.org/TR/REC-html40'>";excelFile+="<head>";excelFile+="<!--[if gte mso 9]>";excelFile+="<xml>";excelFile+="<x:ExcelWorkbook>";excelFile+="<x:ExcelWorksheets>";excelFile+="<x:ExcelWorksheet>";excelFile+="<x:Name>";excelFile+="{worksheet}";excelFile+="</x:Name>";excelFile+="<x:WorksheetOptions>";excelFile+="<x:DisplayGridlines/>";excelFile+="</x:WorksheetOptions>";excelFile+="</x:ExcelWorksheet>";excelFile+="</x:ExcelWorksheets>";excelFile+="</x:ExcelWorkbook>";excelFile+="</xml>";excelFile+="<![endif]-->";excelFile+="</head>";excelFile+="<body>";excelFile+=excel;excelFile+="</body>";excelFile+="</html>";var base64data="base64,"+$.base64.encode(excelFile);var downloadLink=document.createElement("a");downloadLink.href='data:application/vnd.ms-'+defaults.type+';filename=exportData.doc;'+base64data;if(defaults.type==='excel'){downloadLink.download="dataexport.xls";}else if(defaults.type==='doc'){downloadLink.download="dataexport.doc";}else if(defaults.type==='powerpoint'){downloadLink.download="dataexport.ppt";}document.body.appendChild(downloadLink);downloadLink.click();document.body.removeChild(downloadLink);}else if(defaults.type==='png'){html2canvas($(el),{onrendered:function(canvas){var img=canvas.toDataURL("image/png");window.open(img);}});}else if(defaults.type==='pdf'){var doc=new jsPDF('p','pt','a4',true);doc.setFontSize(defaults.pdfFontSize);var startColPosition=defaults.pdfLeftMargin;$(el).find('thead').find('tr').each(function(){$(this).filter(':visible').find('th').each(function(index,data){if($(this).css('display')!=='none'){if(defaults.ignoreColumn.indexOf(index)===-1){var colPosition=startColPosition+(index*50);doc.text(colPosition,20,parseString($(this)));}}});});var startRowPosition=20;var page=1;var rowPosition=0;$(el).find('tbody').find('tr').each(function(index,data){rowCalc=index+1;if(rowCalc%26===0){doc.addPage();page++;startRowPosition=startRowPosition+10;}rowPosition=(startRowPosition+(rowCalc*10))-((page-1)*280);$(this).filter(':visible').find('td').each(function(index,data){if($(this).css('display')!=='none'){if(defaults.ignoreColumn.indexOf(index)===-1){var colPosition=startColPosition+(index*50);doc.text(colPosition,rowPosition,parseString($(this)));}}});});doc.output('datauri');}function parseString(data){if(defaults.htmlContent==='true'){content_data=data.html().trim();}else{content_data=data.text().trim();}if(defaults.escape==='true'){content_data=escape(content_data);}return content_data;}}});})(jQuery);
/*Jquery Base64*/jQuery.base64=(function($){var _PADCHAR="=",_ALPHA="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",_VERSION="1.0";function _getbyte64(s,i){var idx=_ALPHA.indexOf(s.charAt(i));if(idx===-1){throw"Cannot decode base64";}return idx;}function _decode(s){var pads=0,i,b10,imax=s.length,x=[];s=String(s);if(imax===0){return s;}if(imax%4!==0){throw"Cannot decode base64";}if(s.charAt(imax-1)===_PADCHAR){pads=1;if(s.charAt(imax-2)===_PADCHAR){pads=2;}imax-=4;}for(i=0;i<imax;i+=4){b10=(_getbyte64(s,i)<<18)|(_getbyte64(s,i+1)<<12)|(_getbyte64(s,i+2)<<6)|_getbyte64(s,i+3);x.push(String.fromCharCode(b10>>16,(b10>>8)&0xff,b10&0xff));}switch(pads){case 1:b10=(_getbyte64(s,i)<<18)|(_getbyte64(s,i+1)<<12)|(_getbyte64(s,i+2)<<6);x.push(String.fromCharCode(b10>>16,(b10>>8)&0xff));break;case 2:b10=(_getbyte64(s,i)<<18)|(_getbyte64(s,i+1)<<12);x.push(String.fromCharCode(b10>>16));break;}return x.join("");}function _getbyte(s,i){var x=s.charCodeAt(i);if(x>255){throw"INVALID_CHARACTER_ERR: DOM Exception 5";}return x;}function _encode(s){if(arguments.length!==1){throw"SyntaxError: exactly one argument required";}s=String(s);var i,b10,x=[],imax=s.length-s.length%3;if(s.length===0){return s;}for(i=0;i<imax;i+=3){b10=(_getbyte(s,i)<<16)|(_getbyte(s,i+1)<<8)|_getbyte(s,i+2);x.push(_ALPHA.charAt(b10>>18));x.push(_ALPHA.charAt((b10>>12)&0x3F));x.push(_ALPHA.charAt((b10>>6)&0x3f));x.push(_ALPHA.charAt(b10&0x3f));}switch(s.length-imax){case 1:b10=_getbyte(s,i)<<16;x.push(_ALPHA.charAt(b10>>18)+_ALPHA.charAt((b10>>12)&0x3F)+_PADCHAR+_PADCHAR);break;case 2:b10=(_getbyte(s,i)<<16)|(_getbyte(s,i+1)<<8);x.push(_ALPHA.charAt(b10>>18)+_ALPHA.charAt((b10>>12)&0x3F)+_ALPHA.charAt((b10>>6)&0x3f)+_PADCHAR);break;}return x.join("");}return{decode:_decode,encode:_encode,VERSION:_VERSION};}(jQuery));
/*Js Pdf Main*/var jsPDF=function(){var version='20090504';var buffer='';var pdfVersion='1.3';var defaultPageFormat='a4';var pageFormats={'a3':[841.89,1190.55],'a4':[595.28,841.89],'a5':[420.94,595.28],'letter':[612,792],'legal':[612,1008]};var textColor='0 g';var page=0;var objectNumber=2;var state=0;var pages=new Array();var offsets=new Array();var lineWidth=0.200025;var pageHeight;var k;var unit='mm';var fontNumber;var documentProperties={};var fontSize=16;var pageFontSize=16;if(unit==='pt'){k=1;}else if(unit==='mm'){k=72/25.4;}else if(unit==='cm'){k=72/2.54;}else if(unit==='in'){k=72;}var newObject=function(){objectNumber++;offsets[objectNumber]=buffer.length;out(objectNumber+' 0 obj');};var putHeader=function(){out('%PDF-'+pdfVersion);};var putPages=function(){var wPt=pageWidth*k;var hPt=pageHeight*k;for(n=1;n<=page;n++){newObject();out('<</Type /Page');out('/Parent 1 0 R');out('/Resources 2 0 R');out('/Contents '+(objectNumber+1)+' 0 R>>');out('endobj');p=pages[n];newObject();out('<</Length '+p.length+'>>');putStream(p);out('endobj');}offsets[1]=buffer.length;out('1 0 obj');out('<</Type /Pages');var kids='/Kids [';for(i=0;i<page;i++){kids+=(3+2*i)+' 0 R ';}out(kids+']');out('/Count '+page);out(sprintf('/MediaBox [0 0 %.2f %.2f]',wPt,hPt));out('>>');out('endobj');};var putStream=function(str){out('stream');out(str);out('endstream');};var putResources=function(){putFonts();putImages();offsets[2]=buffer.length;out('2 0 obj');out('<<');putResourceDictionary();out('>>');out('endobj');};var putFonts=function(){newObject();fontNumber=objectNumber;name='Helvetica';out('<</Type /Font');out('/BaseFont /'+name);out('/Subtype /Type1');out('/Encoding /WinAnsiEncoding');out('>>');out('endobj');};var putImages=function(){};var putResourceDictionary=function(){out('/ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');out('/Font <<');out('/F1 '+fontNumber+' 0 R');out('>>');out('/XObject <<');putXobjectDict();out('>>');};var putXobjectDict=function(){};var putInfo=function(){out('/Producer (jsPDF '+version+')');if(documentProperties.title!==undefined){out('/Title ('+pdfEscape(documentProperties.title)+')');}if(documentProperties.subject!==undefined){out('/Subject ('+pdfEscape(documentProperties.subject)+')');}if(documentProperties.author!==undefined){out('/Author ('+pdfEscape(documentProperties.author)+')');}if(documentProperties.keywords!==undefined){out('/Keywords ('+pdfEscape(documentProperties.keywords)+')');}if(documentProperties.creator!==undefined){out('/Creator ('+pdfEscape(documentProperties.creator)+')');}var created=new Date();var year=created.getFullYear();var month=(created.getMonth()+1);var day=created.getDate();var hour=created.getHours();var minute=created.getMinutes();var second=created.getSeconds();out('/CreationDate (D:'+sprintf('%02d%02d%02d%02d%02d%02d',year,month,day,hour,minute,second)+')');};var putCatalog=function(){out('/Type /Catalog');out('/Pages 1 0 R');out('/OpenAction [3 0 R /FitH null]');out('/PageLayout /OneColumn');};function putTrailer(){out('/Size '+(objectNumber+1));out('/Root '+objectNumber+' 0 R');out('/Info '+(objectNumber-1)+' 0 R');}var endDocument=function(){state=1;putHeader();putPages();putResources();newObject();out('<<');putInfo();out('>>');out('endobj');newObject();out('<<');putCatalog();out('>>');out('endobj');var o=buffer.length;out('xref');out('0 '+(objectNumber+1));out('0000000000 65535 f ');for(var i=1;i<=objectNumber;i++){out(sprintf('%010d 00000 n ',offsets[i]));}out('trailer');out('<<');putTrailer();out('>>');out('startxref');out(o);out('%%EOF');state=3;};var beginPage=function(){page++;state=2;pages[page]='';pageHeight=pageFormats['a4'][1]/k;pageWidth=pageFormats['a4'][0]/k;};var out=function(string){if(state===2){pages[page]+=string+'\n';}else{buffer+=string+'\n';}};var _addPage=function(){beginPage();out(sprintf('%.2f w',(lineWidth*k)));pageFontSize=fontSize;out('BT /F1 '+parseInt(fontSize)+'.00 Tf ET');};_addPage();var pdfEscape=function(text){return text.replace(/\\/g,'\\\\').replace(/\(/g,'\\(').replace(/\)/g,'\\)');};return{addPage:function(){_addPage();},text:function(x,y,text){if(pageFontSize!==fontSize){out('BT /F1 '+parseInt(fontSize)+'.00 Tf ET');pageFontSize=fontSize;}var str=sprintf('BT %.2f %.2f Td (%s) Tj ET',x*k,(pageHeight-y)*k,pdfEscape(text));out(str);},setProperties:function(properties){documentProperties=properties;},addImage:function(imageData,format,x,y,w,h){},output:function(type,options){endDocument();if(type===undefined){return buffer;}if(type==='datauri'){document.location.href='data:application/pdf;base64,'+Base64.encode(buffer);}},setFontSize:function(size){fontSize=size;}};};                
/*Js Pdf Sprintf*/function sprintf(){var regex=/%%|%(\d+\$)?([-+\'#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuidfegEG])/g;var a=arguments,i=0,format=a[i++];var pad=function(str,len,chr,leftJustify){if(!chr)chr=' ';var padding=(str.length>=len)?'':Array(1+len-str.length>>>0).join(chr);return leftJustify?str+padding:padding+str;};var justify=function(value,prefix,leftJustify,minWidth,zeroPad,customPadChar){var diff=minWidth-value.length;if(diff>0){if(leftJustify||!zeroPad){value=pad(value,minWidth,customPadChar,leftJustify);}else{value=value.slice(0,prefix.length)+pad('',diff,'0',true)+value.slice(prefix.length);}}return value;};var formatBaseX=function(value,base,prefix,leftJustify,minWidth,precision,zeroPad){var number=value>>>0;prefix=prefix&&number&&{'2':'0b','8':'0','16':'0x'}[base]||'';value=prefix+pad(number.toString(base),precision||0,'0',false);return justify(value,prefix,leftJustify,minWidth,zeroPad);};var formatString=function(value,leftJustify,minWidth,precision,zeroPad,customPadChar){if(precision!==null){value=value.slice(0,precision);}return justify(value,'',leftJustify,minWidth,zeroPad,customPadChar);};var doFormat=function(substring,valueIndex,flags,minWidth,_,precision,type){var number;var prefix;var method;var textTransform;var value;if(substring==='%%')return'%';var leftJustify=false,positivePrefix='',zeroPad=false,prefixBaseX=false,customPadChar=' ';var flagsl=flags.length;for(var j=0;flags&&j<flagsl;j++)switch(flags.charAt(j)){case' ':positivePrefix=' ';break;case'+':positivePrefix='+';break;case'-':leftJustify=true;break;case"'":customPadChar=flags.charAt(j+1);break;case'0':zeroPad=true;break;case'#':prefixBaseX=true;break;}if(!minWidth){minWidth=0;}else if(minWidth==='*'){minWidth=+a[i++];}else if(minWidth.charAt(0)==='*'){minWidth=+a[minWidth.slice(1,-1)];}else{minWidth=+minWidth;}if(minWidth<0){minWidth=-minWidth;leftJustify=true;}if(!isFinite(minWidth)){throw new Error('sprintf: (minimum-)width must be finite');}if(!precision){precision='fFeE'.indexOf(type)>-1?6:(type==='d')?0:void(0);}else if(precision==='*'){precision=+a[i++];}else if(precision.charAt(0)==='*'){precision=+a[precision.slice(1,-1)];}else{precision=+precision;}value=valueIndex?a[valueIndex.slice(0,-1)]:a[i++];switch(type){case's':return formatString(String(value),leftJustify,minWidth,precision,zeroPad,customPadChar);case'c':return formatString(String.fromCharCode(+value),leftJustify,minWidth,precision,zeroPad);case'b':return formatBaseX(value,2,prefixBaseX,leftJustify,minWidth,precision,zeroPad);case'o':return formatBaseX(value,8,prefixBaseX,leftJustify,minWidth,precision,zeroPad);case'x':return formatBaseX(value,16,prefixBaseX,leftJustify,minWidth,precision,zeroPad);case'X':return formatBaseX(value,16,prefixBaseX,leftJustify,minWidth,precision,zeroPad).toUpperCase();case'u':return formatBaseX(value,10,prefixBaseX,leftJustify,minWidth,precision,zeroPad);case'i':case'd':{number=parseInt(+value);prefix=number<0?'-':positivePrefix;value=prefix+pad(String(Math.abs(number)),precision,'0',false);return justify(value,prefix,leftJustify,minWidth,zeroPad);}case'e':case'E':case'f':case'F':case'g':case'G':{number=+value;prefix=number<0?'-':positivePrefix;method=['toExponential','toFixed','toPrecision']['efg'.indexOf(type.toLowerCase())];textTransform=['toString','toUpperCase']['eEfFgG'.indexOf(type)%2];value=prefix+Math.abs(number)[method](precision);return justify(value,prefix,leftJustify,minWidth,zeroPad)[textTransform]();}default:return substring;}};return format.replace(regex,doFormat);}
/*Js Pdf Base64*/var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(input){var output="";var chr1,chr2,chr3,enc1,enc2,enc3,enc4;var i=0;input=Base64._utf8_encode(input);while(i<input.length){chr1=input.charCodeAt(i++);chr2=input.charCodeAt(i++);chr3=input.charCodeAt(i++);enc1=chr1>>2;enc2=((chr1&3)<<4)|(chr2>>4);enc3=((chr2&15)<<2)|(chr3>>6);enc4=chr3&63;if(isNaN(chr2)){enc3=enc4=64;}else if(isNaN(chr3)){enc4=64;}output=output+this._keyStr.charAt(enc1)+this._keyStr.charAt(enc2)+this._keyStr.charAt(enc3)+this._keyStr.charAt(enc4);}return output;},decode:function(input){var output="";var chr1,chr2,chr3;var enc1,enc2,enc3,enc4;var i=0;input=input.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(i<input.length){enc1=this._keyStr.indexOf(input.charAt(i++));enc2=this._keyStr.indexOf(input.charAt(i++));enc3=this._keyStr.indexOf(input.charAt(i++));enc4=this._keyStr.indexOf(input.charAt(i++));chr1=(enc1<<2)|(enc2>>4);chr2=((enc2&15)<<4)|(enc3>>2);chr3=((enc3&3)<<6)|enc4;output=output+String.fromCharCode(chr1);if(enc3!==64){output=output+String.fromCharCode(chr2);}if(enc4!==64){output=output+String.fromCharCode(chr3);}}output=Base64._utf8_decode(output);return output;},_utf8_encode:function(string){string=string.replace(/\r\n/g,"\n");var utftext="";for(var n=0;n<string.length;n++){var c=string.charCodeAt(n);if(c<128){utftext+=String.fromCharCode(c);}else if((c>127)&&(c<2048)){utftext+=String.fromCharCode((c>>6)|192);utftext+=String.fromCharCode((c&63)|128);}else{utftext+=String.fromCharCode((c>>12)|224);utftext+=String.fromCharCode(((c>>6)&63)|128);utftext+=String.fromCharCode((c&63)|128);}}return utftext;},_utf8_decode:function(utftext){var string="";var i=0;var c=c1=c2=0;while(i<utftext.length){c=utftext.charCodeAt(i);if(c<128){string+=String.fromCharCode(c);i++;}else if((c>191)&&(c<224)){c2=utftext.charCodeAt(i+1);string+=String.fromCharCode(((c&31)<<6)|(c2&63));i+=2;}else{c2=utftext.charCodeAt(i+1);c3=utftext.charCodeAt(i+2);string+=String.fromCharCode(((c&15)<<12)|((c2&63)<<6)|(c3&63));i+=3;}}return string;}};


function setLoading(progress, timeout) {
    progress = progress || 0;
    timeout = timeout || 1000;
	
    $('.i_progress .progress .progress-bar').css('width',progress + '%');
	if (progress < 100) {
		$('.i_progress .progress').show();
	
	} else {
		setTimeout(function() {
			
            $('.i_progress .progress .progress-bar').css('width',progress + '%');
			$('.i_progress .progress').hide()
		
		}, timeout);
	}
	return;
} /*End setLoading*/
/*Start setNotify*/
function notify(type, message) {
	$('.notifications').notify({
		message: {html: '<i class="fa fa-exclamation"></i> ' + message},
        fadeOut: {enabled: true,delay: 10000},
		type: type,
		closable: true
	}).show();
	return ;
} /*End Notify*/


(function ($) {
    var ajax = function (element, options) {
        var selector = element.selector;
        var options = $.extend(true, {}, $.fn.ajax.defaults, options);
        var data = {};
        var tag_panel_form = '';
        var tag_panel_table = '';

        return $(document).on(options.trigger, selector, function (e) {
            e.preventDefault();
            e.stopPropagation();

            data['request'] = options.type;


            if (typeof $(this).attr('<?php echo $this->security->get_csrf_token_name() ;?>') !== 'undefined') {
                data['<?php echo $this->security->get_csrf_token_name() ;?>'] = $(this).attr('<?php echo $this->security->get_csrf_token_name();?>');
            }
            if(typeof $.cookie !== 'undefined'){
                data['<?php echo $this->security->get_csrf_token_name();?>'] = $.cookie("<?php echo $this->config->item("csrf_cookie_name"); ?>");
            }
            
            if (typeof $(this)[0].pathname !== 'undefined') {
                window.history.pushState('string', 'title', $(this)[0].pathname);
            }

            
            if (typeof $(this).attr('id_generator') !== 'undefined') {
                data['id_generator'] = $(this).attr('id_generator');
            }
            if (typeof $(this).attr('i_pagination_page') !== 'undefined') {
                data['i_pagination_page'] = $(this).attr('i_pagination_page');
            }
            
            if (typeof $(options.tag).find('.i_select_pagesize') !== 'undefined') {
                data['page_size'] = $(options.tag).find('.i_select_pagesize').val();
            }
            
            if (typeof $(this).attr('id_grid') !== 'undefined') {
                options.tag = '#' + $(this).attr('id_grid');
                tag_panel_form = tag_show = options.tag + ' #panel-form';
                tag_panel_table = tag_show = options.tag + ' #panel-table';
               
            }
            
           

            $(selector).each(function () {
                $(this).parent().removeClass('active');
            });
            $(this).parent().addClass('active');
            
            
            $.ajax({
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    /*Upload progress*/
                    xhr.upload.addEventListener("progress", function (e) {
                        if (e.lengthComputable) {
                            var percentComplete = (100 * e.loaded / e.total);
                            setLoading(percentComplete);
                        }
                    }, false);
                    /*Download progress*/

                    xhr.addEventListener("progress", function (e) {
                        if (e.lengthComputable) {
                            var percentComplete = (100 * e.loaded / e.total);
                            setLoading(percentComplete);
                        }
                    }, false);
                    return xhr;
                },
               
                url: $(this).attr('href'),
                type: "POST",
                data: data,
                dataType: 'html',
                beforeSend: function (xhr) {
                    setLoading(0);
                    $(options.tag).find('.i_loading').show();
                }
            }).done(function (result) {
                if (typeof $(this).attr('title') !== 'undefined') {
                    document.title = $(this).attr('title') ;
                }
                if (typeof $(this).attr('data-original-title') !== 'undefined') {
                    document.title = $(this).attr('data-original-title') ;
                }
                
                if (options.modal === false) {
                    switch (options.type) {
                        case "html":
                            $(options.tag).html(result).fadeIn(5000);
                            break;
                        case "prepend":
                            $(options.tag).prepend(result).fadeIn(5000);
                            break;
                        case "append":
                            $(options.tag).append(result).fadeIn(5000);
                            break;
                        case "before" :
                            $(result).insertBefore(options.tag);
                            $(this).attr('id', Number(data['id']) + Number(1));
                            break;
                        case "after" :
                            $(result).insertAfter(options.tag);
                            break;
                        case "form"  :
                            if (typeof $(tag_panel_table) !== 'undefined') {
                                $(tag_panel_table).fadeOut(0);
                            }
                            if (typeof $(tag_panel_form) !== 'undefined') {
                                if (typeof $(tag_panel_form).find('.panel-body') !== 'undefined') {
                                    $(tag_panel_form).find('.panel-body').html(result).fadeIn(1000);
                                }
                                $(tag_panel_form).fadeIn(2000);

                                if (typeof $(tag_panel_form).find('.i_link_back') !== 'undefined') {

                                }
                            }
                            break;
                        case "paging":
                        case "refresh":
                            //$(options.tag).parent().html(result).fadeIn(1000);
                            //$(options.tag).fadeOut(500);
                            $(result).insertAfter(options.tag);
                            $(options.tag).remove();
                            //$(options.tag).fadeIn(1000);
                            break;
                    }
                } else {
                    var modal = $('#i_modal');
                    modal.modal();
                    modal.find('.modal-title').html(document.title);
                    modal.find('.modal-body').html(result);
                }

                init();

            }).fail(function (result, status, e) {
               
                if (e === null) {
                    notify('error', '<?php echo @$this->config->item("internet_error") ;?>');
                } else {
                    notify('error', e);
                }
            }).always(function (result, status, request) {
                /*$('html, body').animate({
                 scrollTop: $(options.tag).offset().top + -100
                 }, 500);*/
                setLoading(100);

                setTimeout(function () {
                    $(options.tag).find('.i_loading').hide();
                }, 500);
            });

            return false;
        });

    };
    $.fn.ajax = function (options) {
        return new ajax(this, options);
    };
    $.fn.ajax.defaults = {
        trigger: 'click',
        type: 'html',
        tag: '.contents',
        reinitialize: true,
        modal: false
    };
})(window.jQuery);

(function($) {
    var ajax_confirm = function(element,options) {
        var selector = element.selector;
        var options = $.extend(true, {}, $.fn.ajax_confirm.defaults, options);
        var data = {};
        var url = '';
        var confirm = '';
        
        return $(document).on('click',selector,function(e){
            e.preventDefault();
            e.stopPropagation();
            var $this = $(this);
    
            if (typeof $(this).attr('href') != 'undefined') {
                url = $(this).attr('href');
            }
            if (typeof $(this).attr('data-confirm') != 'undefined') {
                confirm = $(this).attr('data-confirm');
            }
            if (typeof $(this).attr('id') != 'undefined') {
                data['id'] = $(this).attr('id');
            }
            
             if (typeof $(this).attr('action') != 'undefined') {
                data['action'] = $(this).attr('action');
            }
            
			
            
             bootbox.dialog({
                message: confirm,
                title: "Konfirmasi",
                buttons: {
                    success: {
                        label: '<i class="fa fa-trash"></i> Hapus',
                        className: "btn-success",
                        callback: function() {
                            $.ajax({
                                xhr: function() {
                                    var xhr = new window.XMLHttpRequest();
                                    /*Upload progress*/
                                    xhr.upload.addEventListener("progress", function(e) {if (e.lengthComputable) {var percentComplete = (100 * e.loaded / e.total);setLoading(percentComplete);}}, false);
                                    /*Download progress*/
                                    xhr.addEventListener("progress", function(e) {if (e.lengthComputable) {var percentComplete = (100 * e.loaded / e.total);setLoading(percentComplete);}}, false);
                                    return xhr;
                                },
                                url: url,
                                type: "POST",
                                data: data,
                                dataType: 'json',
                                beforeSend: function(xhr) {
                                    setLoading(0);
                                }
                            }).done(function(result) {
                                notify(result.status, result.message);
                                switch (options.type) {
                                    case "html": $(options.tag).html(result).fadeIn(1000); break;
                                    case "prepend": $(options.tag).prepend(result).fadeIn(1000); break;
                                    case "append": $(options.tag).append(result).fadeIn(1000); break;
                                    case "remove" :
                                        if (typeof data['id']  != 'undefined') {
                                            $('.table tr#' + data['id']).fadeOut('slow').html('');
                                        }
                                        $this.parent().parent().fadeOut('slow').html('');
                                    break;
                                }
                                
                                if(options.reinitialize == true){
                                    initialize_app();
                                }
                                init();
                            }).fail(function(result, status, e) {
                                if (e == null) {
                                    notify('error', '<?php echo @$this->config->item("internet_error") ;?>');
                                } else {
                                    notify('error', e);
                                }
                            }).always(function(result, status, request) {
                                setLoading(100);
                            });
                        }
                    },
                    danger: {
                        label: '<i class="fa fa-close"></i> Batal',
                        className: "btn-danger",
                        callback: function() {
                            notify('error','Penghapusan data dibatalkan');
                        }
                    }
                }
             });
            
            
			
            
            return false;
        });
        
	};
	$.fn.ajax_confirm = function(options) {
	    return new ajax_confirm(this,options);
	}
	$.fn.ajax_confirm.defaults = {
		type: '',
		tag: '',
        reinitialize : true
	}
})(window.jQuery);


(function($) {
    var refresh_form = function(element) {
        var selector = element.selector;
        var btn_reset = $(selector + ' button[type="reset"]');
        if (typeof btn_reset !== 'undefined') {
            return btn_reset.click(function(){
                $(selector).data('bootstrapValidator').resetForm(false);
            });
        }
    };
    var refresh_captcha = function(element) {
        var selector = element.selector;
        var btn_captcha = $(selector + ' button[type="captcha"]');
        var data = {};
	if (typeof btn_captcha!== 'undefined') {
            return btn_captcha.click(function(e) {
		e.preventDefault();
                data['act'] =  'refresh';
                $.ajax({
                    /*Download progress*/
                    xhr: function(){
                        var xhr = new window.XMLHttpRequest();
                        xhr.addEventListener("progress", function(e){
                            if (e.lengthComputable) {
                                var percentComplete = (100 * e.loaded / e.total);
                                setLoading(percentComplete);
                            }
                        }, false);
                        return xhr;
                    },
                    beforeSend: function(xhr) {setLoading(0);},
                    url : btn_captcha.attr('action'),
                    type: "POST",
                    data : data,
                    dataType : 'json'
                }).done(function(result) {
                    $(selector +" #captcha_image").html(result.captcha_image);
                    $(selector + " #captcha_word").val(result.captcha_word);
                }).fail(function(result,status, e) {
                    if(e === null){
                        notify('error', '<?php echo $this->config->item("internet_error") ;?>' );
                    }else{
                        notify('error', e );
                    }
                }).always(function(result,status,request) {
                    setLoading(100);
                });
                return false;
            });
	}
    };
    
    var set_form = function(element, readonly, reset) {
        if (readonly) {
            $(element + ' :input').attr('readonly', 'readonly');
            $(element + ' :button').attr('readonly', 'readonly');
	} else {
            $(element + ' :input').removeAttr('readonly');
            $(element + ' :button').removeAttr('readonly');
            $(element + ' :button').removeAttr('disabled');
	}
        $(element + ' :input').blur();
        $(element + ' :button').blur();
	
        if (reset) {
            if (typeof $('button[name="reset"]') !== 'undefined') {
		$('button[name="reset"]').trigger('click');
            }
            if (typeof $('button[class="fileinput-remove"]')!== 'undefined') {
                $('button[class="fileinput-remove"]').trigger('click');
            }
            if (typeof $('#btn_refresh_captcha') !== 'undefined') {
                $('#btn_refresh_captcha').click();
            }
	}
	return false;
    };
    
    $.fn.initialize_form = function(options) {
        var $element = $(this);
        var options = $.extend(true, {}, $.fn.initialize_form.defaults, options);
        var selector = this.selector;
        var formdata = false;
        var files = {};
        var input_file = $(selector + ' input[type="file"]');
        var button_confirm = $(selector + ' button[name="confirm"]');
        if (typeof input_file !== 'undefined') {
            input_file.change(function(e) {
                e.preventDefault();
                files = e.target.files;
                $(this).blur();
                return false;
            });
        }
        if (typeof button_confirm !== 'undefined') {
            button_confirm.click(function(e) {
                e.preventDefault();
                var message = '';
                if(typeof $(this).attr('data-confirm') !== 'undefined'){
                    message = $(this).attr('data-confirm');
                }
                bootbox.dialog({
                    message: message,
                    title: "Konfirmasi",
                    buttons: {
                        success: {
                            label: '<i class="fa fa-check"></i> Ya',
                            className: "btn-success",
                            callback: function() {
                                $element.submit();
                            }
                        },
                        danger: {
                            label: '<i class="fa fa-close"></i> Tidak',
                            className: "btn-danger",
                            callback: function() {}
                        }
                    }
                });
            });
        }
        return new (function(e){
            $element.refresh_form();
            $element.refresh_captcha();
            $element.bootstrapValidator({
                container: 'tooltip',
                autoFocus : false,
                live : 'disabled', 
                trigger: 'blur',
                message: 'Isian kolom tidak benar', 
                feedbackIcons: { 
                    valid: 'glyphicon glyphicon-ok', 
                    invalid: 'glyphicon glyphicon-remove', 
                    validating: 'glyphicon glyphicon-refresh'
                }
            }).on('error.field.bv', function(e, data) {
                e.preventDefault();
                e.stopPropagation();
                
            }).on('error.form.bv', function(e, data) {
                e.preventDefault();
                e.stopPropagation();
                
            }).on('success.form.bv', function(e) {
                e.preventDefault(); 
                e.stopPropagation();
                var $form = $(e.target);
                var data;
                var processData = true;
                var contentType = true;
                
                
                
                if (typeof $form.attr('enctype')!== 'undefined') {
                    if($form.attr('enctype') === 'multipart/form-data'){
                        formdata = true;
                    }
                }
                if (typeof $form.attr('id_grid')!== 'undefined') {
                    options.tag = "#" + $form.attr('id_grid');
                }
                
                data = new FormData();
                
                data.append('request',options.datatype );
    
                if(formdata === true){
                    processData = false;
                    contentType = false;
                    
                    if (typeof files!== 'undefined') {
                        for (var i = 0; i < files.length; i++) {
                            var file = files[i];
                            //console.log(file.type);
                            //if (!file.type.match('image.*') || !file.type.equal('application/pdf') ) {continue;}
                            data.append(input_file.attr('name'), file,file.name);
                        }
                    }
                }
                var inputform = $form.serializeArray();
               
               
                for (var i = 0; i < inputform.length; i++) {
                    inp = {};
                    var input = inputform[i];
                    data.append(input.name, input.value);
                    
                }
                if(typeof $.cookie !== 'undefined'){
                     //data.set("<?php echo $this->security->get_csrf_token_name();?>",$.cookie("<?php echo $this->config->item('csrf_cookie_name'); ?>"));
                }
                
               
                 
                if(typeof CKEDITOR !== 'undefined'){
                    for(var i in CKEDITOR.instances) {

                        CKEDITOR.instances[i]; 
                        CKEDITOR.instances[i].name;
                        CKEDITOR.instances[i].value;  
                        CKEDITOR.instances[i].updateElement();
                        var str =  CKEDITOR.instances[i].getData();
                        str = str.replace('style','xxxxx');
                        inputform[$(CKEDITOR.instances[i].element).attr('name')] = CKEDITOR.instances[i].getData();
                        data.append($(CKEDITOR.instances[i].element).attr('name') ,str);

                    }
                }
                
                 
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest(); 
                        /*Upload progress*/
                        xhr.upload.addEventListener("progress", function(e) {
                            if (e.lengthComputable) {
                                var percentComplete = (100 * e.loaded / e.total);
                                setLoading(percentComplete);
                            }
                        }, false);
                        /*Download Progress*/
                        xhr.addEventListener("progress", function(e) {
                            if (e.lengthComputable) {
                                var percentComplete = (100 * e.loaded / e.total);
                                setLoading(percentComplete);
                            }
                        }, false);
                        return xhr;
                    },
                    url: $form.attr('action'),
                    type: "POST",
                    data: data,
                    dataType: options.datatype,
                    processData: false,
                    contentType: false,
                    beforeSend: function(xhr) {
                        new set_form(selector, true, false);
                        setLoading(0);
                    }
                }).done(function(result) {
                    if(options.datatype === 'json'){
                        notify(result.status, result.message);
                        if (result.status === 'success' || result.status === 'warning') {
                           
                            switch (options.type) {
                                case "html": $(options.tag).html(result.html).fadeIn(5000); break;
                                case "prepend": $(options.tag).prepend(result.html).fadeIn(5000);break;
                                case "append": $(options.tag).append(result.html).fadeIn(5000); break;
                                case "redirect" : 
                                if(result.redirect !== ""){
                                    setTimeout(function() {
                                        window.location.assign("<?php echo site_url();?>" + result.redirect ); 
                                    }, 2000);
                                }

                                break;
                            }

                            if(options.reinitialize === true){

                            }
                            if (typeof $form.find('input[name="action"]').val()!== 'undefined') {
                                if($form.find('input[name="action"]').val() === 'create'){
                                    new set_form(selector, false, true);
                                }else{
                                    new set_form(selector, false, false);
                                }
                            }else{
                                 new set_form(selector, false, false);
                            }

                        }
                    }else{
                        switch (options.type) {
                            case "html": $(options.tag).html(result).fadeIn(1000); break;
                            case "prepend": $(options.tag).prepend(result).fadeIn(1000);break;
                            case "append": $(options.tag).append(result).fadeIn(1000); break;
                            break;
                        }

                        
                        if (typeof $form.find('input[name="action"]').val()!== 'undefined') {
                            if($form.find('input[name="action"]').val() === 'create'){
                                new set_form(selector, false, true);
                            }else{
                                new set_form(selector, false, false);
                            }
                        }else{
                             new set_form(selector, false, false);
                        }
                    }
                        
                    
                }).fail(function(result, status, e) {
                    if (e === null) {
                        notify('error', '<?php echo $this->config->item("internet_error") ;?>');
                    } else {
                        notify('error', e);
                    }
                    
                }).always(function(result, status, request) {
                    new set_form(selector, false, false);
                    setLoading(100);
                    if(options.reinitialize === true){
                        init();
                    }
                    
                });
            });
        });
    };
    
    $.fn.refresh_form = function() {
            return new refresh_form(this);
    };
    $.fn.refresh_captcha = function() {
            return new refresh_captcha(this);
    };
    $.fn.initialize_form.defaults = {
        type: 'html',
        tag: '',
        reinitialize : false,
        datatype : 'json'
    };

})(window.jQuery);


function init() {
    /*window.onload = disable_back();
    window.onpageshow = function (e) {
        if (e.persisted)
            disable_back();
    }*/
    $('[data-toggle="tooltip"]').tooltip({placement: 'top'});
    $('[data-toggle="popover"]').popover({placement: 'top', trigger: 'focus'});
    $('#i_modal').modal({backdrop: true,keyboard: true,show: false});

    //$('.i_table').resizableColumns({store: window.store});

    $(document).find('.i_table').each(function (){
        $(this).dynamitable().addFilter('.i_filter').addSorter('.js-sorter-asc', 'asc').addSorter('.js-sorter-desc', 'desc');
    });
    $('.i_form_search').initialize_form({datatype: 'html', reinitialize : true}); 
    $('.i_form_input').initialize_form(); 
    
}

$(function() {
    init();
    
    $('.i_link').ajax({type : 'html',reinitialize : false}); 
    $('.i_link_refresh').ajax({type : 'refresh',reinitialize : false}); 
    $('.i_link_create').ajax({type : 'form',reinitialize : false}); 
    $('.i_link_update').ajax({type : 'form',reinitialize : false, modal : false}); 
    $('.i_link_paging').ajax({type : 'paging',reinitialize : false}); 
    $('.i_link_back').ajax({type : 'refresh',reinitialize : false}); 
    $('.i_select_pagesize').ajax({type : 'refresh', trigger: 'change', reinitialize : false});
    
    $('.i_link_delete').ajax_confirm({type : 'remove',tag : '#page-wrapper',reinitialize : false});
   
});

$(document).on("click", '.panel-heading a.i_link_search', function (e) {
    e.preventDefault();
    if ($(this).hasClass('panel-collapsed')) {
        // expand the panel
        $(this).parents('.panel').find('.panel-body').slideDown();
        $(this).removeClass('panel-collapsed');
        $(this).addClass('active');
        //$(this).find('i').removeClass('fa-search').addClass('fa-close');
    }
    else {
        // collapse the panel
        $(this).parents('.panel').find('.panel-body').slideUp();
        $(this).addClass('panel-collapsed');
        $(this).removeClass('active');
        //$(this).find('i').removeClass('fa-close').addClass('fa-search');
    }
});






</script>