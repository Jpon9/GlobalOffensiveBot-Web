@extends('template.main')

@section('title', 'Notices')

@section('content')
	<div id="content">
		<h3>Notices</h3>
		@if (count($errors) > 0)
		    <div class="alert alert-danger">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@elseif (isset($success_message))
			<div class="alert alert-success">
				<p>{{ $success_message }}</p>
			</div>
		@endif
		<div class="form-group">
			<div class="col-sm-4">
				{!! Form::button('Add Notice', array('class' => 'btn btn-success', 'id' => 'plus-notice')) !!}
			</div>
		</div>
		<div class="form-group col-sm-9" id="notice-edit">

		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<button id="submit-notices" onclick="sendNotices()" class="btn btn-primary">Submit<img src="/images/loading.png" alt="loading"></button>
				<p id="success" style="display:none">Success! Notices saved.</p>
				<p id="failure" style="display:none">Error! Notices not saved.</p>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	{!! HTML::script('js/notices.js') !!}
	{!! HTML::script('js/formatting.js') !!}
	<script type="text/javascript">
		var notices;
		$.ajaxSetup({async:false});
		$.getJSON("{!! URL::to('/notices/fetch') !!}", function(data) {
			notices = data;
		});
		$.ajaxSetup({async:true});
		target = document.getElementById("notice-edit");
		var numOfNotices = 0;
		for (var notice in notices) {
			notices[notice]['is_new_item'] = false;
			var nodes = addNotice(notices[notice]);
			// Fill in the nodes with the appropriate data
		}

		// Remove text nodes
		for (var i = 0; i < target.parentElement.childNodes.length; ++i) {
			if (target.parentElement.childNodes[i].nodeType == 3) {
				target.parentElement.removeChild(target.parentElement.childNodes[i]);
			}
		}

		// Bind the buttons
		$("#plus-notice").on("click", function(ev) {
			addNotice();
		});

		function addNotice(notice) {
			if (notice === undefined) {
				// Default notice values
				notice = {
					"type": "autopost+notice",
					"category": "notice",
					"thread_title": "Default Thread Title",
					"notice_title": "Default Notice Title",
					"thread_link": "https://reddit.com/r/GlobalOffensive",
					"poster_account": "GlobalOffensiveBot",
					"notice_start_time": [0, 0, 0],
					"post_time": [0, 0, 0],
					"notice_duration": 6,
					"permanent_notice": false,
					"frequency": "once",
					"created": parseInt(new Date().getTime() / 1000),
					"body": "Default thread body.",
					"sticky_duration": 6,
					"permanent_sticky": false,
					"notice_link": "#swag",
					"self_post": true,
					"disable_posting": false,
					"last_posted": 0,
					"last_posted_id": "",
					"unique_notice_id": generateNoticeId(),
					"is_new_item": true
				}
			}

			var noticeContainer = document.createElement("DIV");
			noticeContainer.className = "notice";
			
			var innerNoticeContainer = document.createElement("DIV");
			innerNoticeContainer.className = "inner-2 form-horizontal";

			var deleteImage = document.createElement("IMG");
			deleteImage.setAttribute("onclick", "deleteNotice(this)");
			deleteImage.src = "/images/delete-hover.png";
			deleteImage.className = "delete";
			deleteImage.alt = "delete";
			innerNoticeContainer.appendChild(deleteImage);

			var noticeH4 = document.createElement("H4");
			noticeH4.setAttribute("onclick", "collapse(this)");
			noticeH4.setAttribute("data-collapsed", "true");
			noticeH4.className = "notice-title";
			var titleImage = document.createElement("IMG");
			titleImage.src = "/images/collapse.png";
			titleImage.className = "arrow collapsed";
			titleImage.alt = "collapse";
			noticeH4.appendChild(titleImage);
			if (notice['notice_title'] !== "Default Notice Title") {
				noticeH4.innerHTML += notice['notice_title'];
			} else if (notice['thread_title'] !== "Default Thread Title") {
				noticeH4.innerHTML += notice['thread_title'];
			} else {
				noticeH4.innerHTML += "New Scheduled Item";
			}
			innerNoticeContainer.appendChild(noticeH4);

			var collapsible = document.createElement("DIV");
			collapsible.className = "collapsible";

			var innerCollapsible = document.createElement("DIV");
			innerCollapsible.className = "inner-2";

			type(innerCollapsible, notice['type']);

			// Notice fields
			var nF = document.createElement("DIV");
			nF.className = "notice-fields";
			// Thread fields
			var tF = document.createElement("DIV");
			tF.className = "thread-fields";

			// Hides fields you don't care about
			if (notice['type'] === 'autopost') {
				nF.style.display = "none";
			} else if (notice['type'] === 'notice') {
				tF.style.display = "none";
			}

			notice['notice_start_time'] = convertUTCToLocal(notice['notice_start_time']);
			notice['post_time'] = convertUTCToLocal(notice['post_time']);
			
			// Build the fields of the item
			resetNotice(innerCollapsible, false);
			frequency(innerCollapsible, notice['frequency']);
			category(nF, notice['category']);
			noticeTitle(nF, notice['notice_title']);
			noticeLink(nF, notice['notice_link'], notice['type'] !== 'notice');
			noticeStartTime(nF, notice['notice_start_time']);
			permanentNotice(nF, notice['permanent_notice']);
			noticeDuration(nF, notice['notice_duration']);
			hideNotice(nF, notice['hide_notice']);
			threadTitle(tF, notice['thread_title']);
			posterAccount(tF, notice['poster_account']);
			postTime(tF, notice['post_time']);
			disablePosting(tF, notice['disable_posting']);
			textOrLinkPost(tF, notice['self_post'],
				notice['sticky_duration'],
				notice['permanent_sticky'],
				notice['body'],
				notice['thread_link']);
			created(innerCollapsible, notice['created']);
			lastPosted(innerCollapsible, notice['last_posted']);
			lastPostedId(innerCollapsible, notice['last_posted_id']);
			uniqueNoticeId(innerCollapsible, notice['unique_notice_id']);
			isNewItem(innerCollapsible, notice['is_new_item']);

			innerCollapsible.appendChild(nF);
			innerCollapsible.appendChild(tF);

			collapsible.appendChild(innerCollapsible);
			innerNoticeContainer.appendChild(collapsible);
			noticeContainer.appendChild(innerNoticeContainer);
			target.appendChild(noticeContainer);
		}

		function convertUTCToLocal(time) {
			// Convert post time from UTC to local time
			var tzOffset = new Date().getTimezoneOffset() / 60;
			time[1] -= parseInt(tzOffset);
			time[2] -= (tzOffset - parseInt(tzOffset)) * 60;
			// Fix any issues caused by the timezone shift
			return correctDayHourMin(time);
		}

		function convertLocalToUTC(time) {
			// Convert post time from UTC to local time
			var tzOffset = new Date().getTimezoneOffset() / 60;
			time[1] += parseInt(tzOffset);
			time[2] += (tzOffset - parseInt(tzOffset)) * 60;
			// Fix any issues caused by the timezone shift
			return correctDayHourMin(time);
		}

		function correctDayHourMin(time) {
			if (time[2] < 0) {
				time[2] += 60;
				time[1] -= 1; 
			} else if (time[2] >= 60) {
				time[2] -= 60;
				time[1] += 1;
			}

			if (time[1] < 0) {
				time[1] += 24;
				time[0] -= 1;
			} else if (time[1] >= 24) {
				time[1] -= 24;
				time[0] += 1;
			}

			if (time[0] < 0) {
				time[0] += 7;
			} else if (time[0] >= 7) {
				time[0] -= 7;
			}

			return time;
		}

		function deleteNotice(trigger) {
			var noticeContainerFound = false;
			var suspect = trigger;
			while (!noticeContainerFound) {
				if (suspect.parentNode.className === "notice") {
					noticeContainerFound = true;
				}
				suspect = suspect.parentNode;
			}
			$(suspect).fadeOut(function() {
				suspect.parentNode.removeChild(suspect);
			});
		}

		function collapse(elem) {
			var isCollapsing = false;
			if (elem.getAttribute("data-collapsed") === null) {
				elem.setAttribute("data-collapsed", "false");
			} else if (elem.getAttribute("data-collapsed") == "false") {
				isCollapsing = true;
				elem.setAttribute("data-collapsed", "true");
			} else {
				elem.setAttribute("data-collapsed", "false");
			}

			var index = 0;
			for (e in elem.parentNode.childNodes) {
				if (elem.parentNode.childNodes[e].className == "collapsible") {
					index = e;
				}
			}

			$(elem.parentNode.childNodes[index]).slideToggle();
			elem.childNodes[0].className = "arrow " + (isCollapsing ? "" : "un") + "collapsed";
		}

		function lpExpand(elem) {
			var indexes = getPostGroupIndexes(elem);

			var sp = indexes[0];
			var lp = indexes[1];
			var spTrigger = indexes[2];

			elem.parentNode.childNodes[spTrigger].className = "sp-trigger";
			elem.className = "active lp-trigger";

			animateGroupSwitch(elem.parentNode.childNodes[lp],
				elem.parentNode.childNodes[sp]);
		}

		function spExpand(elem) {
			var indexes = getPostGroupIndexes(elem);

			var sp = indexes[0];
			var lp = indexes[1];
			var lpTrigger = indexes[2];

			elem.parentNode.childNodes[lpTrigger].className = "lp-trigger";
			elem.className = "active sp-trigger";

			animateGroupSwitch(elem.parentNode.childNodes[sp],
				elem.parentNode.childNodes[lp]);
		}

		function getPostGroupIndexes(elem) {
			var sp = 0, lp = 0, otherTrigger = 0;
			
			for (e in elem.parentNode.childNodes) {
				if (elem.parentNode.childNodes[e].className === "self-post post-type") {
					sp = e;
				} else if (elem.parentNode.childNodes[e].className === "link-post post-type") {
					lp = e;
				} else if (("" + elem.parentNode.childNodes[e].className).search("active") !== -1) {
					otherTrigger = e;
				}
			}

			return [sp, lp, otherTrigger];
		}

		function animateGroupSwitch(expanding, collapsing) {
			$(collapsing).slideUp(250, function() {
				$(expanding).slideDown(250);
			});
		}

		function changeType(trigger) {
			var newType = trigger.options[trigger.selectedIndex].value;
			var nF = undefined, tF = undefined;
			for (var node in trigger.parentNode.parentNode.parentNode.childNodes) {
				if (trigger.parentNode.parentNode.parentNode.childNodes[node].className === "notice-fields") {
					nF = trigger.parentNode.parentNode.parentNode.childNodes[node];
				} else if (trigger.parentNode.parentNode.parentNode.childNodes[node].className === "thread-fields") {
					tF = trigger.parentNode.parentNode.parentNode.childNodes[node];
				}
				if (nF !== undefined && tF !== undefined) {
					break;
				}
			}
			if (nF === undefined || tF === undefined) {
				console.error("Could not find both item type groupings");
				return;
			}
			if (newType === "autopost+notice") {
				nF.style.display = "block";
				tF.style.display = "block";
				$(nF).find("div div [name='notice_link']").parent().parent().hide();
			} else if (newType === "autopost") {
				nF.style.display = "none";
				tF.style.display = "block";
			} else if (newType === "notice") {
				nF.style.display = "block";
				tF.style.display = "none";
				$(nF).find("div div [name='notice_link']").parent().parent().show();
			}
		}

		function sendNotices() {
			var parent = document.getElementById("notice-edit");
			var notices = [];
			var newNotices = [];
			$("#submit-notices img").animate({width: '16px'}, 250);
			for (var i in parent.childNodes) {
				if (parent.childNodes[i].nodeType != 1) { continue; }
				var notice = {};
				var n_type = getChildByAttr(parent.childNodes[i], 'name', 'type');
				var n_frequency = getChildByAttr(parent.childNodes[i], 'name', 'frequency');
				var n_category = getChildByAttr(parent.childNodes[i], 'name', 'category');
				var n_notice_title = getChildByAttr(parent.childNodes[i], 'name', 'notice_title');
				var n_notice_link = getChildByAttr(parent.childNodes[i], 'name', 'notice_link');
				var n_notice_start_time_day = getChildByAttr(parent.childNodes[i], 'name', 'notice_start_day');
				var n_notice_start_time_hour = getChildByAttr(parent.childNodes[i], 'name', 'notice_start_hour');
				var n_notice_start_time_minute = getChildByAttr(parent.childNodes[i], 'name', 'notice_start_minute');
				var n_permanent_notice = getChildByAttr(parent.childNodes[i], 'name', 'permanent_notice');
				var n_notice_duration = getChildByAttr(parent.childNodes[i], 'name', 'notice_duration');
				var n_hide_notice = getChildByAttr(parent.childNodes[i], 'name', 'hide_notice');
				var n_thread_title = getChildByAttr(parent.childNodes[i], 'name', 'thread_title');
				var n_poster_account = getChildByAttr(parent.childNodes[i], 'name', 'poster_account');
				var n_post_time_day = getChildByAttr(parent.childNodes[i], 'name', 'post_day')
				var n_post_time_hour = getChildByAttr(parent.childNodes[i], 'name', 'post_hour')
				var n_post_time_minute = getChildByAttr(parent.childNodes[i], 'name', 'post_minute')
				var n_disable_posting = getChildByAttr(parent.childNodes[i], 'name', 'disable_posting');
				var n_self_post = getChildByAttr(parent.childNodes[i], 'class', 'active', '*');
				var n_sticky_duration = getChildByAttr(parent.childNodes[i], 'name', 'sticky_duration');
				var n_permanent_sticky = getChildByAttr(parent.childNodes[i], 'name', 'permanent_sticky');
				var n_body = getChildByAttr(parent.childNodes[i], 'name', 'body');
				var n_thread_link = getChildByAttr(parent.childNodes[i], 'name', 'thread_link');
				var n_created = getChildByAttr(parent.childNodes[i], 'name', 'created');
				var n_last_posted = getChildByAttr(parent.childNodes[i], 'name', 'last_posted');
				var n_last_posted_id = getChildByAttr(parent.childNodes[i], 'name', 'last_posted_id');

				var n = getChildByAttr(parent.childNodes[i], 'name', 'is_new_item').value === 'true';

				// oV = originalValue, used to condense at least *some* of this monstrosity
				function oV(e) { return e.getAttribute('data-original-value'); }

				var notice = {};
				if (n_type.value !== oV(n_type) || n) { notice.type = n_type.value; }
				if (n_frequency.value !== oV(n_frequency) || n) { notice.frequency = n_frequency.value; }
				if (n_category.value !== oV(n_category) || n) { notice.category = n_category.value; }
				if (n_notice_title.value !== oV(n_notice_title) || n) { notice.notice_title = n_notice_title.value; }
				if (n_notice_link.value !== oV(n_notice_link) || n) { notice.notice_link = n_notice_link.value; }
				// Notice start time
				if (n_notice_start_time_day.value !== oV(n_notice_start_time_day) ||
					n_notice_start_time_hour.value !== oV(n_notice_start_time_hour) ||
					n_notice_start_time_minute.value !== oV(n_notice_start_time_minute) || n) {
					notice.notice_start_time = convertLocalToUTC([
						parseInt(n_notice_start_time_day.value),
						parseInt(n_notice_start_time_hour.value),
						parseInt(n_notice_start_time_minute.value)
					]);
				}
				if (n_permanent_notice.checked !== (oV(n_permanent_notice) === 'true') || n) { notice.permanent_notice = n_permanent_notice.checked; }
				if (n_notice_duration.value !== oV(n_notice_duration) || n) { notice.notice_duration = n_notice_duration.value; }
				if (n_hide_notice.checked !== (oV(n_hide_notice) === 'true') || n) { notice.hide_notice = n_hide_notice.checked; }
				if (n_thread_title.value !== oV(n_thread_title) || n) { notice.thread_title = n_thread_title.value; }
				if (n_poster_account.value !== oV(n_poster_account) || n) { notice.poster_account = n_poster_account.value; }
				// Thread post time
				if (n_post_time_day.value !== oV(n_post_time_day) ||
					n_post_time_hour.value !== oV(n_post_time_hour) || 
					n_post_time_minute.value !== oV(n_post_time_minute) || n) {
					notice.post_time = convertLocalToUTC([
						parseInt(n_post_time_day.value),
						parseInt(n_post_time_hour.value),
						parseInt(n_post_time_minute.value)
					]);
				}
				if (n_disable_posting.checked !== (oV(n_disable_posting) === 'true') || n) { notice.disable_posting = n_disable_posting.checked; }
				if ((n_self_post.innerHTML === "Self-Post") !== (oV(n_self_post) === 'true') || n) { notice.self_post = n_self_post.innerHTML === "Self-Post"; }
				if (n_sticky_duration.value !== oV(n_sticky_duration) || n) { notice.sticky_duration = n_sticky_duration.value; }
				if (n_permanent_sticky.checked !== (oV(n_permanent_sticky) === 'true') || n) { notice.permanent_sticky = n_permanent_sticky.checked; }
				if (n_body.value !== oV(n_body) || n) { notice.body = n_body.value; }
				if (n_thread_link.value !== oV(n_thread_link) || n) { notice.thread_link = n_thread_link.value; }
				if (n_created.value !== oV(n_created) || n) { notice.created = n_created.value; }
				if (n_last_posted.value !== oV(n_last_posted) || n) { notice.last_posted = n_last_posted.value; }
				if (n_last_posted_id.value !== oV(n_last_posted_id) || n) { notice.last_posted_id = n_last_posted_id.value; }
				notice.unique_notice_id = oV(getChildByAttr(parent.childNodes[i], 'name', 'unique_notice_id'));

				var resetTiming = getChildByAttr(parent.childNodes[i], 'name', 'reset_timing').checked;
				if (resetTiming) {
					notice.created = parseInt(new Date().getTime() / 1000);
					notice.last_posted = 0;
					notice.last_posted_id = "";
				}
				notices.push(notice);
				newNotices.push({
					type: n_type.value,
					frequency: n_frequency.value,
					category: n_category.value,
					notice_title: n_notice_title.value,
					notice_link: n_notice_link.value,
					notice_start_time: convertLocalToUTC([
						parseInt(n_notice_start_time_day.value),
						parseInt(n_notice_start_time_hour.value),
						parseInt(n_notice_start_time_minute.value)
					]),
					permanent_notice: n_permanent_notice.checked,
					notice_duration: n_notice_duration.value,
					hide_notice: n_hide_notice.checked,
					thread_title: n_thread_title.value,
					poster_account: n_poster_account.value,
					post_time: convertLocalToUTC([
						parseInt(n_post_time_day.value),
						parseInt(n_post_time_hour.value),
						parseInt(n_post_time_minute.value)
					]),
					disable_posting: n_disable_posting.checked,
					self_post: n_self_post.innerHTML === "Self-Post",
					sticky_duration: n_sticky_duration.value,
					permanent_sticky: n_permanent_sticky.checked,
					body: n_body.value,
					thread_link: n_thread_link.value,
					created: n_created.value,
					last_posted: n_last_posted.value,
					last_posted_id: n_last_posted_id.value,
					unique_notice_id: notice.unique_notice_id,
					is_new_item: false
				});
			}

			while (parent.hasChildNodes()) {
				parent.removeChild(parent.lastChild);
			}

			for (var i in newNotices) {
				addNotice(newNotices[i]);
			}

			$.ajax({
				type: 'POST',
				url: "{!! URL::to('/notices') !!}",
				data: {'notices': JSON.stringify(notices), '_token': '{{ csrf_token() }}'},
				success: function() {
					$("#submit-notices img").animate({width: '0'}, 250, function() {
						$("#success").fadeIn(250, function() {
							setTimeout(function() {
								$("#success").fadeOut(500);
							}, 2500);
						});
					});
				},
				failure: function() {
					$("#submit-notices img").animate({width: '0'}, 250, function() {
						$("#failure").fadeIn(250, function() {
							setTimeout(function() {
								$("#failure").fadeOut(500);
							}, 2500);
						});
					});
				}
			});
		}

		function generateNoticeId() {
			return Math.random().toString(36).substr(2, 8);
		}
	</script>
@endsection