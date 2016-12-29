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
					data: eval('(function(){return ('+val+');})();')
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
	getJsonAttr: function(encodedValue){
		return Helpers.getEvaluated(decodeURIComponent(encodedValue).replace(/%25/g, '%'));
	}
}