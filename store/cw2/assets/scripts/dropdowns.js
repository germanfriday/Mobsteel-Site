function setDynaList(arrDL){
 var theForm = findObj(arrDL[2]);var theForm2 = findObj(arrDL[4]);var oList1 = theForm.elements[arrDL[1]];
 var oList2 = theForm2.elements[arrDL[3]];var arrList = arrDL[5];clearDynaList(oList2);
 if (oList1.selectedIndex == -1){oList1.selectedIndex = 0;}
 populateDynaList(oList2, oList1[oList1.selectedIndex].value, arrList);return true;
}
 
function clearDynaList(oList){
 for (var i = oList.options.length; i >= 0; i--){oList.options[i] = null;}oList.selectedIndex = -1;
}
 
function populateDynaList(oList, nIndex, aArray){
 for (var i = 0; i < aArray.length; i= i + 3){
  if (aArray[i] == nIndex){oList.options[oList.options.length] = new Option(aArray[i + 1], aArray[i + 2]);}}
 if (oList.options.length == 0){oList.options[oList.options.length] = new Option("[none available]",0);}oList.selectedIndex = 0;
}

function findObj(theObj, theDoc){
  var p, i, foundObj;if(!theDoc) theDoc = document;if( (p = theObj.indexOf("?")) > 0 && parent.frames.length)
  {theDoc = parent.frames[theObj.substring(p+1)].document;theObj = theObj.substring(0,p);}
  if(!(foundObj = theDoc[theObj]) && theDoc.all) foundObj = theDoc.all[theObj];
  for (i=0; !foundObj && i < theDoc.forms.length; i++)foundObj = theDoc.forms[i][theObj];
  for(i=0; !foundObj && theDoc.layers && i < theDoc.layers.length; i++)foundObj = findObj(theObj,theDoc.layers[i].document);
  if(!foundObj && document.getElementById) foundObj = document.getElementById(theObj);return foundObj;
}
