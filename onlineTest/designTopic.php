<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>添加题目</title>
<link href="./css/designTopic.css" rel="stylesheet" type="text/css">
<script type="text/javascript">

    /**
     * 验证题目，防止老师忘记设置答案
     * @param formName
     * @returns {boolean}
     */
    function add(formName){
        var questionTitle = "";
        var answer = "";
        var optionTitle = "";
        //先将题目临时存储在内存中，如果存储在.txt文件
        //则会由于过于频繁的操作外存导致效率低下
        var formObj = document.forms.namedItem(formName);
        var formID = formObj.id;
        questionTitle = formObj.ownerDocument.getElementsByName(formID+"title")[0].value;
        //获得选项内容
        var answerList = null;
        if(formObj.id == "singleChoice" || formObj.id == "mulChoice"){
            var firstDiv = formObj.ownerDocument.getElementsByClassName(formID+"div_radio")[0];
            var subDivList = firstDiv.getElementsByTagName("div");
            //遍历选项div---取出选项对应的题目内容
            for(var i = 0;i<subDivList.length;i++){
                var inputArr = subDivList[i].getElementsByTagName("input");
                if(inputArr[1].value.length <= 0){
                    optionTitle += inputArr[0].value;
                }
                if(inputArr[0].checked){
                    answer += inputArr[0].value;
                }
            }
        }else if(formObj.id == "fillBlank"){
            answer = formObj.ownerDocument.getElementsByName("fillBlankAnswer")[0].value;    //获得答案
        }

        if(questionTitle.length <= 0){
            alert("请至少添加一个题目");
            return false;
        }

        if(answer.length <= 0){
            alert("请为题目添加至少一个正确的答案");
            return false;
        }

        if(optionTitle.length > 0){
            alert("请为"+optionTitle+"选项添加标题");
            return false;
        }

        return true;
    }

</script>

<script type="text/javascript">
	function MM_changeProp(objId,theProp,theValue) {
		if(objId=="singleChoice"){
			window.document.getElementById("mulChoice").style.display="none";
			window.document.getElementById("fillBlank").style.display="none";
		}else if(objId=="mulChoice"){
			window.document.getElementById("singleChoice").style.display="none";
			window.document.getElementById("fillBlank").style.display="none";
		}else if(objId=="fillBlank"){
			window.document.getElementById("singleChoice").style.display="none";
			window.document.getElementById("mulChoice").style.display="none";
		}
		var obj = null; 
		with (document){ 
			if (getElementById)
	  			obj = getElementById(objId); 
		}
	  	if (obj){
			if (theValue == true || theValue == false)
		  		eval("obj.style."+theProp+"="+theValue);
			else 
				eval("obj.style."+theProp+"='"+theValue+"'");
	  	}
	}

	function addxx(number){
		var i=1;
		flag=true;
		while(flag){
			s="input"+number+"_"+i;
			if(window.document.getElementById(s)){
				i=i+1;
			}else{
				flag=false;
			}
		}
		var span=document.getElementById("xuanxiang"+number);
		var div=document.createElement("div");
		span.appendChild(div);
		var input1=document.createElement("input");
		var input2=document.createElement("input");
		var label=document.createElement("label");
		var xuan=String.fromCharCode(i+64);
		if(number==1){  //添加单选的选项
			input1.setAttribute('type','radio');
			input1.setAttribute('name','singleChoice');
			input1.onclick=function(){answer(1)};
		}else{  //添加多选的选项
			input1.setAttribute('type','checkbox');
			input1.setAttribute('name','mulChoice[]');
			input1.onclick=function(){answer(2)};
		}
		input1.setAttribute('value',xuan);
		input1.setAttribute('id',s);
		label.innerHTML="选项"+xuan+"：";
		//input2表示后面的文本编辑框
		input2.setAttribute('type','text');
		//设置name值---按序号来，1~n依次为A，B，C，。。。
        input2.setAttribute('name',""+i);
		input2.setAttribute('size','40');
		div.appendChild(input1);
		div.appendChild(label);
		div.appendChild(input2);
	}
	
	function answer(number){
		var span=document.getElementById("answer"+number);
		var s="";
		if(number==1){
			var temp=document.getElementsByName("singleChoice");
			for(i=0;i<temp.length;i++){
				if(temp[i].checked){
					s=temp[i].value;
				}
			}
			span.innerHTML=s;
		}else{
			var temp=document.getElementsByName("mulChoice[]");
			for(i=0;i<temp.length;i++){
				if(temp[i].checked){
					s+=temp[i].value;
				}
			}
			span.innerHTML=s;
		}
	}
	
	function Delete(number){
		var i=1;
		flag=true;
		while(flag){
			s="input"+number+"_"+i;
			if(window.document.getElementById(s)){
				i=i+1;
			}else{
				flag=false;
			}
		}
		s="input"+number+"_"+(i-1);
		window.document.getElementById(s).parentNode.parentNode.removeChild(window.document.getElementById(s).parentNode);
	}
</script>
</head>

