t = function() {
	function t() {
	this.checkUrlLimit = e(this.checkUrlLimit, this), this.urlLimit = e(this.urlLimit, this), this.urlCount = e(this.urlCount, this), this.nextUrlId = e(this.nextUrlId, this), this.toggleDeletable = e(this.toggleDeletable, this), this.toggleAddButton = e(this.toggleAddButton, this), this.removeTag = e(this.removeTag, this), this.addTagsOnBlur = e(this.addTagsOnBlur, this), this.addTagOnEnter = e(this.addTagOnEnter, this), this.deleteVariable = e(this.deleteVariable, this), this.addVariable = e(this.addVariable, this), this.showVariables = e(this.showVariables, this), this.showRawBody = e(this.showRawBody, this), this.showKeyValParams = e(this.showKeyValParams, this), this.toggleParamsType = e(this.toggleParamsType, this), this.hasPayloadFile = e(this.hasPayloadFile, this), this.deleteRawBody = e(this.deleteRawBody, this), this.deletePayloadFile = e(this.deletePayloadFile, this), this.deleteParam = e(this.deleteParam, this), this.addParam = e(this.addParam, this), this.hideParams = e(this.hideParams, this), this.showParams = e(this.showParams, this), this.getParamsSection = e(this.getParamsSection, this), this.deleteHeader = e(this.deleteHeader, this), this.addHeader = e(this.addHeader, this), this.showHeaders = e(this.showHeaders, this), this.deleteParameter = e(this.deleteParameter, this), this.moveSectionDown = e(this.moveSectionDown, this), this.moveSectionUp = e(this.moveSectionUp, this), this.copyUrl = e(this.copyUrl, this), this.deleteUrl = e(this.deleteUrl, this), this.checkRequestType = e(this.checkRequestType, this), this.checkHosts = e(this.checkHosts, this), this.addNewUrl = e(this.addNewUrl, this), this.setScheduledValues = e(this.setScheduledValues, this), this.setEndScheduledValue = e(this.setEndScheduledValue, this), this.setScheduledValue = e(this.setScheduledValue, this), this.switchScheduleOption = e(this.switchScheduleOption, this), this.attachEndpointURLFieldHandlers = e(this.attachEndpointURLFieldHandlers, this), this.showHiddenErrors = e(this.showHiddenErrors, this), this.getNextDeletedIndex = e(this.getNextDeletedIndex, this), this.initDeletedIndexCounter = e(this.initDeletedIndexCounter, this), this.handleSubmit = e(this.handleSubmit, this), this.hideUpDownButtonsWhenEditing = e(this.hideUpDownButtonsWhenEditing, this), this.initStates = e(this.initStates, this), this.initBindings = e(this.initBindings, this), this.initBindings(), this.initStates()
	}
	return t.prototype.initBindings = function() {
		var t;
		return this.setTestType(), $("#endpoint_test_type").on("change", this.setTestType), $("#endpointForm").on("submit", this.handleSubmit), $(".advanced-settings").on("click", ".add-config-button", this.showAdvancedOptions).on("click", ".resize", this.hideAdvancedOptions), t = $("input#tag_names"),
		t.on("keypress", this.addTagOnEnter).on("blur", this.addTagsOnBlur).typeahead({
			minLength: 2
		}, {
			name: "Tags",
			source: function(e, i) {
				var n;
				return i(function() {
					var i, s, r, o;
					for (r = t.data("source"), o = [], i = 0, s = r.length; s > i; i++) n = r[i], n.match(e) && o.push({
						value: n
					});
					return o
				}())
			}
		}), $("div.endpoint-tags").on("click", "a.remove-tag", this.removeTag), $("a.switch-schedule-option").on("click", this.switchScheduleOption), $("input.schedule_test_date").datepicker({
			showOn: "both",
			dateFormat: "M d y",
			buttonText: '<i class="glyphicon glyphicon-calendar"></i>',
			buttonImageOnly: !1,
			minDate: new Date,
			onSelect: this.setScheduledValue
		}),
		$("select.schedule_test_time").on("change", this.setScheduledValue),
		$("input.end_schedule_test_date").datepicker({
			showOn: "both",
			dateFormat: "M d y",
			buttonText: '<i class="glyphicon glyphicon-calendar"></i>',
			buttonImageOnly: !1,
			minDate: new Date,
			onSelect: this.setEndScheduledValue
		}),
		$(".endpoint-data").on("click", ".upload-button", this.showParamsFileUpload).on("click", ".delete", this.hideParamsFileUpload).on("change", ".payload-file", this.paramsFileSelected),
		$("a.add-new-url").on("click", this.addNewUrl), this.attachEndpointURLFieldHandlers(".endpoint-url-field")
	},
	t.prototype.initStates = function() {
		return this.setScheduledValues(), this.checkUrlLimit(), this.showHiddenErrors(), this.checkURLHash(), this.checkHosts(), this.enableUrlSectionButtons(), this.initDeletedIndexCounter()
	}, 
	t.prototype.hideUpDownButtonsWhenEditing = function() {
		return this.isEditing() ? this.hideUpDownButtons() : void 0
	},
	t.prototype.isEditing = function() {
		return !this.isCreating()
	},
	t.prototype.isCreating = function() {
		return "/tests" === $("form").attr("action")
	},
	t.prototype.handleSubmit = function() {
		return $(".endpoint-url-field").each(function(t) {
			return function(e, i) {
			return $(i).find("input.keyval").prop("checked") ? $(i).find(".raw_post_body textarea").prop("disabled", !1).val("") : $(i).find(".params .params-list .config-field").each(function(e, i) {
			return t.deleteParameter($(i), "request_params")
			})
		}
	}(this))
	},
	t.prototype.hideUpDownButtons = function() {
		return $("a.action-icon.move-up").hide(), $("a.action-icon.move-down").hide()
	},
	t.prototype.initDeletedIndexCounter = function() {
		return this.dIdx = -1
	},
	t.prototype.getNextDeletedIndex = function() {
		return this.dIdx--
	},
	t.prototype.checkURLHash = function() {
		var t;
		if ("schedule" === window.location.hash.substr(1)) return $(".start-test-block").addClass("hidden"), t = $(".schedule-test-block"),
		t.removeClass("hidden"), LoaderIO.scrollToSection(t)
	},
	t.prototype.enableUrlSectionButtons = function() {
		var t, e, i, n;
		for (e = $(".endpoint-url-fields .endpoint-url-field"), i = 0, n = e.length; n > i; i++) t = e[i], $(t).find(".panel-controls .move-up").toggleClass("disabled", t === e.first()[0]), $(t).find(".panel-controls .move-down").toggleClass("disabled", t === e.last()[0]);
		return e.find(".panel-controls .remove-url").toggleClass("disabled", 1 === e.length)
	},
	t.prototype.showHiddenErrors = function() {
		return $(".advanced-settings .has-error").length && this.showAdvancedOptions(), $(".params .has-error").length ? this.showParamErrors() : void 0
	},
	t.prototype.setTestType = function() {
		var t;
		return t = $("#endpoint_test_type").val(), "concurrent" === t ? ($(".min-clients").removeClass("hidden").find("input").prop("disabled", !1), $(".max-clients label").text("to")) : ($(".min-clients").addClass("hidden").find("input").prop("disabled", !0), $(".max-clients label").text("Clients")), $(".endpoint-note").addClass("hidden"), $(".endpoint-note[data-test-type='" + t + "']").removeClass("hidden")
	},
	t.prototype.attachEndpointURLFieldHandlers = function(t) {
		return $(t).on("click", ".panel-controls .delete", this.deleteUrl).on("click", ".panel-controls .copy", this.copyUrl).on("click", ".panel-controls .move-up", this.moveSectionUp).on("click", ".panel-controls .move-down", this.moveSectionDown).on("click", ".headers .add-config-button", this.showHeaders).on("click", ".headers .btn-add", this.addHeader).on("click", ".headers .delete", this.deleteHeader).on("click", ".params .add-config-button", this.showParams).on("click", ".params .btn-add", this.addParam).on("click", ".params .params-list .delete", this.deleteParam).on("click", ".params .param-type-options input", this.toggleParamsType).on("click", ".params .raw_post_body .delete", this.deleteRawBody).on("click", ".response .add-config-button", this.showVariables).on("click", ".response .btn-add", this.addVariable).on("click", ".response .delete", this.deleteVariable).on("change", ".host select", this.checkHosts).on("change", ".request_method select", this.checkRequestType).find("[data-toggle=popover]").popover()
	},
	t.prototype.showParamsFileUpload = function(t) {
		return t.preventDefault(), $(t.target).parents(".endpoint-data").find(".inner-panel").removeClass("hidden").end().find(".upload-button").addClass("hidden")
	},
	t.prototype.hideParamsFileUpload = function(t) {
		return t.preventDefault(), $(t.target).parents(".endpoint-data").find(".inner-panel").addClass("hidden").end().find("a.payload-file-name").text("(none)").end().find(".payload-file").val("").end().find("input.payload-file-name").val("").end().find("input.payload-text").val("").end().find(".alert").addClass("hidden").end().find(".upload-button").removeClass("hidden")
	},
	t.prototype.paramsFileSelected = function(t) {
		var e, i, n, s;
		return i = $(t.target).parents(".endpoint-data"), e = t.target.files[0], null != e ? (console.log("file selected", e), s = function(t) {
		return console.log("File error:", t), i.find(".alert").removeClass("hidden")
	}, e.type.match(/text.*/) || "application/json" === e.type ? (n = new FileReader, n.onload = function() {
			var e, r, o, a;
			console.log("read file data", n.result);
			try {
				return e = JSON.parse(n.result), i.find(".alert").addClass("hidden"), i.find("input.payload-text").val(n.result), r = $(t.target).val(), a = r, r.length > 20 && (a = "" + r.substring(0, 20) + " ..."), i.find("input.payload-file-name").val(r).end().find("a.payload-file-name").text(a)
			} catch (l) {
				return o = l, s(o)
			}
		}, n.readAsText(e)) : s("Invalid file type: " + e.type)) : void 0
	},
	t.prototype.showAdvancedOptions = function(t) {
		return null != t && t.preventDefault(), $(".advanced-settings .inner-panel").removeClass("hidden"), $(".advanced-settings .add-config-button").addClass("hidden")
	},
	t.prototype.hideAdvancedOptions = function(t) {
		return null != t && t.preventDefault(), $(".advanced-settings .inner-panel").addClass("hidden"), $(".advanced-settings .add-config-button").removeClass("hidden")
	},
	t.prototype.showParamErrors = function() {
		return $(".params").each(function() {
			return $(this).find(".has-error").length ? ($(this).find(".inner-panel").removeClass("hidden"), $(this).find(".add-config-button").addClass("hidden")) : void 0
		})
	},
	t.prototype.switchScheduleOption = function(t) {
		return t.preventDefault(), $(".schedule-test-block").toggleClass("hidden"), $(".start-test-block").toggleClass("hidden"), $("#endpoint_next_run_at").prop("disabled", $(".schedule-test-block").hasClass("hidden"))
	},
	t.prototype.setScheduledValue = function() {
		var t, e;
		return t = $("input.schedule_test_date").datepicker("getDate"), e = "" + $("select.schedule_test_hours").val() + ":" + $("select.schedule_test_minutes").val() + ":00", $("input#endpoint_next_run_at").val("" + t.getFullYear() + "-" + (t.getMonth() + 1) + "-" + t.getDate() + " " + e), $("input.end_schedule_test_date").datepicker("option", "minDate", t)
	},
	t.prototype.setEndScheduledValue = function() {
		var t;
		return t = $("input.end_schedule_test_date").datepicker("getDate"), $("input#endpoint_stop_schedule_at").val("" + t.getFullYear() + "-" + (t.getMonth() + 1) + "-" + t.getDate())
	},
	t.prototype.setScheduledValues = function() {
		var t, e;
		return this.previousScheduleExists() ? (t = this.getNextRunAtDateTimeFromHiddenField(), null != t && (this.populateDatePicker("input.schedule_test_date", t), $("select.schedule_test_hours").val(t.getHours()), $("select.schedule_test_minutes").val(t.getMinutes())), e = this.getStopScheduleAtDateFromHiddenField(), null != e && this.populateDatePicker("input.end_schedule_test_date", e), $("input.end_schedule_test_date").datepicker("option", "minDate", t)) : !1
	},
	t.prototype.previousScheduleExists = function() {
		return $("input#endpoint_next_run_at").val().length > 0
	},
	t.prototype.getNextRunAtDateTimeFromHiddenField = function() {
		var t;
		return t = $("input#endpoint_next_run_at").val().match(/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/i), null == t ? null : new Date(t[1], t[2] - 1, t[3], t[4], t[5])
	},
	t.prototype.populateDatePicker = function(t, e) {
		var i, n;
		return n = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], i = "" + n[e.getMonth()] + " " + e.getDate() + " " + e.getFullYear().toString().substr(2, 2), $(t).val(i)
	},
	t.prototype.getStopScheduleAtDateFromHiddenField = function() {
		var t;
		return t = $("input#endpoint_stop_schedule_at").val().match(/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/i), null == t ? null : new Date(t[1], t[2] - 1, t[3])
	},
	t.prototype.getEndpointURLSection = function(t) {
		return $(t).closest(".endpoint-url-field")
	},
	t.prototype.getFollowingEndpointSections = function(t) {
		return $(t).nextAll(".endpoint-url-field")
	},
	t.prototype.getUrlIndex = function(t) {
		return Number($(t).closest(".endpoint-url-field").data("index"))
	},
	t.prototype.addNewUrl = function(t) {
		var e, i, n, s;
		return t.preventDefault()
	}
}