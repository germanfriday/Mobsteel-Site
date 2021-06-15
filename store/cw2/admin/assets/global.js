function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
/*
Preload image used as a background image in the navigation.
See admin/assets/cartweaver.css for declaration: 
#leftNav a.leftNav:hover, #leftNav a.leftNavOpen
*/
MM_preloadImages('assets/images/expand.gif')

function dwfaq_getCSSPropertyValue(obj,cP,jP){//v1.1 
//Copyright © 2004-2005 Angela C. Buraglia & DWfaq.com
//All Rights Reserved. Not for distribution. support@dwfaq.com
//Support Newsgroup: news://support.dwfaq.com/support
	if(typeof(obj)!='object'){var obj=document.getElementById(obj);}
	if(typeof(obj.currentStyle)!='object'){
		return (typeof(document.defaultView) == 'object' && document.defaultView.getComputedStyle(obj,''))?
		document.defaultView.getComputedStyle(obj,'').getPropertyValue(cP):
		obj.style.getPropertyValue(cP);}
	else{
		return (navigator.appVersion.indexOf('Mac')!=-1)?
		obj.currentStyle.getPropertyValue(cP):
		obj.currentStyle.getAttribute((jP)?jP:cP);}
}

function dwfaq_ToggleOMaticDisplay(){//v1.0
//Copyright © 2004 Angela C. Buraglia & DWfaq.com
//All Rights Reserved. Not for distribution. support@dwfaq.com
//Support Newsgroup: news://support.dwfaq.com/support
	var obj,cS,args=dwfaq_ToggleOMaticDisplay.arguments;document.MM_returnValue=(typeof(args[0].href)!='string')?true:false;
	for(var i=1;i<args.length;i++){obj=document.getElementById(args[i]);
		if(obj){cS=dwfaq_getCSSPropertyValue(obj,'display');
			if(!obj.dwfaq_OD){obj.dwfaq_OD=(cS!='none'&&cS!='')?cS:(obj.tagName.toUpperCase()=='TR' && cS!=='none')?'':
			(obj.tagName.toUpperCase()=='TR' && typeof(obj.currentStyle)!='object')?'table-row':'block';}
			obj.style.display=(cS!='none')?'none':obj.dwfaq_OD}}
}

function dwfaq_ToggleOMaticClass(){//v1.1
//Copyright © 2004-2005 Angela C. Buraglia & DWfaq.com
//All Rights Reserved. Not for distribution. support@dwfaq.com
//Support Newsgroup: news://support.dwfaq.com/support
	var obj,args=dwfaq_ToggleOMaticClass.arguments;document.MM_returnValue=(typeof(args[0].href)!='string')?true:false;
	for(var i=1;i<args.length-1;i+=2){obj=document.getElementById(args[i]);
		if(obj){if(!obj.dwfaq_OC){obj.dwfaq_OC=(obj.className=='')?true:obj.className;}
			if(obj.dwfaq_OC&&obj.className==args[i+1]){
				(obj.dwfaq_OC==true)?obj.className='':obj.className=obj.dwfaq_OC;}
			else{obj.className=args[i+1];}}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function YY_checkform() { //v4.71
//copyright (c)1998,2002 Yaromat.com
  var a=YY_checkform.arguments,oo=true,v='',s='',err=false,r,o,at,o1,t,i,j,ma,rx,cd,cm,cy,dte,at;
  for (i=1; i<a.length;i=i+4){
    if (a[i+1].charAt(0)=='#'){r=true; a[i+1]=a[i+1].substring(1);}else{r=false}
    o=MM_findObj(a[i].replace(/\[\d+\]/ig,""));
    o1=MM_findObj(a[i+1].replace(/\[\d+\]/ig,""));
    v=o.value;t=a[i+2];
    if (o.type=='text'||o.type=='password'||o.type=='hidden'){
      if (r&&v.length==0){err=true}
      if (v.length>0)
      if (t==1){ //fromto
        ma=a[i+1].split('_');if(isNaN(v)||v<ma[0]/1||v > ma[1]/1){err=true}
      } else if (t==2){
        rx=new RegExp("^[\\w\.=-]+@[\\w\\.-]+\\.[a-zA-Z]{2,4}$");if(!rx.test(v))err=true;
      } else if (t==3){ // date
        ma=a[i+1].split("#");at=v.match(ma[0]);
        if(at){
          cd=(at[ma[1]])?at[ma[1]]:1;cm=at[ma[2]]-1;cy=at[ma[3]];
          dte=new Date(cy,cm,cd);
          if(dte.getFullYear()!=cy||dte.getDate()!=cd||dte.getMonth()!=cm){err=true};
        }else{err=true}
      } else if (t==4){ // time
        ma=a[i+1].split("#");at=v.match(ma[0]);if(!at){err=true}
      } else if (t==5){ // check this 2
            if(o1.length)o1=o1[a[i+1].replace(/(.*\[)|(\].*)/ig,"")];
            if(!o1.checked){err=true}
      } else if (t==6){ // the same
            if(v!=MM_findObj(a[i+1]).value){err=true}
      }
    } else
    if (!o.type&&o.length>0&&o[0].type=='radio'){
          at = a[i].match(/(.*)\[(\d+)\].*/i);
          o2=(o.length>1)?o[at[2]]:o;
      if (t==1&&o2&&o2.checked&&o1&&o1.value.length/1==0){err=true}
      if (t==2){
        oo=false;
        for(j=0;j<o.length;j++){oo=oo||o[j].checked}
        if(!oo){s+='* '+a[i+3]+'\n'}
      }
    } else if (o.type=='checkbox'){
      if((t==1&&o.checked==false)||(t==2&&o.checked&&o1&&o1.value.length/1==0)){err=true}
    } else if (o.type=='select-one'||o.type=='select-multiple'){
      if(t==1&&o.selectedIndex/1==0){err=true}
    }else if (o.type=='textarea'){
      if(v.length<a[i+1]){err=true}
    }
    if (err){s+='* '+a[i+3]+'\n'; err=false}
  }
  if (s!=''){alert('The required information is incomplete or contains errors:\t\t\t\t\t\n\n'+s)}
  document.MM_returnValue = (s=='');
}
