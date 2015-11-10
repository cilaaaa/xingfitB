var mth = new Date().getMonth()+1;
var year = new Date().getFullYear();
var day = new Date().getDate();

$(".timestr").html(year+"-"+mth+"-"+day);
$(".cila-next").click(function(){
	var time = $(".timestr").html().split('-');
	var y = parseInt(time[0]);
	var m = parseInt(time[1]);
	var d = parseInt(time[2]);
	$(".timestr").html(changeTime(y,m,d,"add"));
}) ;
$(".cila-prev").click(function(){
	var time = $(".timestr").html().split('-');
	var y = parseInt(time[0]);
	var m = parseInt(time[1]);
	var d = parseInt(time[2]);
	$(".timestr").html(changeTime(y,m,d,"sub"));
}) ;
function changeTime(y,m,d,type){
	var dayCount = 0; 
	if (type=="add") {
		d +=1;
		switch (m) { 
			case 1: 
			case 3: 
			case 5: 
			case 7: 
			case 8: 
			case 10: 
			case 12: 
			dayCount = 31; 
			break; 
			case 4: 
			case 6: 
			case 9: 
			case 11: 
			dayCount = 30; 
			break; 
			case 2: 
			dayCount = 28; 
			if ((y % 4 == 0) && (y % 100 != 0) || (y % 400 == 0)) { 
				dayCount = 29; 
			} 
			break; 
			default: 
			break; 
		}
		if (d>dayCount) {
			m +=1;
			d=1;
		};
		if (m>12) {
			m=1;
			y+=1;
		};
		return y+"-"+m+"-"+d;
	}else{
		d -=1;
		if (d<1) {
			m -=1;
			switch (m) { 
				case 1: 
				case 3: 
				case 5: 
				case 7: 
				case 8: 
				case 10: 
				case 12: 
				dayCount = 31; 
				break; 
				case 4: 
				case 6: 
				case 9: 
				case 11: 
				dayCount = 30; 
				break; 
				case 2: 
				dayCount = 28; 
				if ((y % 4 == 0) && (y % 100 != 0) || (y % 400 == 0)) { 
					dayCount = 29; 
				} 
				break; 
				default: 
				break; 
			}
			d=dayCount;
		};

		if (m<1) {
			m=12;
			y-=1;
		};
		return y+"-"+m+"-"+d;
	};
}