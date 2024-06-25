function removei( str ){
	var i = str.length;
	var sub= str;
	if ( i > 9){
		if(str.endsWith(" i "))
			sub=str.substr(0,i-2);
		i=sub.length;
		if ( i > 9){
		if(sub.endsWith(" i "))
			sub=sub.substr(0,i-2);
		}
	}
	return sub;
}
function submitfrm(){
	
	$.ajax({
            type: 'post',
            url: 'exec.php',
            dataType: 'text',
            data: $('form').serialize(),
            beforeSend: function() {
                $('#submit').attr('disabled', true);
				$('#myDIV').before('<span class="wait"><img src="/ajax-loader.gif" alt="" /></span>');
                $('#myDIV').html('&nbsp;&nbsp;.........................!!! running remote task now, please waiting for result!!!.............................');
            },
            complete: function() {
                $('#submit').attr('disabled', false);
                $('.wait').remove();
            }, 
            success: function ( data ) {
            	//var txt=data.text;
              //alert(data);
               $('#myDIV').html(data);
            }
          });
	

}
function submitfrm2(){
	var inputObj = document.getElementById( "myDIV" );  
    var fm = document.getElementById( "fm" );  
	fm.submit();
	inputObj.innerHTML = " !!! running remote task now, please waiting for result!!!" ;

}
function change( frm ){
	var inputObj = document.getElementById( "cmd" );  
	if( inputObj ) {  
		inputObj.value = frm.value;
		if(frm.value == 'shutdown'){
		var r=confirm("are you sure for shutdown this machine !!!");
		if (r==true) submitfrm();
		} else submitfrm();		
	} 
}
function change2( frm ){
	var cmd="";
	var inputObj = document.getElementById( "cmd" ); 	
    var TK = document.getElementById( "TK" );
    var SRV = document.getElementById( "SRV" );  	
	var VER = document.getElementById( "VER" );
    var URL = document.getElementById( "URL" ); 
    var a = document.getElementById( "agent" ); 
	var op = document.getElementById( "op" );  	
	var os = document.getElementById( "os" );
    var bool=1; 	
	if( inputObj ) {  
		var iURL=URL.value.trim();
		var iTK=TK.value.trim();
		var iVER=VER.value.trim();
		var ia=a.value.trim();
		var iop=op.value.trim();
		var ios=os.value.trim();
        var cmd2='';		
		if( iTK.length == 0 ) iTK= 'i' ;
		if( iVER.length == 0 ) iVER= 'i' ;
		if( op.value == 'd')  {// d
			cmd = "play "+ a.value +" d " + URL.value;
			if( iVER != 'i') cmd = cmd + " " + iVER ;		
			if(iURL.length == 0) bool=0;
	    }else{			
			cmd = a.value+" "+op.value;   // i,x,di
            if( op.value == 'di') cmd = a.value+" i"; 		
			if( a.value == 'f')
				cmd ="play " + cmd;			
			else 
				if ( os.value == 'win' ) cmd = "run "+ cmd;
				else cmd = "play "+ cmd;
			if( op.value != 'x' ) {		    				
				if(op.value == 'di'){
					cmd2 = "play "+ a.value +" d " + URL.value;
					if( iVER != 'i') cmd2 = cmd2 + " " + iVER ;
					cmd2 = cmd2 + "\r\nwait 2\r\n";
					if(iURL.length == 0) bool=0;    
				} 
				if( a.value == 'f') cmd = cmd +" "+iVER + " " + SRV.value;
				else cmd = cmd +" "+ iTK + " " +iVER + " " + SRV.value;
			}
			
		}						
		inputObj.value = cmd2+removei(cmd);			
		if(ia.length == 0 || iop.length == 0 || ios.length == 0 ) bool = 0;	
		
	} 
	if( bool === 1) {
		var r=confirm("are you sure for this command?\r\n" + inputObj.value);
		if (r==true) submitfrm();
	}else
		alert(" Hey, You might missing some parameter !!! ");
	
}
function change3(){
	var inputObj = document.getElementById( "cmd0" );  
    var OP = document.getElementById( "op" );  
	var tk = document.getElementById("TeamKey");  
	var url = document.getElementById("URLdiv");
	var ex = document.getElementById("extra");
	if (op.value === "di") {
        tk.style.display = "block";
		url.style.display = "block";
		ex.style.display = "block";
		
    } 
    if (op.value === "d") {
        tk.style.display = "none";
		url.style.display = "block";
		ex.style.display = "block";
		
    } 
	if (op.value === "i") {
        tk.style.display = "block";
		url.style.display = "none";
		ex.style.display = "block";
    }
	if (op.value === "x") {
        tk.style.display = "none";
		url.style.display = "none";
		ex.style.display = "none";
    }
}
function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
	if ((new Date().getTime() - start) > milliseconds){
	  break;
	}
   }
}