



/***************************************************************************************************************/

function ReplaceSelectorText(selector, newText) {
	$(selector+" option").remove();
    $(selector).append('<option value="0"> '+ newText +' </option>');
    $(selector).trigger("chosen:updated");
}






function saveSessData(varName, varValue) {
	//$("#collector").load('sess_write.php?sessionVarName='+variable+'&sessionVarValue='+value);
	$("#"+varName).val(varValue);
}



/**************************************************************************************************************************/

/**
 * Implements JSON stringify and parse functions
 * v1.0
 *
 * By Craig Buckler, Optimalworks.net
 *
 * As featured on SitePoint.com
 * Please use as you wish at your own risk.
*
 * Usage:
 *
 * // serialize a JavaScript object to a JSON string
 * var str = JSON.stringify(object);
 *
 * // de-serialize a JSON string to a JavaScript object
 * var obj = JSON.parse(str);
 */

var JSON = JSON || {};

// implement JSON.stringify serialization
JSON.stringify = JSON.stringify || function (obj) {

	var t = typeof (obj);
	if (t != "object" || obj === null) {

		// simple data type
		if (t == "string") obj = '"'+obj+'"';
		return String(obj);

	}
	else {

		// recurse array or object
		var n, v, json = [], arr = (obj && obj.constructor == Array);

		for (n in obj) {
			v = obj[n]; t = typeof(v);

			if (t == "string") v = '"'+v+'"';
			else if (t == "object" && v !== null) v = JSON.stringify(v);

			json.push((arr ? "" : '"' + n + '":') + String(v));
		}

		return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
	}
};


// implement JSON.parse de-serialization
JSON.parse = JSON.parse || function (str) {
	if (str === "") str = '""';
	eval("var p=" + str + ";");
	return p;
};


/**
 * Implements cookie-less JavaScript session variables
 * v1.0
 *
 * By Craig Buckler, Optimalworks.net
 *
 * As featured on SitePoint.com
 * Please use as you wish at your own risk.
*
 * Usage:
 *
 * // store a session value/object
 * Session.set(name, object);
 *
 * // retreive a session value/object
 * Session.get(name);
 *
 * // clear all session data
 * Session.clear();
 *
 * // dump session data
 * Session.dump();
 */

 if (JSON && JSON.stringify && JSON.parse) var Session = Session || (function() {

	// window object
	var win = window.top || window;

	// session store
	var store = (win.name ? JSON.parse(win.name) : {});

	// save store on page unload
	function Save() {
		win.name = JSON.stringify(store);
	};

	// page unload event
	if (window.addEventListener) window.addEventListener("unload", Save, false);
	else if (window.attachEvent) window.attachEvent("onunload", Save);
	else window.onunload = Save;

	// public methods
	return {

		// set a session variable
		set: function(name, value) {
			store[name] = value;
		},

		// get a session value
		get: function(name) {
			return (store[name] ? store[name] : undefined);
		},

		// clear session
		clear: function() { store = {}; },

		// dump session data
		dump: function() { return JSON.stringify(store); }

	};

 })();




 /************************************************************************
 	INDIVIDUAL SCREENS FUNCTIONS
 ***************************************************************************/

// From Index.php

/* FAST SEARCH */
		$("#searchField").keyup(function(){
			if ($(this).val()) {
				var search = $(this).val().toLowerCase();
				$(".locations").hide();
				$("[data-searchTerm*='"+search+"']").show();
			} else {
				$(".locations").show();
			}
		});

		$("#searchField").change(function(){
			if ($(this).val()) {
				var search = $(this).val().toLowerCase();
				$(".locations").hide();
				$("[data-searchTerm*='"+search+"']").show();
			} else {
				$(".locations").show();
			}
		});

/****************/



	var ret = $("#returnTransferCheck").prop('checked');

	if (ret == 1) {
		$("#showReturn").show(700);
		$("#returnTransfer").val('1');
	} else {
		$("#showReturn").hide(500);
		$("#returnTransfer").val('0');
	}

	$("#returnTransferCheck").click(function() {
		var ret = $("#returnTransferCheck").is(':checked');
		if (ret == 1) {
			$("#showReturn").show(700);
			$("#returnTransfer").val('1');
		} else {
			$("#showReturn").hide(500);
			$("#returnTransfer").val('0');
		}
	});

/*
	select replacement, od onog tipa sa youtube
*/
/*
		$("select").live("change", function(e) {
			var v = $(this).val();
			var $op = $(this).find("option[value='"+v+"']");
			$(this).parents(".selWrap").find("span").text($op.text());
		});
*/
