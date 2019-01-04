// get evaluated string helper
var Helpers = {
	getEvaluated: function(val){
		var result;
		var failResult = {
			success: false,
			data: val
		};
		if (String(val).length > 0) {
			try{
				result = {
					success: true,
					//data: eval('(function(){return ('+val+');})();')
					data: JSON.parse(val)
				}
			}catch(e){
				result = failResult;
				result.message = '[Evaluation error] ' + e.message;
			}
		} else {
			result = failResult;
			result.message = '[Evaluation error] No data from: ' + arguments.callee.caller + '  from: ' + arguments.callee.caller.caller;
		}
		if (!result.success) {
			log(result);
		}
		return result;
	},
	// get live javascript code from json_attr template helper
	getJsonAttr: function (encodedValue) {
		/*var decodeURIComponent(encodedValue)
			.replace(/%(?=[\da-fA-F]{2})/gi, function () {
				// PHP tolerates poorly formed escape sequences
				return '%25'
			})*/
		
		return Helpers.getEvaluated(
			decodeURIComponent(encodedValue)
				.replace(/%(?=[\da-f]{2})/gi, function () {
					// PHP tolerates poorly formed escape sequences
					return '%25'
				})
		);
	}
}