<body>
	<div class="div_all">
    <div class="div_all_top">
        <div class="div_all_title">在线考试系统</div>
      	<div class="div_all_ke" style="text-align: center">
            <!--<a style="text-decoration: none;cursor: pointer;" href="releaseTest.php"><span style="margin-left:60px;border: 1px solid;border-radius: 5px">BACK</span></a>-->
            <span>请选择要添加的题目类型</span>



        </div>
		<div class="div_all_ke">
			<span class="div_span_submit" style="margin-left: 20%" onClick="MM_changeProp('singleChoice','display','block')">单选题</span>
			<span class="div_span_submit" style="margin-left: 20%" onClick="MM_changeProp('mulChoice','display','block')">多选题</span>
			<span class="div_span_submit" style="margin-left: 20%" onClick="MM_changeProp('fillBlank','display','block')">填空题</span>
		</div>
    </div>
    <!--单选题-->
	<form name="formSingle" id="singleChoice" style="display: none" ACTION="releaseTest.php" METHOD="post">

        <input type="hidden" name="type" value="Single">

        <div style="margin-top: 10px;" >
			<div style="margin-top:20px;background-color: #999999;height: 50px;width: 80%;margin-left: 10%;line-height: 50px;font-weight: bold;">
				<span> &nbsp;&nbsp;&nbsp;&nbsp;单选题</span>
			</div>
			<!--选择题-->
			<div class="div_all_selected">
				<div class="singleChoicediv_radio">
					<h4>题目：<a name="selected1"><input type="text" name="singleChoicetitle" size="60"/></a>
						<!--<img src="./img/add.png" alt="添加" style="margin-left: 5%;width: 20px;height: 20px;" onClick="addxx(1)"/>
						<img src="./img/sub.png" alt="删除" style="margin-left: 5%;width: 20px;height: 20px;" onClick="Delete(1);answer(1)"/>-->
					</h4>
                    <h4>
                        分数：<a name="selected1"><input type="number" name="score" size="20"/></a>
                        <img src="./img/add.png" alt="添加" style="margin-left: 5%;width: 20px;height: 20px;" onClick="addxx(1)"/>
                        <img src="./img/sub.png" alt="删除" style="margin-left: 5%;width: 20px;height: 20px;" onClick="Delete(1);answer(1)"/>
                    </h4>
					<span id="xuanxiang1">

                    </span>
					<h4 style="color: #FF0004">正确答案：<span id="answer1"></span></h4>
				</div>
			</div>
            <a href="javascript:formSingle.submit();" style="text-decoration: none;">
                <span class="div_span_submit" style="margin-left: 20%" onClick="return add('formSingle');">确认发布</span>
            </a>
		</div>
	</form>

	<!--多选题-->
	<form name="formMul" id="mulChoice" style="display: none" ACTION="releaseTest.php" METHOD="post">

        <input type="hidden" name="type" value="Mul">

        <div style="margin-top: 10px;">
			<div style="margin-top:20px;background-color: #999999;height: 50px;width: 80%;margin-left: 10%;line-height: 50px;font-weight: bold;">
				<span> &nbsp;&nbsp;&nbsp;&nbsp;多选题</span>
			</div>
			<!--多选题-->
			<div class="div_all_more">
				<div class="mulChoicediv_radio">
					<h4>题目：<a name="more1"><input type="text" name="mulChoicetitle" size="60"/></a></h4>
                    <h4>
                        分数：<a name="more1"><input type="number" name="score" size="20"/></a>
                        <img src="./img/add.png" alt="添加" style="margin-left: 5%;width: 20px;height: 20px;" onClick="addxx(2)"/>
                        <img src="./img/sub.png" alt="删除" style="margin-left: 5%;width: 20px;height: 20px;" onClick="Delete(2);answer(2)"/>
                    </h4>
					<span id="xuanxiang2">
			  	<!--<div><input type="checkbox" value="A" name="mulChoice1" id="input2_1" onClick="answer(2)"/><label>选项A：</label><input type="text" size="40"/></div>
				<div><input type="checkbox" value="B" name="mulChoice1" id="input2_2" onClick="answer(2)"/><label>选项B：</label><input type="text" size="40"/></div>
				<div><input type="checkbox" value="C" name="mulChoice1" id="input2_3" onClick="answer(2)"/><label>选项C：</label><input type="text" size="40"/></div>
				<div><input type="checkbox" value="D" name="mulChoice1" id="input2_4" onClick="answer(2)"/><label>选项D：</label><input type="text" size="40"/></div>-->
				    </span>
					<h4 style="color: #FF0004" >正确答案：<span id="answer2"></span></h4>
				</div>
			</div>
            <a href="javascript:formMul.submit();" style="text-decoration: none;">
			    <span class="div_span_submit" style="margin-left: 20%" onClick="return add('formMul')">确认发布</span>
            </a>
		</div>
	</form>

	<!--填空题-->
	<form name="formFill" id="fillBlank" style="display: none" ACTION="releaseTest.php" METHOD="post">
        <input type="hidden" name="type" value="Fill">
        <div style="margin-top: 10px;">
			<!--填空题-->
			<div style="margin-top:20px;background-color: #999999;height: 50px;width: 80%;margin-left: 10%;line-height: 50px;font-weight: bold;">
				<span>&nbsp;&nbsp;&nbsp;&nbsp;填空题</span>
			</div>
			<div class="div_all_input">
				<div class="div_input_text_area">
					<h4>题目：<a name="input1"><input type="text" name="fillBlanktitle" size="60"/></a></h4>
                    <h4>分数：<a name="input1"><input type="number" name="score" size="20"/></a></h4>
					<!--<div><textarea placeholder="请输入您的答案……" class="text_area" name="fillBlank"></textarea></div>-->
					<h4 style="color: #FF0004" >正确答案：<input type="text" name="fillBlankAnswer"></h4>
				</div>
			</div>
            <a href="javascript:formFill.submit();" style="text-decoration: none;">
			    <span class="div_span_submit" style="margin-left: 20%" onClick="return add('formFill')">确认发布</span>
            </a>
		</div>
	</form>

</div>
	
</body>
</html>
