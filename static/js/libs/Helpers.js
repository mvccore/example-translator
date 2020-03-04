// get evaluated string helper
var Helpers = {
	getJsonFromString: function(val){
		var result = {
			success: true,
			data: null,
			message: null
		};
		if (String(val).length > 0) {
			try{
				result.data = JSON.parse(val);
			}catch(e){
				result.success = false;
				result.message = '[Evaluation error] ' + e.message;
			}
		} else {
			result = false;
			result.message = '[Evaluation error] No data from: ' + arguments.callee.caller + '  from: ' + arguments.callee.caller.caller;
		}
		if (!result.success) 
			console.log(result);
		return result;
	},
	// get live javascript code from json_attr template helper
	getJsonAttr: function (encodedValue) {
		return Helpers.getJsonFromString(
			decodeURIComponent(encodedValue)
				.replace(/%(?=[\da-f]{2})/gi, function () {
					// PHP tolerates poorly formed escape sequences
					return '%25'
				})
		);
	}
}
