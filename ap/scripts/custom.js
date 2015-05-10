
/*show and hide div*/
function showDiv(divID)
        {
            var divstyle = new String();
            divstyle = document.getElementById(divID).style.visibility;
            
                document.getElementById(divID).style.visibility = "visible";
                document.getElementById(divID).style.height = "auto";
           
        }
		
		function HideDiv(divID)
        {
            var divstyle = new String();
            divstyle = document.getElementById(divID).style.visibility;
            
                document.getElementById(divID).style.visibility = "hidden";
                document.getElementById(divID).style.height = "0px";
           
            
        }


/*date and time*/
function StartClock12() { 
Time12 = new Date(); 
Cur12Hour = Time12.getHours(); 
Cur12Mins = Time12.getMinutes(); 
Cur12Secs = Time12.getSeconds(); 
The12Time = (Cur12Hour > 12) ? Cur12Hour - 12 : Cur12Hour; 
The12Time += ((Cur12Mins < 10) ? ':0' : ':') + Cur12Mins; 
The12Time += ((Cur12Secs < 10) ? ':0' : ':') + Cur12Secs; 
The12Time += (Cur12Hour > 12) ? ' PM': ' AM'; 
document.CForm.Clock12.value = The12Time; 
window.status = The12Time; 
setTimeout('StartClock12()',1000); 
} 

function StartDate() { 
TDay = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
TMonth = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'); 
TDate = new Date(); 
CurYear = TDate.getYear(); 
CurMonth = TDate.getMonth(); 
CurDayOw = TDate.getDay(); 
CurDay= TDate.getDate(); 
TheDate = TDay[CurDayOw] + ', '; 
TheDate += TMonth[CurMonth] + ' '; 
TheDate += CurDay + ', '; 
TheDate += ((CurYear%1900)+1900); 
document.CForm.CDate.value = TheDate; 
}