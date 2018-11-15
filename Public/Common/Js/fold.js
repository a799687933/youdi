/*
* 折叠分类列表,表格形式
*/
function rowClicked(obj,tableId,clsaaImgUrl){
  if(!arguments[1]) tableId = "list-table";	
  var Browser = new Object();
  var imgPlus = new Image();
  imgPlus.src = clsaaImgUrl+"/menu_plus.gif";
  // 当前图像
  img = obj;
 
  // 取得上二级tr>td>img对象
  obj = obj.parentNode.parentNode;
  // 整个分类列表表格
  var tbl = document.getElementById(tableId);
  // 当前分类级别
  var lvl = parseInt(obj.className);
 
  // 是否找到元素
  var fnd = false;
  var sub_display = img.src.indexOf('menu_minus.gif') > 0 ? 'none' : (Browser.isIE) ? 'block' : 'table-row' ;
     
  // 遍历所有的分类
  for (i = 0; i < tbl.rows.length; i++){
      
      var row = tbl.rows[i];
      if (row == obj){
          // 找到当前行
          fnd = true;
          //document.getElementById('result').innerHTML += 'Find row at ' + i +"<br/>";
      }else{
          if (fnd == true){
              var cur = parseInt(row.className);
              var icon = 'icon_' + row.id;
              if (cur > lvl){
                  row.style.display = sub_display;
                  if (sub_display != 'none'){
                      var iconimg = document.getElementById(icon);
                      iconimg.src = iconimg.src.replace('plus.gif', 'minus.gif');
                  }
              }else{
                  fnd = false;
                  break;
              }
          }
      }
  }
  
  for (i = 0; i < obj.cells[0].childNodes.length; i++){
      var imgObj = obj.cells[0].childNodes[i];
      if (imgObj.tagName == "IMG" && imgObj.src != clsaaImgUrl+'/menu_arrow.gif'){
          imgObj.src = (imgObj.src == imgPlus.src) ? clsaaImgUrl+'/menu_minus.gif' : imgPlus.src;
      }
  }
  
}
