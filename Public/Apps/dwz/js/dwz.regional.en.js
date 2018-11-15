/**
 * @author ZhangHuihua@msn.com
 */
(function($){
	// jQuery validate
	$.extend($.validator.messages, {
		required: "A required field!",
		remote: "Please amend the field!",
		email: "Please input the correct e-mail!",
		url: "Please enter a valid URL!",
		date: "Please enter a valid date",
		dateISO: "Please enter a valid date (ISO).",
		number: "Please enter a valid number!",
		digits: "Only the input integer!",
		creditcard: "Please enter a valid credit card number!",
		equalTo: "Please input again the same value!",
		accept: "Please input have legitimate suffix name string!",
		maxlength: $.validator.format("A maximum of  {0} strings!"),
		minlength: $.validator.format("At least {0} of the length of the string!"),
		rangelength: $.validator.format("String length between {0} and {1}!"),
		range: $.validator.format("Please enter a value between  {0} and {1} between the value!"),
		max: $.validator.format("Please enter a maximum value {0}!"),
		min: $.validator.format("Please enter a minimum value  {0}!"),
		
		alphanumeric: "Letters of the alphabet, numbers, underline",
		lettersonly: "Must be a letter",
		phone: "Digital, spaces, brackets"
	});
	
	// DWZ regional
	$.setRegional("datepicker", {
		dayNames: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		monthNames: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec']
	});
	$.setRegional("alertMsg", {
		title:{error:"Error", info:"Prompt", warn:"Warning", correct:"Success", confirm:"A confirmation prompt"},
		butMsg:{ok:"Confirm", yes:"Yes", no:"Not", cancel:"Cancel"}
	});
})(jQuery);